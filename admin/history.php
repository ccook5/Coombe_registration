<?php
require_once("../connect.inc.php");
require_once("../config.inc.php");
require_once("../functions.inc.php");

page_header();
?>
   <div class="actions">
<a href="index.php">Back to Admin Page</a>
   </div>

 <?php
$sql_hist    = 'SELECT * FROM history WHERE person_id="'.$_GET["person_id"].'"';
$result_hist = mysql_query($sql_hist, $link) or die('Invalid query: ' . mysql_error());

$sql_person    = 'SELECT * FROM people WHERE id="'.$_GET["person_id"].'" LIMIT 1';
$result_person = mysql_query($sql_person, $link) or die('Invalid query: ' . mysql_error());

$person_id = 0;

if ($result_person)
{
  $row_person = mysql_fetch_array($result_person);
  
  $person_id = $row_person["id"]
?>
    <table class="reg_group_block">
     <thead>
      <tr>
       <th>
        <a name="<?php echo $row_person["person_id"]; ?>"><?php echo $row_person["first_name"]." ".$row_person['last_name']; ?></a>&nbsp;&nbsp;&nbsp;
	   </th>
	   <th class="reg_group_block_actions">
 <!--       <a href="update_handler.php?action=edit_reg_group_form&r_group_id=<?php echo $row1["id"]; ?>"><img src="/images/edit.png" alt="edit" title="Edit Reg Group" /></a>
        <a href="update_handler.php?action=del_reg_group_form&r_group_id=<?php echo $row1["id"]; ?>"><img src="/images/delete.png" alt="delete" title="Delete Reg Group" /></a> -->
       </th>
      </tr>
     </thead>

<?php
}

if ($result_hist)
{
  $row_count = 0;
  while($row1 = mysql_fetch_array($result_hist))
  {
?>
        <tr>
    	 <td>
		   <?php echo $row1['timestamp']; ?> (UTC)
		 </td>
<?php
		$dir = "out";
        if ($row1["direction"] == "in")
		{
			$dir = "in";
		}
		else
		{
			$dir = "out";
		}

		echo "       <td class='reg_group_block_inout_".$dir."'>";
		echo "         ".$dir;
		echo "       </td>";
		echo "      </tr>";
	}// end while
}//end if
?>
    </table><!-- end table reg_group_block -->


<?php
mysql_close();

unset($link);

page_footer();
?>
