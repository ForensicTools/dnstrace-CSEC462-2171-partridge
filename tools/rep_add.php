<?php
/* tools/rep_add.php
 * Add reputation flag and associated data to database.
 */

include "inc/setup.php";

if(count($argv) != 4) {
	echo "dnstrace - rep_add.php" . PHP_EOL;
	echo "  A tool to add a reputation flag and source information to dnstrace's database." . PHP_EOL;
	echo PHP_EOL;
	echo "Usage: php rep_add.php \"[flag]\" \"[description]\" \"[score]\"" . PHP_EOL;
	echo "  All values must be present and ideally encapsulated in quotation marks." . PHP_EOL;
	echo "  A good suggestion for flag scoring weight is as follows:" . PHP_EOL;
	echo "     1 for known-good categories      - ex. whitelisted domains, trusted sites" . PHP_EOL;
	echo "     0 for unknown/neutral categories - ex. data streamed in live from users" . PHP_EOL;
	echo "    -1 for known-bad categories       - ex. phishing, malware, ek, zeus, the list goes on" . PHP_EOL;
	include "inc/exit.php";
}

$argFlag = $argv[1];
$argDesc = $argv[2];
$argScore = intval($argv[3]);

$dbInsertNewID = $mysqli->query("INSERT INTO `Sources` (`ID`, `Description`, `Score`) VALUES ('" . $argFlag . "', '" . $argDesc . "', '" . $argScore . "')");
if(!$dbInsertNewID) {
	echo "There was an error running the insert query. No flag added." . PHP_EOL;
	echo "SQL error information: " . $mysqli->error . PHP_EOL;
	include "inc/exit.php";
}

echo "Added \"" . $argFlag . "\" with score " . strval($argScore) . PHP_EOL;
include "inc/exit.php";
?>