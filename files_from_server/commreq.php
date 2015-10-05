<?php require_once('Connections/commreq.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO project (title, `user`, department, account) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['user'], "int"),
                       GetSQLValueString($_POST['department'], "int"),
                       GetSQLValueString($_POST['account'], "text"));

  mysql_select_db($database_commreq, $commreq);
  $Result1 = mysql_query($insertSQL, $commreq) or die(mysql_error());
  $projectid=mysql_insert_id();
  
  if (isset($_POST['upload'])) {
	  $numberofitems=count($_POST['upload']);
	  $querystring="UPDATE uploads SET projectid=".$projectid." WHERE ";
	  for ( $i=0;$i<$numberofitems;$i++) {
	  	$querystring=$querystring."id=".$_POST['upload'][$i];
		if ($i!=$numberofitems-1){
			$querystring=$querystring." OR ";
		}
	  }
	  $Result3 = mysql_query($querystring, $commreq) or die(mysql_error());
  }
  
  if (isset($_POST['type'])) {
	  $numberofitems=count($_POST['type']);
	for ( $i=0;$i<$numberofitems;$i++) {
		$type[$i] = $_POST['type'][$i];
		if ($_POST['description'][$i]!="")
			$description[$i] = $_POST['description'][$i];
		else
			$description[$i]=" ";
		if ($_POST['startdate'][$i]!="")
			$startdate[$i] = $_POST['startdate'][$i];
		else
			$startdate[$i]="0000-00-00";
		if ($_POST['enddate'][$i]!="")
			$enddate[$i] = $_POST['enddate'][$i];
		else
			$enddate[$i]="0000-00-00";
		if ($_POST['duedate'][$i]!="")
			$startdate[$i] = $_POST['duedate'][$i];
		if ($_POST['quantity'][$i]!="")
			$quantity[$i] = $_POST['quantity'][$i];
		else
			$quantity[$i] = 0;
		}
	$insertSQL2="INSERT INTO item (projectid, type, description, date1, date2, quantity, status, submitted) VALUES ";
	$thisDate=date("Y-m-d");
	for ( $i=0;$i<$numberofitems;$i++) {
	$description[$i]=mysql_real_escape_string($description[$i]);
	if($numberofitems-1==$i) {
	$setStatus=3;
	if ($type[$i]==1){
		$setStatus=1;
	}
	$insertSQL2=$insertSQL2."(".$projectid.", ".$type[$i].", '".$description[$i]."', '".$startdate[$i]."', '".$enddate[$i]."', ".$quantity[$i].", ".$setStatus.", '".$thisDate."') ";
	 }
	else {
		$setStatus=3;
	if ($type[$i]==1){
		$setStatus=1;
	}
	$insertSQL2=$insertSQL2."(".$projectid.", ".$type[$i].", '".$description[$i]."', '".$startdate[$i]."', '".$enddate[$i]."', ".$quantity[$i].", ".$setStatus.", '".$thisDate."'), ";
	}
	}
	echo $insertSQL2;
	mysql_select_db($database_commreq, $commreq);
  	$Result2 = mysql_query($insertSQL2, $commreq) or die(mysql_error());
  }
  
  		$projectnumber=$projectid+10000;
		mysql_select_db($database_commreq, $commreq);
		$query_Recordset3 = "SELECT * FROM project JOIN user ON project.user=user.id JOIN department ON project.department=department.id WHERE project.id = ".$projectid;
		$Recordset3 = mysql_query($query_Recordset3, $commreq) or die(mysql_error());
		$row_Recordset3 = mysql_fetch_assoc($Recordset3);
		$totalRows_Recordset3 = mysql_num_rows($Recordset3);

		mysql_select_db($database_commreq, $commreq);
		$query_Recordset2 = "SELECT * FROM item JOIN type ON item.type=type.id WHERE projectid = ".$projectid;
		$Recordset2 = mysql_query($query_Recordset2, $commreq) or die(mysql_error());
		$row_Recordset2 = mysql_fetch_assoc($Recordset2);
		$totalRows_Recordset2 = mysql_num_rows($Recordset2);
		
		mysql_select_db($database_commreq, $commreq);
		$query_Recordset4 = "SELECT * FROM uploads WHERE projectid = ".$projectid;
		$Recordset4 = mysql_query($query_Recordset4, $commreq) or die(mysql_error());
		$row_Recordset4 = mysql_fetch_assoc($Recordset4);
		$totalRows_Recordset4 = mysql_num_rows($Recordset4);
		
		require_once 'includes/liquidplanner.php';
		// LiquidPlanner credentials
		$lp = new LiquidPlanner("YOUR_WORKSPACE_ID", "YOUR_LP_EMAIL_ADDRESS", "YOUR_LP_PASSWORD");
		// No LP project exists
		$lpproject=0;
		
		$subject = "Comm Request #".$projectnumber." Submitted Successfully";
		$message = "<body style=\"BACKGROUND-COLOR: #EAE9E7;\"><TABLE style=\"BACKGROUND-COLOR: #000000;\" border=0 cellSpacing=0 cellPadding=0 width=600 align=center>
<TBODY>
<TR>
<TD><IMG border=0 alt=\"Brentwood Baptist Communications\" src=\"http://brentwoodbaptist.dreamhosters.com/commreq/images/emailheader.jpg\" width=600 height=100><IMG border=0 alt=\"Submission Confirmation\" src=\"http://brentwoodbaptist.dreamhosters.com/commreq/images/emailsubmission.jpg\" width=600 height=37></TD></TR>
<TR>
<TD>
<TABLE border=0 cellSpacing=0 cellPadding=10 width=600>
<TBODY>
<TR>
<TD style=\"LINE-HEIGHT: 1.5em; FONT-FAMILY: Arial, Helvetica, sans-serif; BACKGROUND-COLOR: #EAE9E7; COLOR: #000; FONT-SIZE: 12px\" colSpan=2>";
		$message .= "<h3>You have successfully submitted comm req #".$projectnumber.".</h3> <p>If you find any problems or errors in your submission, please reply to this email or contact the project manager (<a href=\"mailto:projectmanager@brentwoodbaptist.com\">projectmanager@brentwoodbaptist.com</a>).</p></TD></TR></TBODY></TABLE></TD></TR>";
		$message .="<TR>
<TD><IMG border=0 alt=\"Brentwood Baptist Communications\" src=\"http://brentwoodbaptist.dreamhosters.com/commreq/images/emailproject.jpg\" width=600 height=41></TD></TR>";
		$message .="<TR>
<TD>
<TABLE border=0 cellSpacing=0 cellPadding=10 width=600>
<TBODY>
<TR>
<TD style=\"LINE-HEIGHT: 1.5em; FONT-FAMILY: Arial, Helvetica, sans-serif; BACKGROUND-COLOR: #EAE9E7; COLOR: #000; FONT-SIZE: 12px\" colSpan=2>
<p><strong>PROJECT TITLE</strong>: ".$row_Recordset3['title']."<br />";
		$message .="<strong>NAME</strong>: ".$row_Recordset3['username']."<br />";
		$message .="<strong>DEPARTMENT</strong>: ".$row_Recordset3['departmentname'];
		if($row_Recordset3['account']){
		$message .="<br /><strong>ACCOUNT CODE</strong>: ".$row_Recordset3['account'];
		}
		$message .="</p></TD></TR></TBODY></TABLE></TD></TR>";
		$message .="<TR>
