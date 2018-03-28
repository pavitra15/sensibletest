<?php
	include("../../connect.php");
	if(is_ajax())
    {
		$date=date('Y-m-d');
		$id=$_POST['id'];
		$unit_id=$_POST['unit_id'];
		$unit_name=$_POST['unit_name'];
		$abbrevation=$_POST['abbrevation'];
		$query=$db->prepare("update unit_mst set unit_name='$unit_name', abbrevation='$abbrevation', updated_by_date='$date', updated_by_id='$id' where unit_id='$unit_id'");
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