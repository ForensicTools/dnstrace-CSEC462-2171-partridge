<?php
include "../../base.php";
$configPage = "Job";

$jobID = intval($_GET["id"]);
$api = file_get_contents("https://" . $configWhoami . "/api/job_check.php?id=" . $jobID);

$apiData = json_decode($api, true);

if($apiData["Success"] == 0) {
	$failed = true;
} else {
	$jobData = $apiData["Data"];
	$failed = false;
	
	if(strcmp($jobData["Current"], "DONE") === 0) {
		echo '<!DOCTYPE html><html>';
		echo '<head><meta http-equiv="refresh" content="0; url=https://dnstrace.pro/ui/domain-graph/?job='.$jobData["JobID"].'" /></head>';
		echo '<body><p><a href="https://dnstrace.pro/ui/domain-graph/?job='.$jobData["JobID"].'">Click here to go to the graph if you are not automatically redirected.</a></p></body>';
		echo '</html>';
		exit();
	}
}

include "../_inc/format.php";
?>

<!-- Page Content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">IN PROGRESS</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

<?php
include "../_inc/terminate.php";
?>