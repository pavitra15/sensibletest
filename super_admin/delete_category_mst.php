<?php
	session_start();
	include('../connect.php');
	$id=$_POST['id'];
	$category_id=$_POST['category_id'];
	$date=date('Y-m-d');
	$status="delete";
	$query=$db->prepare("update category_mst set status='$status', status_change_date='$date', deleted_by_id='$id' where category_id='$category_id'");
	$query->execute();
?>