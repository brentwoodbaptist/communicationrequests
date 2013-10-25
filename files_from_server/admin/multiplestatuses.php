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

$colname_Recordset1 = "-1";

$checkedBoxes = $_POST['changeStatus'];
$status = $_POST['multistatus'];

$counter=count($checkedBoxes);
$conditionStatement="item.id=".$checkedBoxes[0];
if ($counter>1){
	for($i=1; $i<$counter; $i++) {
		$conditionStatement=$conditionStatement." OR item.id=".$checkedBoxes[$i];
	}
}
echo $conditionStatement;

mysql_select_db($database_commreq, $commreq);
$query_Recordset1 = "UPDATE item SET status=".$status." WHERE ".$conditionStatement;
$Recordset1 = mysql_query($query_Recordset1, $commreq) or die(mysql_error());


mysql_select_db($database_commreq, $commreq);
		$query_Recordset2 = "SELECT *, item.id AS itemid FROM item JOIN project ON item.projectid=project.id JOIN user ON project.user=user.id JOIN status ON item.status=status.id JOIN type ON item.type=type.id WHERE ".$conditionStatement;
		$Recordset2 = mysql_query($query_Recordset2, $commreq) or die(mysql_error());
		$row_Recordset2 = mysql_fetch_assoc($Recordset2);
		$totalRows_Recordset2 = mysql_num_rows($Recordset2);
		
		do {
		$projectnumber=10000+$row_Recordset2['projectid'];
		
		$subject = "Comm Request #".$projectnumber.": ".$row_Recordset2['typename']." status changed to ".$row_Recordset2['statusname'];
		$message = "<body style=\"BACKGROUND-COLOR: #EAE9E7;\"><TABLE style=\"BACKGROUND-COLOR: #000000;\" border=0 cellSpacing=0 cellPadding=0 width=600 align=center>
<TBODY>
<TR>
<TD><IMG border=0 alt=\"Brentwood Baptist Communications\" src=\"http://brentwoodbaptist.dreamhosters.com/commreq/images/emailheader.jpg\" width=600 height=100><IMG border=0 alt=\"Submission Confirmation\" src=\"http://brentwoodbaptist.dreamhosters.com/commreq/images/emailstatus.jpg\" width=600 height=37></TD></TR>
<TR>
<TD>
<TABLE border=0 cellSpacing=0 cellPadding=10 width=600>
<TBODY>
<TR>
<TD style=\"LINE-HEIGHT: 1.5em; FONT-FAMILY: Arial, Helvetica, sans-serif; BACKGROUND-COLOR: #EAE9E7; COLOR: #000; FONT-SIZE: 12px\" colSpan=2>";
		$message .= "<h3>The status for Comm Request #".$projectnumber.": ".$row_Recordset2['typename']." has changed to ".$row_Recordset2['statusname'].".</h3>";
		if ($row_Recordset2['statusname']=="Complete"){
			$message .= "<p>Now that your comm req is complete, please take some time to let us know how we did by completing the short survey <a href=\"http://www.commreq.com/survey.php?id=".$row_Recordset2['itemid']."\">here</a>.</p>";
		}
		if ($row_Recordset2['statusname']=="Canceled"){
			$message .= "<p>If you think this item was canceled by mistake, please reply to this email or email the Project Manager at projectmanager@brentwoodbaptist.com.</p><br />";
		}
		$message .= "</TD></TR></TBODY></TABLE></TD></TR>";
		$message .="<TR>
<TD><IMG border=0 alt=\"Brentwood Baptist Communications\" src=\"http://brentwoodbaptist.dreamhosters.com/commreq/images/emaildetails.jpg\" width=600 height=41></TD></TR>";
		if ($row_Recordset2['date1']!="0000-00-00"){
			list($yr,$mon,$day) = split('-',$row_Recordset2['date1']);
			$display_date = date('F j, Y', mktime(0,0,0,$mon,$day,$yr));
		}
		else
			$display_date="No Date";
		$message.="<TR>
<TD>
<TABLE border=0 cellSpacing=0 cellPadding=10 width=600>
<TBODY>
<TR>
<TD style=\"LINE-HEIGHT: 1.5em; FONT-FAMILY: Arial, Helvetica, sans-serif; BACKGROUND-COLOR: #EAE9E7; COLOR: #000; FONT-SIZE: 12px\" colSpan=2>
<p><strong>PROJECT TITLE: </strong>".$row_Recordset2['title']."<br /><strong>TYPE:</strong> ".$row_Recordset2['typename']."<br /><strong>STATUS:</strong> ".$row_Recordset2['statusname']."</p><p><strong>START/DUE DATE:</strong> ".$display_date."<br /><strong>DESCRIPTION:</strong> ".$row_Recordset2['description']."</p><p><em>Contact the Project Manager at 6138 or reply to this email if you have any further questions.</em></p>";
		$message .="</TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></body>";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		$headers .= 'From: Project Manager <projectmanager@brentwoodbaptist.com>';
		
		$recipient = $row_Recordset2['email'];

		mail($recipient,$subject,$message,$headers);
		} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));


$updateGoTo = "index.php";

header(sprintf("Location: %s", $updateGoTo));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>