<?php include 'header.php';?>
<?php

if (isset($_SESSION['previousu'])) {
   if (basename($_SERVER['PHP_SELF']) != $_SESSION['previousu']) {
        session_destroy();
        ### or alternatively, you can use this for specific variables:
        ### unset($_SESSION['varname']);
   }
}

session_start();
if(!isset($_SESSION['loggedInUser'])){
    header("location:index.php");
}

include_once("includes/connection.php");

$name=$_SESSION['name'];


if(isset($_POST['submit'])) {
	date_default_timezone_set('Asia/Kolkata');
	$q=getdate();
	
	$day = $q['mday'];
	$month = $q['mon'];
	$year = $q['year'];
	
	if($day<10)
		$day = '0'.$day;
	if($month<10)
		$month = '0'.$month;
	
	$string = $day.$month.$year;
	$query = "SELECT gid FROM grievance WHERE gid REGEXP '".$string."'";
	
	$i = mysqli_num_rows(mysqli_query($conn,$query));
	
	if($i<10)
		$num = '000'.$i;
	elseif($i>9 && $i<100)
		$num = '00'.$i;
	elseif($i>99 && $i<1000)
		$num = '0'.$i;
	else
		$num = ''.$i;
	
	$gid = $string.$num;
	$urole = $_POST['urole'];
	$gsub = $_POST['gsub'];
	$gcat = $_POST['gcat'];
	$gdesc = $_POST['gdesc'];
	$uemail = $_SESSION['loggedInUser'];
	$file = NULL;

	if(isset($_FILES['atfile'])) {
		$fileName = $_FILES['atfile']['name'];
		$fileSize = $_FILES['atfile']['size'];
		$fileTmp = $_FILES['atfile']['tmp_name'];
		$fileType = $_FILES['atfile']['type'];
		$temp = explode('.',$fileName);
		$fileExt = strtolower(end($temp));
		$targetName = "proofDocs/".$gid.".".$fileExt;  
		  
			if(file_exists($targetName)) {   
				unlink($targetName);
			}      
			$moved = move_uploaded_file($fileTmp,$targetName);
			if($moved == true) {
				//successful
				$query1="INSERT INTO grievance(gid,uemail,urole,gsub,gcat,gdes,gfile,timeofg) VALUES ('$gid','$uemail','$urole','$gsub','$gcat','$gdesc','".$targetName."',CURRENT_TIMESTAMP)";
				$r=mysqli_query($conn,$query1); 
			}
			else{
				$query1="INSERT INTO grievance(gid,uemail,urole,gsub,gcat,gdes,gfile,timeofg) VALUES ('$gid','$uemail','$urole','$gsub','$gcat','$gdesc','$file',CURRENT_TIMESTAMP)";
				$r=mysqli_query($conn,$query1);	 
			}

	}
	else {
		echo "No file detected";
	}
}

$qu="SELECT * FROM grievance where uemail='".$_SESSION['loggedInUser']."'";
$res=mysqli_query($conn,$qu);


?>
<!DOCTYPE HTML>
<html>
<header align="right"><?php echo $_SESSION['loggedInUser'].' | <a href="logout.php">Logout</a>'; ?></header>
<?php echo "<h4>Hi, ".$name."!</h4>" ?>
<form method = "POST" action = "home.php"><button type = "submit" name = "subgriev" class = "btn btn-primary"><header align="left">Submit a Grievance</header></button></form>

<h3> Grievances Submitted:</h3><br>

