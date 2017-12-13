<?php
/* web/api/badger_confirm.php
 * Confirm receipt of fqdn list, delete file.
 */

include '../base.php';

if(array_key_exists("key", $_GET)) {
	if(ctype_alnum($_GET["key"]) {
		$key = $_GET["key"];
	} else {
		echo json_encode(array("Success" => false, "Reason" => "Key is not alphanumeric."));
		include "inc/exit.php";
	}
} else {
	echo json_encode(array("Success" => false, "Reason" => "Missing key."));
	include "inc/exit.php";
}

unlink("/var/www/html/api/badger/" . $key);

echo json_encode(array("Success" => true, "Reason" => "NA"));
?>