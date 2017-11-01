<?php
/* tools/inc/exit.php
 * Teardown script for administrative tools.
 *
 * Closes $mysqli and terminates program cleanly.
 */

$mysqli->close();
exit();
?>