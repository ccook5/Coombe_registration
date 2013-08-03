<?php 
require_once("../connect.inc.php");
require_once("../config.inc.php");
require_once("../functions.inc.php");

page_header();

$sql    = 'UPDATE people SET in_out="out"';
$result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

if ($result)
{
  echo '<div class="success">Every one has been signed out.</div>';
}

page_footer();

?>