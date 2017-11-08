<?php
/* tools/fqdn_add.php
 * Add FQDN to database for parsing.
 */

include "inc/setup.php";
include "inc/gdns.php";

if(count($argv) <= 1 || count($argv) >= 4) {
	echo "dnstrace - fqdn_add.php" . PHP_EOL;
	echo "  A tool to add an FQDN to dnstrace's database." . PHP_EOL;
	echo PHP_EOL;
	echo "Usage: php fqdn_add.php \"[fqdn]\" \"[rep_flag]\"" . PHP_EOL;
	echo "  If no reputation flag is specified, USERADD (reputation 0) will be used." . PHP_EOL;
	include "inc/exit.php";
}

if(count($argv) == 2) {
	$argRep = "USERADD";
} else {
	$argRep = strtoupper($argv[2]);
}
$argFQDN = $argv[1];

$dbCheckSrcRepExists = $mysqli->query("SELECT * FROM `Sources` WHERE `Sources`.`ID` = '" . $argRep . "'");
if($dbCheckSrcRepExists->num_rows != 1) {
	echo "Cannot find reputation flag, please check your input or run rep_add.php" . PHP_EOL;
	include "inc/exit.php";
}

$getDomainBySOA = gdnsGetSOA($argFQDN);
if($getDomainBySOA[0]) {
	$dbInsertNewFQDN = $mysqli->query("INSERT INTO `Reputation` (`FQDN`, `Domain`, `Source`) VALUES ('" . $argFQDN . "', '" . $getDomainBySOA[1] . "', '". $argRep . "')");
	
	if(!$dbInsertNewFQDN) {
		echo "There was an error running the insert query. No FQDN added." . PHP_EOL;
		echo "SQL error information: " . $mysqli->error . PHP_EOL;
		include "inc/exit.php";
	}
	
	echo "Added \"" . $argFQDN . "\" with flag " . $argRep . PHP_EOL;
} else {
	echo "Domain \"" . $argFQDN . "\" not added" . PHP_EOL;
}
include "inc/exit.php";
?>