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
	$gsub = $_POST['gsub'];
	$gcat = $_POST['gcat'];
	$gtype = $_POST['gtype'];
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
				$query1="INSERT INTO grievance(gid,uemail,gsub,gcat,gtype,gdes,gfile,timeofg) VALUES ('$gid','$uemail','$gsub','$gcat','$gtype','$gdesc','".$targetName."',CURRENT_TIMESTAMP)";
				$r=mysqli_query($conn,$query1); 
			}
			else{
				$query1="INSERT INTO grievance(gid,uemail,gsub,gcat,gtype,gdes,gfile,timeofg) VALUES ('$gid','$uemail','$gsub','$gcat','$gtype','$gdesc','$file',CURRENT_TIMESTAMP)";
				$r=mysqli_query($conn,$query1);	 
			}

	}
	else {
		echo "No file detected";
	}
}


?>
<!DOCTYPE HTML>
<html>
<header align="right"><?php echo $_SESSION['loggedInUser'].' | <a href="logout.php">Logout</a>'; ?></header>
<?php echo "<h4>Hi, ".$name."!</h4>" ?>
<form method = "POST" action = "caste.php"><button type = "submit" name = "subgriev" class = "btn btn-primary"><header align="left">Report Caste Discrimination</header></button></form>

<h3> Grievances Submitted:</h3><br>

