<?php
/* web/api/badger_submit.php
 * Submit job for validation.
 */

include "inc/setup.php";
include '../base.php';
use LayerShifter\TLDExtract\Extract;

$ext = new Extract(null, null, Extract::MODE_ALLOW_ICANN);

if(array_key_exists("key", $_POST) && array_key_exists("fqdn", $_POST) && array_key_exists("type", $_POST) && array_key_exists("result", $_POST)) {
	$key = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST["key"]));
	$postedFQDN = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST["fqdn"]));
	$postedType = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST["type"]));
	$postedResult = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST["result"]));
} else {
	echo json_encode(array("Success" => false, "Reason" => "Missing critical field of data."));
	include "inc/exit.php";
}

$dbGet = $mysqli->query("SELECT * FROM `API_Keys` WHERE `Key` = '" . $key . "'");

if(mysqli_num_rows($dbGet) == 0) {
	echo json_encode(array("Success" => false, "Reason" => "Invalid key."));
	include "inc/exit.php";
}

$json = json_decode($postedResult, true);
	
if(!$json) {
	echo json_encode(array("Success" => false, "Reason" => "Error decoding data. Please contact the administrator."));
	include "inc/exit.php";
}	

$dbInsertJob = $mysqli->query("INSERT INTO `BADGER_Validate` (`Key`, `FQDN`, `Type`, `Data`) VALUES ('" . $key . "', '" . $postedFQDN . "', '" . $postedType . "', '" . $postedResult . "')");

if(!$dbInsertJob) {
	echo json_encode(array("Success" => false, "Reason" => "Error saving data. Please contact the administrator."));
	include "inc/exit.php";
}

echo json_encode(array("Success" => true, "Reason" => "NA"));
include "inc/exit.php";
?>