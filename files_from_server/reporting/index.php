<?php require_once('Connections/commreq_conn.php'); ?>
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

mysql_select_db($database_commreq_conn, $commreq_conn);
$query_Recordset1 = "SELECT * FROM department ORDER BY departmentname ASC";
$Recordset1 = mysql_query($query_Recordset1, $commreq_conn) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        
        <title>Project Reporting // Brentwood Baptist Communications</title>
        <link rel="stylesheet" href="css/main.css" />
        <link rel="stylesheet" href="css/smoothness/jquery-ui.css" />
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
				$(".deptlist").hide();
				$(".orderlist").hide();
				$(".deptlist").slideDown(400);
				$(".showorder").click(showorder);
				$(".showorder").mouseleave(closeorder);
				$(".taskdescription").hide();
				$(".task, .task1").click(showdescription);
				$(".task, .task1").mouseleave(closedescription);
			});
		function showorder() {
				$(".orderlist").slideDown(300);
		}
		function closeorder() {
				$(".orderlist").slideUp(300);
		}
		function showdescription(e) {
				$(this).children(".taskdescription").slideDown(150); 
		}
		function closedescription(e) {
				$(this).children(".taskdescription").hide(); 
		}
		</script>
    </head>
    <body>
    	<header>
        	<div class="centercontent">
            	<img src="images/reporting_logo.png" class="logo">
                <a href="http://www.commreq.com" class="submitlink">Submit a Comm Req</a>
        	</div>
        </header>
        <nav id="subnav">
        	<div class="centercontent">
           	  <div id="subnavlinks">
            		<div class="label">Now Showing:</div>
<div class="dropdown showdept"><span>Choose Your Ministry</span>
                    	<div class="deptlist">
                        	<?php do { ?>
                       	    <a href="report.php?id=<?php echo $row_Recordset1['id']; if (isset($_GET['sort'])) { echo "&sort=".$_GET['sort']; } ?>"><?php echo $row_Recordset1['departmentname']; ?></a>
                        	  <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="label"><?php if($_GET['sort']=="project"){
										echo "Grouped by";
									} else {
										echo "Ordered by";
									} ?></div>
<div class="dropdown showorder"><span><?php if($_GET['sort']=="project"){
										echo "Project";
									} else if($_GET['sort']=="name"){
										echo "Task Name";
									} else {
										echo "Due Date";
									}  ?></span>
                    	<div class="orderlist">
                        	<a href="#">Due Date</a>
                            <a href="#">Project</a>
                            <a href="#">Task Name</a>
                        </div>
                    </div>
            		<div class="clear"></div>
              </div>
       	  </div>
        </nav>
        <section id="maincontent">
        	<div class="centercontent mainheight">
            	<br /><h2 id="homeinstruction">Choose Your Ministry</h2>
                <img src="images/mainarrow.png" />
        	</div>
        </section>
        <?php include('includes/footer.php'); ?>
    </body>
</html>
<?php
mysql_free_result($Recordset1);
?>
