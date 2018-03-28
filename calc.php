<?php
	include('connect.php');
	$query=$db->prepare("select * from product_mst");
	$query->execute();
	echo $query->rowCount();
?>