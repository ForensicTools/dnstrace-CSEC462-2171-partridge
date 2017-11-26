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
	API Key:<br>
	<input type="text" name="key" value="NONE"><br>
	<input type="hidden" name="ui" value="1">
	<input type="submit" value="Submit">
</form>