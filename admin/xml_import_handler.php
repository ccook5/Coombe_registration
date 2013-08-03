<?php
require_once("../connect.inc.php");
require_once("../config.inc.php");
require_once("../functions.inc.php");

require_once("functions.inc.php");
require_once("people.inc.php");
require_once("reg_groups.inc.php");

$action = @$_POST["action"];


if ( empty($_FILES) )
{
	error_page("Error: No file submitted. Please talk to an administrator.");
}

if ($_FILES["file"]["error"] > 0)
{
	error_page('Error: ' . $_FILES["file"]["error"]);
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
   
   <a href="index.php">&lt; Back</a>

    <table class="data">
     <tr>
      <th>Admission Number</th>
      <th>Forename</th>
      <th>Surname</th>
      <th>Year Group</th>
      <th>Reg Group</th>
      <th>Photo</th>
	 </tr>
<?php
		if (!file_exists($_FILES["file"]["tmp_name"]))
		{
			echo ("Error: Could not find uploaded file!");
		}
		$xml = simplexml_load_file($_FILES["file"]["tmp_name"]);

		foreach ($xml->Record as $record)
		{
			check_and_create_reg_group($record->RegGroup);
			
			$reg_group_id = lookup_reg_group_id($record->RegGroup);

			check_and_create_person($record->AdmissionNumber, $record->Forename, $record->LegalSurname, $reg_group_id, $record->Photo);

			echo("     <tr>");
			echo("      <td>".$record->AdmissionNumber."</td>");
			echo("      <td>".$record->Forename."</td>");
			echo("      <td>".$record->LegalSurname."</td>");
			echo("      <td>".$record->RegGroup."</td>");
			echo("      <td>".$record->Photo."</td>");
			echo("	 </tr>");
		}
?>
	</table>
<?php
	}
	page_footer();
?>