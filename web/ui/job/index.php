<?php
include "../../base.php";
$configPage = "Job";

$jobID = intval($_GET["id"]);

include "../_inc/format.php";
?>

<!-- Page Content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Job Status</h1>
			</div>
			<div class="col-lg-8">
				<div class="panel-body">
					<div id="colorStatus" class="alert alert-warning">
						<p id="colorText">Establishing connection with dnstrace API.</p>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<style type="text/css">
					*{margin:0;padding:0;}
					body{
						background:#fff;
						/*background:#ddd;*/
						position:fixed;
						left:0;
						right:0;
						top:0;
						bottom:0;
					}
					.loader{
						position: relative;
						width: 200px;
						height: 200px;
						top: 50%;
						left: 50%;
						margin-top: 100px;
						margin-left: -100px;/*
						background: #fff;
						border-radius: 6px;
						box-shadow: 0 0 6px -2px #666;*/
						transform: rotate(45deg);
					}
					.square{
						position: absolute;
						width: 60px;
						height: 60px;
						background-color: #69f;
						border-radius: 3px;
						left: 70px;
						top: 5px;
									
						background: linear-gradient(45deg,#3edb8d, #2caa6b, #1e8ec0, #973b5e, #7533a8, #6338ba);
						background-size: 1000% 1000%;
						animation: colorGradientFade 16s ease infinite;
					}
					@keyframes colorGradientFade {
						0%{background-position:0% 50%}
						50%{background-position:100% 50%}
						100%{background-position:0% 50%}
					}
					#sqr0{
						left: 5px;
						top: 5px;
						animation:sqr0 12s ease infinite,colorGradientFade 16s ease infinite;
					}@keyframes sqr0{
							 0%{left:  5px;top:  5px;}
						 7.142%{left:  5px;top:  5px;}
						 8.928%{left: 70px;top:  5px;}
						19.642%{left: 70px;top:  5px;}
						21.428%{left:135px;top:  5px;}
						32.142%{left:135px;top:  5px;}
						33.928%{left:135px;top: 70px;}
						44.642%{left:135px;top: 70px;}
						46.428%{left: 70px;top: 70px;}
						57.142%{left: 70px;top: 70px;}
						58.928%{left: 70px;top:135px;}
						69.642%{left: 70px;top:135px;}
						71.428%{left:  5px;top:135px;}
						82.142%{left:  5px;top:135px;}
						83.928%{left:  5px;top: 70px;}
						94.642%{left:  5px;top: 70px;}
						96.428%{left:  5px;top:  5px;}
					}
					#sqr1{
						left: 70px;
						top: 5px;
						animation:sqr1 12s ease infinite,colorGradientFade 16s ease infinite;
					}@keyframes sqr1{
							 0%{left: 70px;top:  5px;}
						 5.357%{left: 70px;top:  5px;}
						 7.142%{left:135px;top:  5px;}
						17.857%{left:135px;top:  5px;}
						19.642%{left:135px;top: 70px;}
						30.357%{left:135px;top: 70px;}
						32.142%{left: 70px;top: 70px;}
						42.857%{left: 70px;top: 70px;}
						44.642%{left: 70px;top:135px;}
						55.357%{left: 70px;top:135px;}
						57.142%{left:  5px;top:135px;}
						67.857%{left:  5px;top:135px;}
						69.642%{left:  5px;top: 70px;}
						80.357%{left:  5px;top: 70px;}
						82.142%{left:  5px;top:  5px;}
						92.857%{left:  5px;top:  5px;}
						94.642%{left: 70px;top:  5px;}
					}
					#sqr2{
						left: 135px;
						top: 5px;
						animation:sqr2 12s ease infinite,colorGradientFade 16s ease infinite;
					}@keyframes sqr2{
							 0%{left:135px;top:  5px;}
						 3.571%{left:135px;top:  5px;}
						 5.357%{left:135px;top: 70px;}
						16.071%{left:135px;top: 70px;}
						17.857%{left: 70px;top: 70px;}
						28.571%{left: 70px;top: 70px;}
						30.357%{left: 70px;top:135px;}
						41.071%{left: 70px;top:135px;}
						42.857%{left:  5px;top:135px;}
						53.571%{left:  5px;top:135px;}
						55.357%{left:  5px;top: 70px;}
						66.071%{left:  5px;top: 70px;}
						67.857%{left:  5px;top:  5px;}
						78.571%{left:  5px;top:  5px;}
						80.357%{left: 70px;top:  5px;}
						91.071%{left: 70px;top:  5px;}
						92.857%{left:135px;top:  5px;}
					}
					#sqr3{
						left: 5px;
						top: 70px;
						animation:sqr3 12s ease infinite,colorGradientFade 16s ease infinite;
					}@keyframes sqr3{
							 0%{left:  5px;top: 70px;}
						 8.928%{left:  5px;top: 70px;}
						10.714%{left:  5px;top:  5px;}
						21.428%{left:  5px;top:  5px;}
						23.214%{left: 70px;top:  5px;}
						33.928%{left: 70px;top:  5px;}
						35.714%{left:135px;top:  5px;}
						46.428%{left:135px;top:  5px;}
						48.214%{left:135px;top: 70px;}
						58.928%{left:135px;top: 70px;}
						60.714%{left: 70px;top: 70px;}
						71.428%{left: 70px;top: 70px;}
						73.214%{left: 70px;top:135px;}
						83.928%{left: 70px;top:135px;}
						85.714%{left:  5px;top:135px;}
						96.428%{left:  5px;top:135px;}
						98.214%{left:  5px;top: 70px;}
					}
					#sqr4{
						left: 70px;
						top: 70px;
						animation:sqr4 12s ease infinite,colorGradientFade 16s ease infinite;
					}@keyframes sqr4{
							 0%{left: 70px;top: 70px;}
						 1.785%{left: 70px;top:135px;}
						12.499%{left: 70px;top:135px;}
						14.285%{left:  5px;top:135px;}
						24.999%{left:  5px;top:135px;}
						26.785%{left:  5px;top: 70px;}
						37.499%{left:  5px;top: 70px;}
						39.285%{left:  5px;top:  5px;}
						49.999%{left:  5px;top:  5px;}
						51.785%{left: 70px;top:  5px;}
						62.499%{left: 70px;top:  5px;}
						64.285%{left:135px;top:  5px;}
						74.999%{left:135px;top:  5px;}
						76.785%{left:135px;top: 70px;}
						87.499%{left:135px;top: 70px;}
						89.285%{left: 70px;top: 70px;}
					}
					#sqr5{
						left: 135px;
						top: 70px;
						animation:sqr5 12s ease infinite,colorGradientFade 16s ease infinite;
					}@keyframes sqr5{
							 0%{left:135px;top: 70px;}
						 1.785%{left:135px;top: 70px;}
						 3.571%{left: 70px;top: 70px;}
						14.285%{left: 70px;top: 70px;}
						16.071%{left: 70px;top:135px;}
						26.785%{left: 70px;top:135px;}
						28.571%{left:  5px;top:135px;}
						39.285%{left:  5px;top:135px;}
						41.071%{left:  5px;top: 70px;}
						51.785%{left:  5px;top: 70px;}
						53.571%{left:  5px;top:  5px;}
						64.285%{left:  5px;top:  5px;}
						66.071%{left: 70px;top:  5px;}
						76.785%{left: 70px;top:  5px;}
						78.571%{left:135px;top:  5px;}
						89.285%{left:135px;top:  5px;}
						91.071%{left:135px;top: 70px;}
					}
					#sqr6{
						left: 5px;
						top: 135px;
						animation:sqr6 12s ease infinite,colorGradientFade 16s ease infinite;
					}@keyframes sqr6{
							 0%{left:  5px;top:135px;}
						10.714%{left:  5px;top:135px;}
						12.499%{left:  5px;top: 70px;}
						23.214%{left:  5px;top: 70px;}
						24.999%{left:  5px;top:  5px;}
						35.714%{left:  5px;top:  5px;}
						37.499%{left: 70px;top:  5px;}
						48.214%{left: 70px;top:  5px;}
						49.999%{left:135px;top:  5px;}
						60.714%{left:135px;top:  5px;}
						62.499%{left:135px;top: 70px;}
						73.214%{left:135px;top: 70px;}
						74.999%{left: 70px;top: 70px;}
						85.714%{left: 70px;top: 70px;}
						87.499%{left: 70px;top:135px;}
						98.214%{left: 70px;top:135px;}
						99.999%{left:  5px;top:135px;}
					}
					
					#next{position: fixed; top:0; bottom:0; left:0; right:0;}
				</style>
				<div class="panel-body">
					<div class="loader">
						<div class="square" id="sqr0"></div>
						<div class="square" id="sqr1"></div>
						<div class="square" id="sqr2"></div>
						<div class="square" id="sqr3"></div>
						<div class="square" id="sqr4"></div>
						<div class="square" id="sqr5"></div>
						<div class="square" id="sqr6"></div>
					</div>
				</div>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

