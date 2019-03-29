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
						
						<p>Possible uses include:
						<ul>
							<li>Examine domain relationships with force-directed graphs.</li>
							<li>Find patterns in cybercrime to discern threat actor movements.</li>
							<li>Detect information leakage due to human errors.</li>
							<li>Generate reputation for a domain (soon).</li>
							<li>Create and download probabilistic blacklists (soon).</li>
						</ul>
						</p>
						
						<p>dnstrace is a tool designed to be used by cybersecurity professionals and researchers. Please use this service responsibly. If you are a researcher and would like additional access to the system (via API key for extended functionality or otherwise), please email the maintainer with details of your usage - we'll get back to you ASAP.</p>
						
						<p>Additionally, dnstrace aims to be extremely programmable and extensible, so API docs are coming soon.</p>
					</div>
				</div>
			</div>
			<!-- /.col-lg-8 -->
			<div class="col-lg-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						Contact
					</div>
					<div class="panel-body">
						<p>This is a project developed and maintained by <a href="https://chris.partridge.tech/" target="_blank">Chris Partridge</a>. This site is made available for research purposes to all people free of charge, and is to my knowledge the first tool of its kind provided for free to the public.</p>
					</div>
				</div>
			</div>
			<!-- /.col-lg-4 -->
			<div class="col-lg-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						Credits
					</div>
					<div class="panel-body">
						<p>D3.js wizardry powering graph visualization by <a href="https://bl.ocks.org/eyaler" target="_blank">eyaler</a>.</p>
						<p>Bootstrap template by <a href="http://davidmiller.io/"  target="_blank">David Miller</a> of <a href="http://blackrockdigital.io/" target="_blank">Blackrock Digital</a>, MIT license.</p>
						<p>Gorgeous loading icon by <a href="http://ciaccodavi.de/" target="_blank">Ciacco Davide</a>.</p>
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