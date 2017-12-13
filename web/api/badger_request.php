<?php
/* web/api/badger_request.php
 * Request job for badger client.
 */

include "inc/setup.php";
include '../base.php';

if(array_key_exists("key", $_GET) && array_key_exists("num", $_GET)) {
	$key = mysqli_real_escape_string($mysqli, htmlspecialchars($_GET["key"]));
	$num = intval($_GET["num"]);
} else {
	echo json_encode(array("Success" => false, "Reason" => "Missing critical field of data."));
	include "inc/exit.php";
}

if($num < 1 || $num > 10000) {
	echo json_encode(array("Success" => false, "Reason" => "Requested domains out of bounds."));
	include "inc/exit.php";
}

$dbGet = $mysqli->query("SELECT * FROM `API_Keys` WHERE `Key` = '" . $key . "'");

if(mysqli_num_rows($dbGet) == 0) {
	echo json_encode(array("Success" => false, "Reason" => "Invalid key."));
	include "inc/exit.php";
}

$dbInsertJob = $mysqli->query("INSERT INTO `BADGER_Jobs` (`BadgerID`, `Requested`) VALUES ('" . $key . "', " . $num . ")");

if(!$dbInsertJob) {
	echo json_encode(array("Success" => false, "Reason" => "Error requesting job. Please contact the administrator."));
	include "inc/exit.php";
}

echo json_encode(array("Success" => true, "Reason" => "NA"));
include "inc/exit.php";
?>