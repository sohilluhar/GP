<?php

ob_start();
session_start();

include_once("includes/connection.php");
include_once 'dompdf/dompdf_config.inc.php';

$from = $_POST['fromdate']." 00:00:00";
$to = $_POST['todate']." 23:59:59";


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

if(isset($_POST['summ'])) {
	$op="<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>Grievance Redressed:</p><br>";
	$op.= "From: ".$from."     To: ".$to;
	
		$op.= "<hr>";
		$op.= "<p align = 'center'><u><b>ANTIRAGGING COMMITTEE</b></u></p>";
		$op.= "<br><br><p>Resolved by Principal: ".$aatr."</p><p>Raised to Principal, In Progress: ".$aipr."</p><br>";
		$op.= "<p>Resolved by committee: ".$aatc."</p><p>Pending: ".$ap."</p><br>";
		$op.= "<p>Total resolved: ".$aat."</p><p>In Progress: ".$aipc."</p><br><hr>";
	
	$op.= "<br><br><div align = 'center'><b>* A computer generated report *</b>";
	//echo $op;
	$dompdf = new DOMPDF();
	$dompdf->load_html($op);
	$dompdf->set_paper('a4', 'portrait');
	$dompdf->render();

	$dompdf->stream('Summary Report',array('Attachment'=>0));
}
//echo "<br><br>count = ".$countval

?>