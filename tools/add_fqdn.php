<?php
/* tools/add_fqdn.php
 * Add FQDN to database for parsing.
 */

include "inc/setup.php";

if(count($argv) <= 1 || count($argv) >= 4) {
	echo "dnstrace - add_fqdn.php" . PHP_EOL;
	echo "  A tool to add an FQDN to dnstrace's database." . PHP_EOL;
	echo PHP_EOL;
	echo "Usage: php add_fqdn.php \"[fqdn]\" \"[rep_flag]\"" . PHP_EOL;
	echo "  If no reputation flag is specified, USERADD (reputation 0) will be used." . PHP_EOL;
	exit();
}

if(count($argv) == 2) {
	$argRep = "USERADD";
} else {
	$argRep = $argv[2];
}
$argFQDN = $argv[1];