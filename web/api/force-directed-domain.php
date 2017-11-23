<?php
/* web/api/force-directed-domain.php
 * Force directed graph by domain only.
 */

// FOR TEMPORARY DEBUGGING
ini_set('display_errors', '1');
// PLEASE REMOVE LATER

include "inc/setup.php";
use LayerShifter\TLDExtract\Extract;

$ext = new Extract(null, null, Extract::MODE_ALLOW_ICANN);
$lookupFQDN = $ext->parse(mysqli_real_escape_string($mysqli, htmlspecialchars($_GET["domain"])));

function fixDomain($subdomain, $domain) {
	if(strlen($subdomain) > 0) {
		return $subdomain . "." . $domain;
	} else {
		return $domain;
	}
}

$preNodes = [];
$nodes = [];
$links = [];

if(!$lookupFQDN->isValidDomain()) {
	echo json_encode(array("Success" => false, "Reason" => "Invalid domain"));
	exit();
}

$dbGet = $mysqli->query("SELECT * FROM `Reputation` WHERE `Domain` = '" . $lookupFQDN->getRegistrableDomain() . "'");

if(mysqli_num_rows($dbGet) == 0) {
	echo json_encode(array("Success" => false, "Reason" => "Domain not in database"));
	exit();
}

$buildReturnable = [];
$buildReturnable["Success"] = true;

while($row = $dbGet->fetch_assoc()) {
	if(strlen($row["Subdomain"]) > 0) {
		$preNodes[] = $row["Subdomain"] . "." . $row["Domain"];
		$links[] = array(
			"source" => $lookupFQDN->getRegistrableDomain(),
			"target" => $row["Subdomain"] . "." . $row["Domain"],
			"value" => 10); // tuning?
	}
}

// A RECORD LOOKUP SECTION
$dbGet = $mysqli->query("SELECT * FROM `DNS_A` WHERE `Domain` = '" . $lookupFQDN->getRegistrableDomain() . "'");

$reducer = [];
while($row = $dbGet->fetch_assoc()) {
	$preNodes[] = $row["IPv4"];
	$links[] = array(
		"source" => fixDomain($row["Subdomain"], $row["Domain"]),
		"target" => $row["IPv4"],
		"value" => 4); // tuning?
	$reducer[] = $row["IPv4"];
}
$reducer = array_unique($reducer);

foreach($reducer as $IPv4Addr) {
	$dbGet = $mysqli->query("SELECT * FROM `DNS_A` WHERE `IPv4` = '" . $IPv4Addr . "' AND `Domain` != '" . $lookupFQDN->getRegistrableDomain() . "'");
	
	while($row = $dbGet->fetch_assoc()) {
		$preNodes[] = fixDomain($row["Subdomain"], $row["Domain"]);
		$links[] = array(
			"source" => $row["IPv4"],
			"target" => fixDomain($row["Subdomain"], $row["Domain"]),
			"value" => 4); // tuning?
	}
}
// A RECORD LOOKUP SECTION

// AAAA RECORD LOOKUP SECTION
$dbGet = $mysqli->query("SELECT * FROM `DNS_AAAA` WHERE `Domain` = '" . $lookupFQDN->getRegistrableDomain() . "'");

$reducer = [];
while($row = $dbGet->fetch_assoc()) {
	$preNodes[] = $row["IPv6"];
	$links[] = array(
		"source" => fixDomain($row["Subdomain"], $row["Domain"]),
		"target" => $row["IPv6"],
		"value" => 6); // tuning?
	$reducer[] = $row["IPv6"];
}
$reducer = array_unique($reducer);

foreach($reducer as $IPv6Addr) {
	$dbGet = $mysqli->query("SELECT * FROM `DNS_AAAA` WHERE `IPv6` = '" . $IPv6Addr . "' AND `Domain` != '" . $lookupFQDN->getRegistrableDomain() . "'");
	
	while($row = $dbGet->fetch_assoc()) {
		$preNodes[] = fixDomain($row["Subdomain"], $row["Domain"]);
		$links[] = array(
			"source" => $row["IPv6"],
			"target" => fixDomain($row["Subdomain"], $row["Domain"]),
			"value" => 6); // tuning?
	}
}
// AAAA RECORD LOOKUP SECTION

// CNAME RECORD LOOKUP SECTION
$dbGet = $mysqli->query("SELECT * FROM `DNS_CNAME` WHERE `Domain` = '" . $lookupFQDN->getRegistrableDomain() . "'");

