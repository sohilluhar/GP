<?php
ob_start();
//https://www.flipkart.com/anand-sarees-solid-fashion-georgette-chiffon-saree/p/itmfbvkh4cmgfnm5?pid=SARFBV8D8CJBDVDS&lid=LSTSARFBV8D8CJBDVDSWOUTKG&marketplace=FLIPKART&sattr[]=color&st=color
//https://www.flipkart.com/ruchika-fashion-embroidered-bollywood-georgette-saree/p/itmfbnzqguwvcv5u?pid=SARFBNGYV3VFCQ6E&lid=LSTSARFBNGYV3VFCQ6E7RPADW&marketplace=FLIPKART&fm=productRecommendation%2Fsimilar&iid=R%3As%3Bp%3ASARFBV8D8CJBDVDS%3Bpt%3App%3Buid%3A5d283ddd-d801-d437-8251-cdc4e5648f0e%3B.SARFBNGYV3VFCQ6E.LSTSARFBNGYV3VFCQ6E7RPADW&ppt=ProductPage&ppn=ProductPage&otracker=pp_reco_Similar%2BProducts_1_27.productCard.PMU_HORIZONTAL_Ruchika%2BFashion%2BEmbroidered%2BBollywood%2BGeorgette%2BSaree_SARFBNGYV3VFCQ6E.LSTSARFBNGYV3VFCQ6E7RPADW_productRecommendation%2Fsimilar_0&cid=SARFBNGYV3VFCQ6E.LSTSARFBNGYV3VFCQ6E7RPADW
session_start();


include_once("includes/connection.php");

if (isset($_SESSION['previousp'])) {
   if (basename($_SERVER['PHP_SELF']) != $_SESSION['previous']) {
        session_destroy();
    }
}

if(isset($_POST['uid'])){
    //$_SESSION['uid'] = $_POST['uid'];
    $_SESSION['gid'] = $_POST['gid'];
}
$q="SELECT * from grievance where gid='".$_SESSION['gid']."'";
$res=mysqli_query($conn,$q);
$row=mysqli_fetch_assoc($res);
$gid=$row['gid'];
$uid=$row['uemail'];
$gsub=$row['gsub'];
$gcat=$row['gcat'];
$gtype=$row['gtype'];
$gdes=$row['gdes'];
$gfile=$row['gfile'];
$timeofg=$row['timeofg'];

$query="INSERT INTO princidb values('$gid','$uid','$gsub','$gcat','gtype','$gdes','$gfile','$timeofg','Action Taken')";
$re=mysqli_query($conn,$query);

?>
<SCRIPT LANGUAGE="JavaScript" type="text/javascript">

    var r = confirm("Do you want to send mail?");
    if (r == true) {
		var flag1 = 1;
    } else {
		var flag1 = 2; 
	}
	document.cookie = "flag1="+flag1;

    window.location.href = "http://localhost/gp/resolve1.php?flag1="+flag1 ;

</script>
<?php
$flag = $_COOKIE["flag1"];
?>