<?php
	include('../connect.php');
	$d_id=$_POST['d_id'];
	$query=$db->prepare("update notification_mst set see='1' where d_id='$d_id'");
	$query->execute();

?>