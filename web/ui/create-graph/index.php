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
					<h3>Notes</h3>
					
					<p>"Degrees" specifies how many iterations of the graph generator you'd like to make from the starting domain you enter. This isn't <i>exactly</i> degrees of seperation because of how the graph is structured. Effectively, each iteration of the software takes a list of input domains, finds everything they resolve to, and then everything that also resolves to the resolved values.</p>
					
					<p>Here are two (abridged) examples, prefaced by their pseudo-degree:
					<ol>
						<li>domain (node) -> IPs (edge) -> domains (nodes)</li>
						<li>domain -> IPs -> domains -> more IPs -> more domains</li>
					</ol>
					</p>
					
					<p>You can see how this has the capacity to go exponential - especially as more data is added to the system and more popular links are established. By default, the system resolves two degrees and does not use MX or NS records. This is because MX and NS records are typically quite centralized, consuming a huge amount of processing time only to generate completely unintelligble graphs without any actionable data. In most cases, use of both of these fields should be avoided unless you're sure of what you're doing.</p>
					
					<p>To prevent the abuse of this system, MX and NS lookups are disabled for anyone without an API key seeking to perform a >1 degree lookup. Additionally, >2 degree lookups are forbidden for non-keyholders until a system is set up that retrieves and auto-excludes edges that are too populous for relevant results to be fetched. Researchers who need additional access to dnstrace (and seek to avoid some of the listed limits) are again encouraged to contact the maintainer for API keys by email.</p>
					
					<p><b>This system is currently in beta and lacks strong performance optimizations, which will be completed subject to maintainer availability.</b></p>
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