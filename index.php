<?php 
	include 'header.php';
	session_start();
	session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Grievance Portal</title>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {
  font-family: Arial;
  color: black;
}

button {
	padding: 20%;
	background: rgba(220,220,220,0.5);
	border-width: 5px;
	border-style: inset outset outset inset;
}

button:hover {
	background: rgba(250,250,250,1);
}

.centered {
  text-align: center;
  height: 100%;
}

.centered img {
  width: 150px;
  border-radius: 50%;
}
</style>
</head>

<body>
<div class = "container">

<div class="col-sm-4">
  <div class="centered">
  <form method = "POST" action = "login.php">
    <button type="submit" class = "btn-block" name="student">
		<img src="img/Avatar.jpg" alt="Avatar Student">
		<h2>STUDENT</h2>
	</button>
  </form>
  </div>
</div>

<div class="col-sm-4">
  <div class="centered">
  <form method = "POST" action = "login.php">
    <button type="submit" class = "btn-block" name="employee">
		<img src="img/Employee.jpg" alt="Avatar Employee">
		<h2>EMPLOYEE</h2>
	</button>
  </form>
  </div>
</div>

<div class="col-sm-4">
  <div class="centered">
  <form method = "POST" action = "loginc.php">
    <button type="submit" class = "btn-block" name="committee">
		<img src="img/Committee.jpg" alt="Avatar Committee">
		<h2>COMMITTEE</h2>
	</button>
  </form>
  </div>
</div>

</div>

</body>
</html>
<?php include 'footer.php';?>