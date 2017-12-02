<?php
/* tools/zone_update.php
 * Execute a full update of all zone files you have access to using czdap-tools
 */

include "inc/setup.php";

if(count($argv) != 2) {
	echo "dnstrace - zone_update.php" . PHP_EOL;
	echo "  Fetches, unpacks, and loads zone data." . PHP_EOL;
	echo "  Ideally, run this automatically off-use-hours for minimal business impact." . PHP_EOL;
	echo PHP_EOL;
	echo "Usage: php zone_update.php [# of workers]" . PHP_EOL;
	echo "  For maximum performance, the # of workers should be the # of cores available." . PHP_EOL;
	include "inc/exit.php";
}

$maxWkr = intval($argv[1]);
$mysqli->query("TRUNCATE `Worker_Zone`");
$mysqli->query("INSERT INTO `Worker_Zone` (Count) VALUES(0)");

$allDomains = $mysqli->query("SELECT * FROM `Reputation`");
while($row = $allDomains->fetch_assoc()) {
	$stay = true;
	while($stay) {
		$dbWorker = $mysqli->query("SELECT * FROM `Worker_Zone`");
		$currentCtr = $dbWorker->fetch_assoc()["Count"];
		
		if($maxWkr > $currentCtr) {
			if(strlen($row["Subdomain"]) > 0) {
				exec("php worker_dns.php \"". $row["Subdomain"] . "." . $row["Domain"] . "\" > /dev/null &");
				echo "assigned \"". $row["Subdomain"] . "." . $row["Domain"] . "\" ";
			} else {
				exec("php worker_dns.php \"" . $row["Domain"] . "\" > /dev/null &");
				echo "assigned \"" . $row["Domain"] . "\" ";
			}
			
			echo "(".($currentCtr+1)."/".$maxWkr." workers active)" . PHP_EOL;
			
			$mysqli->query("UPDATE `Worker_Zone` SET Count = Count + 1");
			$stay = false;
		} else {
			usleep(50000);
		}
	}
}

include "inc/exit.php";
?>