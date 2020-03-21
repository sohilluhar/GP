<?php

if (isset($_SESSION['previousu'])) {
    if (basename($_SERVER['PHP_SELF']) != $_SESSION['previousu']) {
        session_destroy();
    }
}

session_start();
if (!isset($_SESSION['loggedInUser'])) {
    header("location:index.php");
}

include_once("includes/connection.php");

$name = $_SESSION['name'];

//GET total number of griev
$qu = "SELECT * FROM grievance where (uemail='" . $_SESSION['loggedInUser'] . "')";
$total_griev = mysqli_num_rows(mysqli_query($conn, $qu));

$qu = "SELECT * FROM grievance where (uemail='" . $_SESSION['loggedInUser'] . "') AND (status = 'Pending')";
$total_griev_pending = mysqli_num_rows(mysqli_query($conn, $qu));

$qu = "SELECT * FROM grievance where (uemail='" . $_SESSION['loggedInUser'] . "') AND (status = 'In Progress')";
$total_griev_inprogress = mysqli_num_rows(mysqli_query($conn, $qu));

$qu = "SELECT * FROM grievance where (uemail='" . $_SESSION['loggedInUser'] . "') AND (status REGEXP 'Forwarded to')";
$total_griev_forwarded = mysqli_num_rows(mysqli_query($conn, $qu));

$qu = "SELECT * FROM grievance where (uemail='" . $_SESSION['loggedInUser'] . "') AND (status = 'Partially Solved')";
$total_griev_partsolved = mysqli_num_rows(mysqli_query($conn, $qu));

$qu = "SELECT * FROM resolved where (uemail='" . $_SESSION['loggedInUser'] . "') AND (status='Resolved')";
$total_griev_resolved = mysqli_num_rows(mysqli_query($conn, $qu));


$qu = "SELECT * FROM grievance where uemail='" . $_SESSION['loggedInUser'] . "'";
$res = mysqli_query($conn, $qu);

?>


