<?php
require_once("../connect.inc.php");
require_once("../config.inc.php");
require_once("../functions.inc.php");

$firstname_col = 0;
$lastname_col  = 1;
$id_col        = 2;
$reggroup_col  = 3;

$action = @$_POST["action"];

if ($action == "")
{
	page_header();
?>
  <form action="bulk_import.php" method="post" enctype="multipart/form-data">
   <input type="hidden" name="action" value="upload_handler" />
   <table class="form_table">
    <tr>
     <td>
      <label for="file">Filename:</label>
      <input type="file" name="file" id="file" />
     </td>
    </tr>

    <tr><td></td></tr>

    <tr><td><input type="submit" /><input type="reset" /></td></tr>
   </table>
  </form>

  <div><a href="csv_template.csv">CSV Template</a></div>

<?php
	page_footer();
}
else if ($action == "upload_handler")
{
	if ( empty($_FILES) )
	{
		page_header();
		echo '<div class="error">Error: No file submitted. Please talk to an administrator.</div>';
		page_footer();
		exit;
	}
  
	if ($_FILES["file"]["error"] > 0)
	{
		page_header();
		echo '<div class="error">Error: ' . $_FILES["file"]["error"] . "</div>";
		page_footer();
	}
	else
	{
		page_header();
?>
   <table class="f_info">
    <tr><td>Upload:</td><td><?php echo $_FILES["file"]["name"]; ?></td></tr>
    <tr><td>Type:  </td><td><?php echo $_FILES["file"]["type"]; ?></td></tr>
    <tr><td>Size:  </td><td><?php echo $_FILES["file"]["size"]; ?> bytes</td></tr>
    <tr><td>Stored in: </td><td><?php echo $_FILES["file"]["tmp_name"]; ?></td></tr>
   </table>
   
<?php
/*		if (($handle = fopen($_FILES["file"]["tmp_name"], "r")) == FALSE)
		{ 
			echo '<div class="error">Error: Could not open tmp file</div>';
		}*/

		$auto_detect_line_endings = true;
		$handle = fopen($_FILES["file"]["tmp_name"], "rt");

		$row = 0;
?>
   <form action="bulk_import.php" method="POST">
    <input type="hidden" name="action" value="upload_handler_2" />
    <input type="submit" /><input type="reset" />
    <table class="data">
<?php
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
		{
			$num = count($data);
			$row++;
			echo "     <tr>\n";
			for ($c=0; $c < $num; $c++)
			{
				if ($row == 1)
				{
					echo '      <th><input type="hidden" name="col_header['.$c.']" value="'.$data[$c].'" />'.$data[$c] . "</th>\n";
				}
				else
				{
					echo '      <td><input type="text" name="data['.$row.']['.$c.']" value="'.$data[$c] . '" />'."</td>\n";
				}
			}
			echo "     </tr>\n";
		}
?>
	</table>
   </form>
<?php
	}
	page_footer();
}
else if ($action == "upload_handler_2")
{
	page_header();
?>
    <table class="data">
<?php
// keys become values, and values become keys...
	$col_header = array_flip( $_POST["col_header"] );
 
	print("test");
 
	print_r($col_header);

	foreach ($_POST["data"] as $d)
	{
/* check if reg group exists, and create if it does not */	 
		$sql = "SELECT * FROM reg_groups WHERE name=\"".$d[ $col_header["Reg"] ]."\" LIMIT 1";
		$res = mysql_query($sql, $link) or die('Invalid query ['.$sql.']: ' . mysql_error() . " on line ". __LINE__);

		if (mysql_num_rows($res) == 0)
		{
			$sql    = "INSERT INTO reg_groups (id, name) VALUES (NULL, \"".$d[ $col_header["Reg"] ]."\");";
			$result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

			if ($result)
			{
				echo '<div class="success">Added reg group '.$d[ $col_header["Reg"] ].'</div>';
			}
			else
			{
				echo '<div class="error:>Error adding a reg group.</div>';
			}
		}

/* lookup reg_group_id */
		$sql = "SELECT * FROM reg_groups WHERE name=\"".$d[ $col_header["Reg"] ]."\" LIMIT 1";
		$res = mysql_query($sql, $link) or die('Invalid query ['.$sql.']: ' . mysql_error() . " on line ". __LINE__);

		$reg_group_id = 1;

		if ($res)
		{
			$row_rg = mysql_fetch_array($res);

			$reg_group_id = $row_rg["id"];
		}

/* check if person exists, and create if it does not */	 
		$sql = "SELECT * FROM people WHERE id=\"".$d[ $col_header["Adno"] ]."\" LIMIT 1";
		$res = mysql_query($sql, $link) or die('Invalid query ['.$sql.']: ' . mysql_error() . " on line ". __LINE__);

		if (mysql_num_rows($res) == 0)
		{
			$sql    = 'INSERT INTO people (id, first_name, last_name, reg_group, in_out) VALUES ("'.$d[ $col_header["Adno"] ].'", "'.$d[$firstname_col].'", "'.$d[$lastname_col].'", '.$reg_group_id.', "out");';
			$result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error() . ' on line ' . __LINE__);

			if ($result)
			{
				echo '<div class="success">Added '.$d[$firstname_col].' '.$d[$lastname_col].'</div>';
			}
			else
			{
				echo '<div class="error">Error adding person.</div>';
			}
		}
		else if (mysql_num_rows($res) == 1)
		{
			$sql    = 'UPDATE people SET id='.$d[ $col_header["Adno"] ].', first_name="'.$d[$firstname_col].'", last_name="'.$d[$lastname_col].'", reg_group='.$reg_group_id.', in_out="out'.'" WHERE id='.$d[ $col_header["Adno"] ].';';
			$result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error()." on line ".__LINE__);

			if ($result)
			{
				echo '<div class="success">Updated '.$d[$firstname_col].' '.$d[$lastname_col].'</div>';
			}
			else
			{
				echo '<div class="error">Error updating person.</div>';
			}
		}
		echo '<tr>';
		foreach ($d as $a)
		{
			echo '<td>'.$a.'</td>';
		}
		echo '</tr>';
	}
?>
	</table>
<?php

	page_footer();
}
?>
