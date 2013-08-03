<?php

// Clean up data from the user

//$_GET     = array_map('trim', $_GET); 
//$_POST    = array_map('trim', $_POST); 
$_COOKIE  = array_map('trim', $_COOKIE); 
//$_REQUEST = array_map('trim', $_REQUEST);

if (get_magic_quotes_gpc())
{ 
    $_GET     = array_map('stripslashes', $_GET); 
    $_POST    = array_map('stripslashes', $_POST); 
    $_COOKIE  = array_map('stripslashes', $_COOKIE); 
    $_REQUEST = array_map('stripslashes', $_REQUEST); 
} 

$_GET     = array_map('mysql_real_escape_string', $_GET); 
//$_POST    = array_map('mysql_real_escape_string', $_POST); 
$_COOKIE  = array_map('mysql_real_escape_string', $_COOKIE); 
//$_REQUEST = array_map('mysql_real_escape_string', $_REQUEST);


function page_header($redirect_url = "", $redirect_delay = 0, $onload = "", $extra = "")
{
  global $config;
?>
<!doctype html> 
<html>
 <head>
  <meta charset="utf8" />
  <title><?php echo $config["system_name"]; ?></title>

  <!-- Enable IE9 Standards mode -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" >
  <!--<meta http-equiv="X-UA-Compatible" content="IE=9" > -->
  
  <link rel="stylesheet" href="/blueprint/screen.css" type="text/css" media="screen, projection" />
  <link rel="stylesheet" href="/blueprint/print.css"  type="text/css" media="print" />
<!--[if lt IE 8]>
  <link rel="stylesheet" href="/blueprint/ie.css" type="text/css" media="screen, projection" />
<![endif]-->

  <link rel="stylesheet" type="text/css" href="<?php echo $config["stylesheet_url"]; ?>" media="screen, projection" />
  <link rel="stylesheet" type="text/css" href="<?php echo $config["print_stylesheet_url"]; ?>" media="print" />

<?php  
    if ($redirect_url != "") {
        echo '<meta http-equiv="REFRESH" content="'.$redirect_delay.';url='.$redirect_url.'" />';
    }
    echo($extra);
?>
 </head>

 <body onload="<?php echo $onload; ?>">
  <div class="headdiv">
   <h1 class="header"><?php echo $config["system_name"]; ?></h1>
  </div><!-- class="headdiv" -->
  <div class="main">
  
<?php
}

function page_footer()
{
?>
  </div><!-- class="main" -->
 </body>
</html>
<?php
}
?>