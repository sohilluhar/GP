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
$total_griev = 0;

$qu = "SELECT * FROM grievance where (0";
for ($i = 0; $i < count($category); $i++)
    $qu .= " OR gcat='$category[$i]'";
$qu .= ") AND (status = 'In Progress')" . $cond;
$res_inprogress = mysqli_query($conn, $qu);
$total_griev_inprogress = mysqli_num_rows(mysqli_query($conn, $qu));
$total_griev_pending = mysqli_num_rows(mysqli_query($conn, $qu));

$qu = "SELECT * FROM grievance where status='Forwarded to " . $name . "'" . $cond;
$res_forwards = mysqli_query($conn, $qu);
$total_griev_forwars = mysqli_num_rows(mysqli_query($conn, $qu));

$qu = "SELECT * FROM grievance where (0";
for ($i = 0; $i < count($category); $i++)
    $qu .= " OR gcat='$category[$i]'";
$qu .= ") AND (status REGEXP 'Forwarded to')" . $cond;
$res_forwarded = mysqli_query($conn, $qu);
$total_griev_forwarded = mysqli_num_rows(mysqli_query($conn, $qu));

$qu = "SELECT * FROM grievance where (0";
for ($i = 0; $i < count($category); $i++)
    $qu .= " OR gcat='$category[$i]'";
$qu .= ") AND (status = 'Partially Solved')" . $cond;
$res_partsolved = mysqli_query($conn, $qu);
$total_griev_partsolved = mysqli_num_rows(mysqli_query($conn, $qu));

$qu = "SELECT * FROM resolved where (0";
for ($i = 0; $i < count($category); $i++)
    $qu .= " OR gcat='$category[$i]'";
$qu .= ") AND (status='Resolved')" . $cond;
$res_resolved = mysqli_query($conn, $qu);
$total_griev_resolved = mysqli_num_rows(mysqli_query($conn, $qu));

$total_griev = $total_griev + $total_griev_resolved;

$qu = "SELECT * FROM resolved where (0";
for ($i = 0; $i < count($category); $i++)
    $qu .= " OR gcat='$category[$i]'";
$qu .= ") AND (status='Resolved Offline')" . $cond;
$res_offline = mysqli_query($conn, $qu);
$total_griev_offline = mysqli_num_rows(mysqli_query($conn, $qu));


$qu = "SELECT * FROM cancelled where (0";
for ($i = 0; $i < count($category); $i++)
    $qu .= " OR gcat='$category[$i]'";
$qu .= ")" . $cond;
$res_cancel = mysqli_query($conn, $qu);
$total_griev_cancel = mysqli_num_rows(mysqli_query($conn, $qu));

$qu = "SELECT * FROM grievance where (0";
for ($i = 0; $i < count($category); $i++)
    $qu .= " OR gcat='$category[$i]'";
$qu .= ") AND (status = 'Pending')" . $cond;
$res_pending = mysqli_query($conn, $qu);
$total_griev_pending = mysqli_num_rows(mysqli_query($conn, $qu));


$total_griev = $total_griev_pending + $total_griev_inprogress + $total_griev_partsolved +
    $total_griev_resolved + $total_griev_forwars + $total_griev_forwarded + $total_griev_offline;

