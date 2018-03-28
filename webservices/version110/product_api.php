<?php
	include('../../connect.php');
	$token=$_POST['token'];
	$deviceid=$_POST['deviceid'];
	$page_no=$_POST['page_no'];
	$start=(($page_no-1)*30);
	$record=30;
	$url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
    $log_date=$date->format('Y-m-d H:i:s');

    $update_query=$db->prepare("update last_connect set city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login' where deviceid='$deviceid'");
    $update_query->execute();
	$status="active";
	$query=$db->prepare("select * from token_verify where token='$token' and deviceid='$deviceid'");
	$query->execute();
	$token_count=$query->rowCount();
	$response='{"records":{';
	if($token_count==1)
	{
		$type_query=$db->prepare("select d_id, device_type,image_allow from device where deviceid='$deviceid' and status='$status'");
		$type_query->execute();
		if($data=$type_query->fetch())
		{
			do
			{
				$d_id=$data['d_id'];
				$device_type=$data['device_type'];
				$image_allow=$data['image_allow'];
			}
			while($data=$type_query->fetch());
		}
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
			$product=$db->prepare("select * from product,stock_mst,price_mst where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and deviceid='$d_id' and  status='$status' order by product.product_id asc limit $start, $record");
			$product->execute();
			if($product_data=$product->fetch())
			{
				$response.='"product":[';
				do
				{
					$response.='{"Item_Number":'.$product_data['product_id'].',';
					$response.='"Item_Name":"'.$product_data['english_name'].'",';
					$response.='"Regional_Name":"'.$product_data['regional_name'].'",';
					$response.='"barcode":"'.$product_data['barcode'].'",';
					$response.='"Category_Id":'.$product_data['category_id'].',';
					$reponse.='"Kitchen_Id":'.$product_data['kitchen_id'].',';
					
					$bucket=$product_data['bucket_id'];
					$img_url="";
                    $img_query=$db->prepare("select product_id, bucket from product_mst where product_id='$bucket'");
                    $img_query->execute();
                    while($img_data=$img_query->fetch())
                    {
                    	$img_url=$img_data['bucket'];
                    }
                    $response.='"Image":"'.$img_url.'",';
                    $response.='"Reorder_Level":'.$product_data['reorder_level'].',';

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
			$response=substr($response, 0,-1);
			$response.=']';
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
			$product=$db->prepare("select * from product,stock_mst,price_mst where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and deviceid='$d_id' and  status='$status' order by product.product_id asc limit $start, $record");
			$product->execute();
			if($product_data=$product->fetch())
			{
				$response.='"product":[';
				do
				{
					$response.='{"Item_Number":'.$product_data['product_id'].',';
					$response.='"Item_Name":"'.$product_data['english_name'].'",';
					$response.='"Regional_Name":"'.$product_data['regional_name'].'",';
					$response.='"barcode":"'.$product_data['barcode'].'",';
					$response.='"Category_Id":'.$product_data['category_id'].',';
					$reponse.='"Kitchen_Id":'.$product_data['kitchen_id'].',';

					$bucket=$product_data['bucket_id'];
					$img_url="";
                    $img_query=$db->prepare("select product_id, bucket from product_mst where product_id='$bucket'");
                    $img_query->execute();
                    while($img_data=$img_query->fetch())
                    {
                    	$img_url=$img_data['bucket'];
                    }
                    $response.='"Image":"'.$img_url.'",';
                    $response.='"Reorder_Level":'.$product_data['reorder_level'].',';

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
			$response=substr($response, 0,-1);
			$response.=']';
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
			$product=$db->prepare("select * from product,stock_mst,price_mst where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and deviceid='$d_id' and  status='$status' order by product.product_id asc limit $start, $record");
			$product->execute();
			if($product_data=$product->fetch())
			{
				$response.='"product":[';
				do
				{
					$response.='{"Item_Number":'.$product_data['product_id'].',';
					$response.='"Item_Name":"'.$product_data['english_name'].'",';
					$response.='"Regional_Name":"'.$product_data['regional_name'].'",';
					$response.='"barcode":"'.$product_data['barcode'].'",';
					$response.='"Category_Id":'.$product_data['category_id'].',';
					$reponse.='"Kitchen_Id":'.$product_data['kitchen_id'].',';

					$bucket=$product_data['bucket_id'];
					$img_url="";
                    $img_query=$db->prepare("select product_id, bucket from product_mst where product_id='$bucket'");
                    $img_query->execute();
                    while($img_data=$img_query->fetch())
                    {
                    	$img_url=$img_data['bucket'];
                    }
                    $response.='"Image":"'.$img_url.'",';
                    $response.='"Reorder_Level":'.$product_data['reorder_level'].',';

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
			$response=substr($response, 0,-1);
			$response.=']';
		}
		$status="active";
		$product=$db->prepare("select * from product,stock_mst,price_mst where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and deviceid='$d_id' and  status='$status'");
		$product->execute();
		$total_record=$product->rowCount();
		$total_page=ceil($total_record/30);
		$response.=',"image_allow":"'.$image_allow.'","current_page":'.$page_no.',"total_record":'.$total_record.',"total_page":'.$total_page.'}}';

		file_put_contents('gs://glass-approach-179716.appspot.com/api/product.txt', $response);

		function json_validator($data=NULL) 
		{
    		if (!empty($data)) 
    		{
            	@json_decode($data);
                return (json_last_error() === JSON_ERROR_NONE);
        	}
        	return false;
		}
		if(json_validator($response))
		{
			echo $response;
		}
		else
		{
			$responce = array('status' =>0,'message'=>'Properly add data');
			echo json_encode($responce);
			echo $response;
		}
	}
	else
	{
		$responce = array('status' =>1,'message'=>"token mismatch");
		echo json_encode($responce);
	}
?>