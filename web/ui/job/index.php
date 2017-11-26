<?php
/* web/ui/job/index.php
 * Check the status of a given job and refresh occasionally.
 */
 
include '../../base.php';

$jobID = intval($_GET["id"]);
$api = file_get_contents("https://" . $configWhoami . "/api/job_check.php?id=" . $jobID);

$apiData = json_decode($api, true);

if($apiData["Success"] == 0) {
	echo "Couldn't find job, or found too many.";
} else {
	$jobData = $apiData["Data"];
	
	if(strcmp($jobData["Current"], "DONE") !== 0) {
		echo '<!DOCTYPE html><html>';
		echo '<head><meta http-equiv="refresh" content="5; url=https://dnstrace.pro/ui/job/?id='.$jobData["JobID"].'" /></head>';
		echo '<body><p>Your job is currently processing. Its status is currently "'.$jobData["Current"].'" and this was last updated at '.$jobData["TimeEnd"].'</p><br><p>Click <a href="https://dnstrace.pro/ui/job/?id='.$jobData["JobID"].'">here</a> to update this page manually. Otherwise, it will refresh every 5 seconds.</p></body>';
		echo '</html>';
	} else {
		echo '<!DOCTYPE html><html>';
		echo '<head><meta http-equiv="refresh" content="0; url=https://dnstrace.pro/ui/domain-graph/?job='.$jobData["JobID"].'" /></head>';
		echo '<body><p><a href="https://dnstrace.pro/ui/domain-graph/?job='.$jobData["JobID"].'">Click here to go to the graph if you are not automatically redirected.</a></p></body>';
		echo '</html>';
	}
}
?>