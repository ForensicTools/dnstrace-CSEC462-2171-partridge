<?php
include "../../base.php";
$configPage = "Create Graph";

include "../_inc/format.php";
?>

<!-- Page Content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Create New Force-Directed Graph</h1>
				</div>
			<!-- /.col-lg-12 -->
			<div class="col-lg-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						Graph Generation Settings
					</div>
					<div class="panel-body">
						<form action="https://dnstrace.pro/api/job_create.php">
							<div class="form-group">
								<label>Domain</label>
								<input class="form-control" name="domain" placeholder="Enter domain to research">
							</div>
							<div class="form-group">
								<label>Degrees</label>
								<select name="degree" class="form-control">
									<option value="1">1</option>
									<option value="2" selected>2</option>
									<option value="3">3</option>
									<option value="4">4</option>
								</select>
							</div>
							<div class="form-group">
								<label>Use DNS_MX</label>
								<div class="radio">
									<label class="radio-inline">
										<input type="radio" name="mxen" id="mxen" value="1">True
									</label>
									<label class="radio-inline">
										<input type="radio" name="mxen" id="mxen" value="0" checked>False
									</label>
								</div>
							</div>
							<div class="form-group">
								<label>Use DNS_NS</label>
								<div class="radio">
									<label class="radio-inline">
										<input type="radio" name="nsen" value="1">True
									</label>
									<label class="radio-inline">
										<input type="radio" name="nsen" value="0" checked>False
									</label>
								</div>
							</div>
							<div class="form-group">
								<label>Space-delimited list of results to exclude (IPs or FQDNs)</label>
								<textarea class="form-control" rows="3" name="exclude">127.0.0.1 </textarea>
							</div>
							<div class="form-group">
								<label>API Key</label>
								<input class="form-control" name="key" placeholder="None">
							</div>
							<input type="hidden" name="ui" value="1">
							<button type="submit" class="btn btn-default">Submit</button>
							<button type="reset" class="btn btn-default">Reset</button>
						</form>
					</div>
				</div>
			</div>
			<!-- /.col-lg-4 -->
			<div class="col-lg-8">
				<div class="panel-body">
					<h3>Help</h3>
					
					<p>dialogue coming soon</p>
				</div>
			</div>
			<!-- /.col-lg-8 -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

<?php
include "../_inc/terminate.php";
?>