<TD><IMG border=0 alt=\"Brentwood Baptist Communications\" src=\"http://brentwoodbaptist.dreamhosters.com/commreq/images/emailitem.jpg\" width=600 height=41></TD></TR><TR>
<TD>
<TABLE border=0 cellSpacing=0 cellPadding=10 width=600>
<TBODY>
<TR>
<TD style=\"LINE-HEIGHT: 1.5em; FONT-FAMILY: Arial, Helvetica, sans-serif; BACKGROUND-COLOR: #EAE9E7; COLOR: #000; FONT-SIZE: 12px\" colSpan=2>";
		do {
  			$message .="<p><strong>TYPE:</strong> ".$row_Recordset2['typename'];
  			if ($row_Recordset2['type']<=4){ 
				$message .="<br /><strong>START:</strong> ".$row_Recordset2['date1'];
  				$message .="<br /><strong>END:</strong> ".$row_Recordset2['date2'];
  			} else if ($row_Recordset2['type']==5 || $row_Recordset2['type']==8){
				$message .="<br /><strong>DUE:</strong> ".$row_Recordset2['date1'];
  				$message .="<br /><strong>QUANTITY:</strong> ".$row_Recordset2['quantity'];
  			} else if ($row_Recordset2['type']==6 || $row_Recordset2['type']==7 || $row_Recordset2['type']==9 || $row_Recordset2['type']==10){
  				$message .="<br /><strong>DUE:</strong> ".$row_Recordset2['date1'];
  			}
  			$message .="</p><p><strong>DESCRIPTION:</strong> ".$row_Recordset2['description']."</p>
  <IMG border=0 alt=\"\" src=\"http://brentwoodbaptist.dreamhosters.com/commreq/images/emailseparator.jpg\" width=580 height=5>";
  
  			// If not a simple bulletin announcement
  			if ($row_Recordset2['type']!=1){
				// If no LP project exists
				if ($lpproject==0){
					// Project Name
					$project['name']        = $row_Recordset3['title'];
					// Make Comm Req package the parent container
					$project['parent_id']   = 4945992;
					// Commreq ID as the LP reference
					$project['external_reference']   = $projectnumber;
					// Project Description
					$project['description'] = 	"Comm Req: ".$projectnumber."\n
									Submitted by: ".$row_Recordset3['username']."\n
									Department: ".$row_Recordset3['departmentname']."\n
									Account Code: ".$row_Recordset3['account']."\n
									Link: http://www.commreq.com/projectinfo.php?id=".$projectid;
					// Add URLs to any files that were attached
					if($totalRows_Recordset4!=0){
						$filesList .= "\n\n Attached Files";
						do {
							$filesList .= "\n
										File: ".$row_Recordset4['originalfilename']."
										http://www.commreq.com/uploads/".$row_Recordset4['newfilename'];
						} while ($row_Recordset4 = mysql_fetch_assoc($Recordset4));
						$project['description'] .= $filesList;
					}
					
					// Create the project
					$responseProject = $lp->projects_create($project);
					//Return the project ID to use for assinging tasks
					$lpproject=$responseProject['id'];
				}
				
				// Task Name
				$task['name']        = $row_Recordset2['typename']." | ".$row_Recordset3['title'];
				// Use newly created project ID as parent project
				$task['parent_id']   = $lpproject;
				// Commreq ID as the LP reference
				$task['external_reference']   = $projectnumber;
				// Type specific description generation & strip tags
				if ($row_Recordset2['type']<=4){ 
					$task['description'] ="Start Date: ".$row_Recordset2['date1']."\n";
	  				$task['description'] .="End Date: ".$row_Recordset2['date2']."\n";
	  			} else if ($row_Recordset2['type']==5 || $row_Recordset2['type']==8){
					$task['description'] ="Due Date: ".$row_Recordset2['date1']."\n";
	  				$task['description'] .="Quantity: ".$row_Recordset2['quantity']."\n";
	  			} else if ($row_Recordset2['type']==6 || $row_Recordset2['type']==7 || $row_Recordset2['type']==9 || $row_Recordset2['type']==10){
	  				$task['description'] ="Due Date: ".$row_Recordset2['date1']."\n";
	  			}
				$descriptionStripped = htmlspecialchars_decode($row_Recordset2['description']);
				$descriptionStripped = strip_tags($descriptionStripped);
				$descriptionStripped = str_replace("&nbsp;", " ", $descriptionStripped);
				$descriptionStripped = str_replace("&ndash;", "-", $descriptionStripped);
				$descriptionStripped = str_replace("&mdash;", "-", $descriptionStripped);
				$descriptionStripped = str_replace("&ldquo;", "\"", $descriptionStripped);
				$descriptionStripped = str_replace("&rdquo;", "\"", $descriptionStripped);
				$descriptionStripped = str_replace("&rsquo;", "'", $descriptionStripped);
				$descriptionStripped = str_replace("&lsquo;", "'", $descriptionStripped);
				$task['description'] .= "\n".$descriptionStripped."\n \n";
				$task['description'] .= "Comm Req: ".$projectnumber."\n
							Submitted: ".date('Y-m-d')."\n
							Submitted by: ".$row_Recordset3['username']."\n
							Department: ".$row_Recordset3['departmentname']."\n
							Link: http://www.commreq.com/projectinfo.php?id=".$projectid;
				// Add URLs to files
				if($totalRows_Recordset4!=0){
					$task['description'] .= $filesList;
				}
				
				// Add LP due date if one is available
				if($row_Recordset2['date1']!="0000-00-00") {	
					$task['promise_by'] = $row_Recordset2['date1']."T00:00:01+00:00";
				}
				
				// Create task
				$response = $lp->tasks_create($task);
			
			}
			
			
  		} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
		$message .="</TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></body>";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		$headers .= 'From: Project Manager <projectmanager@brentwoodbaptist.com>' . "\r\n";
		$headers .= 'Cc: projectmanager@brentwoodbaptist.com' . "\r\n";
		
		$recipient = $row_Recordset3['email'];

		mail($recipient,$subject,$message,$headers);
  
  $insertGoTo = "success.php?id=".$projectid;
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_commreq, $commreq);
$query_department_RS = "SELECT * FROM department WHERE active=1 ORDER BY departmentname ASC";
$department_RS = mysql_query($query_department_RS, $commreq) or die(mysql_error());
$row_department_RS = mysql_fetch_assoc($department_RS);
$totalRows_department_RS = mysql_num_rows($department_RS);

