<?php
	include('../connect.php');
	if(is_ajax())
    {
		$d_id=$_POST['d_id'];
		$id=$_POST['id'];
		$page_no=$_POST['page_no'];
		$inclusive=$_POST['inclusive'];

		$date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
	    $current_date=$date->format('Y-m-d');

		$next=$page_no+1;
		$limit=30;
		$start_from = ($page_no-1) * $limit;
		
		if($inclusive=="on")
		{
			$product_query=$db->prepare("select product.product_id, price_mst.tax_id, tax_perc from product, price_mst,tax_mst where price_mst.tax_id=tax_mst.tax_id and product.status='active' and product.deviceid='$d_id'  and product.product_id=price_mst.product_id LIMIT $start_from, $limit");
			$product_query->execute();
			if($product_data=$product_query->fetch())
			{
				do
				{

					$product_id=$product_data['product_id'];
					$tax_perc=$product_data['tax_perc'];
					$tax_calc=(1+($tax_perc/100));	
					$query=$db->prepare("update price_mst set prices1=ROUND((IFNULL(price1, 0)/$tax_calc), 2), prices2=ROUND((IFNULL(price2, 0)/$tax_calc), 2), prices3=ROUND((IFNULL(price3, 0)/$tax_calc), 2), prices4=ROUND((IFNULL(price4, 0)/$tax_calc), 2), prices5=ROUND((IFNULL(price5, 0)/$tax_calc), 2), prices6=ROUND((IFNULL(price6, 0)/$tax_calc), 2), prices7=ROUND((IFNULL(price7, 0)/$tax_calc), 2), prices8=ROUND((IFNULL(price8, 0)/$tax_calc), 2),  prices9=ROUND((IFNULL(price9, 0)/$tax_calc), 2),  updated_by_id='$id', updated_by_date='$current_date' where product_id='$product_id'");
					$query->execute();
				}
				while($product_data=$product_query->fetch());
			}
		}
		else
		{
			$product_query=$db->prepare("select product.product_id, price_mst.tax_id, tax_perc from product, price_mst,tax_mst where price_mst.tax_id=tax_mst.tax_id and product.status='active' and product.deviceid='$d_id'  and product.product_id=price_mst.product_id LIMIT $start_from, $limit");
			$product_query->execute();
			if($product_data=$product_query->fetch())
			{
				do
				{
					$product_id=$product_data['product_id'];
					$tax_perc=$product_data['tax_perc'];	
					$tax_calc=(1+($tax_perc/100));	
					$query=$db->prepare("update price_mst set prices1=ROUND((IFNULL(price1, 0)*$tax_calc), 2), prices2=ROUND((IFNULL(price2, 0)*$tax_calc), 2), prices3=ROUND((IFNULL(price3, 0)*$tax_calc), 2), prices4=ROUND((IFNULL(price4, 0)*$tax_calc), 2), prices5=ROUND((IFNULL(price5, 0)*$tax_calc), 2), prices6=ROUND((IFNULL(price6, 0)*$tax_calc), 2), prices7=ROUND((IFNULL(price7, 0)*$tax_calc), 2), prices8=ROUND((IFNULL(price8, 0)*$tax_calc), 2),  prices9=ROUND((IFNULL(price9, 0)*$tax_calc), 2),  updated_by_id='$id', updated_by_date='$current_date' where product_id='$product_id' ");
					$query->execute();
				}		
				while($product_data=$product_query->fetch());
			}
		}
		echo $next;
	}
	else 
    {
        echo "something wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
	?>