if (isset($_POST['submit'])) {
    $fileSize = $_FILES['atfile']['size'];
    if ($fileSize > 134217728) {
        $_SESSION['fileerr'] = "<h5><font color='red'>File size too large. Please choose another file of size less than 2MB.</font></h5>";
    } else {
        date_default_timezone_set('Asia/Kolkata');
        $q = getdate();

        $day = $q['mday'];
        $month = $q['mon'];
        $year = $q['year'];

        if ($day < 10)
            $day = '0' . $day;
        if ($month < 10)
            $month = '0' . $month;

        $string = $day . $month . $year;
        $query = "SELECT gid FROM grievance WHERE gid REGEXP '" . $string . "'";

        $i = mysqli_num_rows(mysqli_query($conn, $query));

        if ($i < 10)
            $num = '000' . $i;
        elseif ($i > 9 && $i < 100)
            $num = '00' . $i;
        elseif ($i > 99 && $i < 1000)
            $num = '0' . $i;
        else
            $num = '' . $i;

        $gid = $string . $num;
        $urole = $_POST['urole'];
        $gsub = $_POST['gsub'];
        $gcat = $_POST['gcat'];
        $gdesc = $_POST['gdesc'];
        $uemail = $_SESSION['loggedInUser'];
        $file = NULL;

        if (isset($_FILES['atfile'])) {
            $fileName = $_FILES['atfile']['name'];
            $fileSize = $_FILES['atfile']['size'];
            $fileTmp = $_FILES['atfile']['tmp_name'];
            $fileType = $_FILES['atfile']['type'];

            $temp = explode('.', $fileName);
            $fileExt = strtolower(end($temp));
            $targetName = "proofDocs/" . $gid . "." . $fileExt;

            if (file_exists($targetName)) {
                unlink($targetName);
            }
            $moved = move_uploaded_file($fileTmp, $targetName);
            if ($moved == true) {
                //successful
                $query1 = "INSERT INTO grievance(gid,uemail,urole,gsub,gcat,gdes,gfile,timeofg) VALUES ('$gid','$uemail','$urole','$gsub','$gcat','$gdesc','" . $targetName . "',CURRENT_TIMESTAMP)";
                $r = mysqli_query($conn, $query1);
                if ($r) {
                    header("location: griev.php");
                }
            } else {
                $query1 = "INSERT INTO grievance(gid,uemail,urole,gsub,gcat,gdes,gfile,timeofg) VALUES ('$gid','$uemail','$urole','$gsub','$gcat','$gdesc','$file',CURRENT_TIMESTAMP)";
                $r = mysqli_query($conn, $query1);
                if ($r) {
                    header("location: griev.php");
                }
            }
        } else {
            echo "No file detected";
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <?php
    include "include/html_head.php";
    ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <?php
    include "include/nav_header.php";
    include "include/community_sidebar.php";
    ?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Dashboard</h1>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-gradient-primary">
                            <div class="inner">
                                <p>Total Grievances</p>
                                <h3><?php echo $total_griev; ?></h3>

                            </div>

                            <a href="comm.php?status=pending" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-gradient-danger">
                            <div class="inner">
                                <p>Pending </p>
                                <h3><?php echo $total_griev_pending; ?></h3>

                            </div>
                            <!--                            <div class="icon">-->
                            <!--                                <i class="ion ion-stats-bars"></i>-->
                            <!--                            </div>-->
                            <a href="comm.php?status=pending" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-gradient-warning">
                            <div class="inner">
                                <p class="text-white">In-Progress</p>
                                <h3 class="text-white"><?php echo $total_griev_inprogress; ?></h3>
                            </div>
                            <!--                            <div class="icon">-->
                            <!--                                <i class="ion ion-person-add"></i>-->
                            <!--                            </div>-->
                            <a href="comm.php?status=in_progress" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->

                    <!-- ./col -->


                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-gradient-orange">
                            <div class="inner">
                                <p class="text-white">Forwards</p>
                                <h3 class="text-white"><?php echo $total_griev_forwars; ?></h3>
                            </div>
                            <!--                            <div class="icon">-->
                            <!--                                <i class="ion ion-pie-graph"></i>-->
                            <!--                            </div>-->
                            <a href="comm.php?status=forwards" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-gradient-orange">
                            <div class="inner">
                                <p class="text-white">Forwarded</p>
                                <h3 class="text-white"><?php echo $total_griev_forwarded; ?></h3>
                            </div>
                            <!--                            <div class="icon">-->
                            <!--                                <i class="ion ion-pie-graph"></i>-->
                            <!--                            </div>-->
                            <a href="comm.php?status=forwarded" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-gradient-olive">
                            <div class="inner">

                                <p>Partially Solved</p>
                                <h3><?php echo $total_griev_partsolved; ?></h3>

                            </div>
                            <!--                            <div class="icon">-->
                            <!--                                <i class="ion ion-pie-graph"></i>-->
                            <!--                            </div>-->
                            <a href="comm.php?status=partially_solved" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-gradient-success">
                            <div class="inner">
                                <p>Solved</p>
                                <h3><?php echo $total_griev_resolved; ?></h3>

                            </div>
                            <!--                            <div class="icon">-->
                            <!--                                <i class="ion ion-pie-graph"></i>-->
                            <!--                            </div>-->
                            <a href="comm.php?status=resolved" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-gradient-secondary">
                            <div class="inner">
                                <p>Offline</p>
                                <h3><?php echo $total_griev_offline; ?></h3>

                            </div>
                            <!--                            <div class="icon">-->
                            <!--                                <i class="ion ion-pie-graph"></i>-->
                            <!--                            </div>-->
                            <a href="comm.php?status=offline" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-12">
                            <h3 class="text-center">Submitted Grievance</h3>
                        </div>
                    </div>
                </div>

                <!-- /.container-fluid -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">

                                    <?php
                                    if (isset($_GET['status'])) {
                                        if ($_GET['status'] == 'pending') {
                                            $qu = "SELECT * FROM grievance where (0";
                                            for ($i = 0; $i < count($category); $i++)
                                                $qu .= " OR gcat='$category[$i]'";
                                            $qu .= ") AND (status = 'Pending')" . $cond;
                                            $res_pending = mysqli_query($conn, $qu);
                                            echo "Pending  ";
                                        } else if ($_GET['status'] == 'in_progress') {
                                            $qu = "SELECT * FROM grievance where (0";
                                            for ($i = 0; $i < count($category); $i++)
                                                $qu .= " OR gcat='$category[$i]'";
                                            $qu .= ") AND (status = 'In Progress')" . $cond;
                                            $res_inprogress = mysqli_query($conn, $qu);
                                            echo "In Progress  ";
                                        } else if ($_GET['status'] == 'forwards') {
                                            $qu = "SELECT * FROM grievance where status='Forwarded to " . $name . "'" . $cond;
                                            $res_forwards = mysqli_query($conn, $qu);
                                            echo "Forwards  ";
                                        } else if ($_GET['status'] == 'forwarded') {
                                            $qu = "SELECT * FROM grievance where (0";
                                            for ($i = 0; $i < count($category); $i++)
                                                $qu .= " OR gcat='$category[$i]'";
                                            $qu .= ") AND (status REGEXP 'Forwarded to')" . $cond;
                                            $res_forwarded = mysqli_query($conn, $qu);
                                            echo "Forwarded  ";
                                        } else if ($_GET['status'] == 'partially_solved') {
                                            $qu = "SELECT * FROM grievance where (0";
                                            for ($i = 0; $i < count($category); $i++)
                                                $qu .= " OR gcat='$category[$i]'";
                                            $qu .= ") AND (status = 'Partially Solved')" . $cond;
                                            $res_partsolved = mysqli_query($conn, $qu);
                                            echo "Partially Solved  ";
                                        } else if ($_GET['status'] == 'resolved') {
                                            $qu = "SELECT * FROM resolved where (0";
                                            for ($i = 0; $i < count($category); $i++)
                                                $qu .= " OR gcat='$category[$i]'";
                                            $qu .= ") AND (status='Resolved')" . $cond;
                                            $res_resolved = mysqli_query($conn, $qu);
                                            echo "Resolved  ";
                                        } else if ($_GET['status'] == 'offline') {
                                            $qu = "SELECT * FROM resolved where (0";
                                            for ($i = 0; $i < count($category); $i++)
                                                $qu .= " OR gcat='$category[$i]'";
                                            $qu .= ") AND (status='Resolved Offline')" . $cond;
                                            $res_offline = mysqli_query($conn, $qu);
                                            echo "Offline Reports  ";
                                        } else {
                                            $qu = "SELECT * FROM cancelled where (0";
                                            for ($i = 0; $i < count($category); $i++)
                                                $qu .= " OR gcat='$category[$i]'";
                                            $qu .= ")" . $cond;
                                            $res_cancel = mysqli_query($conn, $qu);
                                            echo "Cancelled  ";
                                        }
                                    } else {
                                        $qu = "SELECT * FROM grievance where (0";
                                        for ($i = 0; $i < count($category); $i++)
                                            $qu .= " OR gcat='$category[$i]'";
                                        $qu .= ") AND (status = 'Pending')" . $cond;
                                        $res_pending = mysqli_query($conn, $qu);
                                        echo "Pending  ";
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
										<th>Grievance by</th>
										<th>Grievance Subject</th>
										<th>Grievance Category - Type</th>
										<th>Description</th>
										<th>File Attached</th>
										<th>Time of Issue</th>
										<th>Grievance Status</th>
										<th>Acknowledge Issue</th>
										<th>Forward Issue</th>";
                                            } else if ($_GET['status'] == 'in_progress') {
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
                                            } else if ($_GET['status'] == 'forwards') {
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
                                            } else if ($_GET['status'] == 'forwarded') {
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
                                            } else if ($_GET['status'] == 'partially_solved') {
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
                                            } else if ($_GET['status'] == 'resolved') {
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
                                            } else if ($_GET['status'] == 'offline') {
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
                                    if (isset($_GET['status'])) {
                                        if ($_GET['status'] == 'pending') {
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
                                                    echo "<td>";
                                                    if ($row['urole'] == 'Student') {
                                                        echo "<button type='button' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> " . $namerow['fname'] . " " . $namerow['lname'] . " </button>
												
												   <div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Student Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
              <p>Name: " . $name . "</p>
                <p>	Role: Student</p>
                <p>	Email ID: " . $row['uemail'] . "</p>
                    <p>Roll No.: " . $roll . "</p>
                    <p>Class: " . $class . "</p>
                    <p>Department: " . $dept . "</p>
                    <p>Year of Joining: " . $joinyear . "</p>
            </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

												
											</td>";
                                                    } elseif ($row['urole'] == 'Employee') {
                                                        echo "<button type='button' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'>" . $namerow['fname'] . " " . $namerow['lname'] . "  </button>
		
												   <div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Employee Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
             	<p>Name: " . $name . "</p>
                <p>Role: Employee</p>
                <p>Email ID: " . $row['uemail'] . "</p>
                <p>ID No.: " . $roll . "</p>
                <p>Department: " . $dept . "</p>
                <p>Designation: " . $designation . "</p>
                <p>Date of Joining: " . $joindate . "</p>
                                                    </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
											
											
													
											</td>";
                                                    }

                                                    echo "<td>" . $row['gsub'] . "</td>";
                                                    echo "<td>" . $row['gcat'] . "</td>";
                                                    echo "<td>
											<button type = 'button' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'> Show/Hide </button>
											<div class='collapse' id='gdesc" . $row['gid'] . "'>
														<p> " . $row['gdes'] . "</p>
											</div>
											</td>";
                                                    if ($row['gfile'] != NULL) {
                                                        echo "<td> <form action = '" . $row['gfile'] . "' target='_blank' method = 'POST'>
<button type='submit' class='btn btn-link'>View File</button></form> </td>";
                                                    } else echo "<td>No File Attached</td>";
                                                    echo "<td>" . $row['timeofg'] . "</td>";
                                                    echo "<td>" . $row['status'] . "</td>";
                                                    echo "<td><form method='POST' action='acknowledge.php' onclick=\"return confirm('Are you sure?')\">
											<input type = 'hidden' name = 'uid' id='uid' value = '" . $row['uemail'] . "'>
											<input type = 'hidden' name = 'gid' id='gid' value = '" . $row['gid'] . "'>
											<button type='submit' class = 'btn btn-primary' name='sendm' id='sendm'> Acknowledge </button></form></td>";
                                                    echo "<td ><button type = 'button' class = 'btn btn-primary' data-toggle = 'modal' data-target = '#forwardModal" . $row['gid'] . "'> Forward </button>";
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

                                                    echo "</select> </br></br><button type = 'submit' class = 'btn btn-primary btn-block' name = 'forwardissue' id = 'forwardissue' value = 'forwardIssue'  onclick=\"return confirm('Are you sure?')\"> Forward Issue </button>
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
                                        } else if ($_GET['status'] == 'in_progress') {


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
                                                        echo "<button type='button' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												
																			
												   <div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Student Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
              <p>Name: " . $name . "</p>
                <p>	Role: Student</p>
                <p>	Email ID: " . $row['uemail'] . "</p>
                    <p>Roll No.: " . $roll . "</p>
                    <p>Class: " . $class . "</p>
                    <p>Department: " . $dept . "</p>
                    <p>Year of Joining: " . $joinyear . "</p>
            </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
												
											</td>";
                                                    } elseif ($row['urole'] == 'Employee') {
                                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
										
										
						
												   <div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Employee Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
             	<p>Name: " . $name . "</p>
                <p>Role: Employee</p>
                <p>Email ID: " . $row['uemail'] . "</p>
                <p>ID No.: " . $roll . "</p>
                <p>Department: " . $dept . "</p>
                <p>Designation: " . $designation . "</p>
                <p>Date of Joining: " . $joindate . "</p>
                                                    </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->										
										
											</td>";
                                                    }

                                                    echo "<td>" . $row['gsub'] . "</td>";
                                                    echo "<td>" . $row['gcat'] . "</td>";
                                                    echo "<td>
											<button type = 'button' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#gdesc" . $row['gid'] . "'> Show/Hide </button>
											<div class='collapse' id='gdesc" . $row['gid'] . "'>
														<p> " . $row['gdes'] . "</p>
											</div>
											</td>";
                                                    if ($row['gfile'] != NULL) {
                                                        echo "<td>  <form action = '" . $row['gfile'] . "' target='_blank' method = 'POST'><button type='submit' 
 class='btn btn-link'>View File</button></form> </td>";
                                                    } else echo "<td>No File Attached</td>";
                                                    echo "<td>" . $row['timeofg'] . "</td>";
                                                    echo "<td>" . $row['status'] . "</td>";
                                                    echo "<td>" . $row['uptime'] . "</td>";
                                                    echo "<td >
												  <!-- Trigger the modal with a button -->
												  <button type='button' class = 'btn  btn-primary' data-toggle='modal' data-target='#myModal" . $row['gid'] . "'>
												  Details</button>

 <div class=\"modal fade\" id='myModal" . $row['gid'] . "'>
        <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Enter Action Details:</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <form method='POST' action='done.php' >
																	
															
            <div class=\"modal-body\">
            
            <div class='col-12'>
                <input type = 'hidden' name = 'uid1' id='uid1' value = '" . $row['uemail'] . "'>
                <input type = 'hidden' name = 'gid1' id='gid1' value = '" . $row['gid'] . "'>
                <textarea maxlength = '255' name = 'actiondet' id = 'actiondet' class = 'form-control' placeholder = 'Enter Action Details Here!' cols = '50' required></textarea></br>
            </div>
										
            </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
              <button type = 'submit' class = 'btn btn-primary ' name = 'acttaken' id = 'acttaken' value = 'ActionTaken'>Resolved</button>
             
              <button type = 'submit' class = 'btn btn-outline-primary' name = 'partact' id = 'partact' value = 'PartialAction'>Partially Solved</button>																				
            </div>
            	</form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
												 
												 
												  
												  </td>";
                                                    echo "<td ><button type = 'button' class = 'btn  btn-primary' data-toggle = 'modal' data-target = '#forwardModal" . $row['gid'] . "'> Forward </button>";
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

                                                    echo "</select> </br></br><button type = 'submit' class = 'btn btn-primary btn-block' name = 'forwardissue' id = 'forwardissue' value = 'forwardIssue'  onclick=\"return confirm('Are you sure?')\"> Forward Issue </button>
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
                                        } else if ($_GET['status'] == 'forwards') {


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
                                                        echo "<button type='button'  class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
                                                        							
												   <div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Student Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
              <p>Name: " . $name . "</p>
                <p>	Role: Student</p>
                <p>	Email ID: " . $row['uemail'] . "</p>
                    <p>Roll No.: " . $roll . "</p>
                    <p>Class: " . $class . "</p>
                    <p>Department: " . $dept . "</p>
                    <p>Year of Joining: " . $joinyear . "</p>
            </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
											</td>";
                                                    } elseif ($row['urole'] == 'Employee') {
                                                        echo "<button type='button'  class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
																	
												   <div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Employee Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
             	<p>Name: " . $name . "</p>
                <p>Role: Employee</p>
                <p>Email ID: " . $row['uemail'] . "</p>
                <p>ID No.: " . $roll . "</p>
                <p>Department: " . $dept . "</p>
                <p>Designation: " . $designation . "</p>
                <p>Date of Joining: " . $joindate . "</p>
                                                    </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
											</td>";
                                                    }

                                                    echo "<td>" . $row['gsub'] . "</td>";
                                                    echo "<td>" . $row['gcat'] . "</td>";
                                                    echo "<td>
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
                                                    echo "<td>
											<button type = 'button' class = 'btn btn-link' data-toggle = 'collapse' data-target = '#forwarddetails" . $row['gid'] . "'> Show/Hide </button>
											<div class='collapse' id='forwarddetails" . $row['gid'] . "'>
														<p> " . $row['fordet'] . "</p>
											</div>
											</td>";
                                                    echo "<td>" . $row['uptime'] . "</td>";
                                                    echo "<td >
												  <!-- Trigger the modal with a button -->
												  <button type='button' class = 'btn  btn-primary' data-toggle='modal' data-target='#myModal" . $row['gid'] . "'>Details</button>

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
																			<button type = 'submit' class = 'btn btn-primary btn-block' name = 'returncomm' id = 'returncomm' value = 'returnComm'>Return to $respcomm</button>
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
                                        } else if ($_GET['status'] == 'forwarded') {


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
										
																	
												   <div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Student Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
              <p>Name: " . $name . "</p>
                <p>	Role: Student</p>
                <p>	Email ID: " . $row['uemail'] . "</p>
                    <p>Roll No.: " . $roll . "</p>
                    <p>Class: " . $class . "</p>
                    <p>Department: " . $dept . "</p>
                    <p>Year of Joining: " . $joinyear . "</p>
            </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
											</td>";
                                                    } elseif ($row['urole'] == 'Employee') {
                                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
				
												   <div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Employee Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
             	<p>Name: " . $name . "</p>
                <p>Role: Employee</p>
                <p>Email ID: " . $row['uemail'] . "</p>
                <p>ID No.: " . $roll . "</p>
                <p>Department: " . $dept . "</p>
                <p>Designation: " . $designation . "</p>
                <p>Date of Joining: " . $joindate . "</p>
                                                    </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
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
                                                    echo "<td>
												  <!-- Trigger the modal with a button -->
												  <button type='button' class = 'btn  btn-primary' data-toggle='modal' data-target='#myModal" . $row['gid'] . "'>Details</button>



 <div class=\"modal fade\" id='myModal" . $row['gid'] . "'>
        <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Enter Action Details:</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <form method='POST' action='done.php' >
																	
															
            <div class=\"modal-body\">
            
            
            <div class='col-12'>
            ";
                                                    if ($row['act']) {
                                                        echo "<h4> Previous actions on the grievance: </h4>
																<p> " . $row['act'] . " </p>";
                                                    }
                                                    echo "
                <input type = 'hidden' name = 'uid1' id='uid1' value = '" . $row['uemail'] . "'>
                <input type = 'hidden' name = 'gid1' id='gid1' value = '" . $row['gid'] . "'>
                <textarea maxlength = '255' name = 'actiondet' id = 'actiondet' class = 'form-control' placeholder = 'Enter Action Details Here!' cols = '50' required></textarea></br>
            </div>
										
            </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
              <button type = 'submit' class = 'btn btn-primary ' name = 'acttaken' id = 'acttaken' value = 'ActionTaken'>Resolved</button>
             
              <button type = 'submit' class = 'btn btn-outline-primary' name = 'partact' id = 'partact' value = 'PartialAction'>Partially Solved</button>																				
            </div>
            	</form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
					

												 
												  
												  </td>";
                                                    echo "</tr>";
                                                }
                                            }
                                        } else if ($_GET['status'] == 'partially_solved') {


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
                                                        echo "<button type='button' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												
																														
																														
																														   <div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Student Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
              <p>Name: " . $name . "</p>
                <p>	Role: Student</p>
                <p>	Email ID: " . $row['uemail'] . "</p>
                    <p>Roll No.: " . $roll . "</p>
                    <p>Class: " . $class . "</p>
                    <p>Department: " . $dept . "</p>
                    <p>Year of Joining: " . $joinyear . "</p>
            </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
											
											</td>";
                                                    } elseif ($row['urole'] == 'Employee') {
                                                        echo "<button type='button' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												
													   <div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Employee Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
             	<p>Name: " . $name . "</p>
                <p>Role: Employee</p>
                <p>Email ID: " . $row['uemail'] . "</p>
                <p>ID No.: " . $roll . "</p>
                <p>Department: " . $dept . "</p>
                <p>Designation: " . $designation . "</p>
                <p>Date of Joining: " . $joindate . "</p>
                                                    </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
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
												  <button type='button' class = 'btn  btn-primary' data-toggle='modal' data-target='#myModal" . $row['gid'] . "'>Details</button>";


                                                    echo "        <div class=\"modal fade\" id='myModal" . $row['gid'] . "'>
        <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Enter Action Details:</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <form method='POST' action='done.php' >
																	
															
            <div class=\"modal-body\">
            
            
            <div class='col-12'>
            ";
                                                    if ($row['act']) {
                                                        echo "<h4> Previous actions on the grievance: </h4>
																<p> " . $row['act'] . " </p>";
                                                    }
                                                    echo "
                <input type = 'hidden' name = 'uid1' id='uid1' value = '" . $row['uemail'] . "'>
                <input type = 'hidden' name = 'gid1' id='gid1' value = '" . $row['gid'] . "'>
                <textarea maxlength = '255' name = 'actiondet' id = 'actiondet' class = 'form-control' placeholder = 'Enter Action Details Here!' cols = '50' required></textarea></br>
            </div>
										
            </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
              <button type = 'submit' class = 'btn btn-primary ' name = 'acttaken' id = 'acttaken' value = 'ActionTaken'>Resolved</button>
             
              <button type = 'submit' class = 'btn btn-outline-primary' name = 'partact' id = 'partact' value = 'PartialAction'>Partially Solved</button>																				
            </div>
            	</form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>";


                                                    echo " 
												  
												  </td>";
                                                    echo "<td><button type = 'button' class = 'btn  btn-primary' data-toggle = 'modal' data-target = '#forwardModal" . $row['gid'] . "'> Forward </button>";
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

                                                    echo "</select> </br></br><button type = 'submit' class = 'btn btn-primary btn-block' name = 'forwardissue' id = 'forwardissue' value = 'forwardIssue'  onclick=\"return confirm('Are you sure?')\"> Forward Issue </button>
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
                                        } else if ($_GET['status'] == 'resolved') {


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
																														   <div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Student Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
              <p>Name: " . $name . "</p>
                <p>	Role: Student</p>
                <p>	Email ID: " . $row['uemail'] . "</p>
                    <p>Roll No.: " . $roll . "</p>
                    <p>Class: " . $class . "</p>
                    <p>Department: " . $dept . "</p>
                    <p>Year of Joining: " . $joinyear . "</p>
            </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
												  
											</td>";
                                                    } elseif ($row['urole'] == 'Employee') {
                                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												 <div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Employee Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
             	<p>Name: " . $name . "</p>
                <p>Role: Employee</p>
                <p>Email ID: " . $row['uemail'] . "</p>
                <p>ID No.: " . $roll . "</p>
                <p>Department: " . $dept . "</p>
                <p>Designation: " . $designation . "</p>
                <p>Date of Joining: " . $joindate . "</p>
                                                    </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
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
												  <button type='button' class = 'btn  btn-primary' data-toggle='modal' data-target='#myModal" . $row['gid'] . "'>Action Details</button>

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
																	<button type='submit' class = 'btn btn-primary' class='print'>Download Grievance Report as a pdf</button></form>	
																
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
												
																														   <div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Student Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
              <p>Name: " . $name . "</p>
                <p>	Role: Student</p>
                <p>	Email ID: " . $row['uemail'] . "</p>
                    <p>Roll No.: " . $roll . "</p>
                    <p>Class: " . $class . "</p>
                    <p>Department: " . $dept . "</p>
                    <p>Year of Joining: " . $joinyear . "</p>
            </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
												   
											</td>";
                                                    } elseif ($row['urole'] == 'Employee') {
                                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												<div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Employee Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
             	<p>Name: " . $name . "</p>
                <p>Role: Employee</p>
                <p>Email ID: " . $row['uemail'] . "</p>
                <p>ID No.: " . $roll . "</p>
                <p>Department: " . $dept . "</p>
                <p>Designation: " . $designation . "</p>
                <p>Date of Joining: " . $joindate . "</p>
                                                    </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
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
												  <button type='button' class = 'btn  btn-primary' data-toggle='modal' data-target='#myModal" . $row['gid'] . "'>Action Details</button>

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
																	<button type='submit' class = 'btn btn-primary' class='print'>Download Grievance Report as a pdf</button></form>	
																
															</div>
														  </div>
														  
														</div>
												  </div></td>";
                                                    echo "</tr>";
                                                }
                                            }
                                        } else {


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
												
																														   <div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Student Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
              <p>Name: " . $name . "</p>
                <p>	Role: Student</p>
                <p>	Email ID: " . $row['uemail'] . "</p>
                    <p>Roll No.: " . $roll . "</p>
                    <p>Class: " . $class . "</p>
                    <p>Department: " . $dept . "</p>
                    <p>Year of Joining: " . $joinyear . "</p>
            </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
												  
											</td>";
                                                    } elseif ($row['urole'] == 'Employee') {
                                                        echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
												 <div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Employee Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
             	<p>Name: " . $name . "</p>
                <p>Role: Employee</p>
                <p>Email ID: " . $row['uemail'] . "</p>
                <p>ID No.: " . $roll . "</p>
                <p>Department: " . $dept . "</p>
                <p>Designation: " . $designation . "</p>
                <p>Date of Joining: " . $joindate . "</p>
                                                    </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
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
																												   <div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Student Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
              <p>Name: " . $name . "</p>
                <p>	Role: Student</p>
                <p>	Email ID: " . $row['uemail'] . "</p>
                    <p>Roll No.: " . $roll . "</p>
                    <p>Class: " . $class . "</p>
                    <p>Department: " . $dept . "</p>
                    <p>Year of Joining: " . $joinyear . "</p>
            </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
												  
										</td>";
                                                } elseif ($row['urole'] == 'Employee') {
                                                    echo "<button type='button' style = 'color: #4be1e1;' class='btn btn-link' data-toggle = 'modal' data-target = '#studentdetails" . $row['gid'] . "'> View Details </button>
											 <div class=\"modal fade\" id='studentdetails" . $row['gid'] . "'>
             <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h4 class=\"modal-title\">Employee Details</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
             	<p>Name: " . $name . "</p>
                <p>Role: Employee</p>
                <p>Email ID: " . $row['uemail'] . "</p>
                <p>ID No.: " . $roll . "</p>
                <p>Department: " . $dept . "</p>
                <p>Designation: " . $designation . "</p>
                <p>Date of Joining: " . $joindate . "</p>
                                                    </div>
            <div class=\"modal-footer justify-content-between\">
              <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
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
										<button type='submit' class = 'btn  btn-primary' name='sendm' id='sendm'> Acknowledge </button></form></td>";
                                                echo "<td style = 'color: #000000;'><button type = 'button' class = 'btn  btn-primary' data-toggle = 'modal' data-target = '#forwardModal" . $row['gid'] . "'> Forward </button>";
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

                                                echo "</select> </br></br><button type = 'submit' class = 'btn btn-primary btn-block' name = 'forwardissue' id = 'forwardissue' value = 'forwardIssue'  onclick=\"return confirm('Are you sure?')\"> Forward Issue </button>
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
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>

                </div>

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
if (isset($_SESSION['alert'])) {
    echo "<script> alert('" . $_SESSION['alert'] . "');</script>";
    unset($_SESSION['alert']);
}
?>
<?php
include "include/javascripts.php";
?>

<script src="./include/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="./include/plugins/jquery-validation/additional-methods.min.js"></script>

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