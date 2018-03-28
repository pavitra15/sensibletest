<?php
	include('../connect.php');
	$token=$_POST['token'];
	$deviceid=$_POST['deviceid'];
	$json=$_POST['json'];
	
	$url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
   
    $update_query=$db->prepare("update last_connect set city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login' where deviceid='$deviceid'");
    $update_query->execute();
	$status="active";
	$query=$db->prepare("select * from token_verify where token='$token' and deviceid='$deviceid'");
	$query->execute();
	$token_count=$query->rowCount();
	if($token_count==1)
	{
		$type_query=$db->prepare("select d_id,id, device_type from device where deviceid='$deviceid' and status='$status'");
		$type_query->execute();
		if($data=$type_query->fetch())
		{
			do
			{
				$d_id=$data['d_id'];
				$device_type=$product_type=$data['device_type'];
				$id=$data['id'];
			}
			while($data=$type_query->fetch());
		}
		$status="active";
		function json_validator($data=NULL) 
		{
	    	if (!empty($data)) 
	    	{
	        	@json_decode($data);
	            return (json_last_error() === JSON_ERROR_NONE);
	    	}
	    	return false;
		}
		if(json_validator($json))
		{
			$date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    		$created_by_date=$date->format('Y-m-d');
			$object=json_decode($json);
			$english_name=$object->english_name;
			$regional_name=$object->regional_name;
			$stockable=$object->stockable;
			$weightable=$object->weightable;
			$category_id=$object->category_id;
			$unit_id=$object->unit_id;
			$tax_id=$object->tax_id;
			$price1=$object->price1;
			$price2=$object->price2;
			$price3=$object->price3;
			$price4=$object->price4;
			$price5=$object->price5;
			$price6=$object->price6;
			$price7=$object->price7;
			$price8=$object->price8;
			$price9=$object->price9;
			$push_query=$db->prepare("select * from product where deviceid='$d_id' and status='active' and english_name='$english_name'");
			$push_query->execute();
	        $product_count=$push_query->rowCount();
	        if($product_count==0)
	        {
	            $query=$db->prepare("insert into product (regional_name, english_name,weighing, category_id, deviceid, product_type,  status, created_by_date, created_by_id) values('$regional_name', '$english_name', '$weightable', '$category_id', '$d_id', '$product_type', '$status' , '$created_by_date', '$id')");
	            $query->execute();
	            $select_query=$db->prepare("select product_id from product where english_name='$english_name' and regional_name='$regional_name' and deviceid='$d_id'");
	            $select_query->execute();
	            if($data=$select_query->fetch())
	            {
	            	do 
	                {
	                	$product_id=$data['product_id'];
	                } 
	                while ($data=$select_query->fetch());
	                if($product_id)
	                {
	                	$stock_query=$db->prepare("insert into stock_mst(product_id,current_stock,stockable,unit_id)values($product_id,0,'$stockable',$unit_id)");
	                	$stock_query->execute();

	                	$price_query=$db->prepare("insert into price_mst(product_id,tax_id, price1, price2, price3, price4, price5, price6, price7, price8, price9) values('$product_id','$tax_id',$price1,$price2,$price3,$price4,$price5,$price6,$price7,$price8,$price9)");
	                	$price_query->execute();
	                	
	                	$response="{";
	                	if($device_type=="Weighing")
						{
							$customer_query=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
							$customer_query->execute();
							$customer_count=$customer_query->rowCount();
							$customer_name=array(10);
							if($sk=$customer_query->fetch())
							{
								$i=0;
								do
								{
									$customer_name[$i]=$sk['customer_name'];
									$i++;
								}
								while($sk=$customer_query->fetch());
							}
							$product=$db->prepare("select * from product,stock_mst,price_mst where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and product.product_id='$product_id'");
							$product->execute();
							if($product_data=$product->fetch())
							{
								$response.='"product":';
								do
								{
									$response.='{"Item_Number":'.$product_data['product_id'].',';
									$response.='"Item_Name":"'.$product_data['english_name'].'",';
									$response.='"Regional_Name":"'.$product_data['regional_name'].'",';
									$response.='"Category_Id":'.$product_data['category_id'].',';
									$response.='"Stockable_Item":"'.$product_data['stockable'].'",';
									$response.='"Input_Stock":'.$product_data['current_stock'].',';
									$response.='"Unit_Id":'.$product_data['unit_id'].',';
									$response.='"Weighable_Item":"'.$product_data['weighing'].'",';
									$response.='"Tax Index":'.$product_data['tax_id'].',';
									$response.='"Price":{';
									$response.='"default price":'.$product_data['price1'].',';
									for($i=0;$i<$customer_count;$i++)
									{
										$j=$i+2;
										$price="price".$j;
										$response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
									}
									$response=substr($response, 0,-1);
									$response.='}},';
								}
								while($product_data=$product->fetch());
							}
							$response=substr($response, 0,-1);
							$response.='}';
						}
						elseif($device_type=="Non-Table")
						{
							$customer_query=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
							$customer_query->execute();
							$customer_count=$customer_query->rowCount();
							$customer_name=array(10);
							if($sk=$customer_query->fetch())
							{
								$i=0;
								do
								{
									$customer_name[$i]=$sk['customer_name'];
									$i++;
								}
								while($sk=$customer_query->fetch());
							}
							$product=$db->prepare("select * from product,stock_mst,price_mst where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and product.product_id='$product_id'");
							$product->execute();
							if($product_data=$product->fetch())
							{
								$response.='"product":';
								do
								{
									$response.='{"Item_Number":'.$product_data['product_id'].',';
									$response.='"Item_Name":"'.$product_data['english_name'].'",';
									$response.='"Regional_Name":"'.$product_data['regional_name'].'",';
									$response.='"Category_Id":'.$product_data['category_id'].',';
									$response.='"Stockable_Item":"'.$product_data['stockable'].'",';
									$response.='"Input_Stock":'.$product_data['current_stock'].',';
									$response.='"Unit_Id":'.$product_data['unit_id'].',';
									$response.='"Tax Index":'.$product_data['tax_id'].',';
									$response.='"Price":{';
									$response.='"default price":'.$product_data['price1'].',';
									for($i=0;$i<$customer_count;$i++)
									{
										$j=$i+2;
										$price="price".$j;
										$response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
									}
									$response=substr($response, 0,-1);
									$response.='}},';
								}
								while($product_data=$product->fetch());
							}
							$response=substr($response, 0,-1);
							$response.='}';
						}
						elseif($device_type=="Table")
						{
							$customer_query=$db->prepare("select * from premise_dtl where deviceid='$d_id' and status='$status'");
							$customer_query->execute();
							$customer_count=$customer_query->rowCount();
							$customer_name=array(10);
							if($sk=$customer_query->fetch())
							{
								$i=0;
								do
								{
									$customer_name[$i]=$sk['premise_name'];
									$i++;
								}
								while($sk=$customer_query->fetch());
							}
							$product=$db->prepare("select * from product,stock_mst,price_mst where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and product.product_id='$product_id'");
							$product->execute();
							if($product_data=$product->fetch())
							{
								$response.='"product":';
								do
								{
									$response.='{"Item_Number":'.$product_data['product_id'].',';
									$response.='"Item_Name":"'.$product_data['english_name'].'",';
									$response.='"Regional_Name":"'.$product_data['regional_name'].'",';
									$response.='"Category_Id":'.$product_data['category_id'].',';
									$response.='"Stockable_Item":"'.$product_data['stockable'].'",';
									$response.='"Input_Stock":'.$product_data['current_stock'].',';
									$response.='"Unit_Id":'.$product_data['unit_id'].',';
									$response.='"Tax Index":'.$product_data['tax_id'].',';
									$response.='"Price":{';
									$response.='"default price":'.$product_data['price1'].',';
									for($i=0;$i<$customer_count;$i++)
									{
										$j=$i+2;
										$price="price".$j;
										$response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
									}
									$response=substr($response, 0,-1);
									$response.='}},';
								}
								while($product_data=$product->fetch());
							}
							$response=substr($response, 0,-1);
							$response.='}';
						}
		            }
		            echo $response;
		        }
	            else
	            {
	            	$responce = array('status' =>3,'message'=>"Error occured");
					echo json_encode($responce);	
	            }
	        }
	        else
	        {
	        	$responce = array('status' =>2,'message'=>"Product already exists");
				echo json_encode($responce);
	        }
	    }
	    else
	    {
	    	$responce = array('status' =>1,'message'=>"Invalid json");
			echo json_encode($responce);
	    }
	}
	else
	{
		$responce = array('status' =>0,'message'=>"token mismatch");
		echo json_encode($responce);
	}
?>