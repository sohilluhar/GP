<?php include 'header.php';?>
<?php
	session_start();

	if(!isset($_SESSION['loggedInUser'])){
		header("location:index.php");
	}
	include_once("includes/connection.php");

	$name=$_SESSION['name'];
?>

<!Doctype html>
<html>
<header align="right"><?php echo $_SESSION['loggedInUser'].' | <a href="logout.php">Logout</a>'; ?></header>
<?php echo "<h4>".$name."</h4>" ?>
<br>

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
									
									$qu = "SELECT * FROM grievance where (gcat='Caste Discrimination') AND (status = 'Pending')";
									$resp = mysqli_query($conn,$qu);
									
									echo "<th>Grievance id</th>
										<th>Grievance by</th>
										<th>Grievance Subject</th>
										<th>Grievance Type</th>
										<th>Grievance Description</th>
										<th>File Attached</th>
										<th>Time of Issue</th>
										<th>Grievance Status</th>
										<th>Send mail to student</th>
										<th>Raise issue to Principal</th>";
								}
								
								else if($_POST['toggle'] == 'in_progress') {
									
									$qu = "SELECT * FROM grievance where (gcat='Caste Discrimination') AND (status = 'In Progress')";
									$resip = mysqli_query($conn,$qu);

									echo "<th>Grievance id</th>
											<th>Grievance by</th>
											<th>Grievance Subject</th>
											<th>Grievance Type</th>
											<th>Grievance Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Take Action</th>
											<th>Raise issue to Principal</th>";
								}
								
								else if($_POST['toggle'] == 'raised') {
							
									$qu = "SELECT * FROM grievance where (gcat='Caste Discrimination') AND (status='Raised to Principal')";
									$resrp = mysqli_query($conn,$qu);
									
									echo "<th>Grievance id</th>
										<th>Grievance by</th>
										<th>Grievance Subject</th>
										<th>Grievance Type</th>
										<th>Grievance Description</th>
										<th>File Attached</th>
										<th>Time of Issue</th>
										<th>Grievance Status</th>";
								}
								
								else {
							
									$qu = "SELECT * FROM resolved where (gcat='Caste Discrimination') AND (status='Action Taken')";
									$resr = mysqli_query($conn,$qu);
									
									echo "<th>Grievance id</th>
										<th>Grievance by</th>
										<th>Grievance Subject</th>
										<th>Grievance Category</th>
										<th>Grievance Type</th>
										<th>Grievance Description</th>
										<th>File Attached</th>
										<th>Time of Issue</th>
										<th>Grievance Status</th>
										<th>Action Taken</th>";
								}
							}
							
							else {
						
								$qu = "SELECT * FROM grievance where (gcat='Caste Discrimination') AND (status = 'Pending')";
								$resp = mysqli_query($conn,$qu);
								
								echo "<th>Grievance id</th>
									<th>Grievance by</th>
									<th>Grievance Subject</th>
									<th>Grievance Type</th>
									<th>Grievance Description</th>
									<th>File Attached</th>
									<th>Time of Issue</th>
									<th>Grievance Status</th>
									<th>Send mail to student</th>
									<th>Raise issue to Principal</th>";
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
										$q = "SELECT name,id FROM userdet WHERE email = '".$row['uemail']."'";
										$namerow = mysqli_fetch_assoc(mysqli_query($conn,$q));
										$name = $namerow['name'];
										$roll = $namerow['id'];
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$name. " - ".$roll."<br>(".$row['uemail'].")</td>";
										echo "<td>" .$row['gsub']. "</td>";
										echo "<td>" .$row['gtype']. "</td>";
										echo "<td>" .$row['gdes']. "</td>";
										echo "<td>" .$row['gfile']. "</td>";
										echo "<td>" .$row['timeofg']. "</td>";
										echo "<td>" .$row['status']. "</td>";
										echo "<td><form method='POST' action='acknowledge.php'>
											<input type = 'hidden' name = 'uid' id='uid' value = '".$row['uemail']."'>
											<input type = 'hidden' name = 'gid' id='gid' value = '".$row['gid']."'>
											<button type='submit' class = 'btn btn-light' name='sendm' id='sendm'>Send mail</button></form></td>";
										echo "<td><form method='POST' action='raisep.php'>
										<input type = 'hidden' name = 'gid2' id='gid2' value = '".$row['gid']."'>
										<input type = 'hidden' name = 'uid2' id='uid2' value = '".$row['uemail']."'>
										<button type='submit' class = 'btn btn-light' name='sendm' id='sendm'>Raise issue</button></form></td>";
										echo "</tr>";
									}
								}
							}
							
							else if($_POST['toggle'] == 'in_progress') {
								
								if(mysqli_num_rows($resip)>0){
									while($row =mysqli_fetch_assoc($resip)){
										$q = "SELECT name,id FROM userdet WHERE email = '".$row['uemail']."'";
										$namerow = mysqli_fetch_assoc(mysqli_query($conn,$q));
										$name = $namerow['name'];
										$roll = $namerow['id'];
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$name. " - ".$roll."<br>(".$row['uemail'].")</td>";
										echo "<td>" .$row['gsub']. "</td>";
										echo "<td>" .$row['gtype']. "</td>";
										echo "<td>" .$row['gdes']. "</td>";
										echo "<td>" .$row['gfile']. "</td>";
										echo "<td>" .$row['timeofg']. "</td>";
										echo "<td>" .$row['status']. "</td>";
										echo "<td style = 'color: #000000;'>
												  <!-- Trigger the modal with a button -->
												  <button type='button' class = 'btn btn-light' data-toggle='modal' data-target='#myModal".$row['gid']."'>Action Details</button>

												  <!-- Modal -->
												  <div class='modal' id='myModal".$row['gid']."' role='dialog'>
														<div class='modal-dialog'>
														
														  <!-- Modal content-->
														  <div class='modal-content'>
															<div class='modal-header'>
															  <h4 class='modal-title'>Enter Action Details:</h4>
															  <button type='button' class='close' data-dismiss='modal'>&times;</button>
															</div>
															<div class='modal-body'>
																<form method='POST' action='done.php'>
																	<input type = 'hidden' name = 'uid1' id='uid1' value = '".$row['uemail']."'>
																	<input type = 'hidden' name = 'gid1' id='gid1' value = '".$row['gid']."'>
																	<textarea name = 'actiondet' id = 'actiondet' placeholder = 'Enter Action Details Here!' cols = '50' required></textarea></br></br>
																	<button type = 'submit' class = 'btn btn-dark' name = 'acttaken' id = 'acttaken' value = 'ActionTaken'>Confirm</button>
																</form>
															</div>
														  </div>
														  
														</div>
												  </div></td>";
										echo "<td><form method='POST' action='raisep.php'>
										<input type = 'hidden' name = 'gid2' id='gid2' value = '".$row['gid']."'>
										<input type = 'hidden' name = 'uid2' id='uid2' value = '".$row['uemail']."'>
										<button type='submit' class = 'btn btn-light' name='sendm' id='sendm'>Raise issue</button></form></td>";
										echo "</tr>";
									}
								}
							}
							
							else if($_POST['toggle'] == 'raised') {
						
								if(mysqli_num_rows($resrp)>0){
									while($row =mysqli_fetch_assoc($resrp)){
										$q = "SELECT name,id FROM userdet WHERE email = '".$row['uemail']."'";
										$namerow = mysqli_fetch_assoc(mysqli_query($conn,$q));
										$name = $namerow['name'];
										$roll = $namerow['id'];
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$name. " - ".$roll."<br>(".$row['uemail'].")</td>";
										echo "<td>" .$row['gsub']. "</td>";
										echo "<td>" .$row['gtype']. "</td>";
										echo "<td>" .$row['gdes']. "</td>";
										echo "<td>" .$row['gfile']. "</td>";
										echo "<td>" .$row['timeofg']. "</td>";
										echo "<td>" .$row['status']. "</td>";
										echo "</tr>";
									}
								}
							}
							
							else {
						
								if(mysqli_num_rows($resr)>0){
								
									while($row =mysqli_fetch_assoc($resr)){
										$q = "SELECT name FROM userdet WHERE email = '".$row['uemail']."'";
										$namerow = mysqli_fetch_assoc(mysqli_query($conn,$q));
										$name = $namerow['name'];
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$name. "<br>(".$row['uemail'].")</td>";
										echo "<td>" .$row['gsub']. "</td>";
										echo "<td>" .$row['gcat']. "</td>";
										echo "<td>" .$row['gtype']. "</td>";
										echo "<td>" .$row['gdes']. "</td>";
										echo "<td>" .$row['gfile']. "</td>";
										echo "<td>" .$row['timeofg']. "</td>";
										echo "<td>" .$row['status']. "</td>";
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
									$q = "SELECT name,id FROM userdet WHERE email = '".$row['uemail']."'";
									$namerow = mysqli_fetch_assoc(mysqli_query($conn,$q));
									$name = $namerow['name'];
									$roll = $namerow['id'];
									echo "<tr>";
									echo "<td>" .$row['gid']. "</td>";
									echo "<td>" .$name. " - ".$roll."<br>(".$row['uemail'].")</td>";
									echo "<td>" .$row['gsub']. "</td>";
									echo "<td>" .$row['gtype']. "</td>";
									echo "<td>" .$row['gdes']. "</td>";
									echo "<td>" .$row['gfile']. "</td>";
									echo "<td>" .$row['timeofg']. "</td>";
									echo "<td>" .$row['status']. "</td>";
									echo "<td><form method='POST' action='acknowledge.php'>
										<input type = 'hidden' name = 'uid' id='uid' value = '".$row['uemail']."'>
										<input type = 'hidden' name = 'gid' id='gid' value = '".$row['gid']."'>
										<button type='submit' class = 'btn btn-light' name='sendm' id='sendm'>Send mail</button></form></td>";
									echo "<td><form method='POST' action='raisep.php'>
									<input type = 'hidden' name = 'gid2' id='gid2' value = '".$row['gid']."'>
									<input type = 'hidden' name = 'uid2' id='uid2' value = '".$row['uemail']."'>
									<button type='submit' class = 'btn btn-light' name='sendm' id='sendm'>Raise issue</button></form></td>";
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

<?php include('footer.php'); ?>