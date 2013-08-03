<?php
require_once("../connect.inc.php");
require_once("../config.inc.php");
require_once("../functions.inc.php");

page_header();

$show_people_still_signed_in = False;

if (isset($_GET["show_people_still_signed_in"]))
{
        if ($_GET["show_people_still_signed_in"] == "true") 
        {
                $show_people_still_signed_in = True;
        }
}
?>
   <div class="actions">
    <ul class="actions">
     <li><a href="edit_person.php?action=add_person_form">Add Person</a></li>
     <li><a href="bulk_import.php">CSV Import</a></li>
     <li><a href="xml_import_form.php">SIMS Import</a></li>
     <li><a href="edit_reggroup.php?action=add_reg_group_form">Add Registration <br />Group</a></li>
     <br />
     <li><a href="#Staff">Go to Staff</a></li>
     <li><a href="#Unallocated">Go to Unallocated</a></li>
 <?php
        if ($show_people_still_signed_in)
        {
                echo ("    <li><a href=\"?show_people_still_signed_in=false\">Show everyone</a></li>");
        }
        else
        {
                echo ("    <li><a href=\"?show_people_still_signed_in=true\">Only show people <br />still signed in</a></li>");
        }
?>

    </ul>
   </div>
   
 <?php
$sql     = "SELECT * from reg_groups ORDER BY name";
$result1 = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

if ($result1)
{
        while($row1 = mysql_fetch_array($result1))
        {
            $style_bg   = "background-color: #".$row1['background_colour']."; color: silver;";
            $style_text = "background-color: #".$row1['text_colour']."; color: silver;";
?>
    <table class="reg_group_block">
     <thead>
      <tr>

       <th class='reg_group_block_name'>

        <a name="<?php echo $row1["name"]; ?>"><?php echo $row1["name"]; ?></a>&nbsp;&nbsp;&nbsp;
       </th>
       <th class='reg_group_block_empty' style="<?php echo($style_bg); ?>">background</th>
       <th class='reg_group_block_empty' style="<?php echo($style_text); ?>">text</th>
       <th class="reg_group_block_actions">
        <a href="edit_reggroup.php?action=edit_reg_group_form&r_group_id=<?php echo $row1["id"]; ?>">
         <img src="/images/edit.png" alt="edit" title="Edit Reg Group" />
        </a>
        <a href="edit_reggroup.php?action=del_reg_group_form&r_group_id=<?php echo $row1["id"]; ?>">
         <img src="/images/delete.png" alt="delete" title="Delete Reg Group" />
        </a>
        <a href="print_id_cards.php?for=reg_group&reggroup_id=<?php echo $row1["id"]; ?>">
         <img src="/images/idcard.jpeg" alt="Print ID Cards for this Reg Group" title="Print ID Cards for this Reg Group" />
        </a>
       </th>
      </tr>
     </thead>

<?php
                $sql = "SELECT * FROM people WHERE reg_group=".$row1["id"]." ORDER BY last_name";

                $result = mysql_query($sql, $link);

                if (!$result)
                {
                        die('Invalid query: ' . mysql_error());
                }

                while($row = mysql_fetch_array($result))
                {
                        if (!($show_people_still_signed_in && $row['in_out'] == "out"))
                        {
                                if ($row["in_out"] === "in") {
                                        $in_out = 'in';
                                } else {
                                        $in_out = 'out';
                                }
?>
        <tr>
         <td>
          <a href="edit_person.php?action=edit_person_form&person_id=<?php echo $row["id"]; ?>">
           <?php echo $row['first_name']." ".$row["last_name"]; ?>
          </a>
         </td>
         <td class='reg_group_block_inout_<?php echo($in_out); ?>'>
<?php
                                echo("         <a href=\"sign_in_out.php?person_id=".$row["id"]."&dir=".$in_out."\">");
                                echo($in_out);
?>
          </a>
         </td>
         <td class="reg_group_block_spacer">&nbsp;&nbsp;&nbsp;&nbsp;</td>
         <td class="reg_group_block_actions">
          <a href="edit_person.php?action=edit_person_form&person_id=<?php echo $row["id"]; ?>">
           <img src="/images/edit.png" alt="edit" title="Edit Person" />
          </a>
          <a href="edit_person.php?action=del_person_form&person_id=<?php echo $row["id"]; ?>">
           <img src="/images/delete.png" alt="delete" title="Delete Person" />
          </a>
          <a href="history.php?person_id=<?php echo $row["id"]; ?>">
           <img src="/images/history.png" alt="delete" title="View History" />
          </a>
          <a href="print_id_cards.php?for=person&person_id=<?php echo $row["id"]; ?>">
           <img src="/images/idcard.jpeg" alt="Print ID Cards for this person" title="Print ID Cards for this person" />
          </a>
         </td>
        </tr>
<?php
                        }
                }
?>
    </table><!-- end table reg_group_block -->
<?php
        }// end while
}//end if

mysql_close();

unset($link);

page_footer();
?>
