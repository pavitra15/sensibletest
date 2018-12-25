<?php
	session_start();
	include("../connect.php");
	if(is_ajax())
	{
		$date=date('Y-m-d');
		$id=$_POST['id'];
		$d_id=$_POST['d_id'];
		$person_config=$_POST['person_config'];
		$query=$db->prepare("update configuration set person_config='$person_config', updated_by_id='$id', updated_by_date='$date' where d_id='$d_id'");
		$query->execute();
		$_SESSION['person_config']=$person_config;
		echo 1;
	}
	else 
    {
        echo "something wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }