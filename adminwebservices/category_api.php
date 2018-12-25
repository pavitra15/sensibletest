<?php
	include('../connect.php');
	require_once('../array_to_json.php');
	$deviceid=$_POST['deviceid'];
	$token=$_POST['token'];
	$d_id=$_POST['d_id'];
	$url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
   $status='active';
    $insert_query=$db->prepare("insert into last_connect_admin(deviceid,city,state, country, ip, last_login)values('$deviceid','$city','$state','$country','$ip','$last_login') ON DUPLICATE KEY UPDATE city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login'");
    $insert_query->execute();

	$query=$db->prepare("select * from mobile_token_verify where token='$token' and deviceid='$deviceid' and status='$status'");
	$query->execute();
	$token_count=$query->rowCount();
	if($token_count==1)
	{

	    $st=$db->prepare("select category_id, category_name from category_dtl where deviceid='$d_id' and status='$status'");
		$st->execute();

		if($st->rowCount())
		{
			$category_data=$st->fetchAll(PDO::FETCH_ASSOC);
			$data_array = array('status' =>2 ,'category'=>$category_data);
        	echo  array_to_json($data_array);
		}	
		else
		{
			$responce = array('status' =>1,'message'=>'No category found');
			echo json_encode($responce);
		}
	}
	else
	{
		$responce = array('status' =>0,'message'=>"token mismatch");
		echo json_encode($responce);
	}
?>