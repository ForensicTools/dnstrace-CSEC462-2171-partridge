<?php
/* tools/inc/setup.php
 * Preparation script for administrative tools.
 *
 * On success, initializes $mysqli, a connection to the user's RDBMS as admin.
 */

if((@include "config.php") === false) {
	echo "tools/config.php missing, please create that file from tools/config.example.php" . PHP_EOL;
	exit();
}

if((@include "../vendor/autoload.php") === false) {
	echo "../vendor/autoload.php missing, please check Composer section of SETUP.md" . PHP_EOL;
	exit();
}

$mysqli = new mysqli($configDbAddr, $configDbUser, $configDbPass, $configDbDb);
if ($mysqli->connect_errno) {
	exit();
}

include "../vendor/autoload.php";
?>