<?php

ob_start();
session_start();

include_once("includes/connection.php");
include_once 'dompdf/dompdf_config.inc.php';

$from = $_POST['fromdate']." 00:00:00";
$to = $_POST['todate']." 23:59:59";

if($_POST['comm']=="Complaint" || $_POST['comm']=="all") {
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Complaint' AND status = 'Pending'";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$ap = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Complaint' AND status = 'In Progress' AND raised = 1";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$aipr = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Complaint' AND raised = 1 AND status = 'Action Taken'";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$aatr = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Complaint' AND status = 'Action Taken' AND raised = 0";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$aatc = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Complaint' AND status = 'Action Taken'";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$aat = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Complaint' AND status = 'In Progress' AND raised = 0";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$aipc = $count['COUNT(*)'];
}
if($_POST['comm']=="Scholarship" || $_POST['comm']=="all") {
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Scholarship' AND status = 'Pending'";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$sp = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Scholarship' AND status = 'In Progress' AND raised = 1";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$sipr = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Scholarship' AND raised = 1 AND status = 'Action Taken'";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$satr = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Scholarship' AND status = 'Action Taken' AND raised = 0";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$satc = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Scholarship' AND status = 'Action Taken'";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$sat = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Scholarship' AND status = 'In Progress' AND raised = 0";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$sipc = $count['COUNT(*)'];
}
if($_POST['comm']=="Harassment" || $_POST['comm']=="all") {
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Harassment' AND status = 'Pending'";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$hp = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Harassment' AND status = 'In Progress' AND raised = 1";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$hipr = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Harassment' AND raised = 1 AND status = 'Action Taken'";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$hatr = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Harassment' AND status = 'Action Taken' AND raised = 0";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$hatc = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Harassment' AND status = 'Action Taken'";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$hat = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Harassment' AND status = 'In Progress' AND raised = 0";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$hipc = $count['COUNT(*)'];
}
if($_POST['comm']=="Caste Discrimination" || $_POST['comm']=="all") {
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Caste Discrimination' AND status = 'Pending'";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$cp = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Caste Discrimination' AND status = 'In Progress' AND raised = 1";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$cipr = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Caste Discrimination' AND raised = 1 AND status = 'Action Taken'";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$catr = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Caste Discrimination' AND status = 'Action Taken' AND raised = 0";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$catc = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Caste Discrimination' AND status = 'Action Taken'";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$cat = $count['COUNT(*)'];
	$qu = "SELECT COUNT(*) from grievance WHERE (timeofg >= '$from' AND timeofg <= '$to') AND gcat = 'Caste Discrimination' AND status = 'In Progress' AND raised = 0";
	$count = mysqli_fetch_assoc(mysqli_query($conn,$qu));
	$cipc = $count['COUNT(*)'];
}

if(isset($_POST['summ'])) {
	$op="<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>Grievance Redressed:</p><br>";
	$op.= "From: ".$from."     To: ".$to;
	if($_POST['comm']=="Complaint" || $_POST['comm']=="all") {
		$op.= "<hr>";
		$op.= "<p align = 'center'><u><b>ANTIRAGGING COMMITTEE</b></u></p>";
		$op.= "<br><br><p>Resolved by Principal: ".$aatr."</p><p>Raised to Principal, In Progress: ".$aipr."</p><br>";
		$op.= "<p>Resolved by committee: ".$aatc."</p><p>Pending: ".$ap."</p><br>";
		$op.= "<p>Total resolved: ".$aat."</p><p>In Progress: ".$aipc."</p><br><hr>";
	}
	if($_POST['comm']=="Scholarship" || $_POST['comm']=="all") {
		$op.= "<hr>";
		$op.= "<p align = 'center'><u><b>HR COMMITTEE</b></u></p>";
		$op.= "<br><br><p>Resolved by Principal: ".$satr."</p><p>Raised to Principal, In Progress: ".$sipr."</p><br>";
		$op.= "<p>Resolved by committee: ".$satc."</p><p> Pending: ".$sp."</p><br>";
		$op.= "<p>Total resolved: ".$sat."</p><p> In Progress: ".$sipc."</p><br><hr>";
	}
	if($_POST['comm']=="Harassment" || $_POST['comm']=="all") {
		$op.= "<hr>";
		$op.= "<p align = 'center'><u><b>SEXUAL HARASSMENT COMMITTEE</b></u></p>";
		$op.= "<br><br><p>Resolved by Principal: ".$hatr."</p><p>Raised to Principal, In Progress: ".$hipr."</p><br>";
		$op.= "<p>Resolved by committee: ".$hatc."</p><p>Pending: ".$hp."</p><br>";
		$op.= "<p>Total resolved: ".$hat."</p><p>In Progress: ".$hipc."</p><br><hr>";
	}
	if($_POST['comm']=="Caste Discrimination" || $_POST['comm']=="all") {
		$op.= "<hr>";
		$op.= "<p align = 'center'><u><b>SPECIAL COMMITTEE</b></u></p>";
		$op.= "<br><br><p>Resolved by Principal: ".$catr."</p><p>Raised to Principal, In Progress: ".$cipr."</p><br>";
		$op.= "<p>Resolved by committee: ".$catc."</p><p>Pending: ".$cp."</p><br>";
		$op.= "<p>Total resolved: ".$cat."</p><p>In Progress: ".$cipc."</p><br><hr>";
	}
}

	$op.= "<br><br><div align = 'center'><b>* A computer generated report *</b>";
	//echo $op;
	$dompdf = new DOMPDF();
	$dompdf->load_html($op);
	$dompdf->set_paper('a4', 'portrait');
	$dompdf->render();

	$dompdf->stream('Summary Report',array('Attachment'=>0));
//echo "<br><br>count = ".$countval

?>