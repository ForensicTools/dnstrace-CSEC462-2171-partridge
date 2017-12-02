<?php
/* tools/worker_zone.php
 * Update single zone by loading zone into database.
 */

ini_set('memory_limit', '1G'); // you'd be surprised

include "inc/setup.php";
include "inc/gdns.php";

if(count($argv) != 2) {
	echo "dnstrace - worker_zone.php" . PHP_EOL;
	echo "  This shouldn't be invoked manually unless it's for debugging." . PHP_EOL;
	echo PHP_EOL;
	echo "Usage: php worker_zone.php \"path_to_zonefile\"" . PHP_EOL;
	echo "  Given zonefile will be parsed and loaded into database." . PHP_EOL;
	include "inc/exit.php";
}

use LayerShifter\TLDExtract\Extract;
$ext = new Extract(null, null, Extract::MODE_ALLOW_ICANN);
$doneDomains = [];

if(file_exists($argv[1])) {
	$fileName = $argv[1];
	$fileHandle = fopen($fileName, "r");
	echo "Adding zonefile to database..." . PHP_EOL;
	$setRep = false;

	while (!feof($fileHandle)) {
		$lineRaw = fgets($fileHandle);
		$lineClean = explode("\t", $lineRaw);
		
		if(count($lineClean) > 3) {
			if(strcmp($lineClean[3], "ns") === 0) {
				$fixedDomain = rtrim($lineClean[0], ".");
				$parsedDomain = $ext->parse($fixedDomain);
				
				if(!$parsedDomain->isValidDomain()) {
					echo "The domain given does not appear to be valid. Not added." . PHP_EOL;
					echo "Got: " . $fixedDomain . PHP_EOL;
				} else {
					if(!$setRep) {
						$flag = "ZONE-" . strtoupper($parsedDomain["suffix"]);
						exec('php rep_add.php "' . $flag . '" "Zone data for TLD \"' . $parsedDomain["suffix"] . '\" from CZDS (Centralized Zone Data Service) at https://czds.icann.org/" "0" > /dev/null');
						$setRep = true;
					}
					if(!isset($doneDomains[$fixedDomain])) {
						$dbInsertNewDomain = $mysqli->query("INSERT INTO `Reputation` (`Subdomain`, `Domain`, `Source`) VALUES ('" . $parsedDomain["subdomain"] . "', '" . $parsedDomain->getRegistrableDomain() . "', '". $flag . "')");

						if(!$dbInsertNewDomain) {
							echo "There was an error running the insert query. No domain added." . PHP_EOL;
							echo "SQL error information: " . $mysqli->error . PHP_EOL;
						}
						$doneDomains[$fixedDomain] = 1;
					}
				}
			}
		}
	}
	fclose($fileHandle);
}

$mysqli->query("UPDATE `Worker_Zone` SET Count = Count - 1");

include "inc/exit.php";
?>