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

if(!chdir("../deps/czdap-tools/zonedata-download")) {
	echo "czdap-tools does not exist, run setup/deps.sh." . PHP_EOL;
	include "inc/exit.php";
}

echo "Downloading zones from CZDS API, this may take a while..." . PHP_EOL;
exec("python download.py");
if(!chdir("zonefiles")) {
	echo "No zonefiles retrieved?" . PHP_EOL;
	include "inc/exit.php";
}

echo "Extracting downloaded zones, this may take a while..." . PHP_EOL;
exec("gunzip *");

$fileList = [];
$zoneDir = getcwd();
$allZones = glob("*.txt");
foreach($allZones as $zoneTxt){
    $fileList[] = $zoneDir . "/" . $zoneTxt;
}

chdir("../../../../tools");

foreach($fileList as $thisFile) {
	$stay = true;
	while($stay) {
		$dbWorker = $mysqli->query("SELECT * FROM `Worker_Zone`");
		$currentCtr = $dbWorker->fetch_assoc()["Count"];
		
		if($maxWkr > $currentCtr) {
			exec("php worker_zone.php \"" . $thisFile . "\" > /dev/null &");
			echo "assigned \"" . $flag . "\" (".($currentCtr+1)."/".$maxWkr." workers active)" . PHP_EOL;
			
			$mysqli->query("UPDATE `Worker_Zone` SET Count = Count + 1");
			$stay = false;
		} else {
			usleep(50000);
		}
	}
}

//exec("rm -r ../deps/czdap-tools/zonedata-download/zonefiles");

include "inc/exit.php";
?>