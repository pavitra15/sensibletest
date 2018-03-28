<?php
	session_start();
	include('../connect.php');
	$from=$_POST['from'];
	$to=$_POST['to'];
	$cat_all=$_POST['cat_all'];
	$pro_all=$_POST['pro_all'];
	$pre_all=$_POST['pre_all'];

	$pre_type=$_POST['pre_type'];
	
	if(isset($_POST['premise']))
	{
		$premise=$_POST['premise'];
	}
	else
	{
		$premise=array();
	}
	
	$cat_type=$_POST['cat_type'];
	
	if(isset($_POST['category']))
	{
		$category=$_POST['category'];
	}
	else
	{
		$category=array();
	}
	
	$product=$_POST['product'];
	
	if(isset($_POST['products']))
	{
		$products=$_POST['products'];
	}
	else
	{
		$products=array();
	}

	$device_type=$_SESSION['from_type'];
	$id=$_SESSION['login_id'];
	$date = new DateTime("now", new DateTimeZone('Asia/Kolkata'));
	$status="active";
    $clone= clone $date;
    $start_date = $clone->format('Y-m-d');
    $updated_date=$clone->format('Y-m-d h:m:s');

    $type_count=0;
    $category_count=0;
    $product_count=0;
	
    if($pre_type=="on")
	{
		if($device_type=="Table")
		{
			if($pre_all=="on")
			{
				try
	            {
	            	$db->beginTransaction();
					$query=$db->prepare("INSERT INTO premise_dtl (premise_name, no_of_table, deviceid,status, created_by_date,created_by_id) SELECT premise_name, no_of_table, '$to', premise_dtl.status, '$start_date', '$id' FROM  premise_dtl where deviceid='$from'  and status='active'");
					$query->execute();
					$db->commit();
				}
				catch (Exception $e) 
	            {
	                $db->rollBack();
	            	echo $e;
	            }
	        }
	        else
	        {
	        	for($i=0; $i<count($premise); $i++)
				{
					try
		            {
		            	$db->beginTransaction();
						$query=$db->prepare("INSERT INTO premise_dtl (premise_name, no_of_table,deviceid, status, created_by_date, created_by_id) SELECT premise_name,no_of_table , '$to', premise_dtl.status,'$start_date', '$id' FROM premise_dtl where premise_id='$premise[$i]'");
			            $query->execute();
			            $db->commit();
					}
					catch (Exception $e) 
		            {
		                $db->rollBack();
		            	echo $e;
		            }
	        	}
	        }
        }
        else
		{
			if($pre_all=="on")
			{
				try
	            {
	            	$db->beginTransaction();
					$query=$db->prepare("INSERT INTO customer_dtl (customer_name, deviceid,status, created_by_date,created_by_id) SELECT customer_name, '$to','$status','$start_date', '$id' FROM        customer_dtl where deviceid='$from'  and status='active'");
					$query->execute();
					$db->commit();
				}
				 catch (Exception $e) 
	            {
	                $db->rollBack();
	            	echo $e;
	            }
	        }
	        else
	        {
	        	for($i=0; $i<count($premise); $i++)
				{
					try
		            {
		            	$db->beginTransaction();
						$query=$db->prepare("INSERT INTO customer_dtl (customer_name, deviceid, status, created_by_date, created_by_id) SELECT customer_name, '$to', customer_dtl.status,'$start_date', '$id' FROM customer_dtl where customer_id='$premise[$i]'");
			            $query->execute();
			            $db->commit();
					}
					catch (Exception $e) 
		            {
		                $db->rollBack();
		            	echo $e;
		            }
	        	}
	        }
        }
    }


    if($cat_type=="on")
    {
    	if($cat_all=="on")
		{
			try
	        {
	        	$db->beginTransaction();
				$query=$db->prepare("INSERT INTO category_dtl (category_name, status, created_by_date, created_by_id,deviceid) SELECT category_name, category_dtl.status,'$start_date', '$id', '$to' FROM category_dtl where deviceid='$from' and status='active'");
				$query->execute();
				$db->commit();
			}
			catch (Exception $e) 
	        {
	            $db->rollBack();
	        	echo $e;
	        }
	    }
	    else
	    {
			for($i=0; $i<count($category); $i++)
			{
				try
		        {
		        	$db->beginTransaction();
					$query=$db->prepare("INSERT INTO category_dtl(category_name, status, created_by_date, created_by_id,deviceid) SELECT category_name,category_dtl.status, '$start_date', '$id','$to' FROM category_dtl where category_id='$category[$i]'");
		            $query->execute();
		            $db->commit();
				}
				catch (Exception $e) 
		   		{
		        	$db->rollBack();
		            echo $e;
		        }
	        }
		}
    }

    if($product=="on")
    {
    	if($pro_all=="on")
		{
			try
	        {
	        	$db->beginTransaction();
				$query=$db->prepare("INSERT INTO product (regional_name, english_name, product_type, deviceid, status, created_by_date, created_by_id) SELECT regional_name,english_name,product_type, '$to', product.status,'$start_date', '$id' FROM product where deviceid='$from' and product.status='active'");
				$query->execute();
				$stock_query=$db->prepare("INSERT INTO stock_mst(product_id,current_stock,stockable,unit_id,updated_date) SELECT product_id,0,'No',1,'$start_date' from product where deviceid='$to'");
	 	        $stock_query->execute();
			
			    $price_query=$db->prepare("insert into price_mst(product_id,tax_id, price1, price2, price3, price4, price5, price6, price7, price8, price9,prices1, prices2, prices3, prices4, prices5, prices6, prices7, prices8, prices9) SELECT product_id,1,0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 from product where deviceid='$to'");
	 	        $price_query->execute();		
				$db->commit();
			}
			catch (Exception $e) 
	        {
	            $db->rollBack();
	        	echo $e;
	        }
	    }
	    else
	    {
			for($i=0; $i<count($products); $i++)
			{
				try
		        {
		        	$db->beginTransaction();
					$query=$db->prepare("INSERT INTO product  (regional_name, english_name, product_type, deviceid, status, created_by_date, created_by_id) SELECT regional_name,english_name,product_type, '$to', product.status,'$start_date', '$id' FROM product where product_id='$products[$i]'");
		            $query->execute();
		            $product_id=$db->lastInsertId();

		            $stock_query=$db->prepare("INSERT INTO stock_mst(product_id,current_stock,stockable,unit_id,updated_date) SELECT $product_id,0,stockable,unit_id,'$start_date' from stock_mst where product_id='$products[$i]'");
	 	            $stock_query->execute();

	 	            $price_query=$db->prepare("insert into price_mst(product_id,tax_id, price1, price2, price3, price4, price5, price6, price7, price8, price9,prices1, prices2, prices3, prices4, prices5, prices6, prices7, prices8, prices9) SELECT '$product_id',tax_id, price1, price2, price3, price4, price5, price6, price7, price8, price9, prices1, prices2, prices3, prices4, prices5, prices6, prices7, prices8, prices9 from price_mst where product_id='$products[$i]'");
	 	            $price_query->execute();
		            $db->commit();
				}
				catch (Exception $e) 
		   		{
		        	$db->rollBack();
		            echo $e;
		        }
	        }
		}
    }

    $query=$db->prepare("DELETE n1 FROM product n1, product n2 WHERE n1.product_id > n2.product_id AND n1.english_name = n2.english_name and n1.deviceid='$to' and n2.deviceid='$to'");
	$query->execute();
	
	$query=$db->prepare("DELETE n1 FROM premise_dtl n1, premise_dtl n2 WHERE n1.premise_id > n2.premise_id AND n1.premise_name = n2.premise_name and n1.deviceid='$to' and n2.deviceid='$to'");
	$query->execute();

	$query=$db->prepare("DELETE n1 FROM customer_dtl n1, customer_dtl n2 WHERE n1.customer_id > n2.customer_id AND n1.customer_name = n2.customer_name and n1.deviceid='$to' and n2.deviceid='$to'");
	$query->execute();

	$query=$db->prepare("DELETE n1 FROM category_dtl n1, category_dtl n2 WHERE n1.category_id > n2.category_id AND n1.category_name = n2.category_name and n1.deviceid='$to' and n2.deviceid='$to'");
	$query->execute();

	$query=$db->prepare("DELETE b FROM stock_mst b LEFT JOIN product f ON f.product_id = b.product_id WHERE f.product_id IS NULL");
	$query->execute();

	$query=$db->prepare("DELETE b FROM price_mst b LEFT JOIN product f ON f.product_id = b.product_id WHERE f.product_id IS NULL");
	$query->execute();

  //   try {
  //   	$create_query=$db->prepare("CREATE TABLE temp_table LIKE stock_mst");
		// $create_query->execute();

		// $alter_query=$db->prepare("ALTER TABLE temp_table ADD UNIQUE(product_id);");
		// $alter_query->execute();

		// $copy_query=$db->prepare("INSERT IGNORE INTO temp_table SELECT * FROM stock_mst");
		// $copy_query->execute();

		// $rename_query=$db->prepare("RENAME TABLE stock_mst TO old_table1, temp_table TO stock_mst");
		// $rename_query->execute();

		// $drop_query=$db->prepare("DROP TABLE old_table1");
		// $drop_query->execute();
 		
  //   } catch (Exception $e) {
  //   	echo $e;
  //   }

  //   try {
  //   	$create_query=$db->prepare("CREATE TABLE temp_table LIKE price_mst");
		// $create_query->execute();

		// $alter_query=$db->prepare("ALTER TABLE temp_table ADD UNIQUE(product_id);");
		// $alter_query->execute();

		// $copy_query=$db->prepare("INSERT IGNORE INTO temp_table SELECT * FROM price_mst");
		// $copy_query->execute();

		// $rename_query=$db->prepare("RENAME TABLE price_mst TO old_table1, temp_table TO price_mst");
		// $rename_query->execute();

		// $drop_query=$db->prepare("DROP TABLE old_table1");
		// $drop_query->execute();
 		
  //   } catch (Exception $e) {
  //   	echo $e;
  //   }

	

	echo "Copy success";
?>

