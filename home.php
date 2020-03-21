<?php
session_start();
include_once("includes/connection.php");
if (!isset($_SESSION['loggedInUser'])) {
    header("location:index.php");
}
$user = $_SESSION['loggedInUser'];
$uname = $_SESSION['name'];
$_SESSION['fileerr'] = "";


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
    include "include/user_sidebar.php";
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
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-12">
                            <h3 class="text-center">Submit New Grievance</h3>
                        </div>

                    </div>
                </div><!-- /.container-fluid -->
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-8 col-lg-8 col-sm-12 offset-md-2 offset-lg-2">
                        <!-- jquery validation -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Grievance Form</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->

                            <form role="form" method="POST" id="grievanceForm" enctype="multipart/form-data">
                                <input type='hidden' name='urole' value='Student'>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label><span class="text-danger">*</span> Grievance Category:</label>
                                        <select class="form-control" name="gcat" id="gcat" required>
                                            <option value="">Select Grievance Category</option>
                                            <?php
                                            $q = "SELECT category,comm_name,student FROM catdet";
                                            $result = mysqli_query($conn, $q);
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    if ($row['student'] == 1)
                                                        echo "<option value = '" . $row['category'] . "'> " . $row['category'] . " - " . $row['comm_name'] . " </option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><span class="text-danger">*</span> Grievance
                                            Subject:</label>
                                        <input type="text" name="gsub" id="gsub" class="form-control"
                                               placeholder="Enter Grievance Subject">
                                    </div>
                                    <div class="form-group">
                                        <label><span class="text-danger">*</span> Grievance Description:</label>
                                        <textarea class="form-control" rows="3"
                                                  id="gdesc" name="gdesc"
                                                  placeholder="Enter Grievance Description "

                                        ></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFile">Attach any supporting files:</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="atfile" name="atfile">
                                                <label class="custom-file-label" for="exampleInputFile">Choose
                                                    file</label>
                                                <?php
                                                echo $_SESSION['fileerr'];
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" name="submit" id="submit" class="btn btn-primary">Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (left) -->
                    <!-- right column -->

                    <!--/.col (right) -->
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

<script src="./include/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="./include/plugins/jquery-validation/additional-methods.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {

        $('#grievanceForm').validate({
            rules: {
                gcat: {
                    required: true
                },
                gsub: {
                    required: true

                },
                gdesc: {
                    required: true
                },
            },
            messages: {
                gcat: {required: "Please select grievance category"}
                ,
                gsub: {
                    required: "Please provide a grievance subject"
                },
                gdesc: {
                    required: "Please provide a grievance description ",
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
</body>
</html>