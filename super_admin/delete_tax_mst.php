<?php
	session_start();
	include('../connect.php');
	$id=$_POST['id'];
	$tax_id=$_POST['tax_id'];
	$date=date('Y-m-d');
	$status="delete";
	$query=$db->prepare("update tax_mst set status='$status', status_change_date='$date', deleted_by_id='$id' where tax_id='$tax_id'");
	$query->execute();
?>