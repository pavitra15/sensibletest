<?php
	include('../connect.php');
	$deviceid=$_POST['deviceid'];
	$query=$db->prepare("select id, title, sub_title, address, contact, gstn, tax_invoice, bill_name, sr_no, prnt_bill_no, prnt_sr_col, prnt_base_col, prnt_bill_time,prnt_disc_col,footer,consolidated_tax,payment_mode,decimal_point from print_setup where d_id='$deviceid' and status='active'");
	$query->execute();
	if($data=$query->fetch(PDO::FETCH_ASSOC))
	{
		do
		{
			echo json_encode($data);
		}
		while ($data=$query->fetch(PDO::FETCH_ASSOC));
	}
?>