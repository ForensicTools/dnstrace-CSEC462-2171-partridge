<?php
/* web/api/badger_submit.php
 * Submit job for validation.
 */

include "inc/setup.php";
include '../base.php';
use LayerShifter\TLDExtract\Extract;

$ext = new Extract(null, null, Extract::MODE_ALLOW_ICANN);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$post = json_decode(file_get_contents("php://input"), true);
} else {
	echo json_encode(array("Success" => false, "Reason" => "You need to POST to this endpoint."));
	include "inc/exit.php";
}

if($post === null) {
	echo json_encode(array("Success" => false, "Reason" => "Error decoding JSON serverside."));
	include "inc/exit.php";
}

if(array_key_exists("key", $post) && array_key_exists("fqdn", $post) && array_key_exists("type", $post) && array_key_exists("result", $post)) {
	$key = mysqli_real_escape_string($mysqli, $post["key"]);
	$postedFQDN = mysqli_real_escape_string($mysqli, $post["fqdn"]);
	$postedType = mysqli_real_escape_string($mysqli, $post["type"]);
	$postedResult = $post["result"];
} else {
	echo json_encode(array("Success" => false, "Reason" => "Missing critical field of data."));
	include "inc/exit.php";
}

$dbGet = $mysqli->query("SELECT * FROM `API_Keys` WHERE `Key` = '" . $key . "'");

if(mysqli_num_rows($dbGet) == 0) {
	echo json_encode(array("Success" => false, "Reason" => "Invalid key."));
	include "inc/exit.php";
}

$dbInsertJob = $mysqli->query("INSERT INTO `BADGER_Validate` (`BadgerID`, `FQDN`, `Type`, `Data`) VALUES ('" . $key . "', '" . $postedFQDN . "', '" . $postedType . "', '" . json_encode($postedResult) . "')");

if(!$dbInsertJob) {
	echo json_encode(array("Success" => false, "Reason" => "Error saving data. Please contact the administrator."));
	include "inc/exit.php";
}

echo json_encode(array("Success" => true, "Reason" => "NA"));
include "inc/exit.php";
?>