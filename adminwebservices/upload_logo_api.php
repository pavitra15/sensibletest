<?php
	include('../connect.php');
	$d_id=$_POST['d_id'];
	$token=$_POST['token'];
	$deviceid=$_POST['deviceid'];
	// $logo=addslashes(file_get_contents($_FILES['logo']['tmp_name']));
	$logo=$_POST['logo'];
	
	$url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
   
    $insert_query=$db->prepare("insert into last_connect_admin(deviceid,city,state, country, ip, last_login)values('$deviceid','$city','$state','$country','$ip','$last_login') ON DUPLICATE KEY UPDATE city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login'");
    $insert_query->execute();
    $status="active";
    $query=$db->prepare("select * from mobile_token_verify where token='$token' and deviceid='$deviceid' and status='$status'");
    $query->execute();
    $token_count=$query->rowCount();
    if($token_count==1)
    {
    	$query=$db->prepare("update device set print_logo='$logo' where d_id='$d_id'");
    	$query->execute();
    	$count=$query->rowCount();
    	if($count)
    	{
    		$response = array('status' => 2,'message'=>'success');
    		echo json_encode($response);
    	}
    	else
    	{
    		$response = array('status' => 1,'message'=>'fail');
    		echo json_encode($response);
    	}
	}
	else
	{
		$responce = array('status' =>0,'message'=>"token mismatch");
		echo json_encode($responce);
	}
?>