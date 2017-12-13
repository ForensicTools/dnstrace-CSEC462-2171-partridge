<?php
/* web/api/badger_req.php
 * Request job for badger client.
 */

include "inc/setup.php";
include '../base.php';
use LayerShifter\TLDExtract\Extract;

$ext = new Extract(null, null, Extract::MODE_ALLOW_ICANN);

if(array_key_exists("key", $_GET)) {
	$key = mysqli_real_escape_string($mysqli, htmlspecialchars($_GET["key"]));
} else {
	echo json_encode(array("Success" => false, "Reason" => "No key provided."));
	include "inc/exit.php";
}

$dbGet = $mysqli->query("SELECT * FROM `API_Keys` WHERE `Key` = '" . $jobKey . "'");

if(mysqli_num_rows($dbGet) == 0) {
	echo json_encode(array("Success" => false, "Reason" => "Invalid key."));
	include "inc/exit.php";
}

$dbInsertJob = $mysqli->query("INSERT INTO `BADGER_Jobs` (`BadgerID`) VALUES ('" . $key . "')");

if(!$dbInsertJob) {
	echo json_encode(array("Success" => false, "Reason" => "Error requesting job. Please contact the administrator."));
	include "inc/exit.php";
}

echo json_encode(array("Success" => true, "Reason" => "NA"));
include "inc/exit.php";
?>