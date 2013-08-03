<?php
require_once("../connect.inc.php");
require_once("../config.inc.php");
require_once("../functions.inc.php");


$client_page_url = "http://registration.coombe.local/client2/";
$client_page_onload_jscript = "
    document.getElementById('badge_id').focus();
    document.getElementById('badge_id').value = '';
    startPage();";

$javascript_code = "
  <script src='/clock/clock.js'  type='text/javascript'></script>
  <script src='/jpicker/jquery-1.4.4.min.js'  type='text/javascript'></script>
<script type='text/javascript'>
    $(document).ready(function() {
    $('.badge_id').keyup(function(event) {
        function clear_textbox()
        {
            var e = document.getElementById('badge_id');
            e.value = e.defaultValue;
        }
        
        function print_msg(msg)
        {
                document.getElementById('msg').innerHTML=msg;
                
                setTimeout(function() {
                    document.getElementById('msg').innerHTML='Please scan your id card';
                }, 2000);
        }
       
       if (event.keyCode == 13) //For enter.
       {
            var id = event.srcElement.attributes.id.value;
            var badge_code = $('#'+id).val();


            if ((badge_code.length < 4) || (badge_code.length > 12))
            {
                print_msg('Badge ID wrong length');
            }
            else if (badge_code.charCodeAt(0) != 172 && badge_code[0] != '$' && badge_code[0] != '~')
            {
                print_msg('Badge ID not read properly');
            }
            else
            {
                alert('ok');
            }
        }
        if (event.keyCode == 27) //For escape.
        {
            clear_textbox();
        }
    });
    });
</script>
";

page_header($client_page_url, 3600, $client_page_onload_jscript, $javascript_code);

?>
   <div class="form">
    <div id="msg" class="instructions">Please scan your Badge (test):</div>

     <input type="text" 
            name="badge_id"
            class="badge_id"
            id="badge_id"
            onblur="document.getElementById('badge_id').focus()"
            onclick=""
     />

   </div>
   
   
<div id="time"></div>
<div id="date"></div>
<?php 

page_footer();

mysql_close();

unset($link);
?>