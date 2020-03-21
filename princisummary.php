<?php

ob_start();
session_start();

if($_SESSION['loggedInUser'] != 'principal.engg@somaiya.edu'){
    header("location:index.php");
}

include_once("includes/connection.php");
include_once 'dompdf/dompdf_config.inc.php';

date_default_timezone_set('Asia/Kolkata');

$op = "<p align='center'><strong style='font-size:20px'>K. J. Somaiya College of Engineering</strong><br>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'><strong>Grievance Summary Report - ".$_SESSION['name']."</strong></p><br>";
$op .= "<div align = 'right'> Date: ".date('d-m-Y')."</div>";

if(!isset($_SESSION['filterarray'])) {
	$op .= "<hr><hr>";
	//print filter details..
	$op .= "<p align = 'center'>No filters applied</p>";
	$op .= "<hr><hr>";
}
else {
	$op .= "<hr><hr>";
	//print filter details..
	$op .= "<p align = 'center'>Filters Applied</p>
	<p>Grievances submitted by: ";
	if($_SESSION['filterarray']['role'] == 'Student') {
		$op .= "Students <br>";
		if(!empty($_SESSION['filterarray']['class']))
			$op .= "Class: ".$_SESSION['filterarray']['class']."<br>";
		if(!empty($_SESSION['filterarray']['dept']))
			$op .= "Department: ".$_SESSION['filterarray']['dept']."<br>";
	}
	elseif($_SESSION['filterarray']['role'] == 'Employee') {
		$op .= "Employees <br>";
		if(!empty($_SESSION['filterarray']['dept']))
			$op .= "Department: ".$_SESSION['filterarray']['dept']."<br>";
		if(!empty($_SESSION['filterarray']['designation']))
			$op .= "Designation: ".$_SESSION['filterarray']['designation']."<br>";
	}
	elseif($_SESSION['filterarray']['role'] == 'Both') {
		$op .= "Both Students and Employees <br>";
		if(!empty($_SESSION['filterarray']['dept']))
			$op .= "Department: ".$_SESSION['filterarray']['dept']."<br>";
	}
	
	if(!empty($_SESSION['filterarray']['fromdate']))
		$op .= "Grievances submitted after: ".$_SESSION['filterarray']['fromdate']."<br>";
	
	if(!empty($_SESSION['filterarray']['todate']))
		$op .= "Grievances submitted before: ".$_SESSION['filterarray']['todate']."<br>";
	
	$op .= "<hr><hr>";
}		


if(!isset($_SESSION['filterarray'])) {
	$committees = mysqli_query($conn, "SELECT name FROM commdet");
	if(mysqli_num_rows($committees) > 0) {
		while($row = mysqli_fetch_assoc($committees)) {
			$comm = $row['name'];
			
			$categories = mysqli_query($conn, "SELECT category FROM catdet WHERE comm_name = '$comm'");
			if(mysqli_num_rows($categories)>0) {
				while ($inrow = mysqli_fetch_assoc($categories)) {
					
					$op .= "<strong> $comm - ".$inrow['category'].":</strong><br>";
					$cond = " AND (gcat = '".$inrow['category']."')";
					
					$qu = "SELECT COUNT(*) FROM grievance where (status = 'Pending' OR status = 'In Progress' OR status REGEXP 'Forwarded to')".$cond;
					$res_pending = mysqli_fetch_assoc(mysqli_query($conn,$qu));
					$op .= "<br>Unresolved grievances: ".$res_pending['COUNT(*)'];
					
					$qu = "SELECT COUNT(*) FROM grievance where (status = 'Partially Solved')".$cond;
					$res_partsolved = mysqli_fetch_assoc(mysqli_query($conn,$qu));
					$op .= "<br>Partially solved grievances: ".$res_partsolved['COUNT(*)'];
					
					$qu = "SELECT COUNT(*) FROM resolved where (status='Resolved') AND (raised = 0)".$cond;
					$res_resolved = mysqli_fetch_assoc(mysqli_query($conn,$qu));
					$op .= "<br>Grievances resolved independently: ".$res_resolved['COUNT(*)'];
					
					$qu = "SELECT COUNT(*) FROM resolved where (status='Resolved') AND (raised = 1)".$cond;
					$res_resolved = mysqli_fetch_assoc(mysqli_query($conn,$qu));
					$op .= "<br>Grievances resolved with external help: ".$res_resolved['COUNT(*)'];
					
					$qu = "SELECT COUNT(*) FROM resolved where (status='Resolved Offline')".$cond;
					$res_offline = mysqli_fetch_assoc(mysqli_query($conn,$qu));
					$op .= "<br>Offline grievances resolved: ".$res_offline['COUNT(*)'];
					
					$qu = "SELECT COUNT(*) FROM cancelled where 1".$cond;
					$res_cancel = mysqli_fetch_assoc(mysqli_query($conn,$qu));
					$op .= "<br>Cancelled grievances: ".$res_cancel['COUNT(*)'];
					
					$op .= "<hr>";
				}
			}
		}
	}
}

