<?php
ob_start();
session_start();
 if(!isset($_SESSION['loggedInUser']) && !$_SESSION['loggedInUser']=='principal.engg@somaiya.edu'){
    header("location:index.php");
}
				include_once 'dompdf/dompdf_config.inc.php';
				include_once("includes/connection.php");

				   
				$email = $_SESSION['loggedInUser'];	
				
				//$type = $_SESSION['type'] ;
				$sql1 = $_SESSION['sql'] ;
				$s=$_POST['gid2'];
				$q="SELECT * from resolved where gid='".$s."'";
				$count = 0;
				
						$display = 1;
						$result=mysqli_query($conn,$q);
						if(mysqli_num_rows($result)>0){
							//echo "here";
							$count = mysqli_num_rows($result); 
							$op="<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>Grievance Redressed:</p>";
							$op.="
							<p align='center'><strong> Action Details: </strong>
							<table class = 'table table-stripped table-bordered' border='1' cellspacing='0' id = 'example1' class='table table-stripped table-bordered' align='center'>
								
								<tr><td><strong>Grievance ID</strong></td>
								<td><strong>Grievance by</strong></td>
								<td><strong>Grievance Subject</strong></td>
								<td><strong>Grievance Category</strong></td>
								<td><strong>Grievance Type</strong></td>
								<td><strong>Grievance Description</strong></td>
								<td><strong>File Attached</strong></td>
								<td><strong>Time Of Issue</strong></td>
								<td><strong>Grievance Status</strong></td>
								<td><strong>Action Taken</strong></td></tr>
									";
							while($row =mysqli_fetch_assoc($result)){
								
								$op.= "<tr>";
								$q = "SELECT name FROM userdet WHERE email = '".$row['uemail']."'";
								$namerow = mysqli_fetch_assoc(mysqli_query($conn,$q));
								$name = $namerow['name'];
								
								$op.="<td><strong>".$row['gid']."</strong></td>";
								$op.="<td><strong>".$name. "<br>(".$row['uemail'].")</strong></td>";
								$op.="<td><strong>".$row['gsub']."</strong></td>";
								$op.="<td><strong>".$row['gcat']."</strong></td>";
								$op.="<td><strong>".$row['gtype']."</strong></td>";
								$op.="<td><strong>".$row['gdes']."</strong></td>";
								if($row['gfile']!=NULL) {
									$op .= "<td><strong> <a href = '".$row['gfile']."' target='_blank'>View File</strong></td>";
								}
								else $op .= "<td><strong>No File Attached</strong></td>";
								$op.="<td><strong>".$row['timeofg']."</strong></td>";
								$op.="<td><strong>".$row['status']."</strong></td>";
								$op.="<td><strong>".$row['act']."</strong></td>";

								$op.="</tr>";
								
							}
							$op.= "</table>";
						}
						$op.= "<br><br><div align = 'center'><b>* A computer generated report *</b>";
						//echo $op;
						$dompdf = new DOMPDF();
				$dompdf->load_html($op);
				$dompdf->set_paper('a4', 'landscape');
				$dompdf->render();

				$dompdf->stream('Grievance Report',array('Attachment'=>0));
				
?>