<!DOCTYPE html>
<html>
<head>
    <?php
    include "include/html_head.php";
    ?>
    <link rel="stylesheet" href="./include/plugins/datatables-bs4/css/dataTables.bootstrap4.css">

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <?php
    include "include/nav_header.php";
    include "include/user_sidebar.php";
    ?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>List of Grievances</h1>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-2 col-6">
                        <!-- small box -->
                        <div class="small-box bg-gradient-primary">
                            <div class="inner">
                                <p>Total Grievances</p>
                                <h3><?php echo $total_griev; ?></h3>

                            </div>

                            <a href="griev.php?status=pending" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-2 col-6">
                        <!-- small box -->
                        <div class="small-box bg-gradient-danger">
                            <div class="inner">
                                <p>Pending </p>
                                <h3><?php echo $total_griev_pending; ?></h3>

                            </div>
                            <!--                            <div class="icon">-->
                            <!--                                <i class="ion ion-stats-bars"></i>-->
                            <!--                            </div>-->
                            <a href="griev.php?status=pending" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-2 col-6">
                        <!-- small box -->
                        <div class="small-box bg-gradient-warning">
                            <div class="inner">
                                <p class="text-white">In-Progress</p>
                                <h3 class="text-white"><?php echo $total_griev_inprogress; ?></h3>
                            </div>
                            <!--                            <div class="icon">-->
                            <!--                                <i class="ion ion-person-add"></i>-->
                            <!--                            </div>-->
                            <a href="griev.php?status=in_progress" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->

                    <!-- ./col -->


                    <div class="col-lg-2 col-6">
                        <!-- small box -->
                        <div class="small-box bg-gradient-orange">
                            <div class="inner">
                                <p class="text-white">Forwarded</p>
                                <h3 class="text-white"><?php echo $total_griev_forwarded; ?></h3>
                            </div>
                            <!--                            <div class="icon">-->
                            <!--                                <i class="ion ion-pie-graph"></i>-->
                            <!--                            </div>-->
                            <a href="griev.php?status=forwarded" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-2 col-6">
                        <!-- small box -->
                        <div class="small-box bg-gradient-olive">
                            <div class="inner">

                                <p>Partially Solved</p>
                                <h3><?php echo $total_griev_partsolved; ?></h3>

                            </div>
                            <!--                            <div class="icon">-->
                            <!--                                <i class="ion ion-pie-graph"></i>-->
                            <!--                            </div>-->
                            <a href="griev.php?status=partially_solved" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-6">
                        <!-- small box -->
                        <div class="small-box bg-gradient-success">
                            <div class="inner">
                                <p>Solved</p>
                                <h3><?php echo $total_griev_resolved; ?></h3>

                            </div>
                            <!--                            <div class="icon">-->
                            <!--                                <i class="ion ion-pie-graph"></i>-->
                            <!--                            </div>-->
                            <a href="griev.php?status=resolved" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">

                                    <?php
                                    if (isset($_GET['status'])) {
                                        if ($_GET['status'] == 'pending') {
                                            $qu = "SELECT * FROM grievance where (uemail='" . $_SESSION['loggedInUser'] . "') AND (status = 'Pending')";
                                            $res_pending = mysqli_query($conn, $qu);
                                            echo "Pending";
                                        } else if ($_GET['status'] == 'in_progress') {
                                            $qu = "SELECT * FROM grievance where (uemail='" . $_SESSION['loggedInUser'] . "') AND (status = 'In Progress')";
                                            $res_inprogress = mysqli_query($conn, $qu);
                                            echo "In Progress";
                                        } else if ($_GET['status'] == 'forwarded') {
                                            $qu = "SELECT * FROM grievance where (uemail='" . $_SESSION['loggedInUser'] . "') AND (status REGEXP 'Forwarded to')";
                                            $res_forwarded = mysqli_query($conn, $qu);
                                            echo "Forwarded ";
                                        } else if ($_GET['status'] == 'partially_solved') {
                                            $qu = "SELECT * FROM grievance where (uemail='" . $_SESSION['loggedInUser'] . "') AND (status = 'Partially Solved')";
                                            $res_partsolved = mysqli_query($conn, $qu);
                                            echo " Partially Solved";
                                        } else if ($_GET['status'] == 'resolved') {
                                            $qu = "SELECT * FROM resolved where (uemail='" . $_SESSION['loggedInUser'] . "') AND (status='Resolved')";
                                            $res_resolved = mysqli_query($conn, $qu);
                                            echo " Resolved ";
                                        } else if ($_GET['status'] == 'offline') {
                                            $qu = "SELECT * FROM resolved where (uemail='" . $_SESSION['loggedInUser'] . "') AND (status='Resolved Offline')";
                                            $res_offline = mysqli_query($conn, $qu);
                                            echo " Offline";
                                        } else {
                                            $qu = "SELECT * FROM cancelled where (uemail='" . $_SESSION['loggedInUser'] . "')";
                                            $res_cancel = mysqli_query($conn, $qu);
                                            echo " Cancelled ";
                                        }
                                    } else {
                                        $qu = "SELECT * FROM grievance where (uemail='" . $_SESSION['loggedInUser'] . "')";
                                        $res_pending = mysqli_query($conn, $qu);
                                        echo "Pending";
                                    }
                                    ?>
                                    Grievances
                                </h4>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-head-fixed table-striped">

                                    <thead>
                                    <tr>

                                        <?php
                                        if (isset($_GET['status'])) {

                                            if ($_GET['status'] == 'pending') {
                                                echo "<th>Grievance ID</th>
										<th>Grievance Subject</th>
										<th>Grievance Category</th>
										<th>Description</th>
										<th>File Attached</th>
										<th>Time of Issue</th>
										<th>Grievance Status</th>
										<th>Cancel Grievance</th>";
                                            } else if ($_GET['status'] == 'in_progress') {
                                                echo "<th>Grievance ID</th>
											<th>Grievance Subject</th>
											<th>Grievance Category</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>";
                                            } else if ($_GET['status'] == 'forwarded') {
                                                echo "<th>Grievance ID</th>
											<th>Grievance Subject</th>
											<th>Grievance Category</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Forward Details</th>
											<th>Last Status Update On</th>
											<th>Action Details</th>";
                                            } else if ($_GET['status'] == 'partially_solved') {
                                                echo "<th>Grievance ID</th>
											<th>Grievance Subject</th>
											<th>Grievance Category</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>
											<th>Action Details</th>";
                                            } else if ($_GET['status'] == 'resolved') {
                                                echo "<th>Grievance ID</th>
											<th>Grievance Subject</th>
											<th>Grievance Category</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>
											<th>Action Details</th>";
                                            } else if ($_GET['status'] == 'offline') {
                                                echo "<th>Grievance ID</th>
											<th>Grievance Subject</th>
											<th>Grievance Category</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>
											<th>Action Details</th>";
                                            } else {
                                                echo "<th>Grievance ID</th>
											<th>Grievance Subject</th>
											<th>Grievance Category</th>
											<th>Description</th>
											<th>File Attached</th>
											<th>Time of Issue</th>
											<th>Grievance Status</th>
											<th>Last Status Update On</th>";
                                            }
                                        } else {
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

                                    if (isset($_GET['status'])) {
                                        if ($_GET['status'] == 'pending') {


                                            if (mysqli_num_rows($res_pending) > 0) {
                                                while ($row = mysqli_fetch_assoc($res_pending)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row['gid'] . "</td>";
                                                    echo "<td>" . $row['gsub'] . "</td>";
                                                    echo "<td>" . $row['gcat'] . "</td>";
                                                    echo "<td> " . substr($row['gdes'], 0, 30) . " ....
											<button type = 'button' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'> 
											
											 Show/hide more </button>
											<div class='collapse' id='gdesc" . $row['gid'] . "'>
														<p> " . $row['gdes'] . "</p>
											</div>
											</td>";
                                                    if ($row['gfile'] != NULL) {
                                                        echo "<td> <form action = '" . $row['gfile'] . "' target='_blank' method = 'POST'>
<button type='submit'  class='btn btn-link'>View File</button></form> </td>";
                                                    } else echo "<td>No File Attached</td>";
                                                    echo "<td>" . $row['timeofg'] . "</td>";
                                                    echo "<td>" . $row['status'] . "</td>";
                                                    echo "<td><form method='POST' action='cancel.php' onclick=\"return confirm('Are you sure?')\">
											<input type = 'hidden' name = 'uid' id='uid' value = '" . $row['uemail'] . "'>
											<input type = 'hidden' name = 'gid' id='gid' value = '" . $row['gid'] . "'>
											<button type='submit' class = 'btn btn-danger' name='cancg' id='cancg'>Cancel</button></form></td>";
                                                    echo "</tr>";
                                                }
                                            }
                                        } else if ($_GET['status'] == 'in_progress') {


                                            if (mysqli_num_rows($res_inprogress) > 0) {
                                                while ($row = mysqli_fetch_assoc($res_inprogress)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row['gid'] . "</td>";
                                                    echo "<td>" . $row['gsub'] . "</td>";
                                                    echo "<td>" . $row['gcat'] . "</td>";
                                                    echo "<td> " . substr($row['gdes'], 0, 30) . " ....
											<button type = 'button' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'>
											 Show/Hide </button>
											<div class='collapse' id='gdesc" . $row['gid'] . "'>
														<p> " . $row['gdes'] . "</p>
											</div>
											</td>";
                                                    if ($row['gfile'] != NULL) {
                                                        echo "<td>  <form action = '" . $row['gfile'] . "' target='_blank' method = 'POST'>
<button type='submit'  class='btn btn-link'>View File</button></form> </td>";
                                                    } else echo "<td>No File Attached</td>";
                                                    echo "<td>" . $row['timeofg'] . "</td>";
                                                    echo "<td>" . $row['status'] . "</td>";
                                                    echo "<td>" . $row['uptime'] . "</td>";
                                                    echo "</tr>";
                                                }
                                            }
                                        } else if ($_GET['status'] == 'forwarded') {


                                            if (mysqli_num_rows($res_forwarded) > 0) {
                                                while ($row = mysqli_fetch_assoc($res_forwarded)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row['gid'] . "</td>";
                                                    echo "<td>" . $row['gsub'] . "</td>";
                                                    echo "<td>" . $row['gcat'] . "</td>";
                                                    echo "<td>
											<button type = 'button'  class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'>
											 Show/Hide </button>
											<div class='collapse' id='gdesc" . $row['gid'] . "'>
														<p> " . $row['gdes'] . "</p>
											</div>
											</td>";
                                                    if ($row['gfile'] != NULL) {
                                                        echo "<td>  <form action = '" . $row['gfile'] . "' target='_blank' method = 'POST'>
<button type='submit'  class='btn btn-link'>View File</button></form> </td>";
                                                    } else echo "<td>No File Attached</td>";
                                                    echo "<td>" . $row['timeofg'] . "</td>";
                                                    echo "<td>" . $row['status'] . "</td>";
                                                    echo "<td>
											<button type = 'button' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#forwarddetails" . $row['gid'] . "'> 
											Show/Hide </button>
											<div class='collapse' id='forwarddetails" . $row['gid'] . "'>
														<p> " . $row['fordet'] . "</p>
											</div>
											</td>";
                                                    echo "<td>" . $row['uptime'] . "</td>";
                                                    echo "<td >
											  <!-- Trigger the modal with a button -->
											  <button type='button' class = 'btn btn-primary' data-toggle='modal' data-target='#myModal" . $row['gid'] . "'>
											  View</button>

											  <!-- Modal -->
											  <div class='modal' id='myModal" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Action Details:</h4>
														  <button type='button ' class='btn btn-outline-danger' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															
																<input type = 'hidden' name = 'uid1' id='uid1' value = '" . $row['uemail'] . "'>
																<input type = 'hidden' name = 'gid1' id='gid1' value = '" . $row['gid'] . "'>
																<p>" . $row['act'] . "</p>
																<br><br>
																<form action='print.php' method='post' target = '_blank'>
																<input type = 'hidden' name = 'uid2' id='uid2' value = '" . $row['uemail'] . "'>
																<input type = 'hidden' name = 'gid2' id='gid2' value = '" . $row['gid'] . "'>
																<button type='submit' class = 'btn btn-primary' class='print'>Download Action Details as a pdf</button></form>	
															
														</div>
													  </div>
													  
													</div>
											  </div></td>";
                                                    echo "</tr>";
                                                }
                                            }
                                        } else if ($_GET['status'] == 'partially_solved') {

                                            if (mysqli_num_rows($res_partsolved) > 0) {
                                                while ($row = mysqli_fetch_assoc($res_partsolved)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row['gid'] . "</td>";
                                                    echo "<td>" . $row['gsub'] . "</td>";
                                                    echo "<td>" . $row['gcat'] . "</td>";
                                                    echo "<td> " . substr($row['gdes'], 0, 30) . " ....
											<button type = 'button'  class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'>
											 Show/Hide </button>
											<div class='collapse' id='gdesc" . $row['gid'] . "'>
														<p> " . $row['gdes'] . "</p>
											</div>
											</td>";
                                                    if ($row['gfile'] != NULL) {
                                                        echo "<td>  <form action = '" . $row['gfile'] . "' target='_blank' method = 'POST'>
<button type='submit' class='btn btn-link'>View File</button></form> </td>";
                                                    } else echo "<td>No File Attached</td>";
                                                    echo "<td>" . $row['timeofg'] . "</td>";
                                                    echo "<td>" . $row['status'] . "</td>";
                                                    echo "<td>" . $row['uptime'] . "</td>";
                                                    echo "<td >
											  <!-- Trigger the modal with a button -->
											  <button type='button' class = 'btn btn-primary' data-toggle='modal' data-target='#myModal" . $row['gid'] . "'>View</button>

											  <!-- Modal -->
											  <div class='modal' id='myModal" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Action Details:</h4>
														  <button type='button' class='btn btn-outline-danger' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															
																<input type = 'hidden' name = 'uid1' id='uid1' value = '" . $row['uemail'] . "'>
																<input type = 'hidden' name = 'gid1' id='gid1' value = '" . $row['gid'] . "'>
																<p>" . $row['act'] . "</p>
																<br><br>
																<form action='print.php' method='post' target = '_blank'>
																<input type = 'hidden' name = 'uid2' id='uid2' value = '" . $row['uemail'] . "'>
																<input type = 'hidden' name = 'gid2' id='gid2' value = '" . $row['gid'] . "'>
																<button type='submit' class = 'btn btn-primary' class='print'>Download Action Details as a pdf</button></form>	
															
														</div>
													  </div>
													  
													</div>
											  </div></td>";
                                                    echo "</tr>";
                                                }
                                            }
                                        } else if ($_GET['status'] == 'resolved') {


                                            if (mysqli_num_rows($res_resolved) > 0) {
                                                while ($row = mysqli_fetch_assoc($res_resolved)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row['gid'] . "</td>";
                                                    echo "<td>" . $row['gsub'] . "</td>";
                                                    echo "<td>" . $row['gcat'] . "</td>";
                                                    echo "<td> " . substr($row['gdes'], 0, 30) . " ....
											<button type = 'button'  class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'>
											 Show/Hide </button>
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
											  <button type='button' class = 'btn btn-primary' data-toggle='modal' data-target='#myModal" . $row['gid'] . "'>Action Details</button>

											  <!-- Modal -->
											  <div class='modal' id='myModal" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Action Details:</h4>
														  <button type='button' class='btn btn-outline-danger' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															
																<input type = 'hidden' name = 'uid1' id='uid1' value = '" . $row['uemail'] . "'>
																<input type = 'hidden' name = 'gid1' id='gid1' value = '" . $row['gid'] . "'>
																<p>" . $row['act'] . "</p>
																<br><br>
																<form action='print.php' method='post' target = '_blank'>
																<input type = 'hidden' name = 'uid2' id='uid2' value = '" . $row['uemail'] . "'>
																<input type = 'hidden' name = 'gid2' id='gid2' value = '" . $row['gid'] . "'>
																<button type='submit' class = 'btn btn-primary' class='print'>Download Action Details as a pdf</button></form>	
															
														</div>
													  </div>
													  
													</div>
											  </div></td>";
                                                    echo "</tr>";
                                                }
                                            }
                                        } else if ($_GET['status'] == 'offline') {


                                            if (mysqli_num_rows($res_offline) > 0) {
                                                while ($row = mysqli_fetch_assoc($res_offline)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row['gid'] . "</td>";
                                                    echo "<td>" . $row['gsub'] . "</td>";
                                                    echo "<td>" . $row['gcat'] . "</td>";
                                                    echo "<td> " . substr($row['gdes'], 0, 30) . " ....
											<button type = 'button'  class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'> Show/Hide </button>
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
                                                    echo "<td>
											  <!-- Trigger the modal with a button -->
											  <button type='button' class = 'btn btn-primary' data-toggle='modal' data-target='#myModal" . $row['gid'] . "'>Action Details</button>

											  <!-- Modal -->
											  <div class='modal' id='myModal" . $row['gid'] . "' role='dialog'>
													<div class='modal-dialog'>
													
													  <!-- Modal content-->
													  <div class='modal-content'>
														<div class='modal-header'>
														  <h4 class='modal-title'>Action Details:</h4>
														  <button type='button' class='btn btn-outline-danger' data-dismiss='modal'>&times;</button>
														</div>
														<div class='modal-body'>
															
																<input type = 'hidden' name = 'uid1' id='uid1' value = '" . $row['uemail'] . "'>
																<input type = 'hidden' name = 'gid1' id='gid1' value = '" . $row['gid'] . "'>
																<p>" . $row['act'] . "</p>
																<br><br>
																<form action='print.php' method='post' target = '_blank'>
																<input type = 'hidden' name = 'uid2' id='uid2' value = '" . $row['uemail'] . "'>
																<input type = 'hidden' name = 'gid2' id='gid2' value = '" . $row['gid'] . "'>
																<button type='submit' class = 'btn btn-primary' class='print'>Download Action Details as a pdf</button></form>	
															
														</div>
													  </div>
													  
													</div>
											  </div></td>";
                                                    echo "</tr>";
                                                }
                                            }
                                        } else {


                                            if (mysqli_num_rows($res_cancel) > 0) {
                                                while ($row = mysqli_fetch_assoc($res_cancel)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row['gid'] . "</td>";
                                                    echo "<td>" . $row['gsub'] . "</td>";
                                                    echo "<td>" . $row['gcat'] . "</td>";
                                                    echo "<td> " . substr($row['gdes'], 0, 30) . " ....
											<button type = 'button'  class = 'btn btn-primary' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'> Show/Hide </button>
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


                                        if (mysqli_num_rows($res_pending) > 0) {
                                            while ($row = mysqli_fetch_assoc($res_pending)) {
                                                echo "<tr>";
                                                echo "<td>" . $row['gid'] . "</td>";
                                                echo "<td>" . $row['gsub'] . "</td>";
                                                echo "<td>" . $row['gcat'] . "</td>";
                                                echo "<td> " . substr($row['gdes'], 0, 30) . " ....
										<button type = 'button'  class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'> Show/Hide </button>
										<div class='collapse' id='gdesc" . $row['gid'] . "'>
													<p> " . $row['gdes'] . "</p>
										</div>
										</td>";
                                                if ($row['gfile'] != NULL) {
                                                    echo "<td> <form action = '" . $row['gfile'] . "' target='_blank' method = 'POST'>
<button type='submit'  class='btn btn-link'>View File</button></form> </td>";
                                                } else echo "<td>No File Attached</td>";
                                                echo "<td>" . $row['timeofg'] . "</td>";
                                                echo "<td>" . $row['status'] . "</td>";
                                                echo "<td><form method='POST' action='cancel.php' onclick=\"return confirm('Are you sure?')\">
										<input type = 'hidden' name = 'uid' id='uid' value = '" . $row['uemail'] . "'>
										<input type = 'hidden' name = 'gid' id='gid' value = '" . $row['gid'] . "'>
										<button type='submit' class = 'btn btn-danger' name='cancg' id='cancg'>Cancel</button></form></td>";
                                                echo "</tr>";
                                            }
                                        }
                                    }

                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>

                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    <?php
    include "include/footer.php";
    ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
</div>

<?php
include "include/javascripts.php";
?>
<script>
    $(function () {

        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
        });
    });


</script>

</body>
</html>

