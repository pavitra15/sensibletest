<?php
	include('../connect.php');
	$deviceid=$_POST['deviceid'];
	$query=$db->prepare("select id, dashboard,report,print_setting,add_product,update_product,update_price,update_stock,edit_bill from user_access where d_id='$deviceid' and status='active'");
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
