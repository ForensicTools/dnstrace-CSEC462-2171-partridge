<?php
/* tools/badger_initializer.php
 * Primitive badger job creator/initializer
 */

include "inc/setup.php";

function fixDomain($subdomain, $domain) {
	if(strlen($subdomain) > 0) {
		return $subdomain . "." . $domain;
	} else {
		return $domain;
	}
}

use LayerShifter\TLDExtract\Extract;
$ext = new Extract(null, null, Extract::MODE_ALLOW_ICANN);
$offset = 0;

while(true) {
	$dbGetJobs = $mysqli->query("SELECT * FROM `BADGER_Jobs` WHERE `Issued` = 0");
	$fqdns = [];
	if(!$dnsGetJobs) {
		sleep(2);
	} else {
		while($aJob = $dbGetJobs->fetch_assoc()) {
			$dbGetDomains = $mysqli->query("SELECT * FROM `Reputation` LIMIT " . $aJob["Requested"] . " OFFSET " . $offset);
			
			$numRet = $dbGetDomains->num_rows;
			
			while($aDomain = $dbGetDomains->fetch_assoc()) {
				$fqdns[] = fixDomain($aDomain["Subdomain"], $aDomain["Domain"]);
			}
			
			$dbGetJobs = $mysqli->query("UPDATE `BADGER_Jobs` SET `IdxStart` = '" . $offset . "', `IdxEnd` = '" . ($offset + $numRet) . "', `Issued` = 1 WHERE `BadgerID` = '" . $aJob["BadgerID"] . "'");
			
			$data = json_encode($fqdns));
			$dbInsertJob = $mysqli->query("INSERT INTO `BADGER_Temp` (`BadgerID`, `Data`) VALUES ('" . $key . "', " . $data . ")");

			echo "Issued " . $offset . " -> " . ($offset + $numRet) . " to " . $aJob["BadgerID"] . PHP_EOL;
			
			if($numRet < $aJob["Requested"]) {
				$offset = 0;
			} else {
				$offset = $offset + $numRet;
			}
			$dbGetDomains->free();
		}
		$dbGetJobs->free();
	}
	sleep(1);
}
?>