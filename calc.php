<?php
	include('connect.php');
	$query=$db->prepare("select * from product");
	$query->execute();
	echo $query->rowCount();
?>