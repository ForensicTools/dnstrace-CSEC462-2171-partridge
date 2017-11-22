<?php
/* web/graph1.php
 * First degree graph generation from single starting point.
 */

// FOR TEMPORARY DEBUGGING
ini_set('display_errors', '1');
// PLEASE REMOVE LATER

include "inc/setup.php";
use LayerShifter\TLDExtract\Extract;

$ext = new Extract(null, null, Extract::MODE_ALLOW_ICANN);
$lookupFQDN = $ext->parse(mysqli_real_escape_string($mysqli, htmlspecialchars($_GET["domain"])));

if(!$lookupFQDN->isValidDomain()) {
	echo json_encode(array("Success" => false, "Reason" => "Invalid domain"));
	exit();
}

$dbGet = $mysqli->query("SELECT * FROM `Reputation` LEFT JOIN `Sources` ON `Reputation`.`Source`=`Sources`.`ID` WHERE `Domain` = '".$lookupFQDN->getRegistrableDomain()."'");

if(mysqli_num_rows($dbGet) == 0) {
	echo json_encode(array("Success" => false, "Reason" => "Domain not in database"));
	exit();
}

$buildReturnable = [];
$buildReturnable["Success"] = true;

$dbResReputation = [];
$totalReputation = 0;
while($row = $dbGet->fetch_assoc()) {
	$dbResReputation[] = $row;
	$totalReputation += intval($row["Score"]);
}
$buildReturnable["DomainReputation"] = $totalReputation;

$buildReturnable["ExactMatch"] = false;
if(strlen($lookupFQDN["subdomain"]) > 0) {
	foreach($dbResReputation as $row) {
		if(strcmp($row["Subdomain"], $lookupFQDN["subdomain"]) === 0) {
			$buildReturnable["ExactMatch"] = true;
		}
	}
	if(!$buildReturnable["ExactMatch"]) {
		$buildReturnable["Domain"] = $lookupFQDN->getRegistrableDomain();
		$buildReturnable["FQDN"] = $lookupFQDN->getRegistrableDomain();
	} else {
		$buildReturnable["Domain"] = $lookupFQDN->getRegistrableDomain();
		$buildReturnable["FQDN"] = $lookupFQDN["subdomain"] . "." . $lookupFQDN->getRegistrableDomain();
	}
} else {
	$buildReturnable["ExactMatch"] = true;
	$buildReturnable["Domain"] = $lookupFQDN->getRegistrableDomain();
	$buildReturnable["FQDN"] = $lookupFQDN->getRegistrableDomain();
}

// A record lookup time (yay)
$dbGet = $mysqli->query("SELECT * FROM `DNS_A` WHERE `Domain` = '".$buildReturnable["Domain"]."'");

$dbResIPv4 = [];
while($row = $dbGet->fetch_assoc()) {
	$dbResIPv4[] = $row["IPv4"];
}
$dbResIPv4 = array_unique($dbResIPv4);


foreach($dbResIPv4 as $IPv4Addr) {
	
}

echo json_encode($buildReturnable);
?>