elseif(isset($_SESSION['filterarray']) && empty($_SESSION['filterarray']['comm'])) {
	$cond = "";
	$class = "";
	$dept = "";
	$desig = "";
	
	if(!empty($_SESSION['filterarray']['role'])) {
		if($_SESSION['filterarray']['role'] == 'Student') {
			$cond .= " AND (urole = 'Student')";
			$emq = "SELECT email FROM userdet";
			if(!empty($_SESSION['filterarray']['class']))
				$class = $_SESSION['filterarray']['class'];
			if(!empty($_SESSION['filterarray']['dept']))
				$dept = $_SESSION['filterarray']['dept'];
			
			if($class != "")
					$emq .= " WHERE class = '$class'";
			
			if($dept != "") {
				if($emq == "SELECT email FROM userdet")
					$emq .= " WHERE dept = '$dept'";
				else
					$emq .= " AND dept = '$dept'";
			}
			
			if($emq != "SELECT email FROM userdet") {
				$studlist = mysqli_query($conn, $emq);
				if(mysqli_num_rows($studlist)>0) {
					$cond .= " AND (0";
					while($row = mysqli_fetch_assoc($studlist)) {
						$em = $row['email'];
						$cond .= " OR uemail = '$em'";
					}
					$cond .= ")";
				}
			}
		}
		
		elseif($_SESSION['filterarray']['role'] == 'Employee') {
			$cond .= " AND (urole = 'Employee')";
			$emq = "SELECT email FROM facdet";
			
			if(!empty($_SESSION['filterarray']['dept']))
				$dept = $_SESSION['filterarray']['dept'];
			if(!empty($_SESSION['filterarray']['designation']))
				$desig = $_SESSION['filterarray']['designation'];
			
			if($dept != "")
					$emq .= " WHERE dept = '$dept'";
				
			if($desig != "") {
				if($emq == "SELECT email FROM facdet")
					$emq .= " WHERE designation = '$desig'";
				else
					$emq .= " AND designation = '$desig'";
			}
			
			if($emq != "SELECT email FROM facdet") {
				$studlist = mysqli_query($conn, $emq);
				if(mysqli_num_rows($studlist)>0) {
					$cond .= " AND (0";
					while($row = mysqli_fetch_assoc($studlist)) {
						$em = $row['email'];
						$cond .= " OR uemail = '$em'";
					}
					$cond .= ")";
				}
			}
		}
		
		else {
			$cond = "";
			
			$emq = "SELECT email FROM userdet";
			
			if($_SESSION['filterarray']['dept'] != "")
				$dept = $_SESSION['filterarray']['dept'];
			
			if($dept != "") 
				$emq .= " WHERE dept = '$dept'";
			
			if($emq != "SELECT email FROM userdet") {
				$studlist = mysqli_query($conn, $emq);
				if(mysqli_num_rows($studlist)>0) {
					$cond .= " AND (0";
					while($row = mysqli_fetch_assoc($studlist)) {
						$em = $row['email'];
						$cond .= " OR uemail = '$em'";
					}
					$cond .= ")";
				}
			}
			
			$emq = "SELECT email FROM facdet";
			
			if($dept != "")
					$emq .= " WHERE dept = '$dept'";
			
			if($emq != "SELECT email FROM facdet") {
				$studlist = mysqli_query($conn, $emq);
				if(mysqli_num_rows($studlist)>0) {
					$cond .= " AND (0";
					while($row = mysqli_fetch_assoc($studlist)) {
						$em = $row['email'];
						$cond .= " OR uemail = '$em'";
					}
					$cond .= ")";
				}
			}
		}
	}

	if(!empty($_SESSION['filterarray']['fromdate'])){
		$from = $_SESSION['filterarray']['fromdate']." 00:00:00";
		$cond .= " AND (timeofg >= '$from')";
	}
	
	if(!empty($_SESSION['filterarray']['todate'])) {
		$to = $_SESSION['filterarray']['todate']." 23:59:59";
		$cond .= " AND (timeofg <= '$to')";
	}

	if(!empty($_SESSION['filterarray']['gcat'])) {
		$cat = $_SESSION['filterarray']['gcat'];
		$cond .= " AND (gcat = '$cat')";
		
		$inrow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT comm_name FROM catdet WHERE cateogory = '$cat'"));
		$op .= "<strong> ".$inrow['comm_name']."- $cat:</strong><br>";
		
		$qu = "SELECT COUNT(*) FROM grievance where (status = 'Pending' OR status = 'In Progress' OR status REGEXP 'Forwarded to')".$cond;
		$res_pending = mysqli_fetch_assoc(mysqli_query($conn,$qu));
		$op .= "<br>Unresolved grievances: ".$res_pending['COUNT(*)'];
		
		$qu = "SELECT COUNT(*) FROM grievance where (status = 'Partially Solved')".$cond;
		$res_partsolved = mysqli_fetch_assoc(mysqli_query($conn,$qu));
		$op .= "<br>Partially solved grievances: ".$res_partsolved['COUNT(*)'];
		
		$qu = "SELECT COUNT(*) FROM resolved where (status='Resolved') AND (raised = 0)".$cond;
		$res_resolved = mysqli_fetch_assoc(mysqli_query($conn,$qu));
		$op .= "<br>Grievances resolved independently: ".$res_resolved['COUNT(*)'];
		
		$qu = "SELECT COUNT(*) FROM resolved where (status='Resolved') AND (raised = 1)".$cond;
		$res_resolved = mysqli_fetch_assoc(mysqli_query($conn,$qu));
		$op .= "<br>Grievances resolved with external help: ".$res_resolved['COUNT(*)'];
		
		$qu = "SELECT COUNT(*) FROM resolved where (status='Resolved Offline')".$cond;
		$res_offline = mysqli_fetch_assoc(mysqli_query($conn,$qu));
		$op .= "<br>Offline grievances resolved: ".$res_offline['COUNT(*)'];
		
		$qu = "SELECT COUNT(*) FROM cancelled where 1".$cond;
		$res_cancel = mysqli_fetch_assoc(mysqli_query($conn,$qu));
		$op .= "<br>Cancelled grievances: ".$res_cancel['COUNT(*)'];
		
		$op .= "<hr>";
	}
	
	else {
		$committees = mysqli_query($conn, "SELECT name FROM commdet");
		if(mysqli_num_rows($committees) > 0) {
			while($row = mysqli_fetch_assoc($committees)) {
				$comm = $row['name'];
				$categories = mysqli_query($conn, "SELECT category FROM catdet WHERE comm_name = '$comm'");
				if(mysqli_num_rows($categories)) {
					while ($inrow = mysqli_fetch_assoc($categories)) {
						
						$op .= "<strong> $comm - ".$inrow['category'].":</strong><br>";
						$cond = " AND (gcat = '".$inrow['category']."')";
						
						$qu = "SELECT COUNT(*) FROM grievance where (status = 'Pending' OR status = 'In Progress' OR status REGEXP 'Forwarded to')".$cond;
						$res_pending = mysqli_fetch_assoc(mysqli_query($conn,$qu));
						$op .= "<br>Unresolved grievances: ".$res_pending['COUNT(*)'];
						
						$qu = "SELECT COUNT(*) FROM grievance where (status = 'Partially Solved')".$cond;
						$res_partsolved = mysqli_fetch_assoc(mysqli_query($conn,$qu));
						$op .= "<br>Partially solved grievances: ".$res_partsolved['COUNT(*)'];
						
						$qu = "SELECT COUNT(*) FROM resolved where (status='Resolved') AND (raised = 0)".$cond;
						$res_resolved = mysqli_fetch_assoc(mysqli_query($conn,$qu));
						$op .= "<br>Grievances resolved independently: ".$res_resolved['COUNT(*)'];
						
						$qu = "SELECT COUNT(*) FROM resolved where (status='Resolved') AND (raised = 1)".$cond;
						$res_resolved = mysqli_fetch_assoc(mysqli_query($conn,$qu));
						$op .= "<br>Grievances resolved with external help: ".$res_resolved['COUNT(*)'];
						
						$qu = "SELECT COUNT(*) FROM resolved where (status='Resolved Offline')".$cond;
						$res_offline = mysqli_fetch_assoc(mysqli_query($conn,$qu));
						$op .= "<br>Offline grievances resolved: ".$res_offline['COUNT(*)'];
						
						$qu = "SELECT COUNT(*) FROM cancelled where 1".$cond;
						$res_cancel = mysqli_fetch_assoc(mysqli_query($conn,$qu));
						$op .= "<br>Cancelled grievances: ".$res_cancel['COUNT(*)'];
						
						$op .= "<hr>";
					}
				}
			}
		}
	}
}

