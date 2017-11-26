<?php
/* web/api/job-check.php
 * Check the status of a given job.
 */

include "inc/setup.php";
$jobID = intval($_GET["id"]);

$dbGet = $mysqli->query("SELECT * FROM `Jobs` WHERE `JobID` = " . $jobID);
		
if(mysqli_num_rows($dbGet) == 0) {
	echo json_encode(array("Success" => false, "Reason" => "JobID not found."));
	include 'inc/exit.php';
} else {
	$jobData = $dbGet->fetch_assoc();
	unset($jobData["Key"]); // data leakage
	echo json_encode(array("Success" => true, "Data" => $jobData));
}

include 'inc/exit.php';