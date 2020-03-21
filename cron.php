<?php
//everyday at 12:00am

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'includes/PHPMailer_5.2.0/vendor/autoload.php';

session_start();
ob_start();

include("includes/connection.php");
$rem = array();
$rais = array();
$threshrem1 = array();
$threshraise1 = array() ;
$threshrem2 = array() ;
$threshraise2 = array() ;

$i=0;
$j=0;
$q = "SELECT * from catdet";
$res = mysqli_query($conn,$q);
while($ro1 = mysqli_fetch_assoc($res)) {
	$cat = $ro1['category'];
	//echo $cat."<br>";
	$query = "SELECT * FROM grievance where gcat = '$cat'";
	$r = mysqli_query($conn,$query);
	
	while($row = mysqli_fetch_assoc($r)) {
		$timestamp = $row['timeofg'];
		//echo $row['gid']."<br>";
		$datetime = explode(" ",$timestamp);
		$date1 = $datetime[0];
		//$date=new DateTime($date);
		$date = strtotime($date1);
		//echo "tog: ".$date."<br>";

		// $da=date("Y-m-d");
		// $d = new DateTime($da);
		$da = time();
		//echo "current time: ".$da."<br>";
		//echo $d->format("Y M d ");
		$diff = floor(($da - $date)/(60*60*24));
		//$diff = $date->diff($d);
		//$diff = date_diff($d,$date);
		//echo $diff->format('%d');
		// echo $diff;
		// echo "<br>";
		// echo (int)$ro1['thresh_rem'];
		// echo "<br>";
		if($diff == (int)$ro1['thresh_rem']) {
			$rem[$i] = $row['gid'];
			// echo $row['gid'];
			// echo "<br>";
			// echo $rem[$i];
			// echo "<br>";
			$threshrem1[$i] = $ro1['thresh_rem'];
			$threshraise1[$i] = $ro1['thresh_raise'];
			// echo $i;
			// echo "<br>";
			$i = $i+1;
		}
		elseif($diff == (int)$ro1['thresh_raise'] && $ro1['thresh_raise'] != -1) {
			$rais[$j] = $row['gid'];
			$threshrem2[$j] = $ro1['thresh_rem'];
			$threshraise2[$j] = $ro1['thresh_raise'];
			// echo $j;
			// echo "<br>";
			$j = $j+1;
		}
	}
	// $i=0;
	// $j=0;
}
//echo $rem[0];
// print_r($rem);
// echo "<br>";
// echo "Enter";
// echo "<br>";
// echo sizeof($rem);
// echo "<br>";
if(sizeof($rem)>0) {
	//echo "Here";
	for($k=0 ; $k<$i ; $k++) {
		//echo $rem[$k];
		$diff = $threshraise1[$k] - $threshrem1[$k];
		$qu="SELECT * from grievance where gid='".$rem[$k]."'";
		$res=mysqli_query($conn,$qu);
		$row=mysqli_fetch_assoc($res);

		// if($row['gcat']=="Caste Discrimination") {
		// 	$to="brinda.ashar@somaiya.edu"; //Special cell
		// }
		// else if($row['gcat']=="Fee Related" || $row['gcat']=="Other - Student" || $row['gcat']=="Scholarship Related") {
		// 	$to="brinda.ashar@somaiya.edu"; //Students Grievance Redressal Committee
		// }
		// else if($row['gcat']=="Other - Employee" || $row['gcat']=="Salary Related") {
		// 	$to="brinda.ashar@somaiya.edu"; //Grievance Redressal Cell
		// }
		// else if($row['gcat']=="Ragging") {
		// 	$to="brinda.ashar@somaiya.edu"; //Anti-Ragging Committee
		// }
		// else if($row['gcat']=="Re-appeal against committee") {
		// 	$to="brinda.ashar@somaiya.edu"; //Principal
		// }
		// else if($row['gcat']=="Sexual Harassment") {
		// 	$to="brinda.ashar@somaiya.edu"; //Internal Complaints Committee
		// }
		// else $to="brinda.ashar@somaiya.edu";

		$q = "SELECT * FROM catdet WHERE category = '".$row['gcat']."' ";
		$r1 = mysqli_fetch_assoc(mysqli_query($conn,$q));

		$q1 = "SELECT * from commdet WHERE name = '".$r1['comm_name']."' ";
		$r2 = mysqli_fetch_assoc(mysqli_query($conn,$q1));

		$to = $r2['email'];

		// echo "<br>";
		// echo $to;

		$subj = "Reminder for Grievance ID: ".$row['gid'];
		$matter = "Respected Incharge Member(s): <br/><br/>
		This is a reminder to address the grievance as no action has been taken on it since ".$threshrem1[$k]." days. Following are the details of the grievance: <br/><br/>
		<table border = '1'> <tr><th>Grievance ID</th><th>Subject</th><th>Category</th><th>Description</th><th>Timestamp</th></tr>
		<tr><td>".$row['gid']."</td><td>".$row['gsub']."</td><td>".$row['gcat']."</td><td>".$row['gdes']."</td><td>".$row['timeofg']."</td></tr></table><br/><br/>
		If no action is taken on the grievance in the next ".$diff." day(s), it will be forwarded to the principal for consideration. <br/><br/>
		To address the grievance now, <a href='localhost/portal/'>click here.</a><br/><br/>
		<b>**This is a system generated email, do not reply to this email!**</b>";
		//echo $matter;

		$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
		try {
		    //Server settings
		    //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
		    $mail->isSMTP();                                      // Set mailer to use SMTP
		    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		    $mail->SMTPAuth = true;                               // Enable SMTP authentication
		    $mail->Username = 'committee.antir@gmail.com';                 // SMTP username
		    $mail->Password = 'committee@antir1';                           // SMTP password
		    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		    $mail->Port = 587;                                    // TCP port to connect to

		    //Recipients
		    $mail->setFrom('committee.antir@gmail.com');
		    $mail->addAddress($to);     // Add a recipient
		    //$mail->addAddress('ellen@example.com');               // Name is optional
		    //$mail->addReplyTo('info@example.com', 'Information');
		    $mail->addCC('rushabh.bid@somaiya.edu');
		    //$mail->addBCC('bcc@example.com');

		    //Attachments
		    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		    //Content
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = $subj;
		    $mail->Body    = $matter;
		    $mail->AltBody = 'Your grievance has been acknowledged by the authorities, and necessary actions will be taken as convenient.';

		    if($mail->send()) {
		    	//echo "Reminder mail was sent";

		    }
		}
		catch (Exception $e) {
		    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		} 
	}
}
//else echo "Not here";

