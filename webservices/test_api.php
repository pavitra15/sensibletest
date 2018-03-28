<?php
	include('../connect.php');
	$token=$_POST['token'];
	$deviceid=$_POST['deviceid'];
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
		$about_query = $db->prepare("select * from about,admin_device where about.dealer_id=admin_device.dealer_id and admin_device.deviceid='$deviceid'");
		$about_query->execute();
		$about_count=$about_query->rowCount();
		if($about_count>0)
		{
			while($about_dta=$about_query->fetch())
			{
				$company_name=$about_dta['company_name'];
				$website=$about_dta['website'];
				$email=$about_dta['mail'];
				$contact=$about_dta['contact'];
				$powered_by=$about_dta['powered_by'];
			}
			$response='{"about":{"company_name":"'.$company_name.'","website":"'.$website.'","email":"'.$email.'","contact":"'.$contact.'","powered_by":"'.$powered_by.'"},"device_name":';
		}
		else
		{
			$about_query = $db->prepare("select * from about where id=1");
			$about_query->execute();
			$about_count=$about_query->rowCount();
			if($about_count>0)
			{
				while($about_dta=$about_query->fetch())
				{
					$company_name=$about_dta['company_name'];
					$website=$about_dta['website'];
					$email=$about_dta['mail'];
					$contact=$about_dta['contact'];
					$powered_by=$about_dta['powered_by'];
				}
				$response='{"about":{"company_name":"'.$company_name.'","website":"'.$website.'","email":"'.$email.'","contact":"'.$contact.'","powered_by":"'.$powered_by.'"},"device_name":';
			}
		}
		$s = $db->prepare("select device_name from device where deviceid='$deviceid'");
		$s->execute();
		if ($dta=$s->fetch()) 
		{
			do
			{
				$device_name=$dta['device_name'];
			}
			while($dta=$s->fetch());
		}
		$response.='"'.$device_name.'"}';

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