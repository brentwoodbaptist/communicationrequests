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

$colname_Recordset1 = "-1";
if (isset($_GET['id'])) {
  $colname_Recordset1 = $_GET['id'];
}
$projectnumber=$colname_Recordset1+10000;
mysql_select_db($database_commreq, $commreq);
$query_Recordset1 = sprintf("SELECT * FROM project JOIN user ON project.user=user.id JOIN department ON project.department=department.id WHERE project.id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $commreq) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_commreq, $commreq);
$query_Recordset2 = sprintf("SELECT *, item.id AS itemid FROM item JOIN type ON item.type=type.id JOIN status ON item.status=status.id WHERE projectid = %s ORDER BY item.id", GetSQLValueString($colname_Recordset1, "int"));
$Recordset2 = mysql_query($query_Recordset2, $commreq) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_commreq, $commreq);
$query_Recordset3 = sprintf("SELECT * FROM uploads WHERE projectid = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset3 = mysql_query($query_Recordset3, $commreq) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_commreq, $commreq);
$query_Recordset4 = sprintf("SELECT * FROM comments WHERE itemid IN (SELECT id FROM item WHERE projectid = %s) ORDER BY itemid ASC, date ASC", GetSQLValueString($colname_Recordset1, "int"));
$Recordset4 = mysql_query($query_Recordset4, $commreq) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        
        <title>Comm Req #<?php echo $projectnumber; ?> // Brentwood Baptist Communications</title>
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
        <script src="scripts/jquery.js"></script>
        <script src="scripts/jquery-ui.js"></script>
        
        <script>
       	$("document").ready(function() {
				
			});
		</script>
    </head>
    <body>
    	<header>
        	<div class="centercontent">
            	<img src="reporting/images/reporting_logo.png" class="logo">
                <a href="http://www.commreq.com" class="submitlink">Submit a Comm Req</a>
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
                
                <h2>Project Info: Comm Req #<?php echo $projectnumber; ?></h2>

<table class="formattedtable">
<tr><td class="descriptor">Comm Req #:</td><td class="information"><?php echo $projectnumber; ?></td></tr>
<tr><td class="descriptor">Project Title:</td><td class="information"><?php echo $row_Recordset1['title']; ?></td></tr>
<tr><td class="descriptor">Name:</td><td class="information"><?php echo $row_Recordset1['username']; ?></td></tr>
<tr><td class="descriptor">Department:</td><td class="information"><?php echo $row_Recordset1['departmentname']; ?></td></tr>
</table>

<h2>Individual Items</h2>

<?php do { ?>
	<a name="<?php echo $row_Recordset2['itemid'] ?>"></a><div class="itemconfirm">
    
  <?php if ($row_Recordset2['date1']!="0000-00-00"){
			list($yr,$mon,$day) = split('-',$row_Recordset2['date1']);
			$display_date = date('F j, Y', mktime(0,0,0,$mon,$day,$yr));
		}
		else
			$display_date="No Date";
	if ($row_Recordset2['date2']!="0000-00-00"){
			list($yr,$mon,$day) = split('-',$row_Recordset2['date2']);
			$display_date2 = date('F j, Y', mktime(0,0,0,$mon,$day,$yr));
		}
		else
			$display_date2="No Date";
	?>
    <table class="itemtable formattedtable">
  <?php if ($row_Recordset2['type']<=4){ ?>
  <tr><td class="descriptor">Type:</td><td class="information typeinfo">
    <?php if ($row_Recordset2['type']==1) { ?>
    <img src="images/bulletinimage.png" width="20" height="20" />
    <?php } else if ($row_Recordset2['type']==2) { ?>
    <img src="images/webimage.png" width="20" height="20" />
    <?php } else if ($row_Recordset2['type']==4) { ?>
    <img src="images/slideimage.png" width="20" height="20" />
    <?php } else if ($row_Recordset2['type']==5) { ?>
    <img src="images/reprintimage.png" width="20" height="20" />
    <?php } else if ($row_Recordset2['type']==6) { ?>
    <img src="images/otherimage.png" width="20" height="20" />
    <?php } else if ($row_Recordset2['type']==7) { ?>
    <img src="images/meetingimage.png" width="20" height="20" />
	<?php } ?>
    <?php echo $row_Recordset2['typename']; ?></td></tr>
  <tr><td class="descriptor">Status:</td><td class="information"><?php echo $row_Recordset2['statusname']; ?></td></tr>
  <tr><td class="descriptor">Start Date:</td><td class="information"><?php echo $display_date; ?></td></tr>  
  <tr><td class="descriptor">End Date:</td><td class="information"><?php echo $display_date2; ?></td></tr>
  <?php } else if ($row_Recordset2['type']==5){?>
  <tr><td class="descriptor">Type:</td><td class="information typeinfo">
  <?php if ($row_Recordset2['type']==1) { ?>
    <img src="images/bulletinimage.png" width="20" height="20" />
    <?php } else if ($row_Recordset2['type']==2) { ?>
    <img src="images/webimage.png" width="20" height="20" />
    <?php } else if ($row_Recordset2['type']==4) { ?>
    <img src="images/slideimage.png" width="20" height="20" />
    <?php } else if ($row_Recordset2['type']==5) { ?>
    <img src="images/reprintimage.png" width="20" height="20" />
    <?php } else if ($row_Recordset2['type']==6) { ?>
    <img src="images/otherimage.png" width="20" height="20" />
    <?php } else if ($row_Recordset2['type']==7) { ?>
    <img src="images/meetingimage.png" width="20" height="20" />
	<?php } ?>
	<?php echo $row_Recordset2['typename']; ?></td></tr>
  <tr><td class="descriptor">Status:</td><td class="information"><?php echo $row_Recordset2['statusname']; ?></td></tr>
  <tr><td class="descriptor">Due Date:</td><td class="information"><?php echo $display_date; ?></td></tr>
  <tr><td class="descriptor">Quantity:</td><td class="information"><?php echo $row_Recordset2['quantity']; ?></td></tr>
  <?php } else if ($row_Recordset2['type']==6){?>
  <tr><td class="descriptor">Type:</td><td class="information typeinfo">
  <?php if ($row_Recordset2['type']==1) { ?>
    <img src="images/bulletinimage.png" width="20" height="20" />
    <?php } else if ($row_Recordset2['type']==2) { ?>
    <img src="images/webimage.png" width="20" height="20" />
    <?php } else if ($row_Recordset2['type']==4) { ?>
    <img src="images/slideimage.png" width="20" height="20" />
    <?php } else if ($row_Recordset2['type']==5) { ?>
    <img src="images/reprintimage.png" width="20" height="20" />
    <?php } else if ($row_Recordset2['type']==6) { ?>
    <img src="images/otherimage.png" width="20" height="20" />
    <?php } else if ($row_Recordset2['type']==7) { ?>
    <img src="images/meetingimage.png" width="20" height="20" />
	<?php } ?>
	<?php echo $row_Recordset2['typename']; ?></td></tr>
  <tr><td class="descriptor">Status:</td><td class="information"><?php echo $row_Recordset2['statusname']; ?></td></tr>
  <tr><td class="descriptor">Due Date:</td><td class="information"><?php echo $display_date; ?></td></tr>
  <?php } ?>
  <tr><td class="descriptor">Description:</td><td class="information">
  <?php echo $row_Recordset2['description']; ?></td></tr>
  </table>
  </div>
  <?php } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); ?>
  <br /><br />
  <?php if ($totalRows_Recordset3!=0) { ?>
  <h2>Attached Files</h2>
  <div class="textcontainer">
  <?php do { ?>
  <div class="uploadedfile">
  <div class="uploadedimage"><a href="uploads/<?php echo $row_Recordset3['newfilename']; ?>" target="_blank"><img src="images/file.jpg" border="0"/></a></div><div class="uploadedfilename"><a href="uploads/<?php echo $row_Recordset3['newfilename']; ?>" target="_blank"><?php echo $row_Recordset3['originalfilename']; ?></a></div>
  <div class="clear"></div>
  </div>
  
  <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
  </div>
  <?php } ?>
  				
                
                </div>
        	</div>
        </section>
        <?php include('reporting/includes/footer.php'); ?>
    </body>
</html>
<?php
mysql_free_result($Recordset1);
mysql_free_result($Recordset2);
?>