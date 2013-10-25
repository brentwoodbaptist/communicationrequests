<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"

# Enter your DB information below
$hostname_commreq = "localhost";
$database_commreq = "YOUR_DB_NAME";
$username_commreq = "YOUR_DB_USER";
$password_commreq = "YOUR_DB_PASSWORD";
$commreq = mysql_pconnect($hostname_commreq, $username_commreq, $password_commreq) or trigger_error(mysql_error(),E_USER_ERROR); 
?>