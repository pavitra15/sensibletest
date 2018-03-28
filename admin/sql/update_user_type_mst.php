<?php
	include("../../connect.php");
	if(is_ajax())
    {
		$date=date('Y-m-d');
		$id=$_POST['id'];
		$login_id=$_POST['login_id'];
		$name=$_POST['name'];
		$query=$db->prepare("update user_type_mst set name='$name', updated_by_date='$date', updated_by_id='$login_id' where id='$id'");
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