elseif(isset($_SESSION['filterarray']) && !empty($_SESSION['filterarray']['comm'])) {
	$cond = "";
	$class = "";
	$dept = "";
	$desig = "";
	
	if(!empty($_SESSION['filterarray']['role'])) {
		if($_SESSION['filterarray']['role'] == 'Student') {
			$cond .= " AND (urole = 'Student')";
			$emq = "SELECT email FROM userdet";
			if(!empty($_SESSION['filterarray']['class']))
				$class = $_SESSION['filterarray']['class'];
			if(!empty($_SESSION['filterarray']['dept']))
				$dept = $_SESSION['filterarray']['dept'];
			
			if($class != "")
					$emq .= " WHERE class = '$class'";
			
			if($dept != "") {
				if($emq == "SELECT email FROM userdet")
					$emq .= " WHERE dept = '$dept'";
				else
					$emq .= " AND dept = '$dept'";
			}
			
			if($emq != "SELECT email FROM userdet") {
				$studlist = mysqli_query($conn, $emq);
				if(mysqli_num_rows($studlist)>0) {
					$cond .= " AND (0";
					while($row = mysqli_fetch_assoc($studlist)) {
						$em = $row['email'];
						$cond .= " OR uemail = '$em'";
					}
					$cond .= ")";
				}
			}
		}
		
		elseif($_SESSION['filterarray']['role'] == 'Employee') {
			$cond .= " AND (urole = 'Employee')";
			$emq = "SELECT email FROM facdet";
			
			if(!empty($_SESSION['filterarray']['dept']))
				$dept = $_SESSION['filterarray']['dept'];
			if(!empty($_SESSION['filterarray']['designation']))
				$desig = $_SESSION['filterarray']['designation'];
			
			if($dept != "")
					$emq .= " WHERE dept = '$dept'";
				
			if($desig != "") {
				if($emq == "SELECT email FROM facdet")
					$emq .= " WHERE designation = '$desig'";
				else
					$emq .= " AND designation = '$desig'";
			}
			
			if($emq != "SELECT email FROM facdet") {
				$studlist = mysqli_query($conn, $emq);
				if(mysqli_num_rows($studlist)>0) {
					$cond .= " AND (0";
					while($row = mysqli_fetch_assoc($studlist)) {
						$em = $row['email'];
						$cond .= " OR uemail = '$em'";
					}
					$cond .= ")";
				}
			}
		}
		
		else {
			$cond = "";
			
			$emq = "SELECT email FROM userdet";
			
			if($_SESSION['filterarray']['dept'] != "")
				$dept = $_SESSION['filterarray']['dept'];
			
			if($dept != "") 
				$emq .= " WHERE dept = '$dept'";
			
			if($emq != "SELECT email FROM userdet") {
				$studlist = mysqli_query($conn, $emq);
				if(mysqli_num_rows($studlist)>0) {
					$cond .= " AND (0";
					while($row = mysqli_fetch_assoc($studlist)) {
						$em = $row['email'];
						$cond .= " OR uemail = '$em'";
					}
					$cond .= ")";
				}
			}
			
			$emq = "SELECT email FROM facdet";
			
			if($dept != "")
					$emq .= " WHERE dept = '$dept'";
			
			if($emq != "SELECT email FROM facdet") {
				$studlist = mysqli_query($conn, $emq);
				if(mysqli_num_rows($studlist)>0) {
					$cond .= " AND (0";
					while($row = mysqli_fetch_assoc($studlist)) {
						$em = $row['email'];
						$cond .= " OR uemail = '$em'";
					}
					$cond .= ")";
				}
			}
		}
	}

	if(!empty($_SESSION['filterarray']['fromdate'])){
		$from = $_SESSION['filterarray']['fromdate']." 00:00:00";
		$cond .= " AND (timeofg >= '$from')";
	}
	
	if(!empty($_SESSION['filterarray']['todate'])) {
		$to = $_SESSION['filterarray']['todate']." 23:59:59";
		$cond .= " AND (timeofg <= '$to')";
	}
	
	if(!empty($_SESSION['filterarray']['comm'])) {
		$comm = $_SESSION['filterarray']['comm'];
		$categories = mysqli_query($conn, "SELECT category FROM catdet WHERE comm_name = '$comm'");
		if(mysqli_num_rows($categories)) {
			while ($inrow = mysqli_fetch_assoc($categories)) {
				
				$op .= "<strong> $comm - ".$inrow['category'].":</strong><br>";
				$cond = " AND (gcat = '".$inrow['category']."')";
				
				$qu = "SELECT COUNT(*) FROM grievance where (status = 'Pending' OR status = 'In Progress' OR status REGEXP 'Forwarded to')".$cond;
				$res_pending = mysqli_fetch_assoc(mysqli_query($conn,$qu));
				$op .= "<br>Unresolved grievances: ".$res_pending['COUNT(*)'];
				
				$qu = "SELECT COUNT(*) FROM grievance where (status = 'Partially Solved')".$cond;
				$res_partsolved = mysqli_fetch_assoc(mysqli_query($conn,$qu));
				$op .= "<br>Partially solved grievances: ".$res_partsolved['COUNT(*)'];
				
				$qu = "SELECT COUNT(*) FROM resolved where (status='Resolved') AND (raised = 0)".$cond;
				$res_resolved = mysqli_fetch_assoc(mysqli_query($conn,$qu));
				$op .= "<br>Grievances resolved independently: ".$res_resolved['COUNT(*)'];
				
				$qu = "SELECT COUNT(*) FROM resolved where (status='Resolved') AND (raised = 1)".$cond;
				$res_resolved = mysqli_fetch_assoc(mysqli_query($conn,$qu));
				$op .= "<br>Grievances resolved with external help: ".$res_resolved['COUNT(*)'];
				
				$qu = "SELECT COUNT(*) FROM resolved where (status='Resolved Offline')".$cond;
				$res_offline = mysqli_fetch_assoc(mysqli_query($conn,$qu));
				$op .= "<br>Offline grievances resolved: ".$res_offline['COUNT(*)'];
				
				$qu = "SELECT COUNT(*) FROM cancelled where 1".$cond;
				$res_cancel = mysqli_fetch_assoc(mysqli_query($conn,$qu));
				$op .= "<br>Cancelled grievances: ".$res_cancel['COUNT(*)'];
				
				$op .= "<hr>";
			}
		}
	}

}

	$op .= "<br><br><div align = 'center'><b>* A computer generated report *</b>";
	//echo $op;
	$ope = utf8_encode($op);
	$dompdf = new DOMPDF();
	$dompdf->load_html($ope);
	$dompdf->set_paper('a4', 'portrait');
	$dompdf->render();

	$dompdf->stream('Summary Report',array('Attachment'=>0));
