<?php
include("../connect.php");
if($_POST['id'])
{
	$date=date('Y-m-d');
	$id=$_POST['id'];
	$login_id=$_POST['login_id'];
	$name=$_POST['name'];
	$query=$db->prepare("update user_type_mst set name='$name', updated_by_date='$date', updated_by_id='$login_id' where id='$id'");
	$query->execute();
}
?>