<script>
(function poll() {
    setTimeout(function() {
        $.ajax({
            url: "https://<?php echo $configWhoami; ?>/api/job_check.php?id=<?php echo $jobID; ?>",
            type: "GET",
            success: function(ret) {
                console.log(ret);
				if(!ret["Success"]) {
					$("#colorStatus").attr("class", "alert alert-danger");
					$("#colorText").html("The API has returned an error. Please check the details below.");
				} else {
					$("#colorStatus").attr("class", "alert alert-success");
					if(ret["Data"]["Current"] === "WAITING") {
						$("#colorText").html("Your job is waiting for a worker to be assigned to it.");
					} else if(ret["Data"]["Current"] === "DONE") {
						$("#colorText").html("Your job has been completed and is ready to view. Please <a href='https://<?php echo $configWhoami; ?>/ui/domain-graph/?job=<?php echo $jobID; ?>'>click here</a> if you are not redirected automatically.");
						window.location.href = "https://<?php echo $configWhoami; ?>/ui/domain-graph/?job=<?php echo $jobID; ?>";
					} else {
						$("#colorText").html("Your job is in progress. Current status: '" + ret["Data"]["Current"] + "'");
					}
				}
            },
            dataType: "json",
            complete: poll,
            timeout: 1000
        })
    }, 3000);
})();
</script>

<?php
include "../_inc/terminate.php";
?>
