<?php include 'header.php';?>
<?php
session_start();
ob_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'includes/PHPMailer_5.2.0/vendor/autoload.php';

include_once("includes/connection.php");
	$flag=0;
	//$typeFlag=0;
	if(isset($_POST['student'])) {
		$_SESSION['typeFlag'] = 1;
	}
	else if(isset($_POST['employee'])) {
		$_SESSION['typeFlag'] = 3;
	}
	
	if(isset($_POST['submit']) && !isset($_SESSION['logInUser'])) {
		$em=explode("@",$_POST['email']);
		if(true) {
			$flag=1;
		}
		else {
			echo "Captcha validation failed.";
		}
		unset($_SESSION['my_captcha']);
		if($em[1]=="somaiya.edu" && $flag==1) {
			$eml=$_POST['email'];
			if($_SESSION['typeFlag'] == 1) $query="SELECT * FROM userdet WHERE email='$eml'";
			else if($_SESSION['typeFlag'] == 3) $query="SELECT * FROM facdet WHERE email='$eml'";
			$res=mysqli_query($conn,$query);
			//$_SESSION['logInUser']=$row['email'];
			if(mysqli_num_rows($res) > 0) {
				$row=mysqli_fetch_assoc($res);
				$_SESSION['logInUser']=$row['email'];
				$_SESSION['name']=$row['fname'];
				
				$otpgen = rand(100000,999999);
				$t = time() + 3600;
				
				if($_SESSION['typeFlag'] == 1) {
					$qu = "UPDATE userdet SET OTP = '$otpgen', expiry_time = '$t' WHERE email = '".$_SESSION['logInUser']."' ";
				}
				else if($_SESSION['typeFlag'] == 3) {
					$qu = "UPDATE facdet SET OTP = '$otpgen', expiry_time = '$t' WHERE email = '".$_SESSION['logInUser']."' ";
				}
				if(!mysqli_query($conn, $qu))
					echo mysqli_error($conn);
				else {
					$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
					$to = $_SESSION['logInUser'];
					$subj = "OTP for logging in to Grievance Redressal Portal, KJSCE";
					$matter = "A log in attempt was made to the Grievance Redressal Portal with this Email ID. <br><br>
					Please verify by entering the following One Time Password(OTP): <b>".$otpgen."</b> <br/><br/>
					<b>This is a system generated email, do not reply to this email!</b>";
					try {
						//Server settings
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
						//$mail->addBCC('bcc@example.com');

						//Attachments
						//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
						//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

						//Content
						$mail->isHTML(true);                                  // Set email format to HTML
						$mail->Subject = $subj;
						$mail->Body    = $matter;
						$mail->AltBody = 'Please enter the following OTP: '.$otpgen;

						$mail->send();
						//echo 'Message has been sent';
					} catch (Exception $e) {
						echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
					}
				}
			}
			else {
				$q = "INSERT INTO logins VALUES ('".$_POST['email']."', 'Failed attempt from an invalid Somaiya ID', CURRENT_TIMESTAMP)";
				$r = mysqli_query($conn, $q);
				$_SESSION['crederr'] = "<h5><font color='red'>Email not valid. Please try logging in again. If you are a student, do not try to log in from the employee login.</font></h5>";
				$_SESSION['emailerr']="";
			}
		}
		
		else  if ($em[1]!="somaiya.edu" && $flag == 1){
			$q = "INSERT INTO logins VALUES ('".$_POST['email']."', 'Failed intrusion from a Non-Somaiya ID', CURRENT_TIMESTAMP)";
			$r = mysqli_query($conn, $q);
			$_SESSION['emailerr'] = "<h5><font color='red'>Login allowed with Somaiya ID only!</font></h5>";
			$_SESSION['crederr'] = "";
		}
		
		else {
			$_SESSION['emailerr'] = "<h5><font color='red'>Captcha Verification Failed. TRY AGAIN !</font></h5>";
			$_SESSION['crederr'] = "";			
		}
	}

	else if(isset($_POST['resend'])) {
		$em = $_SESSION['logInUser'];
		$q = "SELECT * from userdet where email = '".$em."' ";
		$r = mysqli_fetch_assoc(mysqli_query($conn,$q));

		$ot = $r['OTP'];
		if($ot != "") {
			$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
			$to = $_SESSION['logInUser'];
			$subj = "Resent: OTP for logging in to Grievance Redressal Portal, KJSCE";
			$matter = "Please verify your login by entering the following One Time Password(OTP): <b>".$ot."</b> <br/><br/>
			<b>This is a system generated email, do not reply to this email!</b>";
			try {
				//Server settings
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
				//$mail->addCC('brinda.ashar@somaiya.edu');
				//$mail->addBCC('bcc@example.com');

				//Attachments
				//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
				//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

				//Content
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = $subj;
				$mail->Body    = $matter;
				$mail->AltBody = 'Please enter the following OTP: '.$ot;

				$mail->send();
				//echo 'Message has been sent';
			} catch (Exception $e) {
				echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
			}
		}
	}
	
	else if(isset($_SESSION['logInUser'])) {
		$otpentered = $_POST['otp'];
		if($_SESSION['typeFlag'] == 1) $qu = "SELECT * FROM userdet where email = '".$_SESSION['logInUser']."'";
		if($_SESSION['typeFlag'] == 3) $qu = "SELECT * FROM facdet where email = '".$_SESSION['logInUser']."'";
		$res = mysqli_query($conn, $qu);
		if(mysqli_num_rows($res)>0) {
			$row = mysqli_fetch_assoc($res);
			$otpverify = $row['OTP'];
			$exp = $row['expiry_time'];
			$t = time();
			
			if($exp > $t) {
				$qu = "UPDATE userdet SET OTP = '', expiry_time = '' WHERE email = '".$_SESSION['logInUser']."' ";
				if(!mysqli_query($conn, $qu))
					echo mysqli_error;
				else {
					$_SESSION['loggedInUser'] = $_SESSION['logInUser'];
					$q = "INSERT INTO logins VALUES ('".$_SESSION['logInUser']."', 'Successful Login', CURRENT_TIMESTAMP)";
					$r = mysqli_query($conn, $q);
					if($_SESSION['typeFlag']==1) {
						//echo "in gr";
						header('location:home.php');
					}
					if($_SESSION['typeFlag']==3) {
						//echo "in gr";
						header('location:emphome.php');
					}
				}
			}
			
			else if($exp < $t) {
				echo "<script> alert('OTP expired. Log in again to generate new OTP');</script>";
				header('Location: index.php');
			}
			
			else {
				echo "<script> alert('Incorrect OTP entered.');</script>";
			}
		}
	}
	
	else {
		$_SESSION['emailerr'] = "";
		$_SESSION['crederr'] = "";
	}
