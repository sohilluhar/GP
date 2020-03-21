<?php
	include_once 'dompdf/dompdf_config.inc.php';
	
	$op = "<html><body><div>";
	$op .= "<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>Grievance Summary Report</p><br>";
	$op .= "<hr><hr>";
	
	$op.= "<br><br><div align = 'center'><b>* A computer generated report *</b></div></body></html>";
	
	$ope = utf8_encode($op);
	$dompdf = new DOMPDF();
	$dompdf->set_paper('a4', 'portrait');
	$dompdf->load_html($ope);
	$dompdf->render();

	$dompdf->stream('Summary Report',array('Attachment'=>0));
	
?>