mysql_select_db($database_commreq, $commreq);
$query_user_RS = "SELECT id, username FROM `user` WHERE active=1 ORDER BY username ASC";
$user_RS = mysql_query($query_user_RS, $commreq) or die(mysql_error());
$row_user_RS = mysql_fetch_assoc($user_RS);
$totalRows_user_RS = mysql_num_rows($user_RS);

mysql_select_db($database_commreq, $commreq);
$query_Recordset1 = "SELECT * FROM type WHERE id!=3 ORDER BY id ASC";
$Recordset1 = mysql_query($query_Recordset1, $commreq) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        
        <title>Comm Req Form // Brentwood Baptist Communications</title>
        <link rel="stylesheet" href="reporting/css/main.css" />
        <link rel="stylesheet" href="reporting/css/smoothness/jquery-ui.css" />
        <link rel="shortcut icon" href="/favicon.ico" />
        
        
        <meta name="description" content="">
        <meta property="og:title" content="">
       	<meta property="og:url" content="" />
		<meta property="og:image" content="" /> 
        
        <!--[if lt IE 9]>
			<script src="scripts/html5shiv.js"></script>
            
		<![endif]-->
        <!--[if gte IE 9]>
  			<style type="text/css">
    			.gradient {
       				filter: none !important;
                    background-color:none !important;
    			}
  			</style>
		<![endif]-->
        <script src="reporting/scripts/jquery.js"></script>
        <script src="reporting/scripts/jquery-ui.js"></script>
        <link href="uploadify/uploadify.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="uploadify/swfobject.js"></script>
		<script type="text/javascript" src="uploadify/jquery.uploadify.v2.1.2.min.js"></script>
		<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
		<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
		<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
		<script type="text/javascript">
	tinyMCE.init({
	mode : "exact",
	elements : "description",
	theme : "simple",
	plugins : "paste",
	paste_remove_styles: "true",
	width: "770"
	});
</script>

<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
        
        <script>
       	$("document").ready(function() {
				
			});
		</script>
    </head>
    <body>
    	<header>
        	<div class="centercontent">
            	<img src="reporting/images/commreq_logo.png" class="logo">
                <a href="reporting/" class="submitlink">Project Reporting</a>
        	</div>
        </header>
        <nav id="subnav">
        	<div class="centercontent">
           	  <div id="subnavlinks">
            		<div class="label">Contact:</div>
<a href="mailto:lchaudhry@brentwoodbaptist.com" class="darkened">Laura Chaudhry [lchaudhry@brentwoodbaptist.com]</a>
            		<div class="clear"></div>
              </div>
       	  </div>
        </nav>
        <section id="maincontent">
        	<div class="centercontent">
            	<div id="detailcontainer">
                	<h2>Comm Req Form</h2>
                    <div class="textcontainer">
                    	
                        
                        <div id="containersmallform">
<div id="container">
<div id="itemfields" style="display:none; margin: 10px 0px; padding:10px 15px; border: #AAA 1px solid;">
  <span onclick="this.parentNode.parentNode.removeChild(this.parentNode);" class="removeitem"><img src="images/delete.png" width="18" height="18" alt="Remove Item" /></span>
  
  	<div class="clear"></div>	
  
      
      <span class="typelabel">Type:</span><select name="type" class="type select" required>
        <option value="">Pick a type...</option>
        <?php 
