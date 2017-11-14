<?php
/* tools/fqdn_add.php
 * Add FQDN to database for parsing.
 */

include "inc/setup.php";

if(count($argv) <= 1 || count($argv) >= 4) {
	echo "dnstrace - fqdn_add.php" . PHP_EOL;
	echo "  A tool to add an FQDN (or many FQDNs) to dnstrace's database." . PHP_EOL;
	echo PHP_EOL;
	echo "Usage: php fqdn_add.php \"[fqdn/list]\" \"[rep_flag]\"" . PHP_EOL;
	echo "  If the fqdn/list field is a path to a file, one domain per line will be added." . PHP_EOL;
	echo "     If not, the fqdn/list field will be parsed as a single fqdn." . PHP_EOL;
	echo "  If no reputation flag is specified, USERADD (reputation 0) will be used." . PHP_EOL;
	include "inc/exit.php";
}

if(count($argv) == 2) {
	$argRep = "USERADD";
} else {
	$argRep = strtoupper($argv[2]);
}

function addFQDN($argFQDN, $argRep, $mysqli) {
	$parsedFQDN = tld_extract($argFQDN);

	if(!$parsedFQDN->isValidDomain()) {
		echo "The domain given does not appear to be valid. No FQDN added." . PHP_EOL;
		echo "Got: " . $argFQDN . PHP_EOL;
	} else {
		$dbInsertNewFQDN = $mysqli->query("INSERT INTO `Reputation` (`Subdomain`, `Domain`, `Source`) VALUES ('" . $parsedFQDN["subdomain"] . "', '" . $parsedFQDN->getRegistrableDomain() . "', '". $argRep . "')");

		if(!$dbInsertNewFQDN) {
			echo "There was an error running the insert query. No FQDN added." . PHP_EOL;
			echo "SQL error information: " . $mysqli->error . PHP_EOL;
		} else {
			echo "Added \"" . $argFQDN . "\" with flag " . $argRep . PHP_EOL;
		}
	}
}

$dbCheckSrcRepExists = $mysqli->query("SELECT * FROM `Sources` WHERE `Sources`.`ID` = '" . $argRep . "'");
if($dbCheckSrcRepExists->num_rows != 1) {
	echo "Cannot find reputation flag, please check your input or run rep_add.php" . PHP_EOL;
	include "inc/exit.php";
}

if(file_exists($argv[1])) {
	$fileName = $argv[1];
	$fileHandle = fopen($fileName, "r");

	while (!feof($fileHandle)) {
		$lineRaw = fgets($fileHandle);
		$lineClean = str_replace(array("\r\n", "\r", "\n"), "", $lineRaw);
		
		if(strlen($lineClean) == 0) {
			continue;
		}
		
		addFQDN($lineClean, $argRep, $mysqli);
	}

	fclose($fileHandle);
} else {
	$argFQDN = $argv[1];
	addFQDN($argFQDN, $argRep, $mysqli);
}

include "inc/exit.php";
?>