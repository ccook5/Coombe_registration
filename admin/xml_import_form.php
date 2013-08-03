<?php
require_once("../connect.inc.php");
require_once("../config.inc.php");
require_once("../functions.inc.php");

page_header();

?>
  <form action="xml_import_handler.php" method="post" enctype="multipart/form-data">
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
<!--
  <div><a href="csv_template.csv">CSV Template</a></div>
//-->
<?php
	page_footer();
?>