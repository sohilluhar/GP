<?php include 'header.php';?>
<?php
session_start();

$_SESSION['loggedInUser'] = 'principal.engg@somaiya.edu';

if(!isset($_SESSION['loggedInUser'])){
    header("location:index.php");
}

$_SESSION['previousp'] = basename($_SERVER['PHP_SELF']);

include_once("includes/connection.php");

?>

<!DOCTYPE HTML>

<html>
<header align="right"><?php echo $_SESSION['loggedInUser'].' | <a href="logout.php">Logout</a>'; ?></header>
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
<title>Principal's Dashboard</title>
</head>
<body>
<br>
<table class = "table table-stripped">
	<thead>
		<tr>
			<form method = "POST">
			<th style="text-align:center; width: 17%;"> <button type = 'submit' class="btn btn-info btn-block" name = 'toggle' value = 'forwards'> Forwards  </button> </th>
			<th style="text-align:center; width: 17%;"> <button type = 'submit' class="btn btn-info btn-block" name = 'toggle' value = 'resolved'> Resolved </button> </th>
			<th style="text-align:center; width: 17%;"> <button type = 'submit' class="btn btn-info btn-block" name = 'toggle' value = 'offline'> Offline Reports </button> </th>
			<th style="text-align:center; width: 17%;"> <button type = 'submit' class="btn btn-info btn-block" name = 'toggle' value = 'cancelled'> Cancelled </button> </th>
			</form>
			<th style="text-align:center;"> </th>
			<?php
				if(isset($_SESSION['rollfilter'])) {
					echo '<form method = "POST" action = "filtersummary.php">
					<th style="text-align:right; width: 17%;"> <button type = "submit" class="btn btn-block btn-danger" name = "clearfilter" value = "clearfilter"> Remove Roll Number Filter </button> </th>
					</form>';
				}
				else {
					echo '<form method = "POST" action = "filtersummary.php">
					<th style="text-align:right; width: 17%;"> <button type = "submit" class="btn btn-block btn-success" name = "rollsearch" value = "rollsearch"> Search/Summary  </button> </th>
					</form>';
				}
			?>
		</tr>
	</thead>
	
	
	<tbody>
		<tr><td colspan = '6'>
			<div align = "center">
				<?php
					if(isset($_POST['toggle'])) {
						if($_POST['toggle'] == 'forwards') {
							$qu = "SELECT * FROM grievance where status = 'Forwarded to Principal'";
							if(isset($_SESSION['rollfilter']))
								$qu = "SELECT * FROM grievance where status = 'Forwarded to Principal' AND uemail = '".$_SESSION['rollfilter']."'";
							$res_forwards = mysqli_query($conn,$qu);
							echo "<h4> Forwards </h4>";
						}
						
						else if($_POST['toggle'] == 'resolved') {
							$qu = "SELECT * FROM resolved where status = 'Resolved'";
							if(isset($_SESSION['rollfilter']))
								$qu = "SELECT * FROM resolved where status = 'Resolved' AND uemail = '".$_SESSION['rollfilter']."'";
							$res_resolved = mysqli_query($conn,$qu);
							echo "<h4> Resolved </h4>";
						}
						
						else if($_POST['toggle'] == 'offline') {
							$qu = "SELECT * FROM resolved where status = 'Resolved Offline'";
							if(isset($_SESSION['rollfilter']))
								$qu = "SELECT * FROM resolved where status = 'Resolved Offline' AND uemail = '".$_SESSION['rollfilter']."'";
							$res_offline = mysqli_query($conn,$qu);
							echo "<h4> Offline </h4>";
						}
						
						else {
							$qu = "SELECT * FROM cancelled";
							if(isset($_SESSION['rollfilter']))
								$qu = "SELECT * FROM cancelled where uemail = '".$_SESSION['rollfilter']."'";
							$res_cancel = mysqli_query($conn,$qu);
							echo "<h4> Cancelled </h4>";
						}
					}
					else {
						$qu = "SELECT * FROM grievance where status = 'Forwarded to Principal'";
						if(isset($_SESSION['rollfilter']))
							$qu = "SELECT * FROM grievance where status = 'Forwarded to Principal' AND uemail = '".$_SESSION['rollfilter']."'";
						$res_forwards = mysqli_query($conn,$qu);
						echo "<h4> Forwards </h4>";
					}
				?>
			</div>
			<table class = "table table-striped">
				<thead class = "thead-dark">
					<tr>
						<?php
							if(isset($_POST['toggle'])) {
								if($_POST['toggle'] == 'forwards') {
									echo "<th>Grievance ID</th>
											<th>Grievance by</th>
											<th>Grievance Subject</th>
											<th>Grievance Catefory - Type</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Foward Details</th>
											<th>Last Status Update On</th>
											<th>Add Action Details</th>";
								}
								
								else if($_POST['toggle'] == 'resolved') {
									echo "<th>Grievance ID</th>
											<th>Grievance by</th>
											<th>Grievance Subject</th>
											<th>Grievance Catefory - Type</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>
											<th>Action Details</th>";
								}
								
								else if($_POST['toggle'] == 'offline') {
									echo "<th>Grievance ID</th>
											<th>Grievance by</th>
											<th>Grievance Subject</th>
											<th>Grievance Catefory - Type</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>
											<th>Action Details</th>";
								}
								
								else {
									echo "<th>Grievance ID</th>
											<th>Grievance by</th>
											<th>Grievance Subject</th>
											<th>Grievance Catefory - Type</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>";
								}
							}
							
							else {
								echo "<th>Grievance ID</th>
										<th>Grievance by</th>
										<th>Grievance Subject</th>
										<th>Grievance Catefory - Type</th>
										<th>Description</th>
										<th>File Attached</th>
										<th>Time of Issue</th>
										<th>Grievance Status</th>
										<th>Foward Details</th>
										<th>Last Status Update On</th>
										<th>Add Action Details</th>";
							}
						?>
					</tr>
				</thead>
				
				<tbody>
				
					<?php
						if(isset($_POST['toggle'])) {
							if($_POST['toggle'] == 'forwards') {
						
								echo "<tr style = 'border: 4px solid black;'><td colspan = '11' style = 'text-align: center; background-color: white; color: black;'> ".mysqli_num_rows($res_forwards)." Grievances </td></tr>";
								
								if(mysqli_num_rows($res_forwards)>0){
									while($row =mysqli_fetch_assoc($res_forwards)){
										$q = "SELECT name,id FROM userdet WHERE email = '".$row['uemail']."'";
										$namerow = mysqli_fetch_assoc(mysqli_query($conn,$q));
										$name = $namerow['name'];
										$roll = $namerow['id'];
										
										$q = "SELECT name FROM commdet WHERE category = '".$row['gcat']."'";
										$respcommrow = mysqli_fetch_assoc(mysqli_query($conn,$q));
										$respcomm = $respcommrow['name'];
										
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$name. "<br>";
										echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails".$row['gid']."'> View Details </button>
											<div style = 'color: #000000;' class='modal' id='studentdetails".$row['gid']."' role='dialog'>
												<div class='modal-dialog'>
												
												  <!-- Modal content-->
												  <div class='modal-content'>
													<div class='modal-header'>
													  <h4 class='modal-title'>Mention the reason for forwarding:</h4>
													  <button type='button' class='close' data-dismiss='modal'>&times;</button>
													</div>
													<div class='modal-body'>
														
													</div>
												  </div>
												  
												</div>
										  </div>
										</td>";
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
															  <h4 class='modal-title'>Enter Action Details:</h4>
															  <button type='button' class='close' data-dismiss='modal'>&times;</button>
															</div>
															<div class='modal-body'>";
																
															if($row['act']) {	
																echo "<h4> Previous actions on the grievance: </h4>
																<p> ".$row['act']." </p>";
															}
																
											echo "<h4> Add action details: </h4>
																<form method='POST' action='done.php' class='form-horizontal'>
																	<div class='form-group'>
																		<div class='col-sm-12'>
																			<input type = 'hidden' name = 'uid1' id='uid1' value = '".$row['uemail']."'>
																			<input type = 'hidden' name = 'gid1' id='gid1' value = '".$row['gid']."'>
																			<textarea name = 'actiondet' id = 'actiondet' class = 'form-control' placeholder = 'Enter Action Details Here!' cols = '50' required></textarea></br>
																		</div>
																		<div class = 'col-sm-12'>
																			<button type = 'submit' class = 'btn btn-dark btn-block' name = 'returncomm' id = 'returncomm' value = 'returnComm'>Return to $respcomm</button>
																		</div>
																	</div>
																</form>
															</div>
														  </div>
														  
														</div>
												  </div></td>";
										echo "</tr>";
									}
								}
							}
							
							else if($_POST['toggle'] == 'resolved') {
								
								echo "<tr style = 'border: 4px solid black;'><td colspan = '10' style = 'text-align: center; background-color: white; color: black;'> ".mysqli_num_rows($res_resolved)." Grievances </td></tr>";
								
								if(mysqli_num_rows($res_resolved)>0){
									while($row =mysqli_fetch_assoc($res_resolved)){
										$q = "SELECT name,id FROM userdet WHERE email = '".$row['uemail']."'";
										$namerow = mysqli_fetch_assoc(mysqli_query($conn,$q));
										$name = $namerow['name'];
										$roll = $namerow['id'];
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$name. "<br>";
										echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails".$row['gid']."'> View Details </button>
											<div style = 'color: #000000;' class='modal' id='studentdetails".$row['gid']."' role='dialog'>
												<div class='modal-dialog'>
												
												  <!-- Modal content-->
												  <div class='modal-content'>
													<div class='modal-header'>
													  <h4 class='modal-title'>Mention the reason for forwarding:</h4>
													  <button type='button' class='close' data-dismiss='modal'>&times;</button>
													</div>
													<div class='modal-body'>
														
													</div>
												  </div>
												  
												</div>
										  </div>
										</td>";
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
																	<button type='submit' class = 'btn btn-dark' class='print'>Download Grievance Report as a pdf</button></form>	
																
															</div>
														  </div>
														  
														</div>
												  </div></td>";
										echo "</tr>";
									}
								}
							}
							
							else if($_POST['toggle'] == 'offline') {
								
								echo "<tr style = 'border: 4px solid black;'><td colspan = '10' style = 'text-align: center; background-color: white; color: black;'> ".mysqli_num_rows($res_offline)." Grievances </td></tr>";
								
								if(mysqli_num_rows($res_offline)>0){
									while($row =mysqli_fetch_assoc($res_offline)){
										$q = "SELECT name,id FROM userdet WHERE email = '".$row['uemail']."'";
										$namerow = mysqli_fetch_assoc(mysqli_query($conn,$q));
										$name = $namerow['name'];
										$roll = $namerow['id'];
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$name. "<br>";
										echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails".$row['gid']."'> View Details </button>
											<div style = 'color: #000000;' class='modal' id='studentdetails".$row['gid']."' role='dialog'>
												<div class='modal-dialog'>
												
												  <!-- Modal content-->
												  <div class='modal-content'>
													<div class='modal-header'>
													  <h4 class='modal-title'>Mention the reason for forwarding:</h4>
													  <button type='button' class='close' data-dismiss='modal'>&times;</button>
													</div>
													<div class='modal-body'>
														
													</div>
												  </div>
												  
												</div>
										  </div>
										</td>";
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
																	<button type='submit' class = 'btn btn-dark' class='print'>Download Grievance Report as a pdf</button></form>	
																
															</div>
														  </div>
														  
														</div>
												  </div></td>";
										echo "</tr>";
									}
								}
							}
							
							else {
						
								echo "<tr style = 'border: 4px solid black;'><td colspan = '9' style = 'text-align: center; background-color: white; color: black;'> ".mysqli_num_rows($res_cancel)." Grievances </td></tr>";
								
								if(mysqli_num_rows($res_cancel)>0){
									while($row =mysqli_fetch_assoc($res_cancel)){
										$q = "SELECT name,id FROM userdet WHERE email = '".$row['uemail']."'";
										$namerow = mysqli_fetch_assoc(mysqli_query($conn,$q));
										$name = $namerow['name'];
										$roll = $namerow['id'];
										echo "<tr>";
										echo "<td>" .$row['gid']. "</td>";
										echo "<td>" .$name. "<br>";
										echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails".$row['gid']."'> View Details </button>
											<div style = 'color: #000000;' class='modal' id='studentdetails".$row['gid']."' role='dialog'>
												<div class='modal-dialog'>
												
												  <!-- Modal content-->
												  <div class='modal-content'>
													<div class='modal-header'>
													  <h4 class='modal-title'>Mention the reason for forwarding:</h4>
													  <button type='button' class='close' data-dismiss='modal'>&times;</button>
													</div>
													<div class='modal-body'>
														
													</div>
												  </div>
												  
												</div>
										  </div>
										</td>";
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
							
							echo "<tr style = 'border: 4px solid black;'><td colspan = '11' style = 'text-align: center; background-color: white; color: black;'> ".mysqli_num_rows($res_forwards)." Grievances </td></tr>";
							
							if(mysqli_num_rows($res_forwards)>0){
								while($row =mysqli_fetch_assoc($res_forwards)){
									$q = "SELECT name,id FROM userdet WHERE email = '".$row['uemail']."'";
									$namerow = mysqli_fetch_assoc(mysqli_query($conn,$q));
									$name = $namerow['name'];
									$roll = $namerow['id'];
									
									$q = "SELECT name FROM commdet WHERE category = '".$row['gcat']."'";
									$respcommrow = mysqli_fetch_assoc(mysqli_query($conn,$q));
									$respcomm = $respcommrow['name'];
									
									echo "<tr>";
									echo "<td>" .$row['gid']. "</td>";
									echo "<td>" .$name. "<br>";
									echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails".$row['gid']."'> View Details </button>
										<div style = 'color: #000000;' class='modal' id='studentdetails".$row['gid']."' role='dialog'>
											<div class='modal-dialog'>
											
											  <!-- Modal content-->
											  <div class='modal-content'>
												<div class='modal-header'>
												  <h4 class='modal-title'>Mention the reason for forwarding:</h4>
												  <button type='button' class='close' data-dismiss='modal'>&times;</button>
												</div>
												<div class='modal-body'>
													
												</div>
											  </div>
											  
											</div>
									  </div>
									</td>";
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
														  <h4 class='modal-title'>Enter Action Details:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>";
															
														if($row['act']) {	
															echo "<h4> Previous actions on the grievance: </h4>
															<p> ".$row['act']." </p>";
														}
															
										echo "<h4> Add action details: </h4>
															<form method='POST' action='done.php' class='form-horizontal'>
																<div class='form-group'>
																	<div class='col-sm-12'>
																		<input type = 'hidden' name = 'uid1' id='uid1' value = '".$row['uemail']."'>
																		<input type = 'hidden' name = 'gid1' id='gid1' value = '".$row['gid']."'>
																		<textarea name = 'actiondet' id = 'actiondet' class = 'form-control' placeholder = 'Enter Action Details Here!' cols = '50' required></textarea></br>
																	</div>
																	<div class = 'col-sm-12'>
																		<button type = 'submit' class = 'btn btn-dark btn-block' name = 'returncomm' id = 'returncomm' value = 'returnComm'>Return to $respcomm</button>
																	</div>
																</div>
															</form>
														</div>
													  </div>
													  
													</div>
											  </div></td>";
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
</body>
</html>
<?php include('footer.php'); ?>