<?php
	include('../../connect.php');
	$d_id=$_POST['d_id'];
	$token=$_POST['token'];
	$deviceid=$_POST['deviceid'];
	$json=$_POST['json'];
	file_put_contents('gs://solar-botany-215917.appspot.com/api/my.txt', $json);
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
			$exists_arr= array();
	        $push_query=$db->prepare("select * from product where deviceid='$d_id' and status='active'");
	        $push_query->execute();
	        if($data=$push_query->fetch())
	        {
	            do
	            {
	              $exists_arr[]=strtoupper($data['english_name']);
	            }
	            while($data=$push_query->fetch());
	        }

			$date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    		$created_by_date=$date->format('Y-m-d');
			$object=json_decode($json);
			$records=$object->records;
            $length=sizeof($records);
            $response='{"status":2,"record":[';
            for($m=0; $m<$length; $m++)
            {
            	$product_id=$records[$m]->product_id;
				$english_name=strtoupper($records[$m]->english_name);
				$regional_name=$records[$m]->regional_name;
				if(array_key_exists('barcode', $records[$m]))
				{
					$barcode=$records[$m]->barcode;
				}
				else
				{
					$barcode=0;
				}
				$stockable=$records[$m]->stockable;
				$current_stock=$records[$m]->current_stock;
				$reorder_level=$records[$m]->reorder_level;
				$weightable=$records[$m]->weightable;
				$category_id=$records[$m]->category_id;
				$discount=$records[$m]->discount;
				$kitchen_id=$records[$m]->kitchen_id;
				$unit_id=$records[$m]->unit_id;
				$tax_id=$records[$m]->tax_id;
				$price=$records[$m]->price;
				$price1=$price->price1;
				$price2=$price->price2;
				$price3=$price->price3;
				$price4=$price->price4;
				$price5=$price->price5;
				$price6=$price->price6;
				$price7=$price->price7;
				$price8=$price->price8;
				$price9=$price->price9;
				$calculate_price=$records[$m]->calculate_price;
				$prices1=$calculate_price->price1;
				$prices2=$calculate_price->price2;
				$prices3=$calculate_price->price3;
				$prices4=$calculate_price->price4;
				$prices5=$calculate_price->price5;
				$prices6=$calculate_price->price6;
				$prices7=$calculate_price->price7;
				$prices8=$calculate_price->price8;
				$prices9=$calculate_price->price9;
				if(in_array($english_name, $exists_arr))
                {
                	$count_query=$db->prepare("select english_name from product where product_id='$product_id'");
		            $count_query->execute();
		            if($count_data=$count_query->fetch())
		            {
		                do
		                {
		                    $count=strtoupper($count_data['english_name']);
		                }
		                while ($count_data=$count_query->fetch());
		            }
		            if($count===$english_name)
		            {
		            	$query=$db->prepare("update product set english_name='$english_name', regional_name='$regional_name', barcode='$barcode', weighing='$weightable', category_id='$category_id', discount='$discount', kitchen_id='$kitchen_id', updated_by_date='$created_by_date', updated_by_id='$id' where product_id='$product_id'");

		            	$query->execute();

                		$price_query=$db->prepare("update price_mst set tax_id='$tax_id', price1='$price1', prices1='$prices1', price2='$price2', prices2='$prices2', price3='$price3', prices3='$prices3', price4='$price4', prices4='$prices4', price5='$price5',  prices5='$prices5', price6='$price6', prices6='$prices6', prices7='$prices7', price8='$price8', prices8='$prices8', price9='$price9', prices9='$prices9', updated_by_id='$id', updated_by_date='$created_by_date' where product_id='$product_id'");
						$price_query->execute();


						$stock_query=$db->prepare("update stock_mst set stockable='$stockable', unit_id='$unit_id', current_stock='$current_stock', reorder_level='$reorder_level' where product_id='$product_id'");
						$stock_query->execute();

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
								do
								{
									$response.='{"Item_Number":'.$product_data['product_id'].',';
									$response.='"Item_Name":"'.$product_data['english_name'].'",';
									$response.='"Regional_Name":"'.$product_data['regional_name'].'",';
									$response.='"barcode":"'.$product_data['barcode'].'",';

									$bucket=$product_data['bucket_id'];
									$img_url="";
				                    $img_query=$db->prepare("select product_id, bucket from product_mst where product_id='$bucket'");
				                    $img_query->execute();
				                    while($img_data=$img_query->fetch())
				                    {
				                    	$img_url=$img_data['bucket'];
				                    }
				                    $response.='"Image":"'.$img_url.'",';

									$response.='"Category_Id":'.$product_data['category_id'].',';
									$response.='"Kitchen_Id":'.$product_data['kitchen_id'].',';
									$response.='"discount":'.$product_data['discount'].',';
									$response.='"Stockable_Item":"'.$product_data['stockable'].'",';
									$response.='"Input_Stock":'.$product_data['current_stock'].',';
									$response.='"Reorder_level":'.$product_data['reorder_level'].',';
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
									$response.='},';

									$response.='"Calculate_Price":{';
									$response.='"default price":'.$product_data['prices1'].',';
									for($i=0;$i<$customer_count;$i++)
									{
										$j=$i+2;
										$price="prices".$j;
										$response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
									}
									$response=substr($response, 0,-1);
									$response.='}},';
								}
								while($product_data=$product->fetch());
							}
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
								do
								{
									$response.='{"Item_Number":'.$product_data['product_id'].',';
									$response.='"Item_Name":"'.$product_data['english_name'].'",';
									$response.='"Regional_Name":"'.$product_data['regional_name'].'",';
									$response.='"barcode":"'.$product_data['barcode'].'",';

									$bucket=$product_data['bucket_id'];
									$img_url="";
				                    $img_query=$db->prepare("select product_id, bucket from product_mst where product_id='$bucket'");
				                    $img_query->execute();
				                    while($img_data=$img_query->fetch())
				                    {
				                    	$img_url=$img_data['bucket'];
				                    }
				                    $response.='"Image":"'.$img_url.'",';


									$response.='"Category_Id":'.$product_data['category_id'].',';
									$response.='"Kitchen_Id":'.$product_data['kitchen_id'].',';
									$response.='"discount":'.$product_data['discount'].',';
									$response.='"Stockable_Item":"'.$product_data['stockable'].'",';
									$response.='"Input_Stock":'.$product_data['current_stock'].',';
									$response.='"Reorder_level":'.$product_data['reorder_level'].',';
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
									$response.='},';

									$response.='"Calculate_Price":{';
									$response.='"default price":'.$product_data['prices1'].',';
									for($i=0;$i<$customer_count;$i++)
									{
										$j=$i+2;
										$price="prices".$j;
										$response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
									}
									$response=substr($response, 0,-1);
									$response.='}},';
								}
								while($product_data=$product->fetch());
							}
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
								do
								{
									$response.='{"Item_Number":'.$product_data['product_id'].',';
									$response.='"Item_Name":"'.$product_data['english_name'].'",';
									$response.='"Regional_Name":"'.$product_data['regional_name'].'",';
									$response.='"barcode":"'.$product_data['barcode'].'",';

									$bucket=$product_data['bucket_id'];
									$img_url="";
				                    $img_query=$db->prepare("select product_id, bucket from product_mst where product_id='$bucket'");
				                    $img_query->execute();
				                    while($img_data=$img_query->fetch())
				                    {
				                    	$img_url=$img_data['bucket'];
				                    }
				                    $response.='"Image":"'.$img_url.'",';


									$response.='"Category_Id":'.$product_data['category_id'].',';
									$response.='"Kitchen_Id":'.$product_data['kitchen_id'].',';
									$response.='"discount":'.$product_data['discount'].',';
									$response.='"Stockable_Item":"'.$product_data['stockable'].'",';
									$response.='"Input_Stock":'.$product_data['current_stock'].',';
									$response.='"Reorder_level":'.$product_data['reorder_level'].',';
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
									$response.='},';

									$response.='"Calculate_Price":{';
									$response.='"default price":'.$product_data['prices1'].',';
									for($i=0;$i<$customer_count;$i++)
									{
										$j=$i+2;
										$price="prices".$j;
										$response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
									}
									$response=substr($response, 0,-1);
									$response.='}},';
								}
								while($product_data=$product->fetch());
							}
						}
		            }
		            else
		            {
		            }
                }
                else
                {
					$query=$db->prepare("update product set english_name='$english_name', regional_name='$regional_name', barcode='$barcode', weighing='$weightable', category_id='$category_id', discount='$discount', kitchen_id='$kitchen_id', updated_by_date='$created_by_date', updated_by_id='$id' where product_id='$product_id'");
					$query->execute();

                	$price_query=$db->prepare("update price_mst set tax_id='$tax_id', price1='$price1', prices1='$prices1', price2='$price2', prices2='$prices2', price3='$price3', prices3='$prices3', price4='$price4', prices4='$prices4', price5='$price5',  prices5='$prices5', price6='$price6', prices6='$prices6', prices7='$prices7', price8='$price8', prices8='$prices8', price9='$price9', prices9='$prices9', updated_by_id='$id', updated_by_date='$created_by_date' where product_id='$product_id'");
					$price_query->execute();

					$stock_query=$db->prepare("update stock_mst set stockable='$stockable', unit_id='$unit', current_stock='$current_stock', reorder_level='$reorder_level' where product_id='$product_id'");
					$stock_query->execute();
	
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
							do
							{
								$response.='{"Item_Number":'.$product_data['product_id'].',';
								$response.='"Item_Name":"'.$product_data['english_name'].'",';
								$response.='"Regional_Name":"'.$product_data['regional_name'].'",';
								$response.='"barcode":"'.$product_data['barcode'].'",';

								$bucket=$product_data['bucket_id'];
									$img_url="";
				                    $img_query=$db->prepare("select product_id, bucket from product_mst where product_id='$bucket'");
				                    $img_query->execute();
				                    while($img_data=$img_query->fetch())
				                    {
				                    	$img_url=$img_data['bucket'];
				                    }
				                    $response.='"Image":"'.$img_url.'",';

								$response.='"Category_Id":'.$product_data['category_id'].',';
								$response.='"Kitchen_Id":'.$product_data['kitchen_id'].',';
								$response.='"discount":'.$product_data['discount'].',';
								$response.='"Stockable_Item":"'.$product_data['stockable'].'",';
								$response.='"Input_Stock":'.$product_data['current_stock'].',';
								$response.='"Reorder_level":'.$product_data['reorder_level'].',';
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
								$response.='},';

								$response.='"Calculate_Price":{';
								$response.='"default price":'.$product_data['prices1'].',';
								for($i=0;$i<$customer_count;$i++)
								{
									$j=$i+2;
									$price="prices".$j;
									$response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
								}
								$response=substr($response, 0,-1);
								$response.='}},';
							}
							while($product_data=$product->fetch());
						}
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
							do
							{
								$response.='{"Item_Number":'.$product_data['product_id'].',';
								$response.='"Item_Name":"'.$product_data['english_name'].'",';
								$response.='"Regional_Name":"'.$product_data['regional_name'].'",';
								$response.='"barcode":"'.$product_data['barcode'].'",';


								$bucket=$product_data['bucket_id'];
									$img_url="";
				                    $img_query=$db->prepare("select product_id, bucket from product_mst where product_id='$bucket'");
				                    $img_query->execute();
				                    while($img_data=$img_query->fetch())
				                    {
				                    	$img_url=$img_data['bucket'];
				                    }
				                    $response.='"Image":"'.$img_url.'",';



								$response.='"Category_Id":'.$product_data['category_id'].',';
								$response.='"Kitchen_Id":'.$product_data['kitchen_id'].',';
								$response.='"discount":'.$product_data['discount'].',';
								$response.='"Stockable_Item":"'.$product_data['stockable'].'",';
								$response.='"Input_Stock":'.$product_data['current_stock'].',';
								$response.='"Reorder_level":'.$product_data['reorder_level'].',';
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
								$response.='},';

								$response.='"Calculate_Price":{';
								$response.='"default price":'.$product_data['prices1'].',';
								for($i=0;$i<$customer_count;$i++)
								{
									$j=$i+2;
									$price="prices".$j;
									$response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
								}
								$response=substr($response, 0,-1);
								$response.='}},';
							}
							while($product_data=$product->fetch());
						}
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
							do
							{
								$response.='{"Item_Number":'.$product_data['product_id'].',';
								$response.='"Item_Name":"'.$product_data['english_name'].'",';
								$response.='"Regional_Name":"'.$product_data['regional_name'].'",';
								$response.='"barcode":"'.$product_data['barcode'].'",';

								$bucket=$product_data['bucket_id'];
									$img_url="";
				                    $img_query=$db->prepare("select product_id, bucket from product_mst where product_id='$bucket'");
				                    $img_query->execute();
				                    while($img_data=$img_query->fetch())
				                    {
				                    	$img_url=$img_data['bucket'];
				                    }
				                    $response.='"Image":"'.$img_url.'",';
     
								$response.='"Category_Id":'.$product_data['category_id'].',';
								$response.='"Kitchen_Id":'.$product_data['kitchen_id'].',';
								$response.='"discount":'.$product_data['discount'].',';
								$response.='"Stockable_Item":"'.$product_data['stockable'].'",';
								$response.='"Input_Stock":'.$product_data['current_stock'].',';
								$response.='"Reorder_level":'.$product_data['reorder_level'].',';
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
								$response.='},';

								$response.='"Calculate_Price":{';
								$response.='"default price":'.$product_data['prices1'].',';
								for($i=0;$i<$customer_count;$i++)
								{
									$j=$i+2;
									$price="prices".$j;
									$response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
								}
								$response=substr($response, 0,-1);
								$response.='}},';
							}
							while($product_data=$product->fetch());
						}
					}
			    }
		    }
			$response = rtrim($response, ',');
    		$response.=']}';


    		$objects=json_decode($response);
			$record=$objects->record;
            $lengths=sizeof($record);
            if($lengths==0)
            {
            
            	$response='{"status":2,"record":[';
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
						$product=$db->prepare("select * from product,stock_mst,price_mst where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and product.product_id='12");

						$product->execute();
						if($product_data=$product->fetch())
						{
							do
							{
								$response.='{"Item_Number":'.$product_id.',';
								$response.='"Item_Name":"'.$product_data['english_name'].'",';
								$response.='"Regional_Name":"'.$product_data['regional_name'].'",';
								$response.='"barcode":"'.$product_data['barcode'].'",';

								$bucket=$product_data['bucket_id'];
									$img_url="";
				                    $img_query=$db->prepare("select product_id, bucket from product_mst where product_id='$bucket'");
				                    $img_query->execute();
				                    while($img_data=$img_query->fetch())
				                    {
				                    	$img_url=$img_data['bucket'];
				                    }
				                    $response.='"Image":"'.$img_url.'",';



								$response.='"Category_Id":'.$product_data['category_id'].',';
								$response.='"Kitchen_Id":'.$product_data['kitchen_id'].',';
								$response.='"discount":'.$product_data['discount'].',';
								$response.='"Stockable_Item":"'.$product_data['stockable'].'",';
								$response.='"Input_Stock":'.$product_data['current_stock'].',';
								$response.='"Reorder_level":'.$product_data['reorder_level'].',';
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
								$response.='},';

								$response.='"Calculate_Price":{';
								$response.='"default price":'.$product_data['prices1'].',';
								for($i=0;$i<$customer_count;$i++)
								{
									$j=$i+2;
									$price="prices".$j;
									$response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
								}
								$response=substr($response, 0,-1);
								$response.='}},';
							}
							while($product_data=$product->fetch());
						}
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
						$product=$db->prepare("select * from product,stock_mst,price_mst where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and product.product_id=12");

						$product->execute();
						if($product_data=$product->fetch())
						{
							do
							{
								$response.='{"Item_Number":'.$product_id.',';
								$response.='"Item_Name":"'.$product_data['english_name'].'",';
								$response.='"Regional_Name":"'.$product_data['regional_name'].'",';
								$response.='"barcode":"'.$product_data['barcode'].'",';


								$bucket=$product_data['bucket_id'];
									$img_url="";
				                    $img_query=$db->prepare("select product_id, bucket from product_mst where product_id='$bucket'");
				                    $img_query->execute();
				                    while($img_data=$img_query->fetch())
				                    {
				                    	$img_url=$img_data['bucket'];
				                    }
				                    $response.='"Image":"'.$img_url.'",';



								$response.='"Category_Id":'.$product_data['category_id'].',';
								$response.='"Kitchen_Id":'.$product_data['kitchen_id'].',';
								$response.='"discount":'.$product_data['discount'].',';
								$response.='"Stockable_Item":"'.$product_data['stockable'].'",';
								$response.='"Input_Stock":'.$product_data['current_stock'].',';
								$response.='"Reorder_level":'.$product_data['reorder_level'].',';
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
								$response.='},';

								$response.='"Calculate_Price":{';
								$response.='"default price":'.$product_data['prices1'].',';
								for($i=0;$i<$customer_count;$i++)
								{
									$j=$i+2;
									$price="prices".$j;
									$response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
								}
								$response=substr($response, 0,-1);
								$response.='}},';
							}
							while($product_data=$product->fetch());
						}
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
						$product=$db->prepare("select * from product,stock_mst,price_mst where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and product.product_id=12");

						$product->execute();
						if($product_data=$product->fetch())
						{
							do
							{
								$response.='{"Item_Number":'.$product_id.',';
								$response.='"Item_Name":"'.$product_data['english_name'].'",';
								$response.='"Regional_Name":"'.$product_data['regional_name'].'",';
								$response.='"barcode":"'.$product_data['barcode'].'",';

								$bucket=$product_data['bucket_id'];
									$img_url="";
				                    $img_query=$db->prepare("select product_id, bucket from product_mst where product_id='$bucket'");
				                    $img_query->execute();
				                    while($img_data=$img_query->fetch())
				                    {
				                    	$img_url=$img_data['bucket'];
				                    }
				                    $response.='"Image":"'.$img_url.'",';
				                    
								$response.='"Category_Id":'.$product_data['category_id'].',';
								$response.='"Kitchen_Id":'.$product_data['kitchen_id'].',';
								$response.='"discount":'.$product_data['discount'].',';
								$response.='"Stockable_Item":"'.$product_data['stockable'].'",';
								$response.='"Input_Stock":'.$product_data['current_stock'].',';
								$response.='"Reorder_level":'.$product_data['reorder_level'].',';
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
								$response.='},';

								$response.='"Calculate_Price":{';
								$response.='"default price":'.$product_data['prices1'].',';
								for($i=0;$i<$customer_count;$i++)
								{
									$j=$i+2;
									$price="prices".$j;
									$response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
								}
								$response=substr($response, 0,-1);
								$response.='}},';
							}
							while($product_data=$product->fetch());
						}
					}
				$response = rtrim($response, ',');
            	$response.=']}';
            }
            echo $response;
	    }
	    else
	   	{
	   		$responce = array('status' =>1,'message'=>"Invalid json");
			echo json_encode($responce);
			echo $response;	    	
		}
	}
	else
	{
		$responce = array('status' =>0,'message'=>"token mismatch");
		echo json_encode($responce);
	}
?>