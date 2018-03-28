<?php
// echo '{"records":{"price":[],"stock":[]},"access":"active"}';

	include('../connect.php');
	$token=$_POST['token'];
	$deviceid=$_POST['deviceid'];
	$json=$_POST['json'];
	$flag=0;
	$access_control="";
	$url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
   
    $update_query=$db->prepare("update last_connect set city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login' where deviceid='$deviceid'");
    $update_query->execute();
	$access_query = $db->prepare("select * from device where deviceid='$deviceid'");
    $access_query->execute();
    if ($access_data = $access_query->fetch()) 
    {
        do 
        {
    	    $access_control=$access_data['access_control'];
	    } 
        while ($access_data = $access_query->fetch());
    }
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
		file_put_contents('gs://glass-approach-179716.appspot.com/api/hello.txt', $json);
		$query=$db->prepare("select * from token_verify where token='$token' and deviceid='$deviceid'");
		$query->execute();
		$token_count=$query->rowCount();
		if($token_count==1)
		{
			$response='{"records":{';
			$price_name[0]="default price";
			$type_query=$db->prepare("select device_type,d_id from device where deviceid='$deviceid'");
			$type_query->execute();
			while($type_data=$type_query->fetch())
			{
				$d_id=$type_data['d_id'];
				$device_type=$type_data['device_type'];
			}
			if($device_type=="Table")
			{
				$premise_query=$db->prepare("select Premise_name from premise_dtl where deviceid='$d_id' and status='active'");
				$premise_query->execute();
				$i=1;
				while($premise_data=$premise_query->fetch())
				{
					$price_name[$i]=$premise_data['Premise_name']." price";
					$i++;
				}
			}
			else
			{
				$customer_query=$db->prepare("select customer_name from customer_dtl where deviceid='$d_id' and status='active'");
				$customer_query->execute();
				$i=1;
				while($customer_data=$customer_query->fetch())
				{
					$price_name[$i]=$customer_data['customer_name']." price";
					$i++;
				}	
			}
			$length=sizeof($price_name);
			$transaction_id;
			$object=json_decode($json);
			$item_data=$object->item_data;
			$item_length=sizeof($item_data);
			$kk=0;
			if($item_length>0)
			{	
				$response.='"price":[';
				for($i=0; $i<$item_length; $i++)
				{
					$is_price_change=$item_data[$i]->is_price_changed;
					$product_id=$item_data[$i]->item_id;
					if($is_price_change==1)
					{
						$kk=1;
						$price=array(9);
						$new_prices=$item_data[$i]->new_prices;
						for($k=0;$k<9;$k++)
						{
							if($k<$length)
							{
								$name=$price_name[$k];
								if($name=="")
								{
									$price[$k]=0;
								}
								else
								{	
									$price[$k]=$new_prices->$name;
								}
							}
							else
							{
								$price[$k]=0;
							}
							
						}
						$price_query=$db->prepare("update price_mst set price1='$price[0]', price2='$price[1]', price3='$price[2]', price4='$price[3]', price5='$price[4]', price6='$price[5]', price7='$price[6]', price8='$price[7]', price9='$price[8]'  where product_id='$product_id'");
						if($price_query->execute())
						{
							$response.='{"product_id":"'.$product_id.'","status":1},';
						}
						else
						{
							$response.='{"product_id":"'.$product_id.'","status":0},';
						}
					}
				}
				if($kk==1)
				{
					$response=substr($response, 0,-1);
					$kk=0;
				}
				$response.='],';
				$response.='"stock":[';
				for($i=0; $i<$item_length; $i++)
				{
					$stockable=$item_data[$i]->stockable;
					$product_id=$item_data[$i]->item_id;
					$current_stock=$item_data[$i]->current_stock;
					if($stockable==1)
					{
						$kk=1;
						// $stock_query=$db->prepare("update stock_mst set current_stock='$current_stock' where product_id='$product_id'");
						// $stock_query->execute()
						if(1)
						{
							$response.='{"product_id":"'.$product_id.'","status":1},';
						}
						else
						{
							$response.='{"product_id":"'.$product_id.'","status":0},';
						}
					}
					else
					{	
					}
				}
				if($kk==1)
				{
					$response=substr($response, 0,-1);
				}
				$response.='],';
			}
			else
			{
				$response.='}';
			}

			$sync_by=$object->user_id;
			$created_sync_date=$object->created_sync_date;	
			$response=substr($response, 0,-1);
			$response.='},"access":"'.$access_control.'"}';
				echo $response;
		}
		else
		{
			$responce = array('status' =>2,'message'=>"token mismatch",'access'=>$access_control);
			echo json_encode($responce);
		}
	}
	else
	{

		$responce = array('status' =>0,'message'=>'Json format is wrong','access'=>$access_control);
		echo json_encode($responce);
	}
?>