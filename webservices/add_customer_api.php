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

	$response.='{"status":2,"record":[';
	$query=$db->prepare("select * from token_verify where token='$token' and deviceid='$deviceid'");
	$query->execute();
	$token_count=$query->rowCount();
	if($token_count==1)
	{
		$type_query=$db->prepare("select d_id,id from device where deviceid='$deviceid' and status='$status'");
		$type_query->execute();
		if($data=$type_query->fetch())
		{
			do
			{
				$d_id=$data['d_id'];
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
			$records=$object->records;
			$length=sizeof($records);
			for($i=0; $i<$length; $i++)
			{
				$name=$records[$i]->name;
				$contact=$records[$i]->contact;
				$gstn=$records[$i]->gstn;
				$extra=$records[$i]->extra;
				//new Fields
				$dob=$records[$i]->dob;
				$doa=$records[$i]->doa;
				$date1=$records[$i]->date1;
				$date2=$records[$i]->date2;
				$credit_limit=$records[$i]->credit_limit;
				$credit_enable=$records[$i]->credit_enable;
				$old_balance=$records[$i]->old_balance;
				$day_limit=$records[$i]->day_limit;

				 $query=$db->prepare("insert into customer_mst(customer_name, customer_contact, customer_gstn, customer_extra, deviceid, created_by_id,   created_by_date, status) values('$name', '$contact', '$gstn', '$extra', '$d_id', '$id', '$created_by_date', '$status','$dob','$doa','$date1','$date2','$credit_limit','$credit_enable','$old_balance','$day_limit' )");
	            $query->execute();

	            $select_query=$db->prepare("select customer_id, customer_name,customer_contact,customer_gstn,customer_extra from customer_mst where customer_name='$name' and customer_contact='$contact' and customer_gstn='$gstn' and deviceid='$d_id'");
	            $select_query->execute();
	            if($data=$select_query->fetch())
	            {
	            	do
					{
						$response.='{"customer_id":'.$data['customer_id'].',';
						$response.='"customer_name":"'.$data['customer_name'].'",';
						$response.='"customer_contact":"'.$data['customer_contact'].'",';
						$response.='"customer_gstn":"'.$data['customer_gstn'].'",';
						//new feilds
						$response.='"dob":"'.$data['dob'].'",';
						$response.='"doa":"'.$data['doa'].'",';
						$response.='"date1":"'.$data['date1'].'",';
						$response.='"date2":"'.$data['date2'].'",';
						$response.='"credit_limit":"'.$data['credit_limit'].'",';
						$response.='"credit_enable":"'.$data['credit_enable'].'",';
						$response.='"old_balance":"'.$data['old_balance'].'",';
						$response.='"day_limit":"'.$data['day_limit'].'",';
						$response.='"customer_extra":"'.$data['customer_extra'].'"},';
					}
					while($data=$select_query->fetch());
				}
			}
			$response = rtrim($response, ',');
			$response.=']}';
			echo $response;
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