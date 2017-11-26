<?php
/* web/api/job-create.php
 * Create job and validate inputs.
 */

include "inc/setup.php";
use LayerShifter\TLDExtract\Extract;

$ext = new Extract(null, null, Extract::MODE_ALLOW_ICANN);

$jobFQDN = mysqli_real_escape_string($mysqli, htmlspecialchars($_GET["domain"]));
$jobDegrees = intval($_GET["degree"]);
$jobMXEN = intval($_GET["mxen"]);
$jobNSEN = intval($_GET["nsen"]);
if(array_key_exists("key", $_GET)) {
	$jobKey = mysqli_real_escape_string($mysqli, htmlspecialchars($_GET["key"]));
} else {
	$jobKey = "NO_KEY_PROVIDED";
}

if(($jobMXEN != 0 && $jobMXEN != 1) || ($jobNSEN != 0 && $jobNSEN != 1)) {
	echo json_encode(array("Success" => false, "Reason" => "MXEN or NSEN not binary [0,1]"));
	include "inc/exit.php";
}

$dbGet = $mysqli->query("SELECT * FROM `API_Keys` WHERE `Key` = '" . $jobKey . "'");

if(mysqli_num_rows($dbGet) == 0) {
	if($jobMXEN == 1 || $jobNSEN == 1) {
		if($jobDegrees > 1) {
			echo json_encode(array("Success" => false, "Reason" => "Must have valid API key for multi-degree jobs involving MX or NS records. Please contact the administrator."));
			include "inc/exit.php";
		}
	}
	if($jobDegrees > 2) {
		echo json_encode(array("Success" => false, "Reason" => "Must have valid API key for high-degree jobs. Please contact the administrator."));
		include "inc/exit.php";
	}
}

if($jobDegrees > 4) {
	echo json_encode(array("Success" => false, "Reason" => "C'mon. I do this for free, out of pocket. Don't DoS me."));
	include "inc/exit.php";
}

$lookupFQDN = $ext->parse($jobFQDN);

if(!$lookupFQDN->isValidDomain()) {
	echo json_encode(array("Success" => false, "Reason" => "Invalid domain"));
	include "inc/exit.php";
}

$dbGet = $mysqli->query("SELECT * FROM `Reputation` WHERE `Domain` = '" . $lookupFQDN->getRegistrableDomain() . "'");

if(mysqli_num_rows($dbGet) == 0) {
	echo json_encode(array("Success" => false, "Reason" => "Domain not in database"));
	include "inc/exit.php";
}

$dbInsertJob = $mysqli->query("INSERT INTO `Jobs` (`Key`, `Domain`, `Degree`, `MXEN`, `NSEN`) VALUES ('" . $jobKey . "', '" . $lookupFQDN->getRegistrableDomain() . "', " . $jobDegrees . ", " . $jobMXEN . ", ". $jobNSEN . ")");

if(!$dbInsertJob) {
	echo json_encode(array("Success" => false, "Reason" => "Error interacting with database. Please contact the administrator."));
	include "inc/exit.php";
}

if($_GET["ui"] == 1) {
	echo '<!DOCTYPE html><html>';
	echo '<head><meta http-equiv="refresh" content="0; url=https://dnstrace.pro/ui/job/?id='.$mysqli->insert_id.'" /></head>';
	echo '<body><p><a href="https://dnstrace.pro/ui/job/?id='.$mysqli->insert_id.'">Click here to go to the status page if you are not automatically redirected.</a></p></body>';
	echo '</html>';
} else {
	echo json_encode(array("Success" => true, "JobID" => $mysqli->insert_id));
}
?>