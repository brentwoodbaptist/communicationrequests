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
$searchCriteria=1;
if (isset($_GET['status'])) {
  $searchCriteria = $_GET['status'];
}
$orderCriteria="date1 ASC";
if ($searchCriteria==4 || $searchCriteria==5) {
  $orderCriteria="date1 DESC";
}
mysql_select_db($database_commreq, $commreq);
$query_Recordset1 = "SELECT *, item.id AS itemid, project.id AS projectid FROM item  JOIN project ON item.projectid=project.id JOIN type ON item.type=type.id JOIN user ON project.user=user.id JOIN department on project.department=department.id JOIN status ON item.status=status.id WHERE item.type=1 && item.status=".$searchCriteria." ORDER BY ".$orderCriteria." LIMIT 1000";
$Recordset1 = mysql_query($query_Recordset1, $commreq) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_commreq, $commreq);
$query_Recordset2 = "SELECT * FROM status";
$Recordset2 = mysql_query($query_Recordset2, $commreq) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Comm Reqs &bull; Brentwood Baptist Communications</title>
<link rel="stylesheet" href="../reporting/css/main.css" />
<link rel="stylesheet" href="../reporting/css/smoothness/jquery-ui.css" />
<script type="text/javascript" src="../scripts/jquery-1.4.3.min.js"></script>
<script src="../reporting/scripts/jquery.js"></script>
<script src="../reporting/scripts/jquery-ui.js"></script>
<script src="../reporting/scripts/jquery.metadata.js"></script>
<script src="../reporting/scripts/jquery.tablesorter.js"></script>
        
        <script>
       	$("document").ready(function() {
				$(".deptlist").hide();
				$(".orderlist").hide();
				$(".showdept").click(showdept);
				$(".showdept").mouseleave(closedept);
				$(".showorder").click(showorder);
				$(".showorder").mouseleave(closeorder);
				$(".taskdescription").hide();
				$(".admintype").click(showdescription);
				$(".admintype").mouseleave(closedescription);
				$('a[href^="mailto:"]').each(function() {
  					this.href = this.href.replace('(theat)', '@').replace(/\(thedot\)/g, '.');
				 });
				$(".admintable").tablesorter();
			});
		function showdept() {
				$(".orderlist").hide();
				$(".deptlist").slideDown(300);
		}
		function closedept() {
				$(".orderlist").hide();
				$(".deptlist").slideUp(300);
		}
		function showorder() {
				$(".deptlist").hide();
				$(".orderlist").slideDown(300);
		}
		function closeorder() {
				$(".deptlist").hide();
				$(".orderlist").slideUp(300);
		}
		function showdescription(e) {
				$(this).children(".taskdescription").slideDown(150); 
		}
		function closedescription(e) {
				$(this).children(".taskdescription").hide(); 
		}
		</script>
<script>
function updateStatus(itemid, obj)
{
	var status = obj.options[obj.selectedIndex].value;
    location.href = "updateStatus.php?id="+itemid+"&status="+status;
    
    return true;
}
</script>
</head>

