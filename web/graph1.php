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

$allLookups = $mysqli->query("SELECT * FROM `Reputation` WHERE `Domain` = '".$lookupFQDN->getRegistrableDomain()."' LEFT JOIN `Sources` ON `Reputation`.`Source`=`Sources`.`ID`");

if(mysqli_num_rows($allLookups) == 0) {
	echo json_encode(array("Success" => false, "Reason" => "Domain not in database"));
	exit();
}

$buildReturnable = [];
$buildReturnable["Success"] = true;

$dbResReputation = [];
while($row = $allLookups->fetch_assoc()) {
	$dbResReputation[] = $row;
}

var_dump($dbResReputation);

$buildReturnable["ExactMatch"] = false;
if(strlen($lookupFQDN["subdomain"]) > 0) {
	foreach($dbResReputation as $row) {
		if(strcmp($row["Subdomain"], $lookupFQDN["subdomain"]) === 0) {
			$buildReturnable["ExactMatch"] = true;
		}
	}
	if(!$buildReturnable["ExactMatch"]) {
		$buildReturnable["FQDN"] = $lookupFQDN->getRegistrableDomain();
	} else {
		$buildReturnable["FQDN"] = $lookupFQDN["subdomain"] . "." . $lookupFQDN->getRegistrableDomain();
	}
} else {
	$buildReturnable["ExactMatch"] = true;
	$buildReturnable["FQDN"] = $lookupFQDN->getRegistrableDomain();
}

echo json_encode($buildReturnable);
?>