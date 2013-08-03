<?php 
require_once("../connect.inc.php");
require_once("../config.inc.php");
require_once("../functions.inc.php");

unset($value);

page_header('/admin', 1);

$person_id = $_GET["person_id"];
$dir       = $_GET["dir"];

$sql    = "SELECT * FROM people WHERE id=\"". $person_id."\" LIMIT 1";
$result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

if (!$result)
{
	echo '<div class="success">'.$row["first_name"].' '.$row["last_name"].' updated</div>';
}

$row = mysql_fetch_array($result);

if ($dir == "in")
{
	$in_out = "out";
}
else
{
	$in_out = "in";
}

$sql    = 'UPDATE people SET in_out="'.$in_out.'" WHERE id="'.$person_id.'"';
$result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

if ($result)
{
	echo '<div class="success">'.$row["first_name"].' '.$row["last_name"].' updated</div>';
}

$sql = sprintf("INSERT INTO history (person_id, direction) VALUES (('%s'), ('%s'))", $person_id, $in_out);
$result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error() . ' On Line : ' . __LINE__ . '<br />');

page_footer();

?>