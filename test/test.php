
<?php
	include('../connect.php');
	$d_id='167';
	 $date =  new DateTime("now", new DateTimeZone('Asia/Kolkata'));
    $clone_date=clone $date;
    $id='195';
    $weightable='No';
    $category='3434';
    $discount=0;
    $kitchen=0;
    $comission=0;
    $bucket="0";
    $product_type="Non-Table";
    $status="active";
    $updated_date=$clone_date->format('Y-m-d h:m:s');
	$update_date=$clone_date->format('Y-m-d');
	for($i=9688;$i<=9999;$i++)
	{
        echo $i;
		try
        {
        	$db->beginTransaction();
            $query=$db->prepare("insert into product (english_name, regional_name, weighing,category_id, discount, kitchen_id,comission, bucket_id, deviceid, product_type, status, created_by_date, created_by_id) values('$i', '$i', '$weightable', $category, '$discount', '$kitchen', '$comission',  $bucket, '$d_id','$product_type', '$status' , '$update_date', '$id')");

            echo "insert into product (english_name, regional_name, weighing,category_id, discount, kitchen_id,comission, bucket_id, deviceid, product_type, status, created_by_date, created_by_id) values('$i', '$i', '$weightable', $category, '$discount', '$kitchen', '$comission',  $bucket, '$d_id','$product_type', '$status' , '$update_date', '$id')";
            $query->execute();
            $product_id=$db->lastInsertId();
            $stock_query=$db->prepare("insert into stock_mst(product_id,unit_id,stockable,current_stock,updated_date)values($product_id,'3','No',0,'$updated_date')");
            $stock_query->execute();
            $price_query=$db->prepare("insert into price_mst(product_id,tax_id,price1, price2, price3, price4, price5, price6, price7, price8, price9, prices1, prices2, prices3, prices4, prices5, prices6, prices7,prices8, prices9) values('$product_id',1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)");
            $price_query->execute();
            $db->commit();
        }
        catch (Exception $e) 
        {
        	$db->rollBack();
            echo $e;
       	}
	}
?>
