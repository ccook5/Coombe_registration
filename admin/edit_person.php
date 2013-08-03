<?php 
require_once("../connect.inc.php");
require_once("../config.inc.php");
require_once("../functions.inc.php");

require_once("functions.inc.php");
require_once("people.inc.php");
require_once("reg_groups.inc.php");

unset($value);

if (isset($_GET["action"])) {
    $action = $_GET["action"];
} else if (isset($_POST["action"])) {
    $action = $_POST["action"];
} else {
    $action = "";
}

function print_form($action_option = "add_person_handler", 
                    $id            = Null,
                    $first_name    = "", 
                    $last_name     = "", 
                    $reg_group_id  = NULL,
                    $in = "", $out = "selected", 
                    $old_person_id = NULL)
{
    global $link;
?>
  <form action="edit_person.php" enctype="multipart/form-data" method="post">
   <input type="hidden" name="action" value="<?php echo $action_option; ?>" />
<?php
    if ($old_person_id !== NULL) {
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
      <img src="get_image.php?id=<?php echo($id); ?>" />
      <input type="file" name="photo" value="" />
     </td>
    </tr>
    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
    <tr>
     <td>ID number:</td>
     <td>
<?php
    if (isset($id)) {
        echo('      <input type="text" name="new_id" value="'.$id.'" />');
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
            if ($row_rg["id"] === $reg_group_id)
            {
                echo "       <option name='r_group' value='". $row_rg["id"] . "' selected >";
            }
            else
            {
                echo "       <option name='r_group' value='". $row_rg["id"] . "' >";
            }
            echo($row_rg['name']."</option>\n");
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
    $person_id  = $_POST["new_id"];
    $first_name = $_POST["first_name"];
    $last_name  = $_POST["last_name"];
    $r_group_id = $_POST["r_group_id"];
    $in_out     = $_POST["in_out"];

    $abort = false;

    if (strlen($first_name) > 50)
    {
        page_header('/admin/edit_person.php?action="add_person_handler"&person_id='.$old_id, 10);
        $msg = "Error: the user\'s first name is too long. ";
        $msg .= "Please try again. The maximum length is 50 characters.";
        echo '<span class="error">'.$msg.'</span>';
        $abort = true;
    }

    if (strlen($last_name) > 50)
    {
        page_header('/admin/edit_person.php?action="add_person_handler"&person_id='.$old_id, 10);
        $msg = "Error: the user\'s last name is too long. ";
        $msg .= "Please try again. The maximum length is 50 characters.";
        echo '<span class="error">'.$msg.'</span>';
        $abort = true;
    }
  
    if ($in_out != "in" && $in_out != "out")
    {
        page_header("/admin/edit_person.php", 10);
        echo '<div class="error">Error: The user is neither in nor out. Contact the system administrator.</div>';
        $abort = true;
    }

//TODO: do some more error checking here...test
    if (! empty($_FILES['photo']['tmp_name'])) {
        $photo_data_raw = file_get_contents($_FILES['photo']['tmp_name']);
        $photo_data_b64 = base64_encode($photo_data_raw);
    } else {
        $photo_data_b64 = '';
    }

    if (! $abort)
    {
        page_header("/admin", 1);
        create_person($person_id, $first_name, $last_name, $r_group_id, $photo_data_b64);
    }
}
else if ($action == "edit_person_form")
{
    page_header();
  
    $person_id = $_GET["person_id"];
    $details = get_persons_details($person_id);
  
    $out = "";
    $in  = "";
    if ($details["in_out"] === "in")
    {
        $in  = "selected ";
    }
    else
    {
        $out = "selected ";
    }
    
    print_form("edit_person_handler", 
            $id = $person_id,
            $first_name = $details["first_name"], 
            $last_name = $details["last_name"], 
            $reg_group_id = $details["reg_group"],
            $in, $out, 
            $old_person_id = $person_id);
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
        error_page("Error: the user\'s first name is too long. Please try again. The maximum length is 50 characters.",
                    "/admin/update_handler.php?person_id=".$old_id, 10);
        $abort = true;
    }

    if (strlen($last_name) > 50)
    {
        error_page("Error: the user\'s last name is too long. Please try again. The maximum length is 50 characters.",
                    "/admin/edit_person.php?person_id=".$old_id, 10);
        $abort = true;
    }

    if ($in_out != "in" && $in_out != "out")
    {
        error_page("Error: The user is neither in nor out. Contact the system administrator.",
                    "/admin/edit_person.php", 10);
        $abort = true;
    }

//TODO: do some more error checking here...
    if (! empty($_FILES['photo']['tmp_name'])) {
        $photo_data_raw = file_get_contents($_FILES['photo']['tmp_name']);
        $photo_data_b64 = base64_encode($photo_data_raw);
    } else {
        $photo_data_b64 = '';
    }
    if (! $abort)
    {
        page_header("/admin", 1);

        update_person($old_id, $new_id, $first_name, $last_name, $r_group_id, $in_out, $photo_data_b64);

        $sql = sprintf("INSERT INTO history (person_id, direction) VALUES (('%s'), ('%s'))", $new_id, $in_out);
        $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error() . ' On Line : ' . __LINE__ . '<br />');

        if ($result)
        {
            echo '<div class="success">'.$first_name.' '.$last_name.' now signed '.$in_out.'</div>';
        }
    }
}
else if ($action == "del_person_form")
{
    page_header(/*"/admin", 10*/);

    $person_id = $_GET["person_id"];
    $details = get_persons_details($person_id);
?>
  <div class="confirm_delete">
   <p>You are about to delete "<?php echo $details['first_name']." ".$details['last_name']; ?>"</p>

   <p>
    <a href="edit_person.php?action=del_person_handler&person_id=<?php echo $person_id; ?>">
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
    $details = get_persons_details($person_id);

    if (delete_person($person_id))
    {
        echo '<div class="success">Removed '.$details['first_name'].' '.$details['last_name'].'</div>';
    }
}

page_footer();

?>