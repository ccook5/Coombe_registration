<?php
require_once("../connect.inc.php");
require_once("../config.inc.php");
require_once("../functions.inc.php");
?>
<!doctype html> 
<html>
 <head>
  <title><?php echo $config["system_name"]; ?></title>

  <!-- Enable IE9 Standards mode -->
  <meta http-equiv="X-UA-Compatible" content="IE=9" >
  
  <link rel="stylesheet" href="/blueprint/screen.css" type="text/css" media="screen, projection" />
  <link rel="stylesheet" href="/blueprint/print.css" type="text/css" media="print" />
<!--[if lt IE 8]>
  <link rel="stylesheet" href="/blueprint/ie.css" type="text/css" media="screen, projection" />
<![endif]-->

  <link rel="stylesheet" type="text/css" href="<?php echo $config["stylesheet_url"]; ?>" media="screen, projection" />
  <link rel="stylesheet" type="text/css" href="<?php echo $config["print_stylesheet_url"]; ?>" media="print" />

  <style type="text/css">
  <!--
  @page {size: 8.5cm 5.3cm; margin: 0px; padding: 0px;}
  //-->
  </style>

 </head>

 <body onload="<?php echo $onload; ?>">
 
 <?php

if (isset($_GET["for"]))
{
	if ($_GET["for"] === "reg_group")
	{
		$who_for = "reg_group";

		if (isset($_GET["reggroup_id"]))
		{
			$reggroup_id = $_GET["reggroup_id"];
		}
	}
	else
	{
		$who_for = "person";

		if (isset($_GET["person_id"]))
		{
			$person_id = $_GET["person_id"];
		}
	}
}


$background_colour = "grey";

function print_id_card($row, $row1)
{
?>
		<table class="card">
		 <tr>
		  <td>
		   <table class="card_left">
		    <tr>
		     <td class="images">
<?php // get photo ?>
		      <img src="get_image.php?id=<?php echo($row['id']); ?>">
			  <img src="/images/CGSWebsiteLogo.png">
		     </td>
		    </tr>
		    <tr>
		     <td class="textbox">
			  <?php echo $row['first_name']." ".$row["last_name"]; ?> <br />
			  <?php echo $row1["name"]; ?>
		     </td>
		    </tr>
		   </table>
		  </td>
		  <td>
		   <img src="barcode.php?barcode=<?php echo $row['id']; ?>&quality=100&width=50&height=180">
		   <?php echo $row['id']; ?>
		  </td>
		 </tr>
		</table>
<?php
}


	$sql     = "SELECT * from reg_groups ORDER BY name";
	$result1 = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

	if ($result1)
	{
		$row_count = 0;

		while($row1 = mysql_fetch_array($result1))
		{

	// Query
		$sql = "SELECT * FROM people WHERE reg_group=".$row1["id"]." ORDER BY last_name";

		$result = mysql_query($sql, $link);

			if (!$result)
			{
				die('Invalid query: ' . mysql_error());
			}
			else
			{
				while($row = mysql_fetch_array($result))
				{
					if ($who_for === "reg_group")
					{
						if ($reggroup_id == $row1['id'])
						{
							print_id_card($row, $row1);
						}
					}
					else 
					{
						if ($person_id == $row['id'])
						{
							print_id_card($row, $row1);
						}
					}
				}
			}
		}// end while
	}//end if


mysql_close();

unset($link);

page_footer();
?>
