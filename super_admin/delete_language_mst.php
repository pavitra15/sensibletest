<?php
	session_start();
	include('../connect.php');
	$id=$_POST['id'];
	$language_id=$_POST['language_id'];
	$date=date('Y-m-d');
	$status="delete";
	$query=$db->prepare("update language_mst set status='$status', status_change_date='$date', deleted_by_id='$id' where language_id='$language_id'");
	$query->execute();
?>