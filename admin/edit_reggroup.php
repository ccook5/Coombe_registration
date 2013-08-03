<?php 
require_once("../connect.inc.php");
require_once("../config.inc.php");
require_once("../functions.inc.php");

require_once("reg_groups.inc.php");

unset($value);

if (isset($_GET["action"])) {
    $action = $_GET["action"];
} else if (isset($_POST["action"])) {
    $action = $_POST["action"];
} else {
    $action = "";
}

function print_form($action_option     = "add_reg_group_handler", 
                    $name              = "",
                    $background_colour = "ffffff",
                    $text_colour       = "000000",
                    $reg_group_id      = NULL,
                    $old_person_id     = NULL)
{
?><script type="text/javascript">
  $(document).ready(
    function()
    {
      $('.background_colour').jPicker({window:{title:'Background colour', position:{x: 'center',y: 'center'}}});
      $('.text_colour').jPicker({window:{title:'Text colour', position:{x: 'center',y: 'center'}}});
    });
</script>
   <form>
     <input type="hidden" name="action" value="<?php echo($action_option); ?>" />
<?php
    if ($reg_group_id != NULL) {
        echo('     <input type="hidden" name="r_group_id" value="'.$reg_group_id.'" />');
    }
?>
     <table class="form_table">
      <tr>
       <td>Name:</td>
       <td><input type="text" name="name" value="<?php echo($name); ?>"/></td>
      </tr>
      <tr>
       <td>Background Colour:</td>
       <td><input class="background_colour" type="text" name="background_colour" value="<?php echo($background_colour); ?>" /></td>
      </tr>
      <tr>
       <td>Text Colour:</td>
       <td><input class="text_colour" type="text" name="text_colour" value="<?php echo($text_colour); ?>" /></td>
      </tr>
      <tr>
       <td></td>
       <td>
        <input type="submit" value="Save" />
        <input type="reset" value="Cancel" onclick="window.location='index.php'" />
       </td>
      </tr>
     </table>
    </form>
<?php
}

if ($action == "add_reg_group_form")
{
    $jpicker_code = '
  <link rel="Stylesheet" type="text/css" href="/jpicker/css/jpicker-1.1.6.min.css" />
  <link rel="Stylesheet" type="text/css" href="/jpicker/jPicker.css" />
  <script src="/jpicker/jquery-1.4.4.min.js"  type="text/javascript"></script>
  <script src="/jpicker/jpicker-1.1.6.min.js" type="text/javascript"></script>';

    page_header($redirect_url = "", $redirect_delay = 0, $onload = "", $extra = $jpicker_code);
    print_form();
}
else if ($action == "add_reg_group_handler")
{
    page_header("/admin", 1);

    $name = $_GET["name"];

    $sql    = "INSERT INTO reg_groups (id, name) VALUES (NULL, \"".$name."\");";
    $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

    if ($result)
    {
        echo '<div class="success">Added reg group '.$name.'</div>';
    }
}
else if ($action == "edit_reg_group_form")
{
    $jpicker_code = '
  <link rel="Stylesheet" type="text/css" href="../jpicker/css/jpicker-1.1.6.min.css" />
  <link rel="Stylesheet" type="text/css" href="../jpicker/jPicker.css" />
  <script src="../jpicker/jquery-1.4.4.min.js"  type="text/javascript"></script>
  <script src="../jpicker/jpicker-1.1.6.min.js" type="text/javascript"></script>';

    page_header($redirect_url = "", $redirect_delay = 0, $onload = "", $extra = $jpicker_code);

    $id             = $_GET["r_group_id"];

    $row = get_reg_group($id);
    
    print_form($action_option = "edit_reg_group_handler",
               $name          = get_reg_group_name($id),
               $background_colour = $row['background_colour'],
               $text_colour = $row['text_colour'],
               $reg_group_id  = $id,
               $old_person_id = NULL);
}
else if ($action == "edit_reg_group_handler")
{
    page_header("/admin", 1);

    $id   = $_GET["r_group_id"];
    $name = $_GET["name"];
    $background_colour = $_GET["background_colour"];
    $text_colour = $_GET["text_colour"];

    if (strlen($name) > 11)
    {
        $msg = "Error: the Reg Group name is too long. Please try again. The maximum length is 11 characters.";
        echo '<span class="error">'.$msg.'</span>';
    }
    else
    {
        $sql    = 'UPDATE reg_groups SET name="'.$name.'", '
                  .'background_colour="'.$background_colour.'", '
                  .'text_colour="'.$text_colour.'" WHERE id='.$id;
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

    $reg_group_name = get_reg_group_name($id);
  
?>
  <div class="confirm_delete">
   <p>You are about to delete the Registration Group "<?php echo $reg_group_name; ?>"</p>
   <p>Users in this group will be moved to the Unallocated Group.</p>

   <p>
    <a href="edit_reggroup.php?action=del_reg_group_handler&r_group_id=<?php echo $id; ?>">
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
    
    unallocate_from_reg_group($id);
    remove_reg_group($id);
}

page_footer();

?>