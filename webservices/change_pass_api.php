<?php
	include('../connect.php');
	$token=$_POST['token'];
	$deviceid=$_POST['deviceid'];
	$username=$_POST['username'];
	$old_password=$_POST['old_password'];
	$new_password=$_POST['new_password'];
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
        	$d_id=$access_data['d_id'];
    	} 
        while ($access_data = $access_query->fetch());
    }
    
	$query=$db->prepare("select * from token_verify where token='$token' and deviceid='$deviceid'");
	$query->execute();
	$token_count=$query->rowCount();
	if($token_count==1)
	{
		$sth=$db->prepare("select * from user_dtl where deviceid='$d_id' and user_name='$username' and password='$old_password'");
		$sth->execute();
		$count=$sth->rowCount();
		if($count==1)
		{
			$query=$db->prepare("update user_dtl set password='$new_password' where deviceid='$d_id' and user_name='$username'");
			$query->execute();
			$cnt=$query->rowCount();
			if($cnt==1)
			{
				$responce=array('status'=>1,'message'=>'Password successfully set');
				echo json_encode($responce);	
			}
			else
			{
				$responce=array('status'=>0,'message'=>'Error occured');
				echo json_encode($responce);
			}
		}
		else
		{
			$responce = array('status' =>2,'message'=>"password mismatch");
			echo json_encode($responce);	
		}
	}
	else
	{
		$responce = array('status' =>3,'message'=>"token mismatch");
		echo json_encode($responce);
	}
?>