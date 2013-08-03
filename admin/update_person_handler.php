<?php 
require_once("../connect.inc.php");
require_once("../config.inc.php");
require_once("../functions.inc.php");

unset($value);

if (isset($_GET["action"])) {
	$action = $_GET["action"];
} else if (isset($_POST["action"])) {
	$action = $_POST["action"];
} else {
	$action = "";
}

function print_form($action_option = "add_person_handler", 
		$first_name    = "", 
		$last_name     = "", 
		$reg_group_id  = NULL;
		$in = "", $out = "selected", 
		$old_person_id = NULL)
{
?>
  <form action="update_handler.php" enctype="multipart/form-data" method="post">
   <input type="hidden" name="action" value="<?php echo $action_option; ?>" />
<?php
	if ($old_id !== NULL) {
		echo("   <input type='hidden' name='old_id' value='".$old_person_id."' />");
	}
?>
   <table class="form_table">
    <tr>
	 <td>First Name:</td>
	 <td><input type="text" name="first_name" value="<?php echo $first_name; ?>" /></td>
	</tr>
    <tr>
	 <td>Last Name:</td>
	 <td><input type="text" name="last_name" value="<?php echo $last_name; ?>" /></td>
	</tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
    <tr>
	 <td>Photo:</td>
	 <td>
	  <img src="get_image.php?id=<?php echo($person_id); ?>" />
	  <input type="file" name="photo" value="" />
	 </td>
	</tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
    <tr>
	 <td>ID number:</td>
     <td>
<?php
	if (isset($person_id)) {
		echo('      <input type="text" name="new_id" value="'.$person_id.'" />');
	} else {
		echo('      <input type="text" name="new_id" />');
	}
?>
     </td>
	</tr>
    <tr>
	 <td>Registration Group:</td>
	 <td>
	  <select name="r_group_id">
<?php	  
	$sql_rg    = "SELECT * FROM reg_groups";
	$result_rg = mysql_query($sql_rg, $link) or die('Invalid query: ' . mysql_error());

	if ($result_rg)
	{
		while($row_rg = mysql_fetch_array($result_rg))
        {
			if ($row_rg["id"] == $reg_group_id)
			{
				echo "      <option name='r_group' value='". $row_rg["id"] . "' selected >";
			}
			else
			{
				echo "      <option name='r_group' value='". $row_rg["id"] . "' >";
			}
		}
	}
?>
	  </select>
	 </td>
	</tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
    <tr>
	 <td>In/Out:</td>
	 <td>
	  <select name="in_out">
	   <option name="in" value="in" <?php echo $in; ?> >In</option>
	   <option name="out" value="out" <?php echo $out; ?> >Out</option>
	  </select>
	 </td>
	</tr>
    <tr>
	 <td></td>
	 <td>
	  <input type="submit" value="Save" />
	  <input type="reset" value="Cancel" onclick="window.location='index.php'"/>
	 </td>
	</tr>
   </table>
  </form>
<?php
}