<table class = "table table-stripped">
	<form method = "POST">
	<thead>
		<tr>
			<th style="text-align:center;"> <button type = 'submit' class="btn btn-info" name = 'toggle' value = 'pending'> Pending </button> </th>
			<th style="text-align:center;"> <button type = 'submit' class="btn btn-info" name = 'toggle' value = 'in_progress'> In Progress </button> </th>
			<th style="text-align:center;"> <button type = 'submit' class="btn btn-info" name = 'toggle' value = 'raised'> Raised  </button> </th>
			<th style="text-align:center;"> <button type = 'submit' class="btn btn-info" name = 'toggle' value = 'resolved'> Resolved </button> </th>
		</tr>
	</thead>
	</form>
	
	<tbody>
		<tr><td colspan = '4'>
			<div align = "center">
				<?php
					if(isset($_POST['toggle'])) {
						if($_POST['toggle'] == 'pending')
							echo "<h4> Pending </h4>";
						else if($_POST['toggle'] == 'in_progress')
							echo "<h4> In Progress </h4>";
						else if($_POST['toggle'] == 'raised')
							echo "<h4> Raised </h4>";
						else
							echo "<h4> Resolved </h4>";
					}
					else
						echo "<h4> Pending </h4>";
				?>
			</div>
			<table class = "table table-striped">
				<thead class = "thead-dark">
					<tr>
						<?php
							if(isset($_POST['toggle'])) {
								if($_POST['toggle'] == 'pending') {
									
									$qu = "SELECT * FROM grievance where (uemail='".$_SESSION['loggedInUser']."') AND (status = 'Pending') AND gcat = 'Caste Discrimination'";
									$resp = mysqli_query($conn,$qu);
									echo "	<th>Grievance id</th>
										<th>Grievance Subject</th>
										<th>Grievance Category</th>
										<th>Grievance Type</th>
										<th>Grievance Description</th>
										<th>File Attached</th>
										<th>Time of Issue</th>
										<th>Grievance Status</th>
										<th>Last Status Updated On</th>
										<th>Cancel Grievance</th>";
								}
								
								else if($_POST['toggle'] == 'in_progress') {
									
									$qu = "SELECT * FROM grievance where (uemail='".$_SESSION['loggedInUser']."') AND (status = 'In progress') and gcat = 'Caste Discrimination'";
									$resip = mysqli_query($conn,$qu);

									echo "	<th>Grievance id</th>
										<th>Grievance Subject</th>
										<th>Grievance Category</th>
										<th>Grievance Type</th>
										<th>Grievance Description</th>
										<th>File Attached</th>
										<th>Time of Issue</th>
										<th>Grievance Status</th>
										<th>Last Status Updated On</th>";
								}
								
								else if($_POST['toggle'] == 'raised') {
							
									$qu = "SELECT * FROM grievance where (uemail='".$_SESSION['loggedInUser']."') AND (status = 'Raised to Principal') and gcat = 'Caste Discrimination'";
									$resrp = mysqli_query($conn,$qu);
									
									echo "	<th>Grievance id</th>
										<th>Grievance Subject</th>
										<th>Grievance Category</th>
										<th>Grievance Type</th>
										<th>Grievance Description</th>
										<th>File Attached</th>
										<th>Time of Issue</th>
										<th>Grievance Status</th>
										<th>Last Status Updated On</th>";
								}
								
								else {
							
									$qu = "SELECT * FROM resolved where (uemail='".$_SESSION['loggedInUser']."') AND (status = 'Action Taken') and gcat = 'Caste Discrimination'";
									$resr = mysqli_query($conn,$qu);
									
									echo "	<th>Grievance id</th>
										<th>Grievance Subject</th>
										<th>Grievance Category</th>
										<th>Grievance Type</th>
										<th>Grievance Description</th>
										<th>File Attached</th>
										<th>Time of Issue</th>
										<th>Grievance Status</th>
										<th>Last Status Updated On</th>
										<th>Action Taken</th>";
								}
							}
							
							else {
						
								$qu = "SELECT * FROM grievance where (uemail='".$_SESSION['loggedInUser']."') AND (status = 'Pending') and gcat = 'Caste Discrimination'";
								$resp = mysqli_query($conn,$qu);
								
								echo "	<th>Grievance id</th>
									<th>Grievance Subject</th>
									<th>Grievance Category</th>
									<th>Grievance Type</th>
									<th>Grievance Description</th>
									<th>File Attached</th>
									<th>Time of Issue</th>
									<th>Grievance Status</th>
									<th>Last Status Updated On</th>
									<th>Cancel Grievance</th>";
							}
						?>
					</tr>
				</thead>
				
				<tbody>
					<?php
						if(isset($_POST['toggle'])) {
							if($_POST['toggle'] == 'pending') {
								
								if(mysqli_num_rows($resp)>0){
									while($row =mysqli_fetch_assoc($resp)){
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$row['gsub']. "</td>";
										echo "<td>" .$row['gcat']. "</td>";
										echo "<td>" .$row['gtype']. "</td>";
										echo "<td>" .$row['gdes']. "</td>";
										if($row['gfile']!=NULL) {
											echo "<td> <a href = '".$row['gfile']."' target='_blank'>View File</td>";
										}
										else echo "<td>No File Attached</td>";
										echo "<td>" .$row['timeofg']. "</td>";
										echo "<td>" .$row['status']. "</td>";
										echo "<td>" .$row['uptime']. "</td>";
										echo "<td><form method='POST' action='cancel.php' onclick=\"return confirm('Are you sure?')\">
											<input type = 'hidden' name = 'uid' id='uid' value = '".$row['uemail']."'>
											<input type = 'hidden' name = 'gid' id='gid' value = '".$row['gid']."'>
											<button type='submit' class = 'btn btn-light' name='cancg' id='cancg'>Cancel Grievance</button></form></td>";
										echo "</tr>";
									}
								}
							}
							
							else if($_POST['toggle'] == 'in_progress') {
								
								if(mysqli_num_rows($resip)>0){
									while($row =mysqli_fetch_assoc($resip)){
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$row['gsub']. "</td>";
										echo "<td>" .$row['gcat']. "</td>";
										echo "<td>" .$row['gtype']. "</td>";
										echo "<td>" .$row['gdes']. "</td>";
										if($row['gfile']!=NULL) {
											echo "<td> <a href = '".$row['gfile']."' target='_blank'>View File</td>";
										}
										else echo "<td>No File Attached</td>";
										echo "<td>" .$row['timeofg']. "</td>";
										echo "<td>" .$row['status']. "</td>";
										echo "<td>" .$row['uptime']. "</td>";
										echo "</tr>";
									}
								}
							}
							
							else if($_POST['toggle'] == 'raised') {
						
								if(mysqli_num_rows($resrp)>0){
									while($row =mysqli_fetch_assoc($resrp)){
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$row['gsub']. "</td>";
										echo "<td>" .$row['gcat']. "</td>";
										echo "<td>" .$row['gtype']. "</td>";
										echo "<td>" .$row['gdes']. "</td>";
										if($row['gfile']!=NULL) {
											echo "<td> <a href = '".$row['gfile']."' target='_blank'>View File</td>";
										}
										else echo "<td>No File Attached</td>";
										echo "<td>" .$row['timeofg']. "</td>";
										echo "<td>" .$row['status']. "</td>";
										echo "<td>" .$row['uptime']. "</td>";
										echo "</tr>";
									}
								}
							}
							
							else {
						
								if(mysqli_num_rows($resr)>0){
								
									while($row =mysqli_fetch_assoc($resr)){
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$row['gsub']. "</td>";
										echo "<td>" .$row['gcat']. "</td>";
										echo "<td>" .$row['gtype']. "</td>";
										echo "<td>" .$row['gdes']. "</td>";
										if($row['gfile']!=NULL) {
											echo "<td> <a href = '".$row['gfile']."' target='_blank'>View File</td>";
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
						}
						
						else {
					
							if(mysqli_num_rows($resp)>0){
								while($row =mysqli_fetch_assoc($resp)){
									echo "<tr>";
									echo "<td>" .$row['gid']. "</td>";
									echo "<td>" .$row['gsub']. "</td>";
									echo "<td>" .$row['gcat']. "</td>";
									echo "<td>" .$row['gtype']. "</td>";
									echo "<td>" .$row['gdes']. "</td>";
									if($row['gfile']!=NULL) {
										echo "<td> <a href = '".$row['gfile']."' target='_blank'>View File</td>";
									}
									else echo "<td>No File Attached</td>";
									echo "<td>" .$row['timeofg']. "</td>";
									echo "<td>" .$row['status']. "</td>";
									echo "<td>" .$row['uptime']. "</td>";
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