<?php
	include("../../connect.php");
	if(is_ajax())
	{
		$date=date('Y-m-d');
		$id=$_POST['id'];
		$d_id=$_POST['d_id'];
		$inclusive=$_POST['realtime'];
		$query=$db->prepare("update device set tax_type='$inclusive', updated_by_id='$id', updated_date='$date' where d_id='$d_id'");
		$query->execute();

		$product_query=$db->prepare("select product.product_id, price_mst.tax_id, tax_perc from product, price_mst,tax_mst where price_mst.tax_id=tax_mst.tax_id and product.status='active' and product.deviceid='$d_id'  and product.product_id=price_mst.product_id");
			$product_query->execute();
			echo $product_query->rowCount();
	}
	else 
    {
        echo "something wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }