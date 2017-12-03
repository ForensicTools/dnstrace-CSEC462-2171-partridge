<?php
/* web/api/process_manager.php
 * Frontend data generation manager
 */
 
include "inc/setup.php";

if(count($argv) != 2) {
	echo "dnstrace - process_manager.php" . PHP_EOL;
	echo "  Regulates data generation to prevent server overloads." . PHP_EOL;
	echo PHP_EOL;
	echo "Usage: php process_manager.php [# of workers]" . PHP_EOL;
	echo "  The #workers should be roughly double your available cores." . PHP_EOL;
	include "inc/exit.php";
}

$maxWkr = intval($argv[1]);
$mysqli->query("DELETE FROM `Processors`");
$mysqli->query("INSERT INTO `Processors` (Count) VALUES (0)");

while(true) {
	$dbWorker = $mysqli->query("SELECT * FROM `Processors`");
	$currentCtr = $dbWorker->fetch_assoc()["Count"];
	
	if($maxWkr > $currentCtr) {
		$dbGet = $mysqli->query("SELECT * FROM `Jobs` WHERE `Current` = 'WAITING' ORDER BY `JobID` ASC");
		
		if(mysqli_num_rows($dbGet) == 0) {
			sleep(10);
		} else {
			$newThread = $dbGet->fetch_assoc();
			$mysqli->query('UPDATE `Jobs` SET `Current` = "STARTING" WHERE `JobID` = ' . $newThread["JobID"]);
			
			exec("php domain_graph.php " . $newThread["JobID"] . " > jobs/" . $newThread["JobID"] . ".json &");
		
			echo "(".($currentCtr+1)."/".$maxWkr." processing threads active)" . PHP_EOL;
			$mysqli->query("UPDATE `Processors` SET Count = Count + 1");
			
			sleep(1);
		}
	} else {
		sleep(5);
	}
}

include "inc/exit.php";
?>