<table class = "table table-stripped">
	<thead>
		<tr>
			<form method = "POST">
			<th style="text-align:center; width: 14%;"> <button type = 'submit' class="btn btn-info btn-block" name = 'toggle' value = 'pending'> Pending </button> </th>
			<th style="text-align:center; width: 14%;"> <button type = 'submit' class="btn btn-info btn-block" name = 'toggle' value = 'in_progress'> In Progress </button> </th>
			<th style="text-align:center; width: 14%;"> <button type = 'submit' class="btn btn-info btn-block" name = 'toggle' value = 'forwarded'> Forwarded  </button> </th>
			<th style="text-align:center; width: 14%;"> <button type = 'submit' class="btn btn-info btn-block" name = 'toggle' value = 'partially_solved'> Partially Solved  </button> </th>
			<th style="text-align:center; width: 14%;"> <button type = 'submit' class="btn btn-info btn-block" name = 'toggle' value = 'resolved'> Resolved </button> </th>
			<th style="text-align:center; width: 14%;"> <button type = 'submit' class="btn btn-info btn-block" name = 'toggle' value = 'offline'> Offline Reports </button> </th>
			<th style="text-align:center; width: 14%;"> <button type = 'submit' class="btn btn-info btn-block" name = 'toggle' value = 'cancelled'> Cancelled </button> </th>
			</form>
		</tr>
	</thead>
	
	<tbody>
		<tr><td colspan = '7'>
			<div align = "center">
				<?php
					if(isset($_POST['toggle'])) {
						if($_POST['toggle'] == 'pending') {
							$qu = "SELECT * FROM grievance where (uemail='".$_SESSION['loggedInUser']."') AND (status = 'Pending')";
							$res_pending = mysqli_query($conn,$qu);
							echo "<h4> Pending </h4>";
						}
						else if($_POST['toggle'] == 'in_progress') {
							$qu = "SELECT * FROM grievance where (uemail='".$_SESSION['loggedInUser']."') AND (status = 'In Progress')";
							$res_inprogress = mysqli_query($conn,$qu);
							echo "<h4> In Progress </h4>";
						}
						else if($_POST['toggle'] == 'forwarded') {
							$qu = "SELECT * FROM grievance where (uemail='".$_SESSION['loggedInUser']."') AND (status REGEXP 'Forwarded to')";
							$res_forwarded = mysqli_query($conn,$qu);
							echo "<h4> Forwarded </h4>";
						}
						else if($_POST['toggle'] == 'partially_solved') {
							$qu = "SELECT * FROM grievance where (uemail='".$_SESSION['loggedInUser']."') AND (status = 'Partially Solved')";
							$res_partsolved = mysqli_query($conn,$qu);
							echo "<h4> Partially Solved </h4>";
						}
						else if($_POST['toggle'] == 'resolved') {
							$qu = "SELECT * FROM resolved where (uemail='".$_SESSION['loggedInUser']."') AND (status='Resolved')";
							$res_resolved = mysqli_query($conn,$qu);
							echo "<h4> Resolved </h4>";
						}
						else if($_POST['toggle'] == 'offline') {
							$qu = "SELECT * FROM resolved where (uemail='".$_SESSION['loggedInUser']."') AND (status='Resolved Offline')";
							$res_offline = mysqli_query($conn,$qu);
							echo "<h4> Offline Reports </h4>";
						}
						else {
							$qu = "SELECT * FROM cancelled where (uemail='".$_SESSION['loggedInUser']."')";
							$res_cancel = mysqli_query($conn,$qu);
							echo "<h4> Cancelled </h4>";
						}
					}
					else {
						$qu = "SELECT * FROM grievance where (uemail='".$_SESSION['loggedInUser']."') AND (status = 'Pending')";
						$res_pending = mysqli_query($conn,$qu);
						echo "<h4> Pending </h4>";
					}
				?>
			</div>
			<table class = "table table-striped">
				<thead class = "thead-dark">
					<tr>
						<?php
							if(isset($_POST['toggle'])) {
								
								if($_POST['toggle'] == 'pending') {
									echo "<th>Grievance ID</th>
										<th>Grievance Subject</th>
										<th>Grievance Category</th>
										<th>Description</th>
										<th>File Attached</th>
										<th>Time of Issue</th>
										<th>Grievance Status</th>
										<th>Cancel Grievance</th>";
								}
								
								else if($_POST['toggle'] == 'in_progress') {
									echo "<th>Grievance ID</th>
											<th>Grievance Subject</th>
											<th>Grievance Category</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>";
								}
																
								else if($_POST['toggle'] == 'forwarded') {
									echo "<th>Grievance ID</th>
											<th>Grievance Subject</th>
											<th>Grievance Category</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Foward Details</th>
											<th>Last Status Update On</th>
											<th>Action Details</th>";
								}
								
								else if($_POST['toggle'] == 'partially_solved') {
									echo "<th>Grievance ID</th>
											<th>Grievance Subject</th>
											<th>Grievance Category</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>
											<th>Action Details</th>";
								}
								
								else if($_POST['toggle'] == 'resolved') {
									echo "<th>Grievance ID</th>
											<th>Grievance Subject</th>
											<th>Grievance Category</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>
											<th>Action Details</th>";
								}
								
								else if($_POST['toggle'] == 'offline') {
									echo "<th>Grievance ID</th>
											<th>Grievance Subject</th>
											<th>Grievance Category</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>
											<th>Action Details</th>";
								}
								
								else {
									echo "<th>Grievance ID</th>
											<th>Grievance Subject</th>
											<th>Grievance Category</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>";
								}
							}
							
							else {
								echo "<th>Grievance ID</th>
										<th>Grievance Subject</th>
										<th>Grievance Category</th>
										<th>Description</th>
										<th>File Attached</th>
										<th>Time of Issue</th>
										<th>Grievance Status</th>
										<th>Cancel Grievance</th>";
							}
						?>
					</tr>
				</thead>
				
				<tbody>
					<?php
					
						if(isset($_POST['toggle'])) {
							if($_POST['toggle'] == 'pending') {
								
								echo "<tr style = 'border: 4px solid black;'><td colspan = '8' style = 'text-align: center; background-color: white; color: black;'> ".mysqli_num_rows($res_pending)." Grievances </td></tr>";
								
								if(mysqli_num_rows($res_pending)>0){
									while($row =mysqli_fetch_assoc($res_pending)){
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$row['gsub']. "</td>";
										echo "<td>" .$row['gcat']. "</td>";
										echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc".$row['gid']."'> Show/Hide </button>
											<div class='collapse' id='gdesc".$row['gid']."'>
														<p> ".$row['gdes']."</p>
											</div>
											</td>";
										if($row['gfile']!=NULL) {
											echo "<td> <form action = '".$row['gfile']."' target='_blank' method = 'POST'><button type='submit' style = 'color: #4be1e1;' class='btn btn-link'>View File</button></form> </td>";
										}
										else echo "<td>No File Attached</td>";
										echo "<td>" .$row['timeofg']. "</td>";
										echo "<td>" .$row['status']. "</td>";
										echo "<td><form method='POST' action='cancel.php' onclick=\"return confirm('Are you sure?')\">
											<input type = 'hidden' name = 'uid' id='uid' value = '".$row['uemail']."'>
											<input type = 'hidden' name = 'gid' id='gid' value = '".$row['gid']."'>
											<button type='submit' class = 'btn btn-light' name='cancg' id='cancg'>Cancel Grievance</button></form></td>";
										echo "</tr>";
									}
								}
							}
							
							else if($_POST['toggle'] == 'in_progress') {
								
								echo "<tr style = 'border: 4px solid black;'><td colspan = '8' style = 'text-align: center; background-color: white; color: black;'> ".mysqli_num_rows($res_inprogress)." Grievances </td></tr>";
								
								if(mysqli_num_rows($res_inprogress)>0){
									while($row =mysqli_fetch_assoc($res_inprogress)){
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$row['gsub']. "</td>";
										echo "<td>" .$row['gcat']. "</td>";
										echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc".$row['gid']."'> Show/Hide </button>
											<div class='collapse' id='gdesc".$row['gid']."'>
														<p> ".$row['gdes']."</p>
											</div>
											</td>";
										if($row['gfile']!=NULL) {
											echo "<td>  <form action = '".$row['gfile']."' target='_blank' method = 'POST'><button type='submit' style = 'color: #4be1e1;' class='btn btn-link'>View File</button></form> </td>";
										}
										else echo "<td>No File Attached</td>";
										echo "<td>" .$row['timeofg']. "</td>";
										echo "<td>" .$row['status']. "</td>";
										echo "<td>" .$row['uptime']. "</td>";
										echo "</tr>";
									}
								}
							}
							
							else if($_POST['toggle'] == 'forwarded') {
						
								echo "<tr style = 'border: 4px solid black;'><td colspan = '10' style = 'text-align: center; background-color: white; color: black;'> ".mysqli_num_rows($res_forwarded)." Grievances </td></tr>";
								
								if(mysqli_num_rows($res_forwarded)>0){
									while($row =mysqli_fetch_assoc($res_forwarded)){
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$row['gsub']. "</td>";
										echo "<td>" .$row['gcat']. " - " .$row['gtype']. "</td>";
										echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc".$row['gid']."'> Show/Hide </button>
											<div class='collapse' id='gdesc".$row['gid']."'>
														<p> ".$row['gdes']."</p>
											</div>
											</td>";
										if($row['gfile']!=NULL) {
											echo "<td>  <form action = '".$row['gfile']."' target='_blank' method = 'POST'><button type='submit' style = 'color: #4be1e1;' class='btn btn-link'>View File</button></form> </td>";
										}
										else echo "<td>No File Attached</td>";
										echo "<td>" .$row['timeofg']. "</td>";
										echo "<td>" .$row['status']. "</td>";
										echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#forwarddetails".$row['gid']."'> Show/Hide </button>
											<div class='collapse' id='forwarddetails".$row['gid']."'>
														<p> ".$row['fordet']."</p>
											</div>
											</td>";
										echo "<td>" .$row['uptime']. "</td>";
										echo "<td style = 'color: #000000;'>
											  <!-- Trigger the modal with a button -->
											  <button type='button' class = 'btn btn-light' data-toggle='modal' data-target='#myModal".$row['gid']."'>Action Details</button>

											  <!-- Modal -->
											  <div class='modal' id='myModal".$row['gid']."' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Action Details:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															
																<input type = 'hidden' name = 'uid1' id='uid1' value = '".$row['uemail']."'>
																<input type = 'hidden' name = 'gid1' id='gid1' value = '".$row['gid']."'>
																<p>".$row['act']."</p>
																<br><br>
																<form action='print.php' method='post' target = '_blank'>
																<input type = 'hidden' name = 'uid2' id='uid2' value = '".$row['uemail']."'>
																<input type = 'hidden' name = 'gid2' id='gid2' value = '".$row['gid']."'>
																<button type='submit' class = 'btn btn-dark' class='print'>Download Action Details as a pdf</button></form>	
															
														</div>
													  </div>
													  
													</div>
											  </div></td>";
										echo "</tr>";
									}
								}
							}
							
							else if($_POST['toggle'] == 'partially_solved') {
								
								echo "<tr style = 'border: 4px solid black;'><td colspan = '9' style = 'text-align: center; background-color: white; color: black;'> ".mysqli_num_rows($res_partsolved)." Grievances </td></tr>";
								
								if(mysqli_num_rows($res_partsolved)>0){
									while($row =mysqli_fetch_assoc($res_partsolved)){
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$row['gsub']. "</td>";
										echo "<td>" .$row['gcat']. "</td>";
										echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc".$row['gid']."'> Show/Hide </button>
											<div class='collapse' id='gdesc".$row['gid']."'>
														<p> ".$row['gdes']."</p>
											</div>
											</td>";
										if($row['gfile']!=NULL) {
											echo "<td>  <form action = '".$row['gfile']."' target='_blank' method = 'POST'><button type='submit' style = 'color: #4be1e1;' class='btn btn-link'>View File</button></form> </td>";
										}
										else echo "<td>No File Attached</td>";
										echo "<td>" .$row['timeofg']. "</td>";
										echo "<td>" .$row['status']. "</td>";
										echo "<td>" .$row['uptime']. "</td>";
										echo "<td style = 'color: #000000;'>
											  <!-- Trigger the modal with a button -->
											  <button type='button' class = 'btn btn-light' data-toggle='modal' data-target='#myModal".$row['gid']."'>Action Details</button>

											  <!-- Modal -->
											  <div class='modal' id='myModal".$row['gid']."' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Action Details:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															
																<input type = 'hidden' name = 'uid1' id='uid1' value = '".$row['uemail']."'>
																<input type = 'hidden' name = 'gid1' id='gid1' value = '".$row['gid']."'>
																<p>".$row['act']."</p>
																<br><br>
																<form action='print.php' method='post' target = '_blank'>
																<input type = 'hidden' name = 'uid2' id='uid2' value = '".$row['uemail']."'>
																<input type = 'hidden' name = 'gid2' id='gid2' value = '".$row['gid']."'>
																<button type='submit' class = 'btn btn-dark' class='print'>Download Action Details as a pdf</button></form>	
															
														</div>
													  </div>
													  
													</div>
											  </div></td>";
										echo "</tr>";
									}
								}
							}
							
							else if($_POST['toggle'] == 'resolved') {
								
								echo "<tr style = 'border: 4px solid black;'><td colspan = '9' style = 'text-align: center; background-color: white; color: black;'> ".mysqli_num_rows($res_resolved)." Grievances </td></tr>";
								
								if(mysqli_num_rows($res_resolved)>0){
									while($row =mysqli_fetch_assoc($res_resolved)){
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$row['gsub']. "</td>";
										echo "<td>" .$row['gcat']. "</td>";
										echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc".$row['gid']."'> Show/Hide </button>
											<div class='collapse' id='gdesc".$row['gid']."'>
														<p> ".$row['gdes']."</p>
											</div>
											</td>";
										if($row['gfile']!=NULL) {
											echo "<td>  <form action = '".$row['gfile']."' target='_blank' method = 'POST'><button type='submit' style = 'color: #4be1e1;' class='btn btn-link'>View File</button></form> </td>";
										}
										else echo "<td>No File Attached</td>";
										echo "<td>" .$row['timeofg']. "</td>";
										echo "<td>" .$row['status']. "</td>";
										echo "<td>" .$row['uptime']. "</td>";
										echo "<td style = 'color: #000000;'>
											  <!-- Trigger the modal with a button -->
											  <button type='button' class = 'btn btn-light' data-toggle='modal' data-target='#myModal".$row['gid']."'>Action Details</button>

											  <!-- Modal -->
											  <div class='modal' id='myModal".$row['gid']."' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Action Details:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															
																<input type = 'hidden' name = 'uid1' id='uid1' value = '".$row['uemail']."'>
																<input type = 'hidden' name = 'gid1' id='gid1' value = '".$row['gid']."'>
																<p>".$row['act']."</p>
																<br><br>
																<form action='print.php' method='post' target = '_blank'>
																<input type = 'hidden' name = 'uid2' id='uid2' value = '".$row['uemail']."'>
																<input type = 'hidden' name = 'gid2' id='gid2' value = '".$row['gid']."'>
																<button type='submit' class = 'btn btn-dark' class='print'>Download Action Details as a pdf</button></form>	
															
														</div>
													  </div>
													  
													</div>
											  </div></td>";
										echo "</tr>";
									}
								}
							}
							
							else if($_POST['toggle'] == 'offline') {
								
								echo "<tr style = 'border: 4px solid black;'><td colspan = '9' style = 'text-align: center; background-color: white; color: black;'> ".mysqli_num_rows($res_offline)." Grievances </td></tr>";
								
								if(mysqli_num_rows($res_offline)>0){
									while($row =mysqli_fetch_assoc($res_offline)){
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$row['gsub']. "</td>";
										echo "<td>" .$row['gcat']. "</td>";
										echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc".$row['gid']."'> Show/Hide </button>
											<div class='collapse' id='gdesc".$row['gid']."'>
														<p> ".$row['gdes']."</p>
											</div>
											</td>";
										if($row['gfile']!=NULL) {
											echo "<td>  <form action = '".$row['gfile']."' target='_blank' method = 'POST'><button type='submit' style = 'color: #4be1e1;' class='btn btn-link'>View File</button></form> </td>";
										}
										else echo "<td>No File Attached</td>";
										echo "<td>" .$row['timeofg']. "</td>";
										echo "<td>" .$row['status']. "</td>";
										echo "<td>" .$row['uptime']. "</td>";
										echo "<td style = 'color: #000000;'>
											  <!-- Trigger the modal with a button -->
											  <button type='button' class = 'btn btn-light' data-toggle='modal' data-target='#myModal".$row['gid']."'>Action Details</button>

											  <!-- Modal -->
											  <div class='modal' id='myModal".$row['gid']."' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Action Details:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															
																<input type = 'hidden' name = 'uid1' id='uid1' value = '".$row['uemail']."'>
																<input type = 'hidden' name = 'gid1' id='gid1' value = '".$row['gid']."'>
																<p>".$row['act']."</p>
																<br><br>
																<form action='print.php' method='post' target = '_blank'>
																<input type = 'hidden' name = 'uid2' id='uid2' value = '".$row['uemail']."'>
																<input type = 'hidden' name = 'gid2' id='gid2' value = '".$row['gid']."'>
																<button type='submit' class = 'btn btn-dark' class='print'>Download Action Details as a pdf</button></form>	
															
														</div>
													  </div>
													  
													</div>
											  </div></td>";
										echo "</tr>";
									}
								}
							}
							
							else {
						
								echo "<tr style = 'border: 4px solid black;'><td colspan = '8' style = 'text-align: center; background-color: white; color: black;'> ".mysqli_num_rows($res_cancel)." Grievances </td></tr>";
								
								if(mysqli_num_rows($res_cancel)>0){
									while($row =mysqli_fetch_assoc($res_cancel)){
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$row['gsub']. "</td>";
										echo "<td>" .$row['gcat']. " - " .$row['gtype']. "</td>";
										echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc".$row['gid']."'> Show/Hide </button>
											<div class='collapse' id='gdesc".$row['gid']."'>
														<p> ".$row['gdes']."</p>
											</div>
											</td>";
										if($row['gfile']!=NULL) {
											echo "<td>  <form action = '".$row['gfile']."' target='_blank' method = 'POST'><button type='submit' style = 'color: #4be1e1;' class='btn btn-link'>View File</button></form> </td>";
										}
										else echo "<td>No File Attached</td>";
										echo "<td>" .$row['timeofg']. "</td>";
										echo "<td>" .$row['status']. "</td>";
										echo "<td>" .$row['uptime']. "</td>";
										echo "</tr>";
									}
								}
							}
						}
						
						else {
	
							echo "<tr style = 'border: 4px solid black;'><td colspan = '8' style = 'text-align: center; background-color: white; color: black;'> ".mysqli_num_rows($res_pending)." Grievances </td></tr>";
							
							if(mysqli_num_rows($res_pending)>0){
								while($row =mysqli_fetch_assoc($res_pending)){
									echo "<tr>";
									echo "<td>" .$row['gid']. "</td>";
									echo "<td>" .$row['gsub']. "</td>";
									echo "<td>" .$row['gcat']. "</td>";
									echo "<td>
										<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc".$row['gid']."'> Show/Hide </button>
										<div class='collapse' id='gdesc".$row['gid']."'>
													<p> ".$row['gdes']."</p>
										</div>
										</td>";
									if($row['gfile']!=NULL) {
										echo "<td> <form action = '".$row['gfile']."' target='_blank' method = 'POST'><button type='submit' style = 'color: #4be1e1;' class='btn btn-link'>View File</button></form> </td>";
									}
									else echo "<td>No File Attached</td>";
									echo "<td>" .$row['timeofg']. "</td>";
									echo "<td>" .$row['status']. "</td>";
									echo "<td><form method='POST' action='cancel.php' onclick=\"return confirm('Are you sure?')\">
										<input type = 'hidden' name = 'uid' id='uid' value = '".$row['uemail']."'>
										<input type = 'hidden' name = 'gid' id='gid' value = '".$row['gid']."'>
										<button type='submit' class = 'btn btn-light' name='cancg' id='cancg'>Cancel Grievance</button></form></td>";
									echo "</tr>";
								}
							}
						}
					
					?>
				</tbody>
			</table>
		</td></tr>
	</tbody>
</table>
</html>
<?php include 'footer.php';?>