<?php
include 'header.php';
include_once("includes/connection.php");

if(isset($_POST['sub'])) {
    $path = $_POST['filest'];
    $row = 1;
    if (($handle = fopen("$path", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
            $num = count($data);
            //echo "<p> $num fields in line $row: <br /></p>\n";
            //$row++;
            $fname = $data[0];
            $lname = $data[1];
            $fathername = $data[2];
            $mothername = $data[3];
            $id = $data[4];
            $email = $data[5];
            $class = $data[6];
            $acadyear = $data[7];
            $dept = $data[8];
            // for ($c=0; $c < $num; $c++,$row++) {
            //     echo $data[$c] . "<br />\n";
            // }
            $query = "INSERT INTO userdet(fname,lname,fathername,mothername,id,email,class,acadyear,dept) VALUES ('".$fname."','".$lname."','".$fathername."','".$mothername."','".$id."','".$email."','".$class."','".$acadyear."','".$dept."') ";
            $r = mysqli_query($conn,$query);
            // if($r) echo "Data inserted";
            // else echo "Data not inserted";

        }
        fclose($handle);
    }
}

else if(isset($_POST['subf'])) {
    $path = $_POST['file'];
    $row = 1;
    if (($handle = fopen("$path", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
            $num = count($data);
            //echo "<p> $num fields in line $row: <br /></p>\n";
            //$row++;
            $fname = $data[0];
            $lname = $data[1];
            $fathername = $data[2];
            $mothername = $data[3];
            $id = $data[4];
            $email = $data[5];
            $joindate = $data[6];
            $designation = $data[7];
            // $dept = $data[8];
            // for ($c=0; $c < $num; $c++,$row++) {
            //     echo $data[$c] . "<br />\n";
            // }
            $query = "INSERT INTO facdet(fname,lname,fathername,mothername,id,email,joindate,designation) VALUES ('".$fname."','".$lname."','".$fathername."','".$mothername."','".$id."','".$email."','".$joindate."','".$designation."') ";
            $r = mysqli_query($conn,$query);
            // if($r) echo "Data inserted";
            // else echo "Data not inserted";

        }
        fclose($handle);
    }
}

?>

<!DOCTYPE HTML>
<html>
<body>
    <a href = "admin.php">Go back to home page</a>
</body>
</html>
<?php include 'footer.php'; ?>