if ($action == "add_person_form")
{
	page_header();
	print_form();
}
else if ($action == "add_person_handler")
{  
	$person_id  = $_POST["person_id"];
	$first_name = $_POST["first_name"];
	$last_name  = $_POST["last_name"];
	$r_group_id = $_POST["r_group_id"];
	$in_out     = $_POST["in_out"];

	$abort = false;

	if (strlen($first_name) > 50)
	{
		page_header('/admin/update_handler.php?action="add_person_handler"&person_id='.$old_id, 10);
		echo '<span class="error">Error: the user\'s first name is too long. Please try again. The maximum length is 50 characters.</span>';
		$abort = true;
	}

	if (strlen($last_name) > 50)
	{
		page_header('/admin/update_handler.php?action="add_person_handler"&person_id='.$old_id, 10);
		echo '<span class="error">Error: the user\'s last name is too long. Please try again. The maximum length is 50 characters.</span>';
		$abort = true;
	}
  
	if ($in_out != "in" && $in_out != "out")
	{
		page_header("/admin/update_handler.php", 10);
		echo '<div class="error">Error: The user is neither in nor out. Contact the system administrator.</div>';
		$abort = true;
	}
  
	if (! $abort)
	{
		page_header("/admin", 1);
		$sql    = 'INSERT INTO people ( first_name, last_name, id, in_out, reg_group) VALUES ("'.$first_name.'", "'.$last_name.'", "'.$person_id.'", "'.$in_out.'", "'.$r_group_id.'")';
		$result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

		if ($result)
		{
			echo '<div class="success">'.$first_name.' '.$last_name.' added</div>';
		}
	}
}
else if ($action == "edit_person_form")
{
	page_header();
  
	$person_id = $_POST["person_id"];
  
	$sql    = "SELECT * FROM people WHERE id=\"". $person_id."\" LIMIT 1";
	$result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

	if ($result)
	{
		$row_count = 0;

		while($row = mysql_fetch_array($result))
		{
			if ($row["in_out"] == "in")
			{
				$in  = "selected ";
				$out = "";
			}
			else
			{
				$in  = "";
				$out = "selected ";
			}
			
			print_form("edit_person_handler", 
					$first_name = $row["first_name"], 
					$last_name = $row["last_name"], 
					$in, $out, 
					$old_person_id = $person_id);
?>
    <tr>
	 <td>Photo:</td>
	 <td>
	  <input type="file" name="photo" value="" />
	 </td>
	</tr>
    <tr><td></td><td></td></tr>
    <tr>
     <td>ID number:</td>
     <td>
      <input type="text" name="new_id" value="<?php echo $person_id; ?>" />
     </td>
    </tr>
   </table>
  </form>
<?php
		}
	}
}
else if ($action == "edit_person_handler")
{
  $old_id     = $_POST["old_id"];
  $new_id     = $_POST["new_id"];
  $first_name = $_POST["first_name"];
  $last_name  = $_POST["last_name"];
  $r_group_id = $_POST["r_group_id"];
  $in_out     = $_POST["in_out"];
  
  $abort = false;
  
  if (strlen($first_name) >50)
  {
    page_header("/admin/update_handler.php?person_id=".$old_id, 10);
    echo '<span class="error">Error: the user\'s first name is too long. Please try again. The maximum length is 50 characters.</span>';
	$abort = true;
  }
  
  if (strlen($last_name) > 50)
  {
    page_header("/admin/update_handler.php?person_id=".$old_id, 10);
    echo '<span class="error">Error: the user\'s last name is too long. Please try again. The maximum length is 50 characters.</span>';
	$abort = true;
  }
  
  if ($in_out != "in" && $in_out != "out")
  {
    page_header("/admin/update_handler.php", 10);
    echo '<span class="error">Error: The user is neither in nor out. Contact the system administrator.</span>';
	$abort = true;
  }
  
  if (! $abort)
  {
    page_header("/admin", 1);
    $sql    = 'UPDATE people SET first_name="'.$first_name.'", last_name="'.$last_name.'", id="'.$new_id.'", in_out="'.$in_out.'", reg_group="'.$r_group_id.'" WHERE id="'.$old_id.'"';
    $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

	                
	$sql = sprintf("INSERT INTO history (person_id, direction) VALUES (('%s'), ('%s'))", $new_id, $in_out);
	$result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error() . ' On Line : ' . __LINE__ . '<br />');

	
    if ($result)
    {
      echo '<div class="success">'.$first_name.' '.$last_name.' updated</div>';
    }
  }
}
else if ($action == "del_person_form")
{
  page_header(/*"/admin", 10*/);

  $person_id = $_GET["person_id"];
  $first_name = "";
  $last_name = "";
  
  $sql    = "SELECT * FROM people WHERE id=\"".$person_id."\" LIMIT 1";
  $result = mysql_query($sql, $link) or die('Invalid query ['.$sql.']: ' . mysql_error() . " on line ". __LINE__);

  if ($result)
  {
    while ($row = mysql_fetch_array($result))
    {
	  $first_name = $row["first_name"];
	  $last_name = $row["last_name"];
	}
  }
?>
  <div class="confirm_delete">
   <p>You are about to delete "<?php echo $first_name." ".$last_name; ?>"</p>

   <p>
    <a href="update_handler.php?action=del_person_handler&person_id=<?php echo $person_id; ?>">
	 Confirm
	</a>
	- <a href="/admin">Cancel</a>
   </div>
<?php
}
else if ($action == "del_person_handler")
{
  page_header('/admin', 1);
  
  $person_id = $_GET["person_id"];

  $first_name = "";
  $last_name = "";
  
  $sql    = "SELECT * FROM people WHERE id=\"".$person_id."\" LIMIT 1";
  $result = mysql_query($sql, $link) or die('Invalid query ['.$sql.']: ' . mysql_error() . " on line ". __LINE__);

  if ($result)
  {
    while ($row = mysql_fetch_array($result))
    {
	  $first_name = $row["first_name"];
	  $last_name = $row["last_name"];
	}
  }
// remove group from reg_group table
  $sql    = "DELETE FROM people WHERE id=\"".$person_id."\" LIMIT 1";
  $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());
  
  if ($result)
  {
    echo '<div class="success">Removed '.$first_name.' '.$last_name.'</div>';
  }
    
}
else if ($action == "add_reg_group_form")
{
  page_header();
?>
<form>
 <input type="hidden" name="action" value="add_reg_group_handler" />
 <table class="form_table">
  <tr><td>Name:</td><td><input type="text" name="r_group_name" /></td></tr>
  <tr>
   <td></td>
   <td>
	<input type="submit" value="Save" />
	<input type="reset" value="Cancel" onclick="window.location='index.php'"/>
   </td>
  </tr>
 </table>
</form>
<?php
}
else if ($action == "add_reg_group_handler")
{
  page_header("/admin", 1);
  
  $r_group_name = $_GET["r_group_name"];
  $reg_group_name = "";
  
  $sql    = "INSERT INTO reg_groups (id, name) VALUES (NULL, \"".$r_group_name."\");";
  $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

  if ($result)
  {
    echo '<div class="success">Added reg group '.$r_group_name.'</div>';
  }
}
else if ($action == "edit_reg_group_form")
{
  page_header();
  $id = $_GET["r_group_id"];
  $reg_group_name = "";
  
  $sql    = "SELECT * FROM reg_groups WHERE id=".$id;
  $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

  if ($result)
  {
    $row_count = 0;

    while ($row = mysql_fetch_array($result))
    {
	  $reg_group_name = $row["name"];
	}
  }
  
?>
  <form>
   <input type="hidden" name="action" value="edit_reg_group_handler" />
   <input type="hidden" name="r_group_id" value="<?php echo $id; ?>" />
   <table class="form_table">
    <tr>
	 <td>Name:</td>
	 <td>
	  <input type="text" name="name" value="<?php echo $reg_group_name; ?>" />
	 </td>
	</tr>
    <tr>
	 <td></td>
	 <td>
	  <input type="submit" value="Save" />
	  <input type="reset" value="Cancel" onclick="window.location='index.php'"/>
	 </td>
	</tr>
   </table>
  </form>
<?php
}
else if ($action == "edit_reg_group_handler")
{
  page_header("/admin", 1);

  $id   = $_GET["r_group_id"];
  $name = $_GET["name"];
  
  if (strlen($name) > 11)
  {
    echo '<span class="error">Error: the Reg Group name is too long. Please try again. The maximum length is 11 characters.</span>';
  }
  else
  {
    $sql    = 'UPDATE reg_groups SET name="'.$name.'" WHERE id='.$id;
    $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

    if ($result)
    {
      echo '<div class="success">reg group updated</div>';
    }
  }
}
else if ($action == "del_reg_group_form")
{
  page_header();

  $id = $_GET["r_group_id"];
  $reg_group_name = "";
  
  $sql    = "SELECT * FROM reg_groups WHERE id=".$id." LIMIT 1";
  $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

  if ($result)
  {
    $row_count = 0;

    while ($row = mysql_fetch_array($result))
    {
	  $reg_group_name = $row["name"];
	}
  }
  
?>
  <div class="confirm_delete">
   <p>You are about to delete the Registration Group "<?php echo $reg_group_name; ?>"</p>
   <p>Users in this group will be moved to the Unallocated Group.</p>

   <p>
    <a href="update_handler.php?action=del_reg_group_handler&r_group_id=<?php echo $id; ?>">
	 Confirm
	</a>
	- <a href="/admin">Cancel</a>
   </div>
<?php
}
else if ($action == "del_reg_group_handler")
{
	page_header('/admin', 0);
  
	$id = $_GET["r_group_id"];
	$reg_group_name = "";
  
// move all users in reg group to unalocated group (id #0)  
	$sql    = "UPDATE people SET reg_group=0 WHERE reg_group=".$id;
	$result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());
  
	if ($result)
	{
		echo '<div class="success">Unallocated all users in reg group</div>';
	}
  
// remove group from reg_group table
	$sql    = "DELETE FROM reg_groups WHERE id=".$id." LIMIT 1";
	$result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());
  
	if ($result)
	{
		echo '<div class="success">Removed reg group</div>';
	}
}
else if ($action == "sign_in_out")
{
	page_header('/admin', 1);
	
	$person_id = $_GET["person_id"];
	$dir       = $_GET["dir"];
	
	$sql    = "SELECT * FROM people WHERE id=\"". $person_id."\" LIMIT 1";
	$result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

	if ($result)
	{
		$row_count = 0;

		$row = mysql_fetch_array($result);
    }
	
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
}

page_footer();

?>