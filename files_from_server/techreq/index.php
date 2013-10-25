<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        
        <title>Tech Request Form // Brentwood Baptist Communications</title>
        <link rel="stylesheet" href="../reporting/css/main.css" />
        <link rel="stylesheet" href="../reporting/css/smoothness/jquery-ui.css" />
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
            	<img src="../reporting/images/tech_logo.png" class="logo">
                <a href="http://www.commreq.com" class="submitlink">Submit a Comm Req</a>
        	</div>
        </header>
        <nav id="subnav">
        	<div class="centercontent">
           	  <div id="subnavlinks">
            		<div class="label">Contact:</div>
<a href="mailto:adye@yourchurch.com" class="darkened">Tech [tech@yourchurch.com]</a>
            		<div class="clear"></div>
              </div>
       	  </div>
        </nav>
        <section id="maincontent">
        	<div class="centercontent">
            	<div id="detailcontainer">
                <h2>Tech Request Form</h2>
            	<div id="formcontainer">
                <!-- Place your wufoo form code here -->
        	</div>
            </div>
        </section>
        <?php include('../reporting/includes/footer.php'); ?>
    </body>
</html>
