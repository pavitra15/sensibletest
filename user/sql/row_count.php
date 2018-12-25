<?php
	include('../connect.php');
	if(is_ajax())
	{
		$table_name=$_POST['table_name'];
		$d_id=$_POST['d_id'];
		$status="active";
		$count_query=$db->prepare("select * from $table_name where deviceid=$d_id and status='$status'");
		$count_query->execute();
		echo $count_query->rowCount();
	}
	else 
    {
        echo "something wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
?>