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

if ($action == "")
{
	$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(stripos($ua,'android') !== false) {

		page_header("http://zxing.appspot.com/scan?ret=http%3A%2F%2Fregistration.coombe.local%2Fclient%2Findex.php%3Faction%3Dsign_in_out%26badge_id%3D%25AC{CODE}", 0, "");
		badgeid_form();
		page_footer();
	}
	else
	{
		page_header("http://registration.coombe.local/android/", 
                10, 
                "document.getElementById('badge_id').focus(); document.getElementById('badge_id').value = '';");
		badgeid_form();
		page_footer();
	}
}
else if ($action == "sign_in_out")
{
    $badge_id = $_GET["badge_id"];
	
    if ($badge_id[0] != "¬" && $badge_id[0] != "$" && $badge_id[0] != "~")
    {
        page_header($redirect_url = "http://registration.coombe.local/android/", 
            $redirect_delay = 1, 
            $onload = "document.getElementById('badge_id').focus(); document.getElementById('badge_id').value = '';");
        
		echo '<div class="notice">Scan failed, please retry.</div>'."\n";

        page_footer();
    }
    else
    {
        page_header("http://registration.coombe.local/android/", 
            100, 
            $onload = "document.getElementById('badge_id').focus(); document.getElementById('badge_id').value = '';");
    
// ignore some extra chars added by the scanner
        $chars_to_ignore = array("¬", "$", "~");
    
        $badge_id = str_replace($chars_to_ignore, "", $badge_id);
    
//remove 0's from start of string
    
        if (is_numeric($badge_id))
        {
            $badge_id = (int)$badge_id;
        }

        $sql = sprintf("SELECT * FROM people WHERE (id LIKE \"%s\") LIMIT 1", $badge_id);
  
        $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error() . ' On Line : ' . __LINE__ . '<br />\n');
 
        $row = mysql_fetch_array($result);
    
        if (empty($row))
        {
            echo "<h1 class='new_status'>ID not found</h1><br /><h2>Please try again, or see reception</h2>";
        }
        else 
		{
			if ($row["in_out"] == "in")
			{
				$sql = sprintf("UPDATE people SET in_out='out' WHERE (id=\"%s\") ", $badge_id);
	  
				$result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error() . ' On Line : ' . __LINE__ . '<br />\n');
				echo "<h1 class='new_status'>Goodbye <span class='name'>".$row["first_name"]." ".$row["last_name"]."</span>, you are now signed OUT.</h1>\n";
			}
			else
			{
				$sql = sprintf("UPDATE people SET in_out='in' WHERE (id=\"%s\") ", $badge_id);
	  
				$result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error() . ' On Line : ' . __LINE__ . '<br />\n');
		  
				echo "<h1 class='new_status'>Welcome <span class='name'>".$row["first_name"]." ".$row["last_name"]."</span>, you are now signed IN.</h1>";
				echo "<h2>Please remember to sign out when you leave the school.</h2>\n";
			}
			$sql = sprintf("INSERT INTO history (person_id, direction, terminal_id) VALUES (%s, ('%s'), '%s')", $badge_id, $row["in_out"], $_SERVER['REMOTE_HOST']);
			$result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error() . ' On Line : ' . __LINE__ . '<br />');
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