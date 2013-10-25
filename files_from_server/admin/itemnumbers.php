<?php require_once('../Connections/commreq.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
$searchCriteria="date1";
if (isset($_GET['order'])) {
  $searchCriteria = $_GET['order'];
}
mysql_select_db($database_commreq, $commreq);
$query_Recordset1 = "SELECT *, item.id AS itemid, project.id AS projectid FROM item  JOIN project ON item.projectid=project.id JOIN type ON item.type=type.id JOIN user ON project.user=user.id JOIN department on project.department=department.id JOIN status ON item.status=status.id ORDER BY item.id DESC, ".$searchCriteria." ASC";
$Recordset1 = mysql_query($query_Recordset1, $commreq) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Comm Reqs: Item Numbers &bull; Brentwood Baptist Communications</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery.1.4.2.js"></script>
<style>
.row0 {
	background-color:#D1D1D1;
}
td {
	padding: 3px;
}
</style>
</head>

<body>
<div id="containerlarge">
<div id="container2">
<br /><br />
<table align="center">
<tr><td width="100"><strong>Item Number</strong></td><td width="150"><strong>Item Name</strong></td><td width="300"><strong>Project Name</strong></td><td width="150"><strong>User</strong></td></tr>
<?php 
$counter=0;
do { ?>
<tr class="row<?php echo $counter;?>"><td><?php echo $row_Recordset1['itemid']; ?></td><td><?php echo $row_Recordset1['typename']; ?></td><td><?php echo $row_Recordset1['title']; ?></td><td><?php echo $row_Recordset1['username']; ?></td></tr>

<?php 
if($counter==0)
	$counter=1;
else
	$counter=0;
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>

</table>
<br /><br />

</div>
<div id="footer2">&copy; 2010 Brentwood Baptist Church</div></div>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