do {  
?>
        <option value="<?php echo $row_Recordset1['id']?>" ><?php echo $row_Recordset1['typename']?></option>
        <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
?>
      </select><div class="clear"></div>
      <span id="datelabel" class="datelabel">Start Date:</span><input type="text" name="startdate" value="" size="30" class="startdate text" />
      <input type="text" name="duedate" value="" size="30" style="display: none;" class="duedate text" />
      <div class="clear"></div>
      <span id="otherdatelabel" class="otherdatelabel">End Date:</span><input type="text" name="enddate" value="" size="30" class="enddate text" />      
      <input type="text" name="quantity" value="" size="30" style="display: none;" class="quantity text" />
      <div class="clear"></div>
      
        <div class="clear"></div><br />
        <span class="descriptionlabel">Description: </span><textarea cols="45" rows="3" name="description" class="description"></textarea>
      </div>

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  	<table class="reqtable">
  	<tr><td class="projectlabel">Project Title<span style="color:#FF0000;">*</span>:</td><td><span id="sprytextfield1">
  	<input type="text" name="title" value="" size="32" class="text" required />
  	<span class="textfieldRequiredMsg">A value is required.</span></span></td></tr>
    
    <tr><td class="projectlabel">Name<span style="color:#FF0000;">*</span>:</td><td><span id="spryselect1">
      <select name="user" class="select" required>
        <option value="" >Select Your Name...</option>
        <?php 
do {  
?>
        <option value="<?php echo $row_user_RS['id']?>" ><?php echo $row_user_RS['username']?></option>
        <?php
} while ($row_user_RS = mysql_fetch_assoc($user_RS));
?>
      </select>
      <span class="selectRequiredMsg">Please select an item.</span></span></td></tr>
      
      <tr><td class="projectlabel">Department<span style="color:#FF0000;">*</span>:</td><td><span id="spryselect2">
      <select name="department" class="select" required>
        <option value="" >Select Department...</option>
        <?php 
do {  
?>
        <option value="<?php echo $row_department_RS['id']?>" ><?php echo $row_department_RS['departmentname']?></option>
        <?php
} while ($row_department_RS = mysql_fetch_assoc($department_RS));
?>
      </select>
      <span class="selectRequiredMsg">Please select an item.</span></span></td></tr>
      <tr><td class="projectlabel">Account Code<span style="color:#FF0000;">*</span>:</td><td>
  	<input type="text" name="account" value="" size="32" class="text" required /></td></tr>
    </table>
    <br /><br />
      <h2>Items</h2>
      <div id="individualitems">
      <span id="itementry"></span>
      <span id="additem"><img src="images/additem.jpg" alt="Add New Item" /></span></span>
</div><br /><br /><br />
<h2>File Uploads</h2>
<div id="fileupload">

<div id="uploadqueue"></div><br />
<div id="uploadContent"></div>
<address>*After adding a file, please wait until the file upload box has turned black and says completed to continue.<br />
<span style="font-weight:normal;">File uploads are limited to 10MB per file. Please refrain from using special characters in your filenames including but not limited to ., ', \, ?, *, ", and $. Please contact the project manager if your file is larger than 10MB.</span>
</address>
</div><br /><br />
      <input type="submit" value="Submit Comm Req" id="submitbutton" />
      <div class="clear"></div>
    
  
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<br /><br />
<div id="individualitems"><address>If you're having trouble or have questions your comm req, please contact the project manager at <a href="mailto:projectmanager@brentwoodbaptist.com">projectmanager@brentwoodbaptist.com</a> or 6138. If you're having technical trouble with the form or website, please contact the web manager at <a href="mailto:web@brentwoodbaptist.com">web@brentwoodbaptist.com</a> or 6159.</address></div><br />
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
$('#uploadContent').uploadify({
'uploader': 'uploadify/uploadify.swf',
'script': 'uploadify/uploadify.php',
'scriptAccess': 'always',
'auto': true,
'multi': 'true',
'queueID': 'uploadqueue',
'sizeLimit': '10000000',
'folder': 'uploads/',
'removeCompleted' : false,
'width': 125,
'buttonImg': 'images/addafile.jpg',
'buttonText': 'ADD A FILE',
'cancelImg': 'uploadify/cancel.png',
'onComplete'  : function(event, ID, fileObj, response, data) {
				var input = document.createElement("input");
				input.setAttribute("type", "text");
				input.setAttribute("name", "upload[]");
				var idname="upload"+ID;
				input.setAttribute("id", idname);
				input.setAttribute("value", response);
				document.getElementById("form1").appendChild(input);
				document.getElementById(idname).style.display="none";
				},
'onCancel'  : function(event, ID, fileObj, response, data) {
				var idname="upload"+ID;
				document.getElementById(idname).setAttribute("value", 0);
				}
});
});
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none");
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2");
</script>
                                                
                        
                    </div>
                </div>
        	</div>
        </section>
        <?php include('reporting/includes/footer.php'); ?>
    </body>
    <script type="text/javascript">
var counter = 0;

function additem() {
	counter++;
	var newFields = document.getElementById('itemfields').cloneNode(true);
	newFields.id = '';
	newFields.style.display = 'block';
	var newField = newFields.childNodes;
	for (var i=0;i<newField.length;i++) {
		var theID = newField[i].id;
		if (theID) {
			newField[i].id = theID + counter;
		}
		var theName = newField[i].name;
		if (theName) {
			newField[i].name = theName + "[]";
			newField[i].id = theName + counter;
		}
		
	}
	var insertHere = document.getElementById('itementry');
	insertHere.parentNode.insertBefore(newFields,insertHere);
	var selectname="type"+counter;
	var numero=counter;
	document.getElementById(selectname).onchange=function(){
		datefields(numero);
	}
	datefields(numero);
	var descriptionid="description"+counter;
	tinyMCE.execCommand("mceAddControl", false, descriptionid);
	var datefield="#startdate"+counter;
	$(datefield).datepicker({ 
		dateFormat: 'yy-mm-dd',
		minDate: '0'});
	var datefield1="#enddate"+counter;
	$(datefield1).datepicker({ dateFormat: 'yy-mm-dd',
		minDate: '0' });
	var datefield2="#duedate"+counter;
	$(datefield2).datepicker({ dateFormat: 'yy-mm-dd',
		minDate: '0' });
}

