<html>
<head>
<title>dnstrace</title>
<link rel="icon" href="favicon.ico" type="image/x-icon"/>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
</head>
<body>
<h1>dnstrace</h1>
<br>
<p>A tool for inspecting the relationships between domains using graph databases.</p>
<p>Fill out the following form to create a graph database with the spec provided. Currently, the domain must exist within hpHosts blocklists.</p>
<form action="https://dnstrace.pro/api/job_create.php">
	Domain:<br>
	<input type="text" name="domain"><br>
	Degrees:<br>
	<select name="degree">
		<option value="1">1</option>
		<option value="2" selected>2</option>
		<option value="3">3</option>
		<option value="4">4</option>
	</select><br>
	Use DNS_MX:<br>
	<input type="radio" name="mxen" value="1"> True<br>
	<input type="radio" name="mxen" value="0" checked> False<br>
	Use DNS_NS:<br>
	<input type="radio" name="nsen" value="1"> True<br>
	<input type="radio" name="nsen" value="0" checked> False<br>
	Space-delimited list of results to exclude (IPs or FQDNs):<br>
	<input type="text" name="exclude" value="127.0.0.1"><br>
	API Key:<br>
	<input type="text" name="key" value="NONE"><br>
	<input type="hidden" name="ui" value="1">
	<input type="submit" value="Submit">
</form>
<br>
<p>Graph databases provided by <a href="https://bl.ocks.org/eyaler" target="_blank">eyaler</a>'s magnificent D3.js wizardry</p>
<p>Everything else developed by <a href="https://chris.partridge.tech/" target="_blank">Chris Partridge</a></p>
<p>This project is open source and available on <a href="https://github.com/tweedge/dnstrace/" target="_blank">GitHub</a></p>
</body>
</html>