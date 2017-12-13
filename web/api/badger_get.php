<?php
/* web/api/badger_get.php
 * Get job data for badger client.
 */

include "inc/setup.php";
include '../base.php';

if(array_key_exists("key", $_GET)) {
	$key = mysqli_real_escape_string($mysqli, htmlspecialchars($_GET["key"]));
} else {
	echo json_encode(array("Success" => false, "Reason" => "Missing key."));
	include "inc/exit.php";
}

$dbGet = $mysqli->query("SELECT * FROM `API_Keys` WHERE `Key` = '" . $key . "'");

if(mysqli_num_rows($dbGet) == 0) {
	echo json_encode(array("Success" => false, "Reason" => "Invalid key."));
	include "inc/exit.php";
}

$dbGetTemp = $mysqli->query("SELECT * FROM `BADGER_Temp` WHERE `BadgerID` = '" . $key . "'");

if(!$dbGetTemp) {
	echo json_encode(array("Success" => false, "Reason" => "Data not prepared yet or DNE."));
	include "inc/exit.php";
} else {
	$temp = $dbGetTemp->fetch_assoc();
}

$dbDel = $mysqli->query("DELETE FROM `BADGER_Temp` WHERE `BadgerID` = '" . $key . "'");
if(!$dbGetTemp) {
	echo json_encode(array("Success" => false, "Reason" => "Delete failed, contact the admin."));
	include "inc/exit.php";
}

echo json_encode(array("Success" => true, "Todo" => $temp["Data"]));
include "inc/exit.php";
?>