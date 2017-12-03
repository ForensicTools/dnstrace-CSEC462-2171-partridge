<?php
include "../../base.php";
$configPage = "Home";

include "../_inc/format.php";
?>

<!-- Page Content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Home</h1>
			</div>
			<!-- /.col-lg-12 -->
			<div class="col-lg-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						Introduction
					</div>
					<div class="panel-body">
						<p class="lead">dnstrace visualizes relationships between domains to establish reputation and track low-quality cybercrime</p>
						
						<p>By examining the relationships between domain resolution and (eventually) WHOIS data, we can uncover many secrets hidden in the noise of DNS. dnstrace is a suite of tools that ingest, acquire, parse, and render domain data from many sources on a large scale. It is hosted here and limited access is provided free to all people who wish to perform research using its database.</p>
						
						<p>Uses include:
							<li><b>Examining domain relationships with force-directed graphs</b></li>
							<li>Generate reputation for a domain (soon)</li>
							<li>Retrieving verbose domain information (soon)</li>
							<li>Create and download dynamic blacklists (soon)</li>
						</p>
						
						<p>Additionally, dnstrace aims to be extremely programmable and extensible, so API docs are coming soon.</p>
					</div>
				</div>
			</div>
			<!-- /.col-lg-8 -->
			<div class="col-lg-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						Credits
					</div>
					<div class="panel-body">
						<p>Project developed and maintained by <a href="https://chris.partridge.tech/" target="_blank">Chris Partridge</a>.</p>
						<p>Magnificent D3.js wizardry powering graph databases by <a href="https://bl.ocks.org/eyaler" target="_blank">eyaler</a>.</p>
						<p>Bootstrap template provided by David Miller of <a href="http://blackrockdigital.io/" target="_blank">Blackrock Digital</a>.</p>
					</div>
				</div>
			</div>
			<!-- /.col-lg-4 -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

<?php
include "../_inc/terminate.php";
?>