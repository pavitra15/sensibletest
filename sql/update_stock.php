<?php
	include("../connect.php");
	if(is_ajax())
	{
		$date =  new DateTime("now", new DateTimeZone('Asia/Kolkata'));
		$clone_date=clone $date;
		$updated_date=$clone_date->format('Y-m-d h:m:s');
		$stock_id=$_POST['stock_id'];
		$d_id=$_POST['d_id'];
		$id=$_POST['id'];
		$stockable=$_POST['stockable'];
		$current_stock=$_POST['current_stock'];
		$stock_added=$_POST['new_stock'];
		$reorder_level=$_POST['reorder_level'];
		$unit=$_POST['unit'];
		$stock_from='web';

		$select_query=$db->prepare("select product_id from stock_mst where stock_id='$stock_id'");
		$select_query->execute();
		while($data=$select_query->fetch())
		{
			$product_id=$data['product_id'];
		}

		$stock_query=$db->prepare("insert into stock_dtl(d_id, log_date, stock_from, product_id,stock_added) values('$d_id', '$updated_date', '$stock_from', '$product_id','$stock_added')");
		$stock_query->execute();

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