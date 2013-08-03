<?php
require_once("../connect.inc.php");
require_once("../config.inc.php");
//require_once("../functions.inc.php");

if (isset($_GET["id"]))
{
    $id = $_GET["id"];
}
else
{
    die("Error: no photo id specified!");
}

$sql    = "SELECT * from people WHERE id = ".$id;
$result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

if ($result)
{
    while($row = mysql_fetch_array($result))
    {
        $decoded_data = base64_decode($row["photo"]);

        header("Content-Type: image/jpeg");
        echo ($decoded_data);
    }
}

mysql_close();

unset($link);
?>
