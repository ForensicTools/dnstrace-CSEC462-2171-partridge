<?php
/* web/graph1.php
 * First degree graph generation from single starting point.
 */

include "inc/setup.php";
use LayerShifter\TLDExtract\Extract;

$ext = new Extract(null, null, Extract::MODE_ALLOW_ICANN);
$lookupFQDN = $ext->parse(mysqli_real_escape_string($mysqli, htmlspecialchars($_GET["domain"])));

if(!$lookupFQDN->isValidDomain()) {
	echo json_encode(array("success" => false, "reason" => "Invalid domain"));
	exit();
}

$allLookups = $mysqli->query("SELECT * FROM `Reputation` WHERE `Domain` = '".$lookupFQDN->getRegistrableDomain()."'");

while($row = $allLookups->fetch_assoc()) {
	echo $row["Subdomain"] . "." . $row["Domain"] . " found " . PHP_EOL;
}