<?php
/* tools/update.php
 * 
 */

include "inc/setup.php";

if(count($argv) != 2) {
	echo "dnstrace - update.php" . PHP_EOL;
	echo "  ." . PHP_EOL;
	echo PHP_EOL;
	echo "Usage: php update.php [# of workers]" . PHP_EOL;
	echo "  ." . PHP_EOL;
	include "inc/exit.php";
}

use LayerShifter\TLDExtract\Extract;

$maxWkr = intval($argv[1]);
$mysqli->query("TRUNCATE `Worker`");
$mysqli->query("INSERT INTO `Worker` (Count) VALUES(0)");

$allDomains = $mysqli->query("SELECT * FROM `Reputation`");
while($row = $allDomains->fetch_assoc()) {
	$stay = true;
	while($stay) {
		$dbWorker = $mysqli->query("SELECT * FROM `Worker`");
		$currentCtr = $dbWorker->fetch_assoc()["Count"];
		
		if($maxWkr > $currentCtr) {
			$ext = new Extract(null, null, Extract::MODE_ALLOW_ICANN);
			
			if(strlen($row["Subdomain"]) > 0) {
				$parsedRow = $ext->parse($row["Subdomain"] . "." . $row["Domain"]);
			} else {
				$parsedRow = $ext->parse($row["Domain"]);
			}
			
			exec("php worker.php \"" . $parsedRow->getFullHost() . "\" > /dev/null &");
			
			if(strlen($parsedRow->getFullHost()) > 60) {
				$disp = "... " . substr($parsedRow->getFullHost(), strlen($parsedRow->getFullHost()) - 60, strlen($parsedRow->getFullHost()));
			} else {
				$disp = $parsedRow->getFullHost();
			}
			
			echo "(".$currentCtr."/".$maxWkr.") assigned worker to " . $disp . PHP_EOL;
			
			$mysqli->query("UPDATE `Worker` SET Count = Count + 1");
			$stay = false;
		} else {
			usleep(50000);
		}
	}
}

include "inc/exit.php";
?>