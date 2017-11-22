<?php
/* web/force-directed-domain.php
 * Force directed graph by domain only.
 */

// FOR TEMPORARY DEBUGGING
ini_set('display_errors', '1');
// PLEASE REMOVE LATER

include "inc/setup.php";
use LayerShifter\TLDExtract\Extract;

$ext = new Extract(null, null, Extract::MODE_ALLOW_ICANN);
$lookupFQDN = $ext->parse(mysqli_real_escape_string($mysqli, htmlspecialchars($_GET["domain"])));

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

// A record lookup time (yay)
$dbGet = $mysqli->query("SELECT * FROM `DNS_A` WHERE `Domain` = '" . $lookupFQDN->getRegistrableDomain() . "'");

$reducer = [];
while($row = $dbGet->fetch_assoc()) {
	$preNodes[] = $row["IPv4"];
	$links[] = array(
		"source" => $row["Subdomain"] . "." . $row["Domain"],
		"target" => $row["IPv4"],
		"value" => 4); // tuning?
	$reducer[] = $row["IPv4"];
}
$reducer = array_unique($reducer);

foreach($reducer as $IPv4Addr) {
	$dbGet = $mysqli->query("SELECT * FROM `DNS_A` WHERE `IPv4` = '" . $IPv4Addr . "' AND `Domain` != '" . $lookupFQDN->getRegistrableDomain() . "'");
	
	while($row = $dbGet->fetch_assoc()) {
		if(strlen($row["Subdomain"]) > 1) {
			$preNodes[] = $row["Subdomain"] . "." . $row["Domain"];
		} else {
			$preNodes[] = $row["Domain"];
		}
		$links[] = array(
			"source" => $row["IPv4"],
			"target" => $row["Subdomain"] . "." . $row["Domain"],
			"value" => 4); // tuning?
	}
}


// cleanup
$preNodes = array_unique($preNodes);
$buildReturnable["Data"] = [$preNodes, $links]; // testing

echo json_encode($buildReturnable);
?>