?>
<!Doctype html>
<html>
<title>Grievance Portal</title>

<head>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
function reload() {
	img = document.getElementById("capt");
	img.src="captcha-image-adv.php?rand_number=" + Math.random();
}
</script>
<!------ Include the above in your HEAD tag ---------->
<style>


/* Create two equal columns that floats next to each other */


/* Create three equal columns that floats next to each other */
.column {
  float: left;
  width: 100%;
  padding: 10px;
  height: 200px; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
 
  content: "";
  display: table;
  clear: both;
}
.header36{
  float: left;
  width: 100vw;
  left-margin:600px;
  font-family: "Times New Roman", Times, serif;
  font-size: 180%;
  text-align: center;
}
</style>
<div class="container-fluid">

<div class="row">
</div>

<div align = "center" class="header36">
<em>Grievance Redressal Portal</em>  
</div>

</div>

<div class="container">    
    <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
        <div class="panel panel-info" >
			<div class="panel-heading">
				<div class="panel-title">Sign In</div>
			</div>     

			<div style="padding-top:10px" class="panel-body" >

				<div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
					
					<?php

						if(!isset($_SESSION['logInUser'])) {
							echo '<form id="loginform" method = "POST" class="form-horizontal" role="form">
								
								<div style="margin-bottom: 20px" class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
									<input id="email" type = "email" name = "email" class="form-control" value="" placeholder="username or email">                                        
								</div> 
								<input type="text" name="t1" id="t1" placeholder="Enter Captcha">
									<img src="captcha-image-adv.php" id="capt"><br>
								<div style="margin-top:10px" class="form-group">
									<!-- Button -->

									<div class="col-sm-12 controls">
									  <button type = "submit" name = "submit" id = "submit" class="btn btn-success">Login</button>
									  <input type="button" onClick="reload()"; value="Reload image" class="btn btn-success">
									</div>
								</div>
								
							</form>';
							
							echo $_SESSION['crederr'];
							echo $_SESSION['emailerr'];
						}

						else {
							echo '<form id="otpform" method = "POST" class="form-horizontal" role="form">
								
								<div style="margin-bottom: 20px" class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
									<input id="otp" type = "password" name = "otp" class="form-control" value="" placeholder="Enter 6 digit OTP">                                        
								</div> 
								<div style="margin-top:10px" class="form-group">
									<!-- Button -->

									<div class="col-sm-12 controls">
									  <button type = "submit" name = "submit" id = "submit" class="btn btn-success">Login</button>
									  <button name = "resend" id = "resend" class = "btn btn-info">Resend OTP</button>
									</div>
								</div>
								
							</form>';						
						}					

					?>							

			</div>                     
		</div>  
    </div>
</div>

</body>		
</html>

<?php include 'footer.php';?>