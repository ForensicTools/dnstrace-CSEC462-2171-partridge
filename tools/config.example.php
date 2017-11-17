<?php
/* tools/config.example.php
 * Example configuration file.
 *
 * Copy to tools/config.php and edit the following to your spec.
 * The mandatory fields to fill out are filled with underlines.
 */

$currentDirectory = getcwd();


// YOU PROBABLY HAVE TO EDIT THESE
//    DATABASE
$configDbAddr = "____________"; // Percona/MySQL IP address or hostname
$configDbUser = "____________"; // dnstrace *ADMINISTRATIVE* user
$configDbPass = "____________"; // dnstrace *ADMINISTRATIVE* password
// YOU PROBABLY HAVE TO EDIT THESE


// YOU PROBABLY DON'T HAVE TO EDIT THESE
//    DATABASE
$configDbDb = "dnstrace"; // database to use
//    DEPENDENCIES
$configDepWhois = $currentDirectory . "/../deps/python-whois/pwhois"; // full path to pwhois
// YOU PROBABLY DON'T HAVE TO EDIT THESE


unset($currentDirectory);
?>