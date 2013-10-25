<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_commreq_conn = "mysql.brentwoodbaptist.tv";
$database_commreq_conn = "commreqs";
$username_commreq_conn = "db_admin";
$password_commreq_conn = "7777Concord";
$commreq_conn = mysql_pconnect($hostname_commreq_conn, $username_commreq_conn, $password_commreq_conn) or trigger_error(mysql_error(),E_USER_ERROR); 
?>