<?php include 'header.php'; ?>
<?php
session_start();

include_once("includes/connection.php");

if (!isset($_SESSION['loggedInUser'])) {
    header("location:index.php");
} else {
    $q = "SELECT category FROM catdet WHERE comm_name = '" . $_SESSION['name'] . "'";
    $catresult = mysqli_query($conn, $q);
    $i = 0;
    while ($catrow = mysqli_fetch_assoc($catresult)) {
        $category[$i] = $catrow['category'];
        $i++;
    }
}

$name = $_SESSION['name'];
$cond = "";

$q = "SELECT name FROM commdet";
$commnames = mysqli_query($conn, $q);
?>

    <!Doctype html>
    <html>
    <header align="right"><?php echo $_SESSION['loggedInUser'] . ' | <a href="logout.php">Logout</a>'; ?></header>
    <?php echo "<h4>" . $name . "</h4>" ?>

    <div class="col-sm-2" style="float:right;">
        <form method="POST" action="offlinereport.php">
            <button type='submit' class="btn btn-success btn-block" name='offline_report' value='offline_report'> Manual
                Entry
            </button>
        </form>
    </div>

    <?php
    if ($_SESSION['loggedInUser'] == "principal.engg@somaiya.edu")
        echo "<div class = 'col-sm-3' style = 'float:left;'>
			<form method = 'POST' action = 'princi.php'>
				<button type = 'submit' class='btn btn-success btn-block' name = 'princi' value = 'princi'> Principal Dashboard </button>
			</form>
		</div>";
    ?>

    <?php
    if (isset($_SESSION['filtercondc'])) {
        $cond = $_SESSION['filtercondc'];
        echo '<form method = "POST" action = "commsummary.php" target = "_blank">
		<div style="float: right;" class = "col-sm-2"> <button type = "submit" class="btn btn-block btn-success" name = "generatesum" value = "generatesum"> Generate Summary </button> </div>
		</form>';
        echo '<form method = "POST" action = "filtersummarycomm.php">
		<div style="float: right;" class = "col-sm-2"> <button type = "submit" class="btn btn-block btn-success" name = "changefilters" value = "changefilters"> Change Filters </button> </div>
		<div style="float: right;" class = "col-sm-2"> <button type = "submit" class="btn btn-block btn-danger" name = "clearfilters" value = "clearfilters"> Remove Filters </button> </div>
		</form>';
    } else {
        echo '<form method = "POST" action = "commsummary.php" target = "_blank">
		<div style="float: right;" class = "col-sm-2"> <button type = "submit" class="btn btn-block btn-success" name = "generatesum" value = "generatesum"> Generate Summary </button> </div>
		</form>';
        echo '<form method = "POST" action = "filtersummarycomm.php">
				<div style="float: right;" class = "col-sm-2"> <button type = "submit" class="btn btn-block btn-success" name = "rollsearch" value = "rollsearch"> Filter Grievances </button> </div>
			</form>';
    }
    ?>

    <table class="table table-stripped">
        <thead>
        <tr>
            <form method="POST">
                <th style="text-align:center; width: 12.5%;">
                    <button type='submit' class="btn btn-info btn-block" name='toggle' value='pending'> Pending</button>
                </th>
                <th style="text-align:center; width: 12.5%;">
                    <button type='submit' class="btn btn-info btn-block" name='toggle' value='in_progress'> In
                        Progress
                    </button>
                </th>
                <th style="text-align:center; width: 12.5%;">
                    <button type='submit' class="btn btn-info btn-block" name='toggle' value='forwards'> Forwards
                    </button>
                </th>
                <th style="text-align:center; width: 12.5%;">
                    <button type='submit' class="btn btn-info btn-block" name='toggle' value='forwarded'> Forwarded
                    </button>
                </th>
                <th style="text-align:center; width: 12.5%;">
                    <button type='submit' class="btn btn-info btn-block" name='toggle' value='partially_solved'>
                        Partially Solved
                    </button>
                </th>
                <th style="text-align:center; width: 12.5%;">
                    <button type='submit' class="btn btn-info btn-block" name='toggle' value='resolved'> Resolved
                    </button>
                </th>
                <th style="text-align:center; width: 12.5%;">
                    <button type='submit' class="btn btn-info btn-block" name='toggle' value='offline'> Offline
                        Reports
                    </button>
                </th>
                <th style="text-align:center; width: 12.5%;">
                    <button type='submit' class="btn btn-info btn-block" name='toggle' value='cancelled'> Cancelled
                    </button>
                </th>
            </form>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td colspan='8'>
                <div align="center">
                    <?php
                    if (isset($_POST['toggle'])) {
                        if ($_POST['toggle'] == 'pending') {
                            $qu = "SELECT * FROM grievance where (0";
                            for ($i = 0; $i < count($category); $i++)
                                $qu .= " OR gcat='$category[$i]'";
                            $qu .= ") AND (status = 'Pending')" . $cond;
                            $res_pending = mysqli_query($conn, $qu);
                            echo "<h4> Pending </h4>";
                        } else if ($_POST['toggle'] == 'in_progress') {
                            $qu = "SELECT * FROM grievance where (0";
                            for ($i = 0; $i < count($category); $i++)
                                $qu .= " OR gcat='$category[$i]'";
                            $qu .= ") AND (status = 'In Progress')" . $cond;
                            $res_inprogress = mysqli_query($conn, $qu);
                            echo "<h4> In Progress </h4>";
                        } else if ($_POST['toggle'] == 'forwards') {
                            $qu = "SELECT * FROM grievance where status='Forwarded to " . $name . "'" . $cond;
                            $res_forwards = mysqli_query($conn, $qu);
                            echo "<h4> Forwards </h4>";
                        } else if ($_POST['toggle'] == 'forwarded') {
                            $qu = "SELECT * FROM grievance where (0";
                            for ($i = 0; $i < count($category); $i++)
                                $qu .= " OR gcat='$category[$i]'";
                            $qu .= ") AND (status REGEXP 'Forwarded to')" . $cond;
                            $res_forwarded = mysqli_query($conn, $qu);
                            echo "<h4> Forwarded </h4>";
                        } else if ($_POST['toggle'] == 'partially_solved') {
                            $qu = "SELECT * FROM grievance where (0";
                            for ($i = 0; $i < count($category); $i++)
                                $qu .= " OR gcat='$category[$i]'";
                            $qu .= ") AND (status = 'Partially Solved')" . $cond;
                            $res_partsolved = mysqli_query($conn, $qu);
                            echo "<h4> Partially Solved </h4>";
                        } else if ($_POST['toggle'] == 'resolved') {
                            $qu = "SELECT * FROM resolved where (0";
                            for ($i = 0; $i < count($category); $i++)
                                $qu .= " OR gcat='$category[$i]'";
                            $qu .= ") AND (status='Resolved')" . $cond;
                            $res_resolved = mysqli_query($conn, $qu);
                            echo "<h4> Resolved </h4>";
                        } else if ($_POST['toggle'] == 'offline') {
                            $qu = "SELECT * FROM resolved where (0";
                            for ($i = 0; $i < count($category); $i++)
                                $qu .= " OR gcat='$category[$i]'";
                            $qu .= ") AND (status='Resolved Offline')" . $cond;
                            $res_offline = mysqli_query($conn, $qu);
                            echo "<h4> Offline Reports </h4>";
                        } else {
                            $qu = "SELECT * FROM cancelled where (0";
                            for ($i = 0; $i < count($category); $i++)
                                $qu .= " OR gcat='$category[$i]'";
                            $qu .= ")" . $cond;
                            $res_cancel = mysqli_query($conn, $qu);
                            echo "<h4> Cancelled </h4>";
                        }
                    } else {
                        $qu = "SELECT * FROM grievance where (0";
                        for ($i = 0; $i < count($category); $i++)
                            $qu .= " OR gcat='$category[$i]'";
                        $qu .= ") AND (status = 'Pending')" . $cond;
                        $res_pending = mysqli_query($conn, $qu);
                        echo "<h4> Pending </h4>";
                    }
                    ?>
                </div>
                <table class="table table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <?php
                        if (isset($_POST['toggle'])) {
                            if ($_POST['toggle'] == 'pending') {
                                echo "<th>Grievance ID</th>
										<th>Grievance by</th>
										<th>Grievance Subject</th>
										<th>Grievance Category - Type</th>
										<th>Description</th>
										<th>File Attached</th>
										<th>Time of Issue</th>
										<th>Grievance Status</th>
										<th>Acknowledge Issue</th>
										<th>Forward Issue</th>";
                            } else if ($_POST['toggle'] == 'in_progress') {
                                echo "<th>Grievance ID</th>
											<th>Grievance by</th>
											<th>Grievance Subject</th>
											<th>Grievance Category - Type</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>
											<th>Add Action Details</th>
											<th>Forward Issue</th>";
                            } else if ($_POST['toggle'] == 'forwards') {
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
                            } else if ($_POST['toggle'] == 'forwarded') {
                                echo "<th>Grievance ID</th>
											<th>Grievance by</th>
											<th>Grievance Subject</th>
											<th>Grievance Category - Type</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Foward Details</th>
											<th>Last Status Update On</th>
											<th>Add Action Details</th>";
                            } else if ($_POST['toggle'] == 'partially_solved') {
                                echo "<th>Grievance ID</th>
											<th>Grievance by</th>
											<th>Grievance Subject</th>
											<th>Grievance Category - Type</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>
											<th>Add Action Details</th>
											<th>Forward Issue</th>";
                            } else if ($_POST['toggle'] == 'resolved') {
                                echo "<th>Grievance ID</th>
											<th>Grievance by</th>
											<th>Grievance Subject</th>
											<th>Grievance Category - Type</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>
											<th>Action Details</th>";
                            } else if ($_POST['toggle'] == 'offline') {
                                echo "<th>Grievance ID</th>
											<th>Grievance by</th>
											<th>Grievance Subject</th>
											<th>Grievance Category - Type</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>
											<th>Action Details</th>";
                            } else {
                                echo "<th>Grievance ID</th>
											<th>Grievance by</th>
											<th>Grievance Subject</th>
											<th>Grievance Category - Type</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>";
                            }
                        } else {
                            echo "<th>Grievance ID</th>
									<th>Grievance by</th>
									<th>Grievance Subject</th>
									<th>Grievance Category - Type</th>
									<th>Description</th>
									<th>File Attached</th>
									<th>Time of Issue</th>
									<th>Grievance Status</th>
									<th>Acknowledge Issue</th>
									<th>Forward Issue</th>";
                        }
                        ?>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    if (isset($_POST['toggle'])) {
                        if ($_POST['toggle'] == 'pending') {

                            echo "<tr style = 'border: 4px solid black;'><td colspan = '10' style = 'text-align: center; background-color: white; color: black;'> " . mysqli_num_rows($res_pending) . " Grievances </td></tr>";

                            if (mysqli_num_rows($res_pending) > 0) {
                                while ($row = mysqli_fetch_assoc($res_pending)) {
                                    if ($row['urole'] == 'Student')
                                        $q = "SELECT * FROM userdet WHERE email = '" . $row['uemail'] . "'";
                                    elseif ($row['urole'] == 'Employee')
                                        $q = "SELECT * FROM facdet WHERE email = '" . $row['uemail'] . "'";
                                    $namerow = mysqli_fetch_assoc(mysqli_query($conn, $q));
                                    $name = "";
                                    if ($namerow['lname'] != "")
                                        $name .= $namerow['lname'] . " ";
                                    if ($namerow['fname'] != "")
                                        $name .= $namerow['fname'] . " ";
                                    if ($namerow['fathername'] != "")
                                        $name .= $namerow['fathername'] . " ";
                                    if ($namerow['mothername'] != "")
                                        $name .= $namerow['mothername'] . " ";
                                    $roll = $namerow['id'];
                                    if ($row['urole'] == 'Student')
                                        $class = $namerow['class'];
                                    elseif ($row['urole'] == 'Employee')
                                        $designation = $namerow['designation'];
                                    $dept = $namerow['dept'];
                                    if ($row['urole'] == 'Student')
                                        $joinyear = $namerow['joinyear'];
                                    elseif ($row['urole'] == 'Employee')
                                        $joindate = $namerow['joindate'];
                                    echo "<tr>";
                                    echo "<td>" . $row['gid'] . "</td>";
                                    echo "<td>" . $namerow['fname'] . " " . $namerow['lname'] . "<br>";
                                    if ($row['urole'] == 'Student') {
                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Grievance By:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															Name: " . $name . "<br>
															Role: Student<br>
															Email ID: " . $row['uemail'] . "<br>
															Roll No.: " . $roll . "<br>
															Class: " . $class . "<br>
															Department: " . $dept . "<br>
															Year of Joining: " . $joinyear . "<br>
														</div>
													  </div>
													  
													</div>
											  </div>
											</td>";
                                    } elseif ($row['urole'] == 'Employee') {
                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Grievance By:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															Name: " . $name . "<br>
															Role: Employee<br>
															Email ID: " . $row['uemail'] . "<br>
															ID No.: " . $roll . "<br>
															Department: " . $dept . "<br>
															Designation: " . $designation . "<br>
															Date of Joining: " . $joindate . "<br>
														</div>
													  </div>
													  
													</div>
											  </div>
											</td>";
                                    }

                                    echo "<td>" . $row['gsub'] . "</td>";
                                    echo "<td>" . $row['gcat'] . "</td>";
                                    echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'> Show/Hide </button>
											<div class='collapse' id='gdesc" . $row['gid'] . "'>
														<p> " . $row['gdes'] . "</p>
											</div>
											</td>";
                                    if ($row['gfile'] != NULL) {
                                        echo "<td> <form action = '" . $row['gfile'] . "' target='_blank' method = 'POST'><button type='submit' style = 'color: #4be1e1;' class='btn btn-link'>View File</button></form> </td>";
                                    } else echo "<td>No File Attached</td>";
                                    echo "<td>" . $row['timeofg'] . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td><form method='POST' action='acknowledge.php' onclick=\"return confirm('Are you sure?')\">
											<input type = 'hidden' name = 'uid' id='uid' value = '" . $row['uemail'] . "'>
											<input type = 'hidden' name = 'gid' id='gid' value = '" . $row['gid'] . "'>
											<button type='submit' class = 'btn btn-light' name='sendm' id='sendm'> Acknowledge </button></form></td>";
                                    echo "<td style = 'color: #000000;'><button type = 'button' class = 'btn btn-light' data-toggle = 'modal' data-target = '#forwardModal" . $row['gid'] . "'> Forward </button>";
                                    echo "<div class='modal' id='forwardModal" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Mention the reason for forwarding:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															<form method='POST' action='raisep.php' class='form-horizontal'>
																<div class='form-group'>
																	<div class='col-sm-12'>
																		<input type = 'hidden' name = 'uid1' id='uid1' value = '" . $row['uemail'] . "'>
																		<input type = 'hidden' name = 'gid1' id='gid1' value = '" . $row['gid'] . "'>
																		<textarea maxlength = '255' class = 'form-control' name = 'forwarddet' id = 'forwarddet' placeholder = 'Mention reason for forwarding along with other required details!' cols = '50' required></textarea></br>
																	    <b>Forward to:</b> <select name = 'forwardto' id = 'forwardto' class='form-control' required style = 'height: 5%;'>
																		<option value = ''> Select Committee </option>";

                                    mysqli_data_seek($commnames, 0);
                                    if (mysqli_num_rows($commnames) > 0) {
                                        while ($row = mysqli_fetch_assoc($commnames)) {
                                            echo "<option value = '" . $row['name'] . "'> " . $row['name'] . " </option>";
                                        }
                                    }

                                    echo "</select> </br></br><button type = 'submit' class = 'btn btn-dark btn-block' name = 'forwardissue' id = 'forwardissue' value = 'forwardIssue'  onclick=\"return confirm('Are you sure?')\"> Forward Issue </button>
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
                        } else if ($_POST['toggle'] == 'in_progress') {

                            echo "<tr style = 'border: 4px solid black;'><td colspan = '11' style = 'text-align: center; background-color: white; color: black;'> " . mysqli_num_rows($res_inprogress) . " Grievances </td></tr>";

                            if (mysqli_num_rows($res_inprogress) > 0) {
                                while ($row = mysqli_fetch_assoc($res_inprogress)) {
                                    if ($row['urole'] == 'Student')
                                        $q = "SELECT * FROM userdet WHERE email = '" . $row['uemail'] . "'";
                                    elseif ($row['urole'] == 'Employee')
                                        $q = "SELECT * FROM facdet WHERE email = '" . $row['uemail'] . "'";
                                    $namerow = mysqli_fetch_assoc(mysqli_query($conn, $q));
                                    $name = "";
                                    if ($namerow['lname'] != "")
                                        $name .= $namerow['lname'] . " ";
                                    if ($namerow['fname'] != "")
                                        $name .= $namerow['fname'] . " ";
                                    if ($namerow['fathername'] != "")
                                        $name .= $namerow['fathername'] . " ";
                                    if ($namerow['mothername'] != "")
                                        $name .= $namerow['mothername'] . " ";
                                    $roll = $namerow['id'];
                                    if ($row['urole'] == 'Student')
                                        $class = $namerow['class'];
                                    elseif ($row['urole'] == 'Employee')
                                        $designation = $namerow['designation'];
                                    $dept = $namerow['dept'];
                                    if ($row['urole'] == 'Student')
                                        $joinyear = $namerow['joinyear'];
                                    elseif ($row['urole'] == 'Employee')
                                        $joindate = $namerow['joindate'];
                                    echo "<tr>";
                                    echo "<td>" . $row['gid'] . "</td>";
                                    echo "<td>" . $namerow['fname'] . " " . $namerow['lname'] . "<br>";
                                    if ($row['urole'] == 'Student') {
                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Grievance By:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															Name: " . $name . "<br>
															Role: Student<br>
															Email ID: " . $row['uemail'] . "<br>
															Roll No.: " . $roll . "<br>
															Class: " . $class . "<br>
															Department: " . $dept . "<br>
															Year of Joining: " . $joinyear . "<br>
														</div>
													  </div>
													  
													</div>
											  </div>
											</td>";
                                    } elseif ($row['urole'] == 'Employee') {
                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Grievance By:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															Name: " . $name . "<br>
															Role: Employee<br>
															Email ID: " . $row['uemail'] . "<br>
															ID No.: " . $roll . "<br>
															Department: " . $dept . "<br>
															Designation: " . $designation . "<br>
															Date of Joining: " . $joindate . "<br>
														</div>
													  </div>
													  
													</div>
											  </div>
											</td>";
                                    }

                                    echo "<td>" . $row['gsub'] . "</td>";
                                    echo "<td>" . $row['gcat'] . "</td>";
                                    echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'> Show/Hide </button>
											<div class='collapse' id='gdesc" . $row['gid'] . "'>
														<p> " . $row['gdes'] . "</p>
											</div>
											</td>";
                                    if ($row['gfile'] != NULL) {
                                        echo "<td>  <form action = '" . $row['gfile'] . "' target='_blank' method = 'POST'><button type='submit' style = 'color: #4be1e1;' class='btn btn-link'>View File</button></form> </td>";
                                    } else echo "<td>No File Attached</td>";
                                    echo "<td>" . $row['timeofg'] . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td>" . $row['uptime'] . "</td>";
                                    echo "<td style = 'color: #000000;'>
												  <!-- Trigger the modal with a button -->
												  <button type='button' class = 'btn btn-light' data-toggle='modal' data-target='#myModal" . $row['gid'] . "'>Action Details</button>

												  <!-- Modal -->
												  <div class='modal' id='myModal" . $row['gid'] . "' role='dialog'>
														<div class='modal-dialog'>
														
														  <!-- Modal content-->
														  <div class='modal-content'>
															<div class='modal-header'>
															  <h4 class='modal-title'>Enter Action Details:</h4>
															  <button type='button' class='close' data-dismiss='modal'>&times;</button>
															</div>
															<div class='modal-body'>
																<form method='POST' action='done.php' class='form-horizontal'>
																	<div class='form-group'>
																		<div class='col-sm-12'>
																			<input type = 'hidden' name = 'uid1' id='uid1' value = '" . $row['uemail'] . "'>
																			<input type = 'hidden' name = 'gid1' id='gid1' value = '" . $row['gid'] . "'>
																			<textarea maxlength = '255' name = 'actiondet' id = 'actiondet' class = 'form-control' placeholder = 'Enter Action Details Here!' cols = '50' required></textarea></br>
																		</div>
																		<div class = 'col-sm-6'>
																			<button type = 'submit' class = 'btn btn-dark btn-block' name = 'partact' id = 'partact' value = 'PartialAction'>Partially Solved</button>
																		</div>
																		<div class = 'col-sm-6'>
																			<button type = 'submit' class = 'btn btn-dark btn-block' name = 'acttaken' id = 'acttaken' value = 'ActionTaken'>Resolved</button>
																		</div>
																	</div>
																</form>
															</div>
														  </div>
														  
														</div>
												  </div></td>";
                                    echo "<td style = 'color: #000000;'><button type = 'button' class = 'btn btn-light' data-toggle = 'modal' data-target = '#forwardModal" . $row['gid'] . "'> Forward </button>";
                                    echo "<div class='modal' id='forwardModal" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Mention the reason for forwarding:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															<form method='POST' action='raisep.php' class='form-horizontal'>
																<div class='form-group'>
																	<div class='col-sm-12'>
																		<input type = 'hidden' name = 'uid1' id='uid1' value = '" . $row['uemail'] . "'>
																		<input type = 'hidden' name = 'gid1' id='gid1' value = '" . $row['gid'] . "'>
																		<textarea  maxlength = '255' class = 'form-control' name = 'forwarddet' id = 'forwarddet' placeholder = 'Mention reason for forwarding along with other required details!' cols = '50' required></textarea></br>
																	    <b>Forward to:</b> <select name = 'forwardto' id = 'forwardto' class='form-control' required style = 'height: 5%;'>
																		<option value = ''> Select Committee </option>";

                                    mysqli_data_seek($commnames, 0);
                                    if (mysqli_num_rows($commnames) > 0) {
                                        while ($row = mysqli_fetch_assoc($commnames)) {
                                            echo "<option value = '" . $row['name'] . "'> " . $row['name'] . " </option>";
                                        }
                                    }

                                    echo "</select> </br></br><button type = 'submit' class = 'btn btn-dark btn-block' name = 'forwardissue' id = 'forwardissue' value = 'forwardIssue'  onclick=\"return confirm('Are you sure?')\"> Forward Issue </button>
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
                        } else if ($_POST['toggle'] == 'forwards') {

                            echo "<tr style = 'border: 4px solid black;'><td colspan = '11' style = 'text-align: center; background-color: white; color: black;'> " . mysqli_num_rows($res_forwards) . " Grievances </td></tr>";

                            if (mysqli_num_rows($res_forwards) > 0) {
                                while ($row = mysqli_fetch_assoc($res_forwards)) {
                                    if ($row['urole'] == 'Student')
                                        $q = "SELECT * FROM userdet WHERE email = '" . $row['uemail'] . "'";
                                    elseif ($row['urole'] == 'Employee')
                                        $q = "SELECT * FROM facdet WHERE email = '" . $row['uemail'] . "'";
                                    $namerow = mysqli_fetch_assoc(mysqli_query($conn, $q));
                                    $name = "";
                                    if ($namerow['lname'] != "")
                                        $name .= $namerow['lname'] . " ";
                                    if ($namerow['fname'] != "")
                                        $name .= $namerow['fname'] . " ";
                                    if ($namerow['fathername'] != "")
                                        $name .= $namerow['fathername'] . " ";
                                    if ($namerow['mothername'] != "")
                                        $name .= $namerow['mothername'] . " ";
                                    $roll = $namerow['id'];
                                    if ($row['urole'] == 'Student')
                                        $class = $namerow['class'];
                                    elseif ($row['urole'] == 'Employee')
                                        $designation = $namerow['designation'];
                                    $dept = $namerow['dept'];
                                    if ($row['urole'] == 'Student')
                                        $joinyear = $namerow['joinyear'];
                                    elseif ($row['urole'] == 'Employee')
                                        $joindate = $namerow['joindate'];
                                    echo "<tr>";
                                    echo "<td>" . $row['gid'] . "</td>";
                                    echo "<td>" . $namerow['fname'] . " " . $namerow['lname'] . "<br>";
                                    if ($row['urole'] == 'Student') {
                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Grievance By:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															Name: " . $name . "<br>
															Role: Student<br>
															Email ID: " . $row['uemail'] . "<br>
															Roll No.: " . $roll . "<br>
															Class: " . $class . "<br>
															Department: " . $dept . "<br>
															Year of Joining: " . $joinyear . "<br>
														</div>
													  </div>
													  
													</div>
											  </div>
											</td>";
                                    } elseif ($row['urole'] == 'Employee') {
                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Grievance By:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															Name: " . $name . "<br>
															Role: Employee<br>
															Email ID: " . $row['uemail'] . "<br>
															ID No.: " . $roll . "<br>
															Department: " . $dept . "<br>
															Designation: " . $designation . "<br>
															Date of Joining: " . $joindate . "<br>
														</div>
													  </div>
													  
													</div>
											  </div>
											</td>";
                                    }

                                    echo "<td>" . $row['gsub'] . "</td>";
                                    echo "<td>" . $row['gcat'] . "</td>";
                                    echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'> Show/Hide </button>
											<div class='collapse' id='gdesc" . $row['gid'] . "'>
														<p> " . $row['gdes'] . "</p>
											</div>
											</td>";
                                    if ($row['gfile'] != NULL) {
                                        echo "<td>  <form action = '" . $row['gfile'] . "' target='_blank' method = 'POST'><button type='submit' style = 'color: #4be1e1;' class='btn btn-link'>View File</button></form> </td>";
                                    } else echo "<td>No File Attached</td>";
                                    echo "<td>" . $row['timeofg'] . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#forwarddetails" . $row['gid'] . "'> Show/Hide </button>
											<div class='collapse' id='forwarddetails" . $row['gid'] . "'>
														<p> " . $row['fordet'] . "</p>
											</div>
											</td>";
                                    echo "<td>" . $row['uptime'] . "</td>";
                                    echo "<td style = 'color: #000000;'>
												  <!-- Trigger the modal with a button -->
												  <button type='button' class = 'btn btn-light' data-toggle='modal' data-target='#myModal" . $row['gid'] . "'>Action Details</button>

												  <!-- Modal -->
												  <div class='modal' id='myModal" . $row['gid'] . "' role='dialog'>
														<div class='modal-dialog'>
														
														  <!-- Modal content-->
														  <div class='modal-content'>
															<div class='modal-header'>
															  <h4 class='modal-title'>Enter Action Details:</h4>
															  <button type='button' class='close' data-dismiss='modal'>&times;</button>
															</div>
															<div class='modal-body'>";

                                    if ($row['act']) {
                                        echo "<h4> Previous actions on the grievance: </h4>
																<p> " . $row['act'] . " </p>";
                                    }

                                    echo "<h4> Add action details: </h4>
																<form method='POST' action='done.php' class='form-horizontal'>
																	<div class='form-group'>
																		<div class='col-sm-12'>
																			<input type = 'hidden' name = 'uid1' id='uid1' value = '" . $row['uemail'] . "'>
																			<input type = 'hidden' name = 'gid1' id='gid1' value = '" . $row['gid'] . "'>
																			<textarea  maxlength = '255' name = 'actiondet' id = 'actiondet' class = 'form-control' placeholder = 'Enter Action Details Here!' cols = '50' required></textarea></br>
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
                        } else if ($_POST['toggle'] == 'forwarded') {

                            echo "<tr style = 'border: 4px solid black;'><td colspan = '11' style = 'text-align: center; background-color: white; color: black;'> " . mysqli_num_rows($res_forwarded) . " Grievances </td></tr>";

                            if (mysqli_num_rows($res_forwarded) > 0) {
                                while ($row = mysqli_fetch_assoc($res_forwarded)) {
                                    if ($row['urole'] == 'Student')
                                        $q = "SELECT * FROM userdet WHERE email = '" . $row['uemail'] . "'";
                                    elseif ($row['urole'] == 'Employee')
                                        $q = "SELECT * FROM facdet WHERE email = '" . $row['uemail'] . "'";
                                    $namerow = mysqli_fetch_assoc(mysqli_query($conn, $q));
                                    $name = "";
                                    if ($namerow['lname'] != "")
                                        $name .= $namerow['lname'] . " ";
                                    if ($namerow['fname'] != "")
                                        $name .= $namerow['fname'] . " ";
                                    if ($namerow['fathername'] != "")
                                        $name .= $namerow['fathername'] . " ";
                                    if ($namerow['mothername'] != "")
                                        $name .= $namerow['mothername'] . " ";
                                    $roll = $namerow['id'];
                                    if ($row['urole'] == 'Student')
                                        $class = $namerow['class'];
                                    elseif ($row['urole'] == 'Employee')
                                        $designation = $namerow['designation'];
                                    $dept = $namerow['dept'];
                                    if ($row['urole'] == 'Student')
                                        $joinyear = $namerow['joinyear'];
                                    elseif ($row['urole'] == 'Employee')
                                        $joindate = $namerow['joindate'];
                                    echo "<tr>";
                                    echo "<td>" . $row['gid'] . "</td>";
                                    echo "<td>" . $namerow['fname'] . " " . $namerow['lname'] . "<br>";
                                    if ($row['urole'] == 'Student') {
                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Grievance By:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															Name: " . $name . "<br>
															Role: Student<br>
															Email ID: " . $row['uemail'] . "<br>
															Roll No.: " . $roll . "<br>
															Class: " . $class . "<br>
															Department: " . $dept . "<br>
															Year of Joining: " . $joinyear . "<br>
														</div>
													  </div>
													  
													</div>
											  </div>
											</td>";
                                    } elseif ($row['urole'] == 'Employee') {
                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Grievance By:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															Name: " . $name . "<br>
															Role: Employee<br>
															Email ID: " . $row['uemail'] . "<br>
															ID No.: " . $roll . "<br>
															Department: " . $dept . "<br>
															Designation: " . $designation . "<br>
															Date of Joining: " . $joindate . "<br>
														</div>
													  </div>
													  
													</div>
											  </div>
											</td>";
                                    }

                                    echo "<td>" . $row['gsub'] . "</td>";
                                    echo "<td>" . $row['gcat'] . "</td>";
                                    echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'> Show/Hide </button>
											<div class='collapse' id='gdesc" . $row['gid'] . "'>
														<p> " . $row['gdes'] . "</p>
											</div>
											</td>";
                                    if ($row['gfile'] != NULL) {
                                        echo "<td>  <form action = '" . $row['gfile'] . "' target='_blank' method = 'POST'><button type='submit' style = 'color: #4be1e1;' class='btn btn-link'>View File</button></form> </td>";
                                    } else echo "<td>No File Attached</td>";
                                    echo "<td>" . $row['timeofg'] . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#forwarddetails" . $row['gid'] . "'> Show/Hide </button>
											<div class='collapse' id='forwarddetails" . $row['gid'] . "'>
														<p> " . $row['fordet'] . "</p>
											</div>
											</td>";
                                    echo "<td>" . $row['uptime'] . "</td>";
                                    echo "<td style = 'color: #000000;'>
												  <!-- Trigger the modal with a button -->
												  <button type='button' class = 'btn btn-light' data-toggle='modal' data-target='#myModal" . $row['gid'] . "'>Action Details</button>

												  <!-- Modal -->
												  <div class='modal' id='myModal" . $row['gid'] . "' role='dialog'>
														<div class='modal-dialog'>
														
														  <!-- Modal content-->
														  <div class='modal-content'>
															<div class='modal-header'>
															  <h4 class='modal-title'>Enter Action Details:</h4>
															  <button type='button' class='close' data-dismiss='modal'>&times;</button>
															</div>
															<div class='modal-body'>";

                                    if ($row['act']) {
                                        echo "<h4> Previous actions on the grievance: </h4>
																<p> " . $row['act'] . " </p>";
                                    }

                                    echo "<h4> Add action details: </h4>
																	<form method='POST' action='done.php' class='form-horizontal'>
																	<div class='form-group'>
																		<div class='col-sm-12'>
																			<input type = 'hidden' name = 'uid1' id='uid1' value = '" . $row['uemail'] . "'>
																			<input type = 'hidden' name = 'gid1' id='gid1' value = '" . $row['gid'] . "'>
																			<textarea  maxlength = '255' name = 'actiondet' id = 'actiondet' class = 'form-control' placeholder = 'Enter Action Details Here!' cols = '50' required></textarea></br>
																		</div>
																		<div class = 'col-sm-6'>
																			<button type = 'submit' class = 'btn btn-dark btn-block' name = 'partact' id = 'partact' value = 'PartialAction'>Partially Solved</button>
																		</div>
																		<div class = 'col-sm-6'>
																			<button type = 'submit' class = 'btn btn-dark btn-block' name = 'acttaken' id = 'acttaken' value = 'ActionTaken'>Resolved</button>
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
                        } else if ($_POST['toggle'] == 'partially_solved') {

                            echo "<tr style = 'border: 4px solid black;'><td colspan = '11' style = 'text-align: center; background-color: white; color: black;'> " . mysqli_num_rows($res_partsolved) . " Grievances </td></tr>";

                            if (mysqli_num_rows($res_partsolved) > 0) {
                                while ($row = mysqli_fetch_assoc($res_partsolved)) {
                                    if ($row['urole'] == 'Student')
                                        $q = "SELECT * FROM userdet WHERE email = '" . $row['uemail'] . "'";
                                    elseif ($row['urole'] == 'Employee')
                                        $q = "SELECT * FROM facdet WHERE email = '" . $row['uemail'] . "'";
                                    $namerow = mysqli_fetch_assoc(mysqli_query($conn, $q));
                                    $name = "";
                                    if ($namerow['lname'] != "")
                                        $name .= $namerow['lname'] . " ";
                                    if ($namerow['fname'] != "")
                                        $name .= $namerow['fname'] . " ";
                                    if ($namerow['fathername'] != "")
                                        $name .= $namerow['fathername'] . " ";
                                    if ($namerow['mothername'] != "")
                                        $name .= $namerow['mothername'] . " ";
                                    $roll = $namerow['id'];
                                    if ($row['urole'] == 'Student')
                                        $class = $namerow['class'];
                                    elseif ($row['urole'] == 'Employee')
                                        $designation = $namerow['designation'];
                                    $dept = $namerow['dept'];
                                    if ($row['urole'] == 'Student')
                                        $joinyear = $namerow['joinyear'];
                                    elseif ($row['urole'] == 'Employee')
                                        $joindate = $namerow['joindate'];
                                    echo "<tr>";
                                    echo "<td>" . $row['gid'] . "</td>";
                                    echo "<td>" . $namerow['fname'] . " " . $namerow['lname'] . "<br>";
                                    if ($row['urole'] == 'Student') {
                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Grievance By:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															Name: " . $name . "<br>
															Role: Student<br>
															Email ID: " . $row['uemail'] . "<br>
															Roll No.: " . $roll . "<br>
															Class: " . $class . "<br>
															Department: " . $dept . "<br>
															Year of Joining: " . $joinyear . "<br>
														</div>
													  </div>
													  
													</div>
											  </div>
											</td>";
                                    } elseif ($row['urole'] == 'Employee') {
                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Grievance By:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															Name: " . $name . "<br>
															Role: Employee<br>
															Email ID: " . $row['uemail'] . "<br>
															ID No.: " . $roll . "<br>
															Department: " . $dept . "<br>
															Designation: " . $designation . "<br>
															Date of Joining: " . $joindate . "<br>
														</div>
													  </div>
													  
													</div>
											  </div>
											</td>";
                                    }

                                    echo "<td>" . $row['gsub'] . "</td>";
                                    echo "<td>" . $row['gcat'] . "</td>";
                                    echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'> Show/Hide </button>
											<div class='collapse' id='gdesc" . $row['gid'] . "'>
														<p> " . $row['gdes'] . "</p>
											</div>
											</td>";
                                    if ($row['gfile'] != NULL) {
                                        echo "<td>  <form action = '" . $row['gfile'] . "' target='_blank' method = 'POST'><button type='submit' style = 'color: #4be1e1;' class='btn btn-link'>View File</button></form> </td>";
                                    } else echo "<td>No File Attached</td>";
                                    echo "<td>" . $row['timeofg'] . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td>" . $row['uptime'] . "</td>";
                                    echo "<td style = 'color: #000000;'>
												  <!-- Trigger the modal with a button -->
												  <button type='button' class = 'btn btn-light' data-toggle='modal' data-target='#myModal" . $row['gid'] . "'>Action Details</button>

												  <!-- Modal -->
												  <div class='modal' id='myModal" . $row['gid'] . "' role='dialog'>
														<div class='modal-dialog'>
														
														  <!-- Modal content-->
														  <div class='modal-content'>
															<div class='modal-header'>
															  <h4 class='modal-title'>Enter Action Details:</h4>
															  <button type='button' class='close' data-dismiss='modal'>&times;</button>
															</div>
															<div class='modal-body'>";

                                    if ($row['act']) {
                                        echo "<h4> Previous actions on the grievance: </h4>
																<p> " . $row['act'] . " </p>";
                                    }

                                    echo "<h4> Add action details: </h4>
																	<form method='POST' action='done.php' class='form-horizontal'>
																	<div class='form-group'>
																		<div class='col-sm-12'>
																			<input type = 'hidden' name = 'uid1' id='uid1' value = '" . $row['uemail'] . "'>
																			<input type = 'hidden' name = 'gid1' id='gid1' value = '" . $row['gid'] . "'>
																			<textarea  maxlength = '255' name = 'actiondet' id = 'actiondet' class = 'form-control' placeholder = 'Enter Action Details Here!' cols = '50' required></textarea></br>
																		</div>
																		<div class = 'col-sm-6'>
																			<button type = 'submit' class = 'btn btn-dark btn-block' name = 'partact' id = 'partact' value = 'PartialAction'>Partially Solved</button>
																		</div>
																		<div class = 'col-sm-6'>
																			<button type = 'submit' class = 'btn btn-dark btn-block' name = 'acttaken' id = 'acttaken' value = 'ActionTaken'>Resolved</button>
																		</div>
																	</div>
																</form>
															</div>
														  </div>
														  
														</div>
												  </div></td>";
                                    echo "<td style = 'color: #000000;'><button type = 'button' class = 'btn btn-light' data-toggle = 'modal' data-target = '#forwardModal" . $row['gid'] . "'> Forward </button>";
                                    echo "<div class='modal' id='forwardModal" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Mention the reason for forwarding:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															<form method='POST' action='raisep.php' class='form-horizontal'>
																<div class='form-group'>
																	<div class='col-sm-12'>
																		<input type = 'hidden' name = 'uid1' id='uid1' value = '" . $row['uemail'] . "'>
																		<input type = 'hidden' name = 'gid1' id='gid1' value = '" . $row['gid'] . "'>
																		<textarea  maxlength = '255' class = 'form-control' name = 'forwarddet' id = 'forwarddet' placeholder = 'Mention reason for forwarding along with other required details!' cols = '50' required></textarea></br>
																	    <b>Forward to:</b> <select name = 'forwardto' id = 'forwardto' class='form-control' required style = 'height: 5%;'>
																		<option value = ''> Select Committee </option>";

                                    mysqli_data_seek($commnames, 0);
                                    if (mysqli_num_rows($commnames) > 0) {
                                        while ($row = mysqli_fetch_assoc($commnames)) {
                                            echo "<option value = '" . $row['name'] . "'> " . $row['name'] . " </option>";
                                        }
                                    }

                                    echo "</select> </br></br><button type = 'submit' class = 'btn btn-dark btn-block' name = 'forwardissue' id = 'forwardissue' value = 'forwardIssue'  onclick=\"return confirm('Are you sure?')\"> Forward Issue </button>
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
                        } else if ($_POST['toggle'] == 'resolved') {

                            echo "<tr style = 'border: 4px solid black;'><td colspan = '10' style = 'text-align: center; background-color: white; color: black;'> " . mysqli_num_rows($res_resolved) . " Grievances </td></tr>";

                            if (mysqli_num_rows($res_resolved) > 0) {
                                while ($row = mysqli_fetch_assoc($res_resolved)) {
                                    if ($row['urole'] == 'Student')
                                        $q = "SELECT * FROM userdet WHERE email = '" . $row['uemail'] . "'";
                                    elseif ($row['urole'] == 'Employee')
                                        $q = "SELECT * FROM facdet WHERE email = '" . $row['uemail'] . "'";
                                    $namerow = mysqli_fetch_assoc(mysqli_query($conn, $q));
                                    $name = "";
                                    if ($namerow['lname'] != "")
                                        $name .= $namerow['lname'] . " ";
                                    if ($namerow['fname'] != "")
                                        $name .= $namerow['fname'] . " ";
                                    if ($namerow['fathername'] != "")
                                        $name .= $namerow['fathername'] . " ";
                                    if ($namerow['mothername'] != "")
                                        $name .= $namerow['mothername'] . " ";
                                    $roll = $namerow['id'];
                                    if ($row['urole'] == 'Student')
                                        $class = $namerow['class'];
                                    elseif ($row['urole'] == 'Employee')
                                        $designation = $namerow['designation'];
                                    $dept = $namerow['dept'];
                                    if ($row['urole'] == 'Student')
                                        $joinyear = $namerow['joinyear'];
                                    elseif ($row['urole'] == 'Employee')
                                        $joindate = $namerow['joindate'];
                                    echo "<tr>";
                                    echo "<td>" . $row['gid'] . "</td>";
                                    echo "<td>" . $namerow['fname'] . " " . $namerow['lname'] . "<br>";
                                    if ($row['urole'] == 'Student') {
                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Grievance By:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															Name: " . $name . "<br>
															Role: Student<br>
															Email ID: " . $row['uemail'] . "<br>
															Roll No.: " . $roll . "<br>
															Class: " . $class . "<br>
															Department: " . $dept . "<br>
															Year of Joining: " . $joinyear . "<br>
														</div>
													  </div>
													  
													</div>
											  </div>
											</td>";
                                    } elseif ($row['urole'] == 'Employee') {
                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Grievance By:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															Name: " . $name . "<br>
															Role: Employee<br>
															Email ID: " . $row['uemail'] . "<br>
															ID No.: " . $roll . "<br>
															Department: " . $dept . "<br>
															Designation: " . $designation . "<br>
															Date of Joining: " . $joindate . "<br>
														</div>
													  </div>
													  
													</div>
											  </div>
											</td>";
                                    }

                                    echo "<td>" . $row['gsub'] . "</td>";
                                    echo "<td>" . $row['gcat'] . "</td>";
                                    echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'> Show/Hide </button>
											<div class='collapse' id='gdesc" . $row['gid'] . "'>
														<p> " . $row['gdes'] . "</p>
											</div>
											</td>";
                                    if ($row['gfile'] != NULL) {
                                        echo "<td>  <form action = '" . $row['gfile'] . "' target='_blank' method = 'POST'><button type='submit' style = 'color: #4be1e1;' class='btn btn-link'>View File</button></form> </td>";
                                    } else echo "<td>No File Attached</td>";
                                    echo "<td>" . $row['timeofg'] . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td>" . $row['uptime'] . "</td>";
                                    echo "<td style = 'color: #000000;'>
												  <!-- Trigger the modal with a button -->
												  <button type='button' class = 'btn btn-light' data-toggle='modal' data-target='#myModal" . $row['gid'] . "'>Action Details</button>

												  <!-- Modal -->
												  <div class='modal' id='myModal" . $row['gid'] . "' role='dialog'>
														<div class='modal-dialog'>
														
														  <!-- Modal content-->
														  <div class='modal-content'>
															<div class='modal-header'>
															  <h4 class='modal-title'>Action Details:</h4>
															  <button type='button' class='close' data-dismiss='modal'>&times;</button>
															</div>
															<div class='modal-body'>
																
																	<input type = 'hidden' name = 'uid1' id='uid1' value = '" . $row['uemail'] . "'>
																	<input type = 'hidden' name = 'gid1' id='gid1' value = '" . $row['gid'] . "'>
																	<p>" . $row['act'] . "</p>
																	<br><br>
																	<form action='print.php' method='post' target = '_blank'>
																	<input type = 'hidden' name = 'uid2' id='uid2' value = '" . $row['uemail'] . "'>
																	<input type = 'hidden' name = 'gid2' id='gid2' value = '" . $row['gid'] . "'>
																	<button type='submit' class = 'btn btn-dark' class='print'>Download Grievance Report as a pdf</button></form>	
																
															</div>
														  </div>
														  
														</div>
												  </div></td>";
                                    echo "</tr>";
                                }
                            }
                        } else if ($_POST['toggle'] == 'offline') {

                            echo "<tr style = 'border: 4px solid black;'><td colspan = '10' style = 'text-align: center; background-color: white; color: black;'> " . mysqli_num_rows($res_offline) . " Grievances </td></tr>";

                            if (mysqli_num_rows($res_offline) > 0) {
                                while ($row = mysqli_fetch_assoc($res_offline)) {
                                    if ($row['urole'] == 'Student')
                                        $q = "SELECT * FROM userdet WHERE email = '" . $row['uemail'] . "'";
                                    elseif ($row['urole'] == 'Employee')
                                        $q = "SELECT * FROM facdet WHERE email = '" . $row['uemail'] . "'";
                                    $namerow = mysqli_fetch_assoc(mysqli_query($conn, $q));
                                    $name = "";
                                    if ($namerow['lname'] != "")
                                        $name .= $namerow['lname'] . " ";
                                    if ($namerow['fname'] != "")
                                        $name .= $namerow['fname'] . " ";
                                    if ($namerow['fathername'] != "")
                                        $name .= $namerow['fathername'] . " ";
                                    if ($namerow['mothername'] != "")
                                        $name .= $namerow['mothername'] . " ";
                                    $roll = $namerow['id'];
                                    if ($row['urole'] == 'Student')
                                        $class = $namerow['class'];
                                    elseif ($row['urole'] == 'Employee')
                                        $designation = $namerow['designation'];
                                    $dept = $namerow['dept'];
                                    if ($row['urole'] == 'Student')
                                        $joinyear = $namerow['joinyear'];
                                    elseif ($row['urole'] == 'Employee')
                                        $joindate = $namerow['joindate'];
                                    echo "<tr>";
                                    echo "<td>" . $row['gid'] . "</td>";
                                    echo "<td>" . $namerow['fname'] . " " . $namerow['lname'] . "<br>";
                                    if ($row['urole'] == 'Student') {
                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Grievance By:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															Name: " . $name . "<br>
															Role: Student<br>
															Email ID: " . $row['uemail'] . "<br>
															Roll No.: " . $roll . "<br>
															Class: " . $class . "<br>
															Department: " . $dept . "<br>
															Year of Joining: " . $joinyear . "<br>
														</div>
													  </div>
													  
													</div>
											  </div>
											</td>";
                                    } elseif ($row['urole'] == 'Employee') {
                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Grievance By:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															Name: " . $name . "<br>
															Role: Employee<br>
															Email ID: " . $row['uemail'] . "<br>
															ID No.: " . $roll . "<br>
															Department: " . $dept . "<br>
															Designation: " . $designation . "<br>
															Date of Joining: " . $joindate . "<br>
														</div>
													  </div>
													  
													</div>
											  </div>
											</td>";
                                    }

                                    echo "<td>" . $row['gsub'] . "</td>";
                                    echo "<td>" . $row['gcat'] . "</td>";
                                    echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'> Show/Hide </button>
											<div class='collapse' id='gdesc" . $row['gid'] . "'>
														<p> " . $row['gdes'] . "</p>
											</div>
											</td>";
                                    if ($row['gfile'] != NULL) {
                                        echo "<td>  <form action = '" . $row['gfile'] . "' target='_blank' method = 'POST'><button type='submit' style = 'color: #4be1e1;' class='btn btn-link'>View File</button></form> </td>";
                                    } else echo "<td>No File Attached</td>";
                                    echo "<td>" . $row['timeofg'] . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td>" . $row['uptime'] . "</td>";
                                    echo "<td style = 'color: #000000;'>
												  <!-- Trigger the modal with a button -->
												  <button type='button' class = 'btn btn-light' data-toggle='modal' data-target='#myModal" . $row['gid'] . "'>Action Details</button>

												  <!-- Modal -->
												  <div class='modal' id='myModal" . $row['gid'] . "' role='dialog'>
														<div class='modal-dialog'>
														
														  <!-- Modal content-->
														  <div class='modal-content'>
															<div class='modal-header'>
															  <h4 class='modal-title'>Action Details:</h4>
															  <button type='button' class='close' data-dismiss='modal'>&times;</button>
															</div>
															<div class='modal-body'>
																
																	<input type = 'hidden' name = 'uid1' id='uid1' value = '" . $row['uemail'] . "'>
																	<input type = 'hidden' name = 'gid1' id='gid1' value = '" . $row['gid'] . "'>
																	<p>" . $row['act'] . "</p>
																	<br><br>
																	<form action='print.php' method='post' target = '_blank'>
																	<input type = 'hidden' name = 'uid2' id='uid2' value = '" . $row['uemail'] . "'>
																	<input type = 'hidden' name = 'gid2' id='gid2' value = '" . $row['gid'] . "'>
																	<button type='submit' class = 'btn btn-dark' class='print'>Download Grievance Report as a pdf</button></form>	
																
															</div>
														  </div>
														  
														</div>
												  </div></td>";
                                    echo "</tr>";
                                }
                            }
                        } else {

                            echo "<tr style = 'border: 4px solid black;'><td colspan = '9' style = 'text-align: center; background-color: white; color: black;'> " . mysqli_num_rows($res_cancel) . " Grievances </td></tr>";

                            if (mysqli_num_rows($res_pending) > 0) {
                                while ($row = mysqli_fetch_assoc($res_pending)) {
                                    if ($row['urole'] == 'Student')
                                        $q = "SELECT * FROM userdet WHERE email = '" . $row['uemail'] . "'";
                                    elseif ($row['urole'] == 'Employee')
                                        $q = "SELECT * FROM facdet WHERE email = '" . $row['uemail'] . "'";
                                    $namerow = mysqli_fetch_assoc(mysqli_query($conn, $q));
                                    $name = "";
                                    if ($namerow['lname'] != "")
                                        $name .= $namerow['lname'] . " ";
                                    if ($namerow['fname'] != "")
                                        $name .= $namerow['fname'] . " ";
                                    if ($namerow['fathername'] != "")
                                        $name .= $namerow['fathername'] . " ";
                                    if ($namerow['mothername'] != "")
                                        $name .= $namerow['mothername'] . " ";
                                    $roll = $namerow['id'];
                                    if ($row['urole'] == 'Student')
                                        $class = $namerow['class'];
                                    elseif ($row['urole'] == 'Employee')
                                        $designation = $namerow['designation'];
                                    $dept = $namerow['dept'];
                                    if ($row['urole'] == 'Student')
                                        $joinyear = $namerow['joinyear'];
                                    elseif ($row['urole'] == 'Employee')
                                        $joindate = $namerow['joindate'];
                                    echo "<tr>";
                                    echo "<td>" . $row['gid'] . "</td>";
                                    echo "<td>" . $namerow['fname'] . " " . $namerow['lname'] . "<br>";
                                    if ($row['urole'] == 'Student') {
                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Grievance By:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															Name: " . $name . "<br>
															Role: Student<br>
															Email ID: " . $row['uemail'] . "<br>
															Roll No.: " . $roll . "<br>
															Class: " . $class . "<br>
															Department: " . $dept . "<br>
															Year of Joining: " . $joinyear . "<br>
														</div>
													  </div>
													  
													</div>
											  </div>
											</td>";
                                    } elseif ($row['urole'] == 'Employee') {
                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Grievance By:</h4>
														  <button type='button' class='close' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															Name: " . $name . "<br>
															Role: Employee<br>
															Email ID: " . $row['uemail'] . "<br>
															ID No.: " . $roll . "<br>
															Department: " . $dept . "<br>
															Designation: " . $designation . "<br>
															Date of Joining: " . $joindate . "<br>
														</div>
													  </div>
													  
													</div>
											  </div>
											</td>";
                                    }
                                    echo "<td>" . $row['gsub'] . "</td>";
                                    echo "<td>" . $row['gcat'] . "</td>";
                                    echo "<td>
											<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'> Show/Hide </button>
											<div class='collapse' id='gdesc" . $row['gid'] . "'>
														<p> " . $row['gdes'] . "</p>
											</div>
											</td>";
                                    if ($row['gfile'] != NULL) {
                                        echo "<td>  <form action = '" . $row['gfile'] . "' target='_blank' method = 'POST'><button type='submit' style = 'color: #4be1e1;' class='btn btn-link'>View File</button></form> </td>";
                                    } else echo "<td>No File Attached</td>";
                                    echo "<td>" . $row['timeofg'] . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td>" . $row['uptime'] . "</td>";
                                    echo "</tr>";
                                }
                            }
                        }
                    } else {

                        echo "<tr style = 'border: 4px solid black;'><td colspan = '10' style = 'text-align: center; background-color: white; color: black;'> " . mysqli_num_rows($res_pending) . " Grievances </td></tr>";

                        if (mysqli_num_rows($res_pending) > 0) {
                            while ($row = mysqli_fetch_assoc($res_pending)) {
                                if ($row['urole'] == 'Student')
                                    $q = "SELECT * FROM userdet WHERE email = '" . $row['uemail'] . "'";
                                elseif ($row['urole'] == 'Employee')
                                    $q = "SELECT * FROM facdet WHERE email = '" . $row['uemail'] . "'";
                                $namerow = mysqli_fetch_assoc(mysqli_query($conn, $q));
                                $name = "";
                                if ($namerow['lname'] != "")
                                    $name .= $namerow['lname'] . " ";
                                if ($namerow['fname'] != "")
                                    $name .= $namerow['fname'] . " ";
                                if ($namerow['fathername'] != "")
                                    $name .= $namerow['fathername'] . " ";
                                if ($namerow['mothername'] != "")
                                    $name .= $namerow['mothername'] . " ";
                                $roll = $namerow['id'];
                                if ($row['urole'] == 'Student')
                                    $class = $namerow['class'];
                                elseif ($row['urole'] == 'Employee')
                                    $designation = $namerow['designation'];
                                $dept = $namerow['dept'];
                                if ($row['urole'] == 'Student')
                                    $joinyear = $namerow['joinyear'];
                                elseif ($row['urole'] == 'Employee')
                                    $joindate = $namerow['joindate'];
                                echo "<tr>";
                                echo "<td>" . $row['gid'] . "</td>";
                                echo "<td>" . $namerow['fname'] . " " . $namerow['lname'] . "<br>";
                                if ($row['urole'] == 'Student') {
                                    echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
											<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
												<div class='modal-dialog'>
												
												  <!-- Modal content-->
												  <div class='modal-content'>
													<div class='modal-header'>
													  <h4 class='modal-title'>Grievance By:</h4>
													  <button type='button' class='close' data-dismiss='modal'>&times;</button>
													</div>
													<div class='modal-body'>
														Name: " . $name . "<br>
														Role: Student<br>
														Email ID: " . $row['uemail'] . "<br>
														Roll No.: " . $roll . "<br>
														Class: " . $class . "<br>
														Department: " . $dept . "<br>
														Year of Joining: " . $joinyear . "<br>
													</div>
												  </div>
												  
												</div>
										  </div>
										</td>";
                                } elseif ($row['urole'] == 'Employee') {
                                    echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
											<div style = 'color: #000000;' class='modal' id='studentdetails" . $row['gid'] . "' role='dialog'>
												<div class='modal-dialog'>
												
												  <!-- Modal content-->
												  <div class='modal-content'>
													<div class='modal-header'>
													  <h4 class='modal-title'>Grievance By:</h4>
													  <button type='button' class='close' data-dismiss='modal'>&times;</button>
													</div>
													<div class='modal-body'>
														Name: " . $name . "<br>
														Role: Employee<br>
														Email ID: " . $row['uemail'] . "<br>
														ID No.: " . $roll . "<br>
														Department: " . $dept . "<br>
														Designation: " . $designation . "<br>
														Date of Joining: " . $joindate . "<br>
													</div>
												  </div>
												  
												</div>
										  </div>
										</td>";
                                }
                                echo "<td>" . $row['gsub'] . "</td>";
                                echo "<td>" . $row['gcat'] . "</td>";
                                echo "<td>
										<button type = 'button' style = 'color: #4be1e1;' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'> Show/Hide </button>
										<div class='collapse' id='gdesc" . $row['gid'] . "'>
													<p> " . $row['gdes'] . "</p>
										</div>
										</td>";
                                if ($row['gfile'] != NULL) {
                                    echo "<td> <form action = '" . $row['gfile'] . "' target='_blank' method = 'POST'><button type='submit' style = 'color: #4be1e1;' class='btn btn-link'>View File</button></form> </td>";
                                } else echo "<td>No File Attached</td>";
                                echo "<td>" . $row['timeofg'] . "</td>";
                                echo "<td>" . $row['status'] . "</td>";
                                echo "<td><form method='POST' action='acknowledge.php' onclick=\"return confirm('Are you sure?')\">
										<input type = 'hidden' name = 'uid' id='uid' value = '" . $row['uemail'] . "'>
										<input type = 'hidden' name = 'gid' id='gid' value = '" . $row['gid'] . "'>
										<button type='submit' class = 'btn btn-light' name='sendm' id='sendm'> Acknowledge </button></form></td>";
                                echo "<td style = 'color: #000000;'><button type = 'button' class = 'btn btn-light' data-toggle = 'modal' data-target = '#forwardModal" . $row['gid'] . "'> Forward </button>";
                                echo "<div class='modal' id='forwardModal" . $row['gid'] . "' role='dialog'>
												<div class='modal-dialog'>
												
												  <!-- Modal content-->
												  <div class='modal-content'>
													<div class='modal-header'>
													  <h4 class='modal-title'>Mention the reason for forwarding:</h4>
													  <button type='button' class='close' data-dismiss='modal'>&times;</button>
													</div>
													<div class='modal-body'>
														<form method='POST' action='raisep.php' class='form-horizontal'>
															<div class='form-group'>
																<div class='col-sm-12'>
																	<input type = 'hidden' name = 'uid1' id='uid1' value = '" . $row['uemail'] . "'>
																	<input type = 'hidden' name = 'gid1' id='gid1' value = '" . $row['gid'] . "'>
																	<textarea  maxlength = '255' class = 'form-control' name = 'forwarddet' id = 'forwarddet' placeholder = 'Mention reason for forwarding along with other required details!' cols = '50' required></textarea></br>
																	<b>Forward to:</b> <select name = 'forwardto' id = 'forwardto' class='form-control' required style = 'height: 5%;'>
																	<option value = ''> Select Committee </option>";

                                mysqli_data_seek($commnames, 0);
                                if (mysqli_num_rows($commnames) > 0) {
                                    while ($row = mysqli_fetch_assoc($commnames)) {
                                        echo "<option value = '" . $row['name'] . "'> " . $row['name'] . " </option>";
                                    }
                                }

                                echo "</select> </br></br><button type = 'submit' class = 'btn btn-dark btn-block' name = 'forwardissue' id = 'forwardissue' value = 'forwardIssue'  onclick=\"return confirm('Are you sure?')\"> Forward Issue </button>
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
            </td>
        </tr>
        </tbody>
    </table>
    </html>

<?php
if (isset($_SESSION['alert'])) {
    echo "<script> alert('" . $_SESSION['alert'] . "');</script>";
    unset($_SESSION['alert']);
}
include('footer.php'); ?>