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

$colname_Recordset2 = "-1";
if (isset($_GET['id'])) {
  $colname_Recordset2 = $_GET['id'];
}
mysql_select_db($database_commreq_conn, $commreq_conn);
$query_Recordset2 = sprintf("SELECT * FROM department WHERE id = %s", GetSQLValueString($colname_Recordset2, "int"));
$Recordset2 = mysql_query($query_Recordset2, $commreq_conn) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

/**
 * @author Mark Rickert <mjar81@gmail.com>
 * @version 2012-06-21
 *
 * This example is designed to be run from the command line using
 * PHP-CLI, and is a minimal example of using the php-liquidplanner
 * library to create a new task in your workspace. You must configure
 * it to use your own workspace ID, email address, and Liquid Planner
 * password, and set a parent_id value of one of your existing
 * projects or project folders.
 */
require_once 'scripts/liquidplanner.php';

// Enter your LiquidPlanner credentials below
$lp = new LiquidPlanner("YOUR_WORKSPACE_ID", "YOUR_LP_EMAIL_ADDRESS", "YOUR_LP_PASSWORD");

/*
	You can see what params are supported in the API guide
	in the section: "Filtering Items"
	http://www.liquidplanner.com/storage/help/liquidplanner_API.pdf
*/
$params = array(
	'limit' => 50,
	'filter' => array('is_done is true','owner_id!=188134','client_id='.$row_Recordset2['lp_id']),
	'order' => 'updated_at'
);
$params2 = array(
	'limit' => 1000,
	'filter' => array('client_id='.$row_Recordset2['lp_id']),
);

/* Get specified tasks Liquid Planner */
$response = $lp->tasks(NULL, $params);
$response2 = $lp->projects(NULL, $params2);

usort($response, cmp2);

function cmp2($a, $b)
{
	return strcasecmp($b[done_on], $a[done_on]);
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        
        <title>Completed Tasks // Brentwood Baptist Communications</title>
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
				$(".showdept").click(showdept);
				$(".showdept").mouseleave(closedept);
				$(".showorder").click(showorder);
				$(".showorder").mouseleave(closeorder);
				$(".taskdescription").hide();
				$(".task, .task1").click(showdescription);
				$(".task, .task1").mouseleave(closedescription);
				$('a[href^="mailto:"]').each(function() {
  					this.href = this.href.replace('(theat)', '@').replace(/\(thedot\)/g, '.');
				});
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
    </head>
    <body>
    	<header>
        	<div class="centercontent">
            	<a href="/reporting/"><img src="images/reporting_logo.png" class="logo"></a>
                <a href="http://www.commreq.com" class="submitlink">Submit a Comm Req</a>
                <a href="report.php?id=<?php echo $_GET['id']; ?>" class="submitlink">Back to Current Tasks</a>
            </div>
        </header>
        <nav id="subnav">
        	<div class="centercontent">
           	  <div id="subnavlinks">
            		<div class="label">Now Showing:</div>
<div class="dropdown showdept"><span><?php echo $row_Recordset2['departmentname']; ?></span>
                    	<div class="deptlist">
                        	<?php do { ?>
                       	    <a href="completed.php?id=<?php echo $row_Recordset1['id']; ?>"><?php echo $row_Recordset1['departmentname']; ?></a>
                        	  <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="label">Last 50 Completed Tasks</div>
            		<div class="clear"></div>
              </div>
       	  </div>
        </nav>
        <section id="maincontent">
        	<div class="centercontent">
            	<?php if(count($response)==0) {
						echo "<br /><br /><h2 class=\"norecord\">We currently don't have any completed tasks for this ministry. You may also want to <a href=\"report.php?id=".$_GET['id']."\">check for current tasks.</a> If you have questions, <a href=\"mailto:projectmanager@brentwoodbaptist.com\">contact us</a>.</h2><br /><br /><br />";
					}
					else { ?>
                
                <h2>Completed</h2>
                <table class="formattedtable">
                	<thead>
                    	<th class="commreq gradient">
                        	Comm Req<br />
                        	<span>(If Applicable)</span>
                        </th>
                        <th class="task gradient">
                        	Task
                        </th>
                        <th class="project gradient">
                        	Project
                        </th>
                        <th class="duedate1 gradient">
                        	Finish Date
                        </th>
                        <th class="email gradient">
                        	Email
                        </th>
                    </thead>
                    <tbody>
                    <?php foreach($response as $item) { ?>		
                        <tr>
                        	<td class="commreq gradient">
                            	<?php if($item[external_reference]!="") {?>
                            	<a href="http://www.commreq.com/projectinfo.php?id=<?php echo $item[external_reference]-10000;?>" target="_blank"><?php echo $item[external_reference];?></a>
                                <?php } ?>
                            </td>
                            <td class="task borderleft gradient">
                            	<?php echo $item[name];
									if($item[description]!="") { ?>
                                    <img src="images/plus.png" />
                                <div class="taskdescription">
                                <h3>&gt;&gt;</h3>
                                <p><?php echo nl2br($item[description]);?></p>
                                <?php } ?>
                                </div>
                            </td>
                            <td class="project borderleft gradient">
                            	<?php foreach($response2 as $project){
                					if($item[project_id]==$project[id]){
                    					echo $project[name];
                    				}
            					}?>
                            </td>
                            <td class="duedate1 borderleft gradient">
                            	<?php 
								if($item[done_on]!="") {
								list($yr,$mon,$day) = split('-',$item[done_on]);
								$display_date = date('m-j-Y', mktime(0,0,0,$mon,$day,$yr)); 
								echo $display_date;
								}
								?>
                            </td>
                            <td class="email borderleft gradient">
                            	<a href="mailto:<?php echo $item[id];?><?php if($item[package_id]!=0) { echo "P"; } ?>-02113a806779d759(theat)in(thedot)liquidplanner(thedot)com?cc=lchaudhry@brentwoodbaptist.com"><img src="images/email.png"></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <h2 class="norecord"><a href="report.php?id=<?php echo $_GET['id']; ?>">&lt;&lt; See Current Tasks</a></h2><br />
                <?php } ?>
        	</div>
        </section>
        <?php include('includes/footer.php'); ?>
    </body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
