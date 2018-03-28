<?php
	session_start();
	include('../connect.php');
	$id=$_POST['id'];
	$state_id=$_POST['state_id'];
	$date=date('Y-m-d');
	$status="delete";
	$query=$db->prepare("update state_mst set status='$status', status_change_date='$date', deleted_by_id='$id' where state_id='$state_id'");
	$query->execute();
?>