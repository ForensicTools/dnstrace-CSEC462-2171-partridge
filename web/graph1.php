<?php
/* web/graph1.php
 * First degree graph generation from single starting point.
 */

include "inc/setup.php";
use LayerShifter\TLDExtract\Extract;

$ext = new Extract(null, null, Extract::MODE_ALLOW_ICANN);
$lookupFQDN = $ext->parse(htmlspecialchars($_GET["domain"]));

var_dump($lookupFQDN);

//$doesExistPls = $mysqli->query("SELECT * FROM `Reputation` WHERE ")