<?php
	session_start();
	include('../connect.php');
	$id=$_POST['id'];
	$product_id=$_POST['product_id'];
	$date=date('Y-m-d');
	$status="delete";
	$query=$db->prepare("update product_mst set status='$status', status_change_date='$date', deleted_by_id='$id' where product_id='$product_id'");
	$query->execute();
?>