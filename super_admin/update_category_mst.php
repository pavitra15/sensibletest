<?php
include("../connect.php");
if($_POST['id'])
{
	$date=date('Y-m-d');
	$category_id=$_POST['category_id'];
	$id=$_POST['id'];
	$firstname=$_POST['category_name'];
	 $query=$db->prepare("update category_mst set category_name='$firstname', updated_by_date='$date', updated_by_id='$id' where category_id='$category_id'");
	$query->execute();
}
?>