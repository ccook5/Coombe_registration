<?php
require_once("../connect.inc.php");
require_once("../config.inc.php");
require_once("../functions.inc.php");


if (array_key_exists("action", $_GET))
{
    $action = $_GET["action"];
}
else
{
    $action = "";
}

function badgeid_form($badge_id = "")
{
?>
   <div class="form">
    <div class="instructions">Please scan your Badge:</div>

    <form method="GET"  action="index.php" name="register_form" onblur="document.getElementById('badge_id').focus()"> 
     <input type="hidden" name="action"    value="sign_in_out"  id="hidden_input_action" />
     <input type="text"   name="badge_id"  class="badge_id"     id="badge_id" onblur="document.getElementById('badge_id').focus()" value="<?php echo $badge_id; ?>"/>
    </form>
   </div>
<?php 
}

$client_page_url = "http://registration.coombe.local/client/";
$client_page_onload_jscript = "document.getElementById('badge_id').focus(); document.getElementById('badge_id').value = '';";

if ($action == "")
{
    page_header($client_page_url, 10, $client_page_onload_jscript);
    badgeid_form();
    page_footer();
}
else if ($action == "sign_in_out")
{
    page_header($client_page_url, 1, $client_page_onload_jscript);
    $badge_id = $_GET["badge_id"];

    if (strlen($badge_id) <= 0 || ($badge_id[0] != "¬" && $badge_id[0] != "$" && $badge_id[0] != "~") )
    {
        echo('<div class="notice">Scan failed, please retry.</div>'."\n");
        page_footer();
    }
    else
    {
// ignore some extra chars added by the scanner
        $chars_to_ignore = array("¬", "$", "~");
        $badge_id = str_replace($chars_to_ignore, "", $badge_id);

        if (strlen($badge_id) == 8) {
            $badge_id = substr($badge_id, 2);
        }

        $badge_id = "'".$badge_id."'";

        $sql    = sprintf("SELECT * FROM people WHERE id = %s LIMIT 1", $badge_id);
        $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error() . ' On Line : ' . __LINE__ . '<br />\n');
        $row    = mysql_fetch_array($result);
    
        if (empty($row))
        {
            echo "<h1 class='new_status'>ID not found</h1><br /><h2>Please try again, or see reception</h2>";
        }
        else 
        {
            //$badge_id is allready quoted at this point, unless we are using the id from the database...
            if ($row["in_out"] == "in")
            {
                $sql = sprintf("UPDATE people SET in_out='out' WHERE id = %s;", $badge_id);
                $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error() . ' On Line : ' . __LINE__ . '<br />');

                echo "<h1 class='new_status'>Goodbye <span class='name'>".$row["first_name"]." ".$row["last_name"]."</span>, you are now signed OUT.</h1>\n";

                $sql = sprintf("INSERT INTO history (person_id, direction) VALUES (('%s'), ('%s'))", $row['id'], "out");
                $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error() . ' On Line : ' . __LINE__ . '<br />');
            }
            else
            {
                $sql = sprintf("UPDATE people SET in_out='in' WHERE id = %s;", $badge_id);
                $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error() . ' On Line : ' . __LINE__ . '<br />');
  
                echo "<h1 class='new_status'>Welcome <span class='name'>".$row["first_name"]." ".$row["last_name"]."</span>, you are now signed IN.</h1>";
                echo "<h2>Please remember to sign out when you leave the school.</h2>\n";

                $sql = sprintf("INSERT INTO history (person_id, direction) VALUES (('%s'), ('%s'))", $row['id'], "in");
                $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error() . ' On Line : ' . __LINE__ . '<br />');
            }
        }

        page_footer();
    }
}
?>
  
<?php

page_footer();

mysql_close();

unset($link);
?>