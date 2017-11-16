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
			if(strlen($row["Subdomain"]) > 0) {
				$parsedRow = tld_extract($row["Subdomain"] . "." . $row["Domain"]);
			} else {
				$parsedRow = tld_extract($row["Domain"]);
			}
			
			if(strlen($parsedRow->getFullHost()) > 60) {
				$disp = "... " . substr($parsedRow->getFullHost(), strlen($parsedRow->getFullHost()) - 60, strlen($parsedRow->getFullHost()));
			} else {
				$disp = $parsedRow->getFullHost();
			}
			
			exec("php worker.php \"" . $parsedRow->getFullHost() . "\" > /dev/null &");
			echo "Assigned worker to " . $disp . PHP_EOL;
			$mysqli->query("UPDATE `Worker` SET Count = Count + 1");
			$stay = false;
		} else {
			usleep(50000);
		}
	}
}

include "inc/exit.php";
?>