<?php
	session_start();
	include('../connect.php');
	$id=$_POST['id'];
	$login_id=$_POST['login_id'];
	$date=date('Y-m-d');
	$status="delete";
	$query=$db->prepare("update user_type_mst set status='$status', status_change_date='$date', deleted_by_id='$login_id' where id='$id'");
	$query->execute();
?>