<?php
	include('../connect.php');
	$deviceid=$_POST['deviceid'];
	$token=$_POST['token'];
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

		$did_query=$db->prepare("select d_id from device where deviceid='$deviceid'");
	    $did_query->execute();
	    while($did_data=$did_query->fetch())
	    {
	    	$d_id=$did_data['d_id'];
	    }

	    $st=$db->prepare("select * from premise_dtl where deviceid='$d_id' and status='$status'");
		$st->execute();
		if($da=$st->fetch())
		{
			$user='{"premise":[';
			do
			{
				$user.='{"premise_id":'.$da['premise_id'].',';
				$user.='"no_of_table":'.$da['no_of_table'].',';
				$user.='"premise_name":"'.$da['premise_name'].'"},';
			}	
			while ($da=$st->fetch());
		}
		$user = rtrim($user, ',');
		$user.=']}';

		
		function json_validator($data=NULL) 
		{
	    	if (!empty($data)) 
	    	{
	        	@json_decode($data);
	            return (json_last_error() === JSON_ERROR_NONE);
	        }
	        return false;
		}
		if(json_validator($user))
		{
			echo $user;
		}
		else
		{
			$responce = array('status' =>1,'message'=>'Properly add data');
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