<?php
	include('../connect.php');
	$deviceid=$_POST['deviceid'];
	$mobile_otp=$_POST['mobile_otp'];
	$email_otp=$_POST['email_otp'];
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
		$sth = $db->prepare("select * from temp_hrd_reset where deviceid='$deviceid' AND mobile_otp='$mobile_otp' and email_otp='$email_otp'");
		$sth->execute();
		$count = $sth->rowCount(); 
		if ($count>0)
		{
			if($data=$sth->fetch())
			{
				do
				{
					$dtm=$data['dtm'];
				}
				while ($data=$sth->fetch());
			}
			$current=date("Y-m-d h:i:s");
			$first=strtotime($current);
			$second=strtotime($dtm);
			if(($first-$second)<420)//
			{
				$responce = array('status' =>1,'message'=>'success');
				echo json_encode($responce);
			}
			else
			{
				$responce = array('status' =>2,'message'=>"time out" );
				echo json_encode($responce);
			}
		}
		else
		{
			$responce = array('status' =>3,'message'=>"Invalid otp");
			echo json_encode($responce);
		}
	}
	else
	{
		$responce = array('status' =>0,'message'=>"Token Mismatch");
		echo json_encode($responce);
	}
?>