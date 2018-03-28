<?php
	include("../../connect.php");
	if(is_ajax())
	{
		$date=date('Y-m-d');
		$id=$_POST['id'];
		$state_id=$_POST['state_id'];
		$state_name=$_POST['state_name'];
		$query=$db->prepare("update state_mst set state_name='$state_name', updated_by_date='$date', updated_by_id='$id' where state_id='$state_id'");
		if($query->execute())
			echo 1;
		else
			echo 2;
	}
	else 
    {
        echo "something wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
?>