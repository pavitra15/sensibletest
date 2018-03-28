<?php
	include('../connect.php');
	$user_id=$_POST['user_id'];
	$query=$db->prepare("update dealer_notification_mst set see='1' where user_id='$user_id'");
	$query->execute();

?>