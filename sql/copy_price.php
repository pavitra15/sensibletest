<?php
	include('../connect.php');
	if(is_ajax())
    {
		$id=$_POST['id'];
		$d_id=$_POST['d_id'];

		$column_name="price".$id;
		$column_names="prices".$id;

		try {
			$query=$db->prepare("UPDATE price_mst INNER JOIN product ON price_mst.product_id = product.product_id SET price_mst.".$column_name." = price_mst.price1, price_mst.".$column_names." = price_mst.prices1 WHERE price_mst.product_id = product.product_id and product.deviceid='$d_id'");
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