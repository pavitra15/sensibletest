<?php
	include("../../connect.php");
	if(is_ajax())
    {
		$date=date('Y-m-d');
		$category_id=$_POST['category_id'];
		$id=$_POST['id'];
		$firstname=$_POST['category_name'];
		$query=$db->prepare("update category_mst set category_name='$firstname', updated_by_date='$date', updated_by_id='$id' where category_id='$category_id'");
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