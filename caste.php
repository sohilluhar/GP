<?php include 'header.php';?>
<?php

session_start();
include_once("includes/connection.php");
if(!isset($_SESSION['loggedInUser'])){
    header("location:index.php");
}

$user=$_SESSION['loggedInUser'];

$query = "SELECT name from userdet where email = '$user'";
$res=mysqli_query($conn,$query);

if(mysqli_num_rows($res)>0)
	$name = mysqli_fetch_assoc($res)['name'];
else
	$name = "Guest";
?>

<!Doctype html>
<html>
<header align="right"><?php echo $_SESSION['loggedInUser'].' | <a href="logout.php">Logout</a>'; ?></header>
<?php echo "<h4>Hi, ".$name."!</h4>" ?>
<form method = "POST" action = "castereports.php"><button type = "submit" name = "subgriev" class = "btn btn-primary"><header align="left">View your Caste Discrimination Reports</header></button></form>

<body>
<div class="container">
            <form method = "POST" action="castereports.php" class="form-horizontal">
                <h2 align="center">Caste Description Report Form</h2>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Grievance Subject: </label>
                    <div class="col-sm-9">
                        <input type = "text" name = "gsub" id = "gsub" required placeholder = "Enter Grievance Subject" class="form-control" autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Grievance Description:</label>
                    <div class="col-sm-9">
                        <textarea name = "gdesc" id = "gdesc" class="form-control" required placeholder = "Describe the grievance"></textarea>
                    </div>
                </div>
                <input type="hidden" id="gcat" name="gcat" value="Caste Discrimination">
                <input type="hidden" id="gtype" name="gtype" value="-">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Attach any supporting files: </label>
                    <div class="col-sm-9">
                        <input type="file" name="atfile" id="atfile" class="form-control">
                    </div>
                </div>
                <button type = "submit" name = "submit" id = "submit" class="btn btn-primary btn-block">Submit Grievance</button>
            </form> <!-- /form -->
        </div> <!-- ./container -->
</body>

</html>
<?php include 'footer.php';?>