<?php
include("../connect.php");
if($_POST['id'])
{
	$date=date('Y-m-d');
	$id=$_POST['id'];
	$tax_id=$_POST['tax_id'];
	$tax_name=$_POST['tax_name'];
	$tax_type=$_POST['tax_type'];
	$tax_perc=$_POST['tax_perc'];
	$query=$db->prepare("update tax_mst set tax_type='$tax_type', tax_name='$tax_name', tax_perc='$tax_perc', updated_date='$date', updated_by_id='$id' where tax_id='$tax_id'");

	$query->execute();
}
?>