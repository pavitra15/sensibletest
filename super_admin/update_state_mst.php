<?php
include("../connect.php");
if($_POST['id'])
{
	$date=date('Y-m-d');
	$id=$_POST['id'];
	$state_id=$_POST['state_id'];
	$state_name=$_POST['state_name'];
	$query=$db->prepare("update state_mst set state_name='$state_name', updated_by_date='$date', updated_by_id='$id' where state_id='$state_id'");
	$query->execute();
}
?>