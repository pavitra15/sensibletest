<?php
	include("../connect.php");
	if(is_ajax())
	{
		$date =  new DateTime();
		$clone_date=clone $date;
		$updated_date=$clone_date->format('Y-m-d h:m:s');
		$stock_id=$_POST['stock_id'];
		$id=$_POST['id'];
		$stockable=$_POST['stockable'];
		$current_stock=$_POST['current_stock'];
		$reorder_level=$_POST['reorder_level'];
		$unit=$_POST['unit'];
		$query=$db->prepare("update stock_mst set stockable='$stockable', reorder_level='$reorder_level', current_stock='$current_stock', unit_id='$unit', updated_date='$updated_date' where stock_id='$stock_id'");
		$query->execute();
		if($query->rowCount())
		{
			echo "1_";
		}
		else
		{
			echo "2_";
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