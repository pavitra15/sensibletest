<?php
	include('../../connect.php');
	if(is_ajax())
    {
		$login_id=$_POST['login_id'];
		$delete_id=$_POST['delete_id'];
		$table_name=$_POST['table_name'];
		$column_name=$_POST['column_name'];

		$date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
	    $current_date=$date->format('Y-m-d');
	    try {
		    $status="delete";
			$query=$db->prepare("update ".$table_name." set status='$status', status_change_date='$current_date', deleted_by_id='$login_id' where ".$column_name."='$delete_id'");
			$query->execute();	
	    } catch (Exception $e) {
	    	echo $e;    	
	    }
	}
	else 
    {
        echo "something wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
?>