function datefields(number) {
	var typename="type"+number;
	var selectvalue=document.getElementById(typename).value;
	if (selectvalue==1 || selectvalue==2 || selectvalue==3 || selectvalue==4) {
	var startdate="startdate"+number;
	document.getElementById(startdate).style.display = "block";
	var enddate="enddate"+number;
	document.getElementById(enddate).style.display = "block";
	var duedate="duedate"+number;
	document.getElementById(duedate).style.display = "none";
	document.getElementById(duedate).value = "";
	var quantity="quantity"+number;
	document.getElementById(quantity).style.display = "none";
	document.getElementById(quantity).value = "";
	document.getElementById(quantity).removeAttribute("required");
	var datelabel="datelabel"+number;
	document.getElementById(datelabel).innerHTML = "Start Date:";
	var otherdatelabel="otherdatelabel"+number;
	document.getElementById(otherdatelabel).innerHTML = "End Date:";
	}
	else if (selectvalue==6 || selectvalue==9 || selectvalue==10){
	var startdate="startdate"+number;
	document.getElementById(startdate).style.display = "none";
	document.getElementById(startdate).value = "";
	var enddate="enddate"+number;
	document.getElementById(enddate).style.display = "none";
	document.getElementById(enddate).value = "";
	var duedate="duedate"+number;
	document.getElementById(duedate).style.display = "block";
	var quantity="quantity"+number;
	document.getElementById(quantity).style.display = "none";
	document.getElementById(quantity).value = "";
	document.getElementById(quantity).removeAttribute("required");
	var datelabel="datelabel"+number;
	document.getElementById(datelabel).innerHTML = "Due Date:";
	var otherdatelabel="otherdatelabel"+number;
	document.getElementById(otherdatelabel).innerHTML = "";
	}
	else if (selectvalue==5 || selectvalue==8){
	var startdate="startdate"+number;
	document.getElementById(startdate).style.display = "none";
	document.getElementById(startdate).value = "";
	var enddate="enddate"+number;
	document.getElementById(enddate).style.display = "none";
	document.getElementById(enddate).value = "";
	var duedate="duedate"+number;
	document.getElementById(duedate).style.display = "block";
	var quantity="quantity"+number;
	document.getElementById(quantity).style.display = "block";
	document.getElementById(quantity).setAttribute("required","required");
	var datelabel="datelabel"+number;
	document.getElementById(datelabel).innerHTML = "Due Date:";
	var otherdatelabel="otherdatelabel"+number;
	document.getElementById(otherdatelabel).innerHTML = "Quantity<span style=\"color:#FF0000;\">*</span>:";
	}
	else if (selectvalue==7){
	var startdate="startdate"+number;
	document.getElementById(startdate).style.display = "none";
	document.getElementById(startdate).value = "";
	var enddate="enddate"+number;
	document.getElementById(enddate).style.display = "none";
	document.getElementById(enddate).value = "";
	var duedate="duedate"+number;
	document.getElementById(duedate).style.display = "block";
	var quantity="quantity"+number;
	document.getElementById(quantity).style.display = "none";
	document.getElementById(quantity).value = "";
	document.getElementById(quantity).removeAttribute("required");
	var datelabel="datelabel"+number;
	document.getElementById(datelabel).innerHTML = "Meet Prior To:";
	var otherdatelabel="otherdatelabel"+number;
	document.getElementById(otherdatelabel).innerHTML = "";
	}
}

function init() {
	document.getElementById('additem').onclick = additem;
	additem();
}

window.onload = init;

</script>
</html>
