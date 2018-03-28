<?php
	include('./../connect.php');
	$query=$db->prepare("update admin_notification set see='1'");
	$query->execute();
?>