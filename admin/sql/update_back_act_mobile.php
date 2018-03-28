<?php
	include("../../connect.php");
	if(is_ajax())
	{
		$date=date('Y-m-d');
		$id=$_POST['id'];
		$login_id=$_POST['login_id'];
		$mobile_no=$_POST['mobile_no'];
		$query=$db->prepare("update back_act_otp set mobile_no='$mobile_no',  updated_by_date='$date', updated_by_id='$login_id' where id='$id'");

		$query->execute();
	}
	else 
    {
        echo "something wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
?>