//echo "<br><br>count = ".$countval

?>

<!--
$cond = "";
	$class = "";
	$dept = "";
	$desig = "";
	
	if(!empty($_SESSION['filterarray']['role'])) {
		if($_SESSION['filterarray']['role'] == 'Student') {
			$cond .= " AND (urole = 'Student')";
			$emq = "SELECT email FROM userdet";
			if(!empty($_SESSION['filterarray']['class']))
				$class = $_SESSION['filterarray']['class'];
			if(!empty($_SESSION['filterarray']['dept']))
				$dept = $_SESSION['filterarray']['dept'];
			
			if($class != "")
					$emq .= " WHERE class = '$class'";
			
			if($dept != "") {
				if($emq == "SELECT email FROM userdet")
					$emq .= " WHERE dept = '$dept'";
				else
					$emq .= " AND dept = '$dept'";
			}
			
			if($emq != "SELECT email FROM userdet") {
				$studlist = mysqli_query($conn, $emq);
				if(mysqli_num_rows($studlist)>0) {
					$cond .= " AND (0";
					while($row = mysqli_fetch_assoc($studlist)) {
						$em = $row['email'];
						$cond .= " OR uemail = '$em'";
					}
					$cond .= ")";
				}
			}
		}
		
		elseif($_SESSION['filterarray']['role'] == 'Employee') {
			$cond .= " AND (urole = 'Employee')";
			$emq = "SELECT email FROM facdet";
			
			if(!empty($_SESSION['filterarray']['dept']))
				$dept = $_SESSION['filterarray']['dept'];
			if(!empty($_SESSION['filterarray']['designation']))
				$desig = $_SESSION['filterarray']['designation'];
			
			if($dept != "")
					$emq .= " WHERE dept = '$dept'";
				
			if($desig != "") {
				if($emq == "SELECT email FROM facdet")
					$emq .= " WHERE designation = '$desig'";
				else
					$emq .= " AND designation = '$desig'";
			}
			
			if($emq != "SELECT email FROM facdet") {
				$studlist = mysqli_query($conn, $emq);
				if(mysqli_num_rows($studlist)>0) {
					$cond .= " AND (0";
					while($row = mysqli_fetch_assoc($studlist)) {
						$em = $row['email'];
						$cond .= " OR uemail = '$em'";
					}
					$cond .= ")";
				}
			}
		}
		
		else {
			$cond = "";
			
			$emq = "SELECT email FROM userdet";
			
			if($_SESSION['filterarray']['dept'] != "")
				$dept = $_SESSION['filterarray']['dept'];
			
			if($dept != "") 
				$emq .= " WHERE dept = '$dept'";
			
			if($emq != "SELECT email FROM userdet") {
				$studlist = mysqli_query($conn, $emq);
				if(mysqli_num_rows($studlist)>0) {
					$cond .= " AND (0";
					while($row = mysqli_fetch_assoc($studlist)) {
						$em = $row['email'];
						$cond .= " OR uemail = '$em'";
					}
					$cond .= ")";
				}
			}
			
			$emq = "SELECT email FROM facdet";
			
			if($dept != "")
					$emq .= " WHERE dept = '$dept'";
			
			if($emq != "SELECT email FROM facdet") {
				$studlist = mysqli_query($conn, $emq);
				if(mysqli_num_rows($studlist)>0) {
					$cond .= " AND (0";
					while($row = mysqli_fetch_assoc($studlist)) {
						$em = $row['email'];
						$cond .= " OR uemail = '$em'";
					}
					$cond .= ")";
				}
			}
		}
	}

	if(!empty($_SESSION['filterarray']['fromdate'])){
		$from = $_SESSION['filterarray']['fromdate']." 00:00:00";
		$cond .= " AND (timeog >= '$from')";
	}
	
	if(!empty($_SESSION['filterarray']['todate'])) {
		$to = $_SESSION['filterarray']['todate']." 23:59:59";
		$cond .= " AND (timeog <= '$to')";
	}
	
	if(!empty($_SESSION['filterarray']['comm'])) {
		$name = $_SESSION['filterarray']['comm'];
		$catlist = mysqli_query($conn, "SELECT category from catdet WHERE comm_name = '$name'");
		if(mysqli_num_rows($catlist)>0) {
			$cond .= " AND (0";
			while($row = mysqli_fetch_assoc($catlist)) {
				$cat = $row['category'];
				$cond .= " OR gcat = '$cat'";
			}
			$cond .= ")";
		}
	}
	
	else {
		if(!empty($_SESSION['filterarray']['gcat'])) {
			$cat = $_SESSION['filterarray']['gcat'];
			$cond .= " AND (gcat = '$cat')";
		}
	}
-->