$reducer = [];
while($row = $dbGet->fetch_assoc()) {
	$preNodes[] = $row["CNAME"];
	$links[] = array(
		"source" => fixDomain($row["Subdomain"], $row["Domain"]),
		"target" => $row["CNAME"],
		"value" => 7); // tuning?
	$reducer[] = $row["CNAME"];
}
$reducer = array_unique($reducer);

foreach($reducer as $CNAME) {
	$dbGet = $mysqli->query("SELECT * FROM `DNS_CNAME` WHERE `CNAME` = '" . $CNAME . "' AND `Domain` != '" . $lookupFQDN->getRegistrableDomain() . "'");
	
	while($row = $dbGet->fetch_assoc()) {
		$preNodes[] = fixDomain($row["Subdomain"], $row["Domain"]);
		$links[] = array(
			"source" => $row["CNAME"],
			"target" => fixDomain($row["Subdomain"], $row["Domain"]),
			"value" => 7); // tuning?
	}
}
// CNAME RECORD LOOKUP SECTION

// MX RECORD LOOKUP SECTION
$dbGet = $mysqli->query("SELECT * FROM `DNS_MX` WHERE `Domain` = '" . $lookupFQDN->getRegistrableDomain() . "'");

$reducer = [];
while($row = $dbGet->fetch_assoc()) {
	$preNodes[] = fixDomain($row["MX_Subdomain"], $row["MX_Domain"]);
	$links[] = array(
		"source" => fixDomain($row["Subdomain"], $row["Domain"]),
		"target" => fixDomain($row["MX_Subdomain"], $row["MX_Domain"]),
		"value" => 2); // tuning?
	$reducer[] = $row["MX_Subdomain"] . "." . $row["MX_Domain"];
}
$reducer = array_unique($reducer);

foreach($reducer as $MXD) {
	$dbGet = $mysqli->query("SELECT * FROM `DNS_MX` WHERE CONCAT(`MX_Subdomain`, '.', `MX_Domain`) = '" . $MXD . "' AND `Domain` != '" . $lookupFQDN->getRegistrableDomain() . "'");
	
	while($row = $dbGet->fetch_assoc()) {
		$preNodes[] = fixDomain($row["Subdomain"], $row["Domain"]);
		$links[] = array(
			"source" => fixDomain($row["MX_Subdomain"], $row["MX_Domain"]),
			"target" => fixDomain($row["Subdomain"], $row["Domain"]),
			"value" => 2); // tuning?
	}
}
// MX RECORD LOOKUP SECTION

// NS RECORD LOOKUP SECTION
$dbGet = $mysqli->query("SELECT * FROM `DNS_NS` WHERE `Domain` = '" . $lookupFQDN->getRegistrableDomain() . "'");

$reducer = [];
while($row = $dbGet->fetch_assoc()) {
	$preNodes[] = fixDomain($row["NS_Subdomain"], $row["NS_Domain"]);
	$links[] = array(
		"source" => fixDomain($row["Subdomain"], $row["Domain"]),
		"target" => fixDomain($row["NS_Subdomain"], $row["NS_Domain"]),
		"value" => 1); // tuning?
	$reducer[] = $row["NS_Subdomain"] . "." . $row["NS_Domain"];
}
$reducer = array_unique($reducer);

foreach($reducer as $NSD) {
	$dbGet = $mysqli->query("SELECT * FROM `DNS_NS` WHERE CONCAT(`NS_Subdomain`, '.', `NS_Domain`) = '" . $NSD . "' AND `Domain` != '" . $lookupFQDN->getRegistrableDomain() . "'");
	
	while($row = $dbGet->fetch_assoc()) {
		$preNodes[] = fixDomain($row["Subdomain"], $row["Domain"]);
		$links[] = array(
			"source" => fixDomain($row["NS_Subdomain"], $row["NS_Domain"]),
			"target" => fixDomain($row["Subdomain"], $row["Domain"]),
			"value" => 1); // tuning?
	}
}
// NS RECORD LOOKUP SECTION

// cleanup
$preNodes = array_unique($preNodes);
foreach($preNodes as $node) { // placeholder
	$nodes[] = array(
		"id" => $node,
		"group" => 1
	);
}
$buildReturnable = array("nodes" => $nodes, "links" => $links); // testing

echo json_encode($buildReturnable);
?>