<body>
		<header>
        	<div class="centercontent">
            	  <a href="index.php"><img src="../reporting/images/commreq_logo.png" class="logo"></a>
                <a href="index.php" class="submitlink">All Comm Reqs</a>
        	</div>
        </header>
        <nav id="subnav">
        	<div class="centercontent">
           	  <div id="subnavlinks">
            		<div class="label">Jump to (bulletin only): </div>
					<a href="bulletin.php" class="statusnav">New</a>
					<a href="bulletin.php?status=3" class="statusnav">In Process</a>
					<a href="bulletin.php?status=2" class="statusnav">On Hold</a>
					<a href="bulletin.php?status=4" class="statusnav">Completed</a>
					<a href="bulletin.php?status=5" class="statusnav">Canceled</a>
            		<div class="clear"></div>
              </div>
       	  </div>
        </nav>
        <section id="maincontent">
        	<div class="centercontent">
				<h2>
        <?php 
          switch($row_Recordset1['status']){
            case 1: echo "New";
                    break;
            case 2: echo "On Hold";
                    break;
            case 3: echo "In Process";
                    break;
            case 4: echo "Completed";
                    break;
            case 5: echo "Canceled";
                    break;

          } ?> Comm Reqs</h2>

  <form action="multiplestatuses.php" method="post">
  		<table class="formattedtable admintable">
  			<thead>
  				<th class="admincheckbox gradient"></th>
  				<th class="adminid gradient">ID <span class="uparrow">↑</span><span class="downarrow">↓</span></th>
  				<th class="admintype gradient">Type <span class="uparrow">↑</span><span class="downarrow">↓</span></th>
  				<th class="adminname gradient">Project Name <span class="uparrow">↑</span><span class="downarrow">↓</span></th>
  				<th class="admindue gradient">Due Date <span class="uparrow">↑</span><span class="downarrow">↓</span></th>
  				<th class="adminsubmitted gradient">Submitted By <span class="uparrow">↑</span><span class="downarrow">↓</span></th>
  				<th class="adminstatus gradient">Status</th>
  			</thead>
  			<tbody>
  			<?php do { ?>
  				<tr>
  					<td class="admincheckbox gradient"><input type="checkbox" name="changeStatus[]" value="<?php echo $row_Recordset1['itemid']; ?>" /></td>
					<td class="adminid gradient borderleft">
						<a href="projectinfo.php?id=<?php echo $row_Recordset1['projectid']; ?>">
						<?php 
						$projectid=$row_Recordset1['projectid']+10000;
  						echo $projectid; 
  						?>
  						</a>
  					</td>
  					<td class="admintype gradient borderleft">
              <?php switch ($row_Recordset1['type']){ 
    case 1:
    echo "<img src=\"../images/bulletin.png\" />";
    break;
  case 2:
    echo "<img src=\"../images/web.png\" />";
    break;
  case 3:
    echo "<img src=\"../images/econnect.png\" />";
    break;
  case 4:
    echo "<img src=\"../images/slide.png\" />";
    break;
  case 5:
    echo "<img src=\"../images/reprint.png\" />";
    break;
  case 6:
    echo "<img src=\"../images/other.png\" />";
    break;
  case 7:
    echo "<img src=\"../images/meeting.png\" />";
    break;
  case 8:
    echo "<img src=\"../images/sign.png\" />";
    break;
  case 9:
    echo "<img src=\"../images/story.png\" />";
    break;
  case 10:
    echo "<img src=\"../images/video.png\" />";
    break;
  }
  ?>
              <?php echo $row_Recordset1['typename']; ?> <img src="../reporting/images/plus.png" />
						<div class="taskdescription topdescription">
							<h3>&gt;&gt;</h3>
							<?php 
	  						list($yr,$mon,$day) = split('-',$row_Recordset1['date1']);
	  						$display_date = date('F j, Y', mktime(0,0,0,$mon,$day,$yr));
							list($yr,$mon,$day) = split('-',$row_Recordset1['date2']);
	  						$display_date2 = date('F j, Y', mktime(0,0,0,$mon,$day,$yr));
	  						switch ($row_Recordset1['type']){ 
  								case 1:
								case 2:
								case 3:
								case 4:
									echo "<p><strong>Start Date:</strong> ".$display_date."<br />
									End Date: ".$display_date2."</p>";
									break;
								case 5:
									echo "<p><strong>Due Date: ".$display_date."<br />
									Quantity: ".$row_Recordset1['quantity']."</p>";
									break;
								case 6:
									echo "<p><strong>Due Date: ".$display_date."</p>";
									break;
  							}
							?>
                            <p><?php echo $row_Recordset1['description']; ?></p>
                            <?php
  							list($yr,$mon,$day) = split('-',$row_Recordset1['submitted']);
							$submitted_date = date('F j, Y', mktime(0,0,0,$mon,$day,$yr));
							echo "<p><strong>Date Submitted: ".$submitted_date."<br />";
							echo "Department: ".$row_Recordset1['departmentname']."</p>";
							?>
						</div>
  					</td>
  					<td class="adminname gradient borderleft"><?php echo $row_Recordset1['title']; ?></td>
  					<td class="admindue gradient borderleft">
  						<?php 
			  			if ($row_Recordset1['date1']!="0000-00-00"){
							list($yr,$mon,$day) = split('-',$row_Recordset1['date1']);
			  				$display_date = date('m-d-y', mktime(0,0,0,$mon,$day,$yr));
						} else {
							$display_date="No Date";
						}
						echo $display_date; 
						?>
					</td>
  					<td class="adminsubmitted gradient borderleft"><a href="mailto:<?php echo $row_Recordset1['email']; ?>?subject=Issue%20Regarding%20Comm%20Req%20%23<?php echo 10000+$row_Recordset1['projectid']; ?>&body=The%20following%20is%20regarding%20the%20<?php echo $row_Recordset1['typename']; ?>%20item%20in%20comm%20req%20%23<?php echo 10000+$row_Recordset1['projectid']; ?>:%20<?php echo $row_Recordset1['title']; ?>.%20Full%20details%20of%20the%20comm%20req%20can%20be%20found%20at%20the%20following%20address%0D%0Dhttp://www.commreq.com/projectinfo.php?id=<?php echo $row_Recordset1['projectid']; ?>%0D%0D"><?php echo $row_Recordset1['username']; ?></a></td>
  					<td class="adminstatus gradient borderleft">
  						<select name="status<?php echo $row_Recordset1['itemid']; ?>" onchange="updateStatus(<?php echo $row_Recordset1['itemid']; ?>, this);">
        					<option value="1" <?php if ($row_Recordset1['status']==1){ echo "SELECTED"; }?>>New</option>
        					<option value="2" <?php if ($row_Recordset1['status']==2){ echo "SELECTED"; }?>>On Hold</option>
        					<option value="3" <?php if ($row_Recordset1['status']==3){ echo "SELECTED"; }?>>In Process</option>
        					<option value="4" <?php if ($row_Recordset1['status']==4){ echo "SELECTED"; }?>>Complete</option>
        					<option value="5" <?php if ($row_Recordset1['status']==5){ echo "SELECTED"; }?>>Canceled</option>
      					</select></td>
  <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
  	</tbody>
  </table>
  
  <p style="text-align:right;"><strong style="font-size:14px;">SET ALL SELECTED COMM REQS AS </strong> <select name="multistatus">
        <option value=""></option>
        <option value="1">New</option>
        <option value="2">On Hold</option>
        <option value="3">In Process</option>
        <option value="4">Complete</option>
        <option value="5">Canceled</option>
      </select> <input type="submit" value="Go" /></p><br /><br />
  </form>
</div>
</section>
<?php include('../reporting/includes/footer.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
