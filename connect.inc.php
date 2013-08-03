<?php

//set the timezone, mostly so that newer versions of php don't complain.

date_default_timezone_set('Europe/London');

// Database Settings
//-------------------
// These need to match you mysql database settings. See your sys admin, isp or web host if necessary.

$db_username="registration";
$db_password="password";
$db_host    ="mysql";
$db_database="registration";

$link = mysql_pconnect($db_host, $db_username, $db_password);
@mysql_select_db($db_database) or die( "Unable to select database");

?>