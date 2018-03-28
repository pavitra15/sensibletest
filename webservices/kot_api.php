<?php
	include('../connect.php');
	$d_id=$_POST['d_id'];
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
			$kot_list=$object->kot_list;
            $length=sizeof($kot_list);
            $array = array();
            for($m=0; $m<$length; $m++)
            {
            	$bill_no=$kot_list[$m]->bill_no;
            	$kitchen_id= $kot_list[$m]->kitchen_id;
            	$user_id=$kot_list[$m]->user_id;
            	$date_time=$kot_list[$m]->date_time;
            	$state=$kot_list[$m]->state;
				try 
				{

					$db->beginTransaction();

					$select_query=$db->prepare("select transaction_id from transaction_mst where device_id='$d_id' and bill_no='$bill_no'");
					$select_query->execute();
					if($data=$select_query->fetch())
					{
						do
						{
							$transaction_id=$data['transaction_id'];
						}
						while($data=$select_query->fetch());
	            		$query=$db->prepare("insert into kot_mst(transaction_id, bill_no, kitchen_id, created_by_id, created_date_time, state) values('$transaction_id','$bill_no','$kitchen_id', '$user_id' ,'$date_time', '$state')");
	            		$query->execute();
	            		$kot_id=$db->lastInsertId();

	            		$kot_item=$kot_list[$m]->kot_item;
	            		$cnt=sizeof($kot_item);
						for ($j=0; $j <$cnt ; $j++) 
						{
							$product_id=$kot_item[$j]->product_id;
							$quantity=$kot_item[$j]->quantity;
							$state=$kot_item[$j]->state;
							$query=$db->prepare("insert into kot_dtl(kot_id,product_id, quantity, state) values($kot_id,'$product_id','$quantity','$state')");
	            			$query->execute();
						}
	            		if($db->commit())
	            		{
	            			$s=array('state'=>1, 'id'=>$kot_list[$m]->id);
	 						array_push($array,$s);
	            		}
	            	}
	            	else
	            	{
	            		$s=array('state'=>3, 'id'=>$kot_list[$m]->id);
 						array_push($array,$s);
	            	}
            	} catch (Exception $e) {
            		$db->rollBack();
            		$s=array('state'=>3, 'id'=>$kot_list[$m]->id);
 					array_push($array,$s);
            	}
				
	    	}
            $records = array('status' =>2,'records' =>$array);
 			echo json_encode($records,JSON_NUMERIC_CHECK);
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