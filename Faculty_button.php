<!DOCTYPE html>
<html>
<title>Bootstrap Example</title>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style type="text/css">
body {
  font-family: Arial;
  color: black;
}

.left {
  height: 100%;
  width: 50%;
  padding-top: 20px;
  float: left;
}

.right {
  height: 100%;
  width: 50%;
  padding-top: 20px;
  float: right;
}

.centered {
  margin-left: 22.5%;
  text-align: center;
  width: 55%;
  height: 55%;
}

.centered img {
  width: 150px;
  border-radius: 50%;
}
button {
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}
.button {
  background-color: #4CAF50;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}
</style>
</head>
<body>
  <form action="loginc.php" method="post" id="form_stu_Grie" name="form_stu_Grie">
        <button type="submit" class="button" name="faculty" align="left">Login for Committee</button>
  </form><br>
  <form action="login.php" method="post">
         <button type="submit" class="button" Name="GrievanceF" id="GrievanceF" align="left">Grievance Redressal</button> 
  </form><br>
  <form action="login.php" method="post">
        <button type="submit" class="button" name="Cast_DiscriminationF" id="Caste_DiscriminationF" align="left">Caste Discrimination</button>
  </form>


 </body>
</html>
