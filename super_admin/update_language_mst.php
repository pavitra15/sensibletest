<?php
include("../connect.php");
if($_POST['id'])
{
	$date=date('Y-m-d');
	$id=$_POST['id'];
	$language_id=$_POST['language_id'];
	$language_name=$_POST['language_name'];
	$query=$db->prepare("update language_mst set language_name='$language_name', updated_by_date='$date', updated_by_id='$id' where language_id='$language_id'");
	$query->execute();
}
?>