<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_MAMP = "localhost";
$database_MAMP = "DuIt";
$username_MAMP = "root";
$password_MAMP = "root";
$MAMP = mysql_pconnect($hostname_MAMP, $username_MAMP, $password_MAMP) or trigger_error(mysql_error(),E_USER_ERROR); 
?>