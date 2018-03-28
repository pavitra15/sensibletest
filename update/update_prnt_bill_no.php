<?php
include("../connect.php");
if($_POST['id'])
{
	$date=date('Y-m-d');
	$id=$_POST['id'];
	$d_id=$_POST['d_id'];
	$billno=$_POST['billno'];
	$query=$db->prepare("update device set prnt_billno='$billno', updated_by_id='$id', updated_date='$date' where d_id='$d_id'");
	$query->execute();
	echo "1_";
}