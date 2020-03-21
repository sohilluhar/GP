<?php

ob_start();
session_start();
$cond = "";

include_once("includes/connection.php");
include_once 'dompdf/dompdf_config.inc.php';

date_default_timezone_set('Asia/Kolkata');

$op = "<p align='center'><strong style='font-size:20px'>K. J. Somaiya College of Engineering</strong><br>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'><strong>Grievance Summary Report - ".$_SESSION['name']."</strong></p><br>";
$op .= "<div align = 'right'> Date: ".date('d-m-Y')."</div>";

if(!isset($_SESSION['filterarrayc'])) {
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
	if($_SESSION['filterarrayc']['role'] == 'Student') {
		$op .= "Students <br>";
		if(!empty($_SESSION['filterarrayc']['class']))
			$op .= "Class: ".$_SESSION['filterarrayc']['class']."<br>";
		if(!empty($_SESSION['filterarrayc']['dept']))
			$op .= "Department: ".$_SESSION['filterarrayc']['dept']."<br>";
	}
	elseif($_SESSION['filterarrayc']['role'] == 'Employee') {
		$op .= "Employees <br>";
		if(!empty($_SESSION['filterarrayc']['dept']))
			$op .= "Department: ".$_SESSION['filterarrayc']['dept']."<br>";
		if(!empty($_SESSION['filterarrayc']['designation']))
			$op .= "Designation: ".$_SESSION['filterarrayc']['designation']."<br>";
	}
	elseif($_SESSION['filterarrayc']['role'] == 'Both') {
		$op .= "Both Students and Employees <br>";
		if(!empty($_SESSION['filterarrayc']['dept']))
			$op .= "Department: ".$_SESSION['filterarrayc']['dept']."<br>";
	}
	
	if(!empty($_SESSION['filterarrayc']['fromdate']))
		$op .= "Grievances submitted after: ".$_SESSION['filterarrayc']['fromdate']."<br>";
	
	if(!empty($_SESSION['filterarrayc']['todate']))
		$op .= "Grievances submitted before: ".$_SESSION['filterarrayc']['todate']."<br>";
	
	$op .= "<hr><hr>";
}		


if(!isset($_SESSION['filterarrayc'])) {
	$comm = $_SESSION['name'];
	$categories = mysqli_query($conn, "SELECT category FROM catdet WHERE comm_name = '$comm'");
		if(mysqli_num_rows($categories)>0) {
		while ($inrow = mysqli_fetch_assoc($categories)) {
			
			$op .= "<strong> $comm - ".$inrow['category'].":</strong><br>";
			$cond .= " AND (gcat = '".$inrow['category']."')";
			
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

elseif(isset($_SESSION['filterarrayc']) && !empty($_SESSION['filterarrayc']['comm']) && !empty($_SESSION['filterarrayc']['gcat'])) {
	$cond = "";
	$class = "";
	$dept = "";
	$desig = "";
	
	if(!empty($_SESSION['filterarrayc']['role'])) {
		if($_SESSION['filterarrayc']['role'] == 'Student') {
			$cond .= " AND (urole = 'Student')";
			$emq = "SELECT email FROM userdet";
			if(!empty($_SESSION['filterarrayc']['class']))
				$class = $_SESSION['filterarrayc']['class'];
			if(!empty($_SESSION['filterarrayc']['dept']))
				$dept = $_SESSION['filterarrayc']['dept'];
			
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
		
		elseif($_SESSION['filterarrayc']['role'] == 'Employee') {
			$cond .= " AND (urole = 'Employee')";
			$emq = "SELECT email FROM facdet";
			
			if(!empty($_SESSION['filterarrayc']['dept']))
				$dept = $_SESSION['filterarrayc']['dept'];
			if(!empty($_SESSION['filterarrayc']['designation']))
				$desig = $_SESSION['filterarrayc']['designation'];
			
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
			
			if($_SESSION['filterarrayc']['dept'] != "")
				$dept = $_SESSION['filterarrayc']['dept'];
			
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

	if(!empty($_SESSION['filterarrayc']['fromdate'])){
		$from = $_SESSION['filterarrayc']['fromdate']." 00:00:00";
		$cond .= " AND (timeofg >= '$from')";
	}
	
	if(!empty($_SESSION['filterarrayc']['todate'])) {
		$to = $_SESSION['filterarrayc']['todate']." 23:59:59";
		$cond .= " AND (timeofg <= '$to')";
	}

	if(!empty($_SESSION['filterarrayc']['gcat'])) {
		$cat = $_SESSION['filterarrayc']['gcat'];
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
}

elseif(isset($_SESSION['filterarrayc']) && !empty($_SESSION['filterarrayc']['comm']) && empty($_SESSION['filterarrayc']['gcat'])) {
	$cond = "";
	$class = "";
	$dept = "";
	$desig = "";
	
	if(!empty($_SESSION['filterarrayc']['role'])) {
		if($_SESSION['filterarrayc']['role'] == 'Student') {
			$cond .= " AND (urole = 'Student')";
			$emq = "SELECT email FROM userdet";
			if(!empty($_SESSION['filterarrayc']['class']))
				$class = $_SESSION['filterarrayc']['class'];
			if(!empty($_SESSION['filterarrayc']['dept']))
				$dept = $_SESSION['filterarrayc']['dept'];
			
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
		
		elseif($_SESSION['filterarrayc']['role'] == 'Employee') {
			$cond .= " AND (urole = 'Employee')";
			$emq = "SELECT email FROM facdet";
			
			if(!empty($_SESSION['filterarrayc']['dept']))
				$dept = $_SESSION['filterarrayc']['dept'];
			if(!empty($_SESSION['filterarrayc']['designation']))
				$desig = $_SESSION['filterarrayc']['designation'];
			
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
			
			if($_SESSION['filterarrayc']['dept'] != "")
				$dept = $_SESSION['filterarrayc']['dept'];
			
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

	if(!empty($_SESSION['filterarrayc']['fromdate'])){
		$from = $_SESSION['filterarrayc']['fromdate']." 00:00:00";
		$cond .= " AND (timeofg >= '$from')";
	}
	
	if(!empty($_SESSION['filterarrayc']['todate'])) {
		$to = $_SESSION['filterarrayc']['todate']." 23:59:59";
		$cond .= " AND (timeofg <= '$to')";
	}
	
	if(!empty($_SESSION['filterarrayc']['comm'])) {
		$comm = $_SESSION['filterarrayc']['comm'];
		$categories = mysqli_query($conn, "SELECT category FROM catdet WHERE comm_name = '$comm'");
		if(mysqli_num_rows($categories)) {
			while ($inrow = mysqli_fetch_assoc($categories)) {
				
				$op .= "<strong> $comm - ".$inrow['category'].":</strong><br>";
				$cond .= " AND (gcat = '".$inrow['category']."')";
				
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
	echo $op;
	$ope = utf8_encode($op);
	$dompdf = new DOMPDF();
	$dompdf->load_html($ope);
	$dompdf->set_paper('a4', 'portrait');
	$dompdf->render();

	$dompdf->stream('Summary Report',array('Attachment'=>0));
//echo "<br><br>count = ".$countval

?>