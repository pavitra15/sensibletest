<?php
// echo '{"records":{"price":[],"stock":[]},"access":"active"}';

	include('../../connect.php');
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
		$query=$db->prepare("select * from token_verify where token='$token' and deviceid='$deviceid'");
		$query->execute();
		$token_count=$query->rowCount();
		if($token_count==1)
		{
			$object=json_decode($json);
			$item_data=$object->item_data;
			$item_length=sizeof($item_data);
			$response='{"records":{';
			$response.='"stock":[';
			for($i=0; $i<$item_length; $i++)
			{

				$product_id=$item_data[$i]->item_id;
				$stock_added=$item_data[$i]->stock_added;
				$reorder_level=$item_data[$i]->reorder_level;
				$update_date=$item_data[$i]->update_date;
				$kk=1;
				$stock_query=$db->prepare("update stock_mst set current_stock=current_stock+'$stock_added', reorder_level='$reorder_level', updated_date='$update_date'  where product_id='$product_id'");		
				if($stock_query->execute())
				{
					$response.='{"product_id":"'.$product_id.'","status":1},';
				}
				else
				{
					$response.='{"product_id":"'.$product_id.'","status":0},';
				}
			}
			$response = rtrim($response, ',');
			$response.='],"access":"'.$access_control.'"}}';
		
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