if(sizeof($rais)>0) {
	for($k=0 ; $k<$j ; $k++) {
		$diff = $threshraise2[$k] - $threshrem2[$k];
		$query="UPDATE grievance SET status='Raised to Principal', raised = '1' WHERE gid='".$rais[$k]."'";
		$result=mysqli_query($conn,$query);
		$qu="SELECT * from grievance where gid='".$rais[$k]."'";
		$resu=mysqli_query($conn,$qu);
		$row1=mysqli_fetch_assoc($resu);
		$to = $row1['uemail'];

		// if($row1['gcat']=="Caste Discrimination") {
		// 	$tocc="brinda.ashar@somaiya.edu"; //Special cell
		// }
		// else if($row1['gcat']=="Fee Related" || $row1['gcat']=="Other - Student" || $row1['gcat']=="Scholarship Related") {
		// 	$tocc="brinda.ashar@somaiya.edu"; //Students Grievance Redressal Committee
		// }
		// else if($row1['gcat']=="Other - Employee" || $row1['gcat']=="Salary Related") {
		// 	$tocc="brinda.ashar@somaiya.edu"; //Grievance Redressal Cell
		// }
		// else if($row1['gcat']=="Ragging") {
		// 	$tocc="brinda.ashar@somaiya.edu"; //Anti-Ragging Committee
		// }
		// else if($row1['gcat']=="Re-appeal against committee") {
		// 	$tocc="brinda.ashar@somaiya.edu"; //Principal
		// }
		// else if($row1['gcat']=="Sexual Harassment") {
		// 	$tocc="brinda.ashar@somaiya.edu"; //Internal Complaints Committee
		// }
		// $tocc="brinda.ashar@somaiya.edu";

		$q = "SELECT * FROM catdet WHERE category = '".$row1['gcat']."' ";
		$r1 = mysqli_fetch_assoc(mysqli_query($conn,$q));

		$q1 = "SELECT * from commdet WHERE name = '".$r1['comm_name']."' ";
		$r2 = mysqli_fetch_assoc(mysqli_query($conn,$q1));

		$tocc = $r2['email'];

		$subj = "Raising of Grievance ID: ".$row1['gid'];
		$matter = "Respected Sir/Ma'am: <br/><br/>
		Since there was no action taken on this grievance since ".$threshraise2[$k]." days, it has been raised to the Principal. Following are the details of the grievance: <br/><br/>
		<table border = '1'> <tr><th>Grievance ID</th><th>Subject</th><th>Category</th><th>Description</th><th>Timestamp</th></tr>
		<tr><td>".$row1['gid']."</td><td>".$row1['gsub']."</td><td>".$row1['gcat']."</td><td>".$row1['gdes']."</td><td>".$row1['timeofg']."</td></tr></table><br/><br/>
		<b>**This is a system generated email, do not reply to this email!**</b>";
		//echo $matter;

		$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
		try {
		    //Server settings
		    //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
		    $mail->isSMTP();                                      // Set mailer to use SMTP
		    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		    $mail->SMTPAuth = true;                               // Enable SMTP authentication
		    $mail->Username = 'committee.antir@gmail.com';                 // SMTP username
		    $mail->Password = 'committee@antir1';                           // SMTP password
		    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		    $mail->Port = 587;                                    // TCP port to connect to

		    //Recipients
		    $mail->setFrom('committee.antir@gmail.com');
		    $mail->addAddress($to);     // Add a recipient
		    //$mail->addAddress('ellen@example.com');               // Name is optional
		    //$mail->addReplyTo('info@example.com', 'Information');
		    //$mail->addCC('rushabh.bid@somaiya.edu');
		    $mail->addCC($tocc);

		    //Attachments
		    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		    //Content
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = $subj;
		    $mail->Body    = $matter;
		    $mail->AltBody = 'Your grievance has been acknowledged by the authorities, and necessary actions will be taken as convenient.';

		    
		    if($mail->send()) {
		    	//echo "Raising mail was sent";
		    }
		}
		catch (Exception $e) {
		    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		}
	}
}

?>