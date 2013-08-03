<?php
require_once("../connect.inc.php");
require_once("../config.inc.php");
require_once("../functions.inc.php");


?>
<!DOCTYPE html>
<html>
 <head>
  <title><?php echo $config["system_name"]; ?></title>
 <style>
 TABLE.reg_group_block  {
    margin:           auto;
    margin-bottom:    20px;
    border-collapse:  collapse;
    border:           1px solid black;
    width:            80%;
    page-break-after: always;
}

TABLE.reg_group_block  :last {
    page-break-after: always;
}

.reg_group_block_title {
    margin:           1x;
    padding:          1px;
    text-align:       center;
    colspan:          2;
}

TABLE.reg_group_block THEAD TR TH {
    margin:           0px;
    border:           1px solid black;
    padding:          1px;
    colspan:          3;
}

TABLE.reg_group_block TR TH {
    margin:           0px;
    border:           1px solid black;
    padding:          1px;
}

TABLE.reg_group_block TR TD {
    margin:            0px;
    border:            1px solid black;
    padding-left:      3px;
	padding-top:	   3px;
	padding-bottom:	   3px;
	page-break-inside: avoid;
	page-break-before: auto;
}

</style>
 
 </head>
 
 <body>
  <div class="headdiv">
   <h1 class="header"><?php echo $config["system_name"]; ?></h1>
  </div><!-- class="headdiv" -->
  <div class="main">
   <br />
   <hr />
 <?php
$sql     = "SELECT * from reg_groups";
$result1 = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

if ($result1)
{
	$row_count = 0;

	while($groups = mysql_fetch_array($result1))
	{
		$sql    = "SELECT * FROM people WHERE reg_group=".$groups["id"];
		$result = mysql_query($sql, $link);

		if (!$result)
		{
			die('Invalid query: ' . mysql_error());
		}
		else
		{
			while($row = mysql_fetch_array($result))
			{
				if (!( $row_count % $config["max_rows_per_page"] ) )
				{
?>
  <table class="reg_group_block">
   <thead>
    <tr>
     <th>
      <?php echo $groups["name"]; ?>&nbsp;&nbsp;&nbsp;
     </th>
     <th></th>
     <th></th>
    </tr>
   </thead>
   <tbody>

<?php
				}
				echo "    <tr>\n";
				echo "     <td>".$row['first_name']." ".$row["last_name"]."</td>\n";

				if ($row["in_out"] == "in")
				{
					echo "     <td class='reg_group_block_inout_in'>";
				}
				else
				{
					echo "     <td class='reg_group_block_inout_in'>";
				}
				echo $row['in_out']."</td>\n";
?>
     <td class="reg_group_block_spacer">&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>

<?php
				$row_count ++;
				if (!( $row_count % $config["max_rows_per_page"] ) )
				{
?>
   </tbody>
  </table><!-- end table reg_group_block -->
	
<?php
				}
			}
		}
	}// end while
}//end if
?>

<?php
mysql_close();

unset($link);

page_footer();
?>
