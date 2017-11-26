<?php
/* web/api/inc/exit.php
 * Teardown script.
 *
 * Closes $mysqli and terminates program cleanly.
 */

$mysqli->close();
exit();
?>