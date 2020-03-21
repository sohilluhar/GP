<?php
//every year in July

include("includes/connection.php");

$q1 = "UPDATE userdet SET class = 'SY' where class = 'FY'";
$q2 = "UPDATE userdet SET class = 'TY' where class = 'SY'";
$q3 = "UPDATE userdet SET class = 'LY' where class = 'TY'";
$q4 = "UPDATE userdet SET class = 'Passed Out' AND suspend = 1 where class = 'LY'";

$r1 = mysqli_query($conn,$q1);
$r2 = mysqli_query($conn,$q2);
$r3 = mysqli_query($conn,$q3);
$r4 = mysqli_query($conn,$q4);

$q = "SELECT sustime from userdet";
$o = mysqli_query($conn,$q);
while($row = mysqli_fetch_assoc($o)) {
	if(date("Y") - $row['sustime'] == 4) {
		$q = "DELETE from userdet where id = '".$row['id']."' ";
		$q1 = "DELETE from grievances where gid = '".$row['id']."' ";
		$q2 = "DELETE from resolved where gid = '".$row['id']."' ";
		$q3 = "DELETE from cancelled where gid = '".$row['id']."' ";
		$r = mysqli_query($conn,$q);
		$r1 = mysqli_query($conn,$q1);
		$r1 = mysqli_query($conn,$q2);
		$r1 = mysqli_query($conn,$q3);
	}
}

?>