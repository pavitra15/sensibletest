<?php
	session_start();
	include('../connect.php');
	$id=$_POST['id'];
	$unit_id=$_POST['unit_id'];
	$date=date('Y-m-d');
	$status="delete";
	$query=$db->prepare("update unit_mst set status='$status', status_change_date='$date', deleted_by_id='$id' where unit_id='$unit_id'");
	$query->execute();
?>