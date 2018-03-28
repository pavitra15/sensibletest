<?php
	include('../connect.php');
	$deviceid=$_POST['deviceid'];
	$token=$_POST['token'];
	$status="active";
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
	    $access_query = $db->prepare("select * from device where deviceid='$deviceid'");
	    $access_query->execute();
	    if ($access_data = $access_query->fetch()) 
	    {
	        do 
	        {
	        	$d_id=$access_data['d_id'];
	        	$user_id=$access_data['id'];
	    	} 
	        while ($access_data = $access_query->fetch());
	    }

		$sth = $db->prepare("select mobile  from user_mst where id=$user_id");
		$sth->execute();
		if($data=$sth->fetch())
		{
			do
			{
				$mobile=$data['mobile'];
			}
			while ($data=$sth->fetch());
		}
		if($mobile=="")
		{
			$responce = array('status' =>1,'message'=>'please update mobile number into website');
			echo json_encode($responce);	
		}
		else
		{
	        $mobile_otp= rand(100000, 999999);
	        $authKey = "153437AHNd3Hcat5923dae5";
	        $mobileNumber = $mobile;
	        $senderId = "SENSBL";
	        $message   = " POSiBILL device soft reset verification code is ".$mobile_otp; 
	        $route = "default";
	        $postData = array(
	        	'authkey' => $authKey,
	            'mobiles' => $mobile,
	            'message' => $message,
	            'sender' => $senderId,
	            'route' => $route,
	            'otp'=>$mobile_otp
	        );
	        $url="https://control.msg91.com/api/sendotp.php";
	        $ch = curl_init();
	        curl_setopt_array($ch, array(
	        CURLOPT_URL => $url,
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_POST => true,
	        CURLOPT_POSTFIELDS => $postData
	        ));
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	        if(curl_exec($ch))
	        {
	        	$dtm=date("Y-m-d h:i:s");
	        	$query=$db->prepare("insert into temp_sft_reset(mobile_otp,deviceid,dtm) values('$mobile_otp','$deviceid','$dtm')");
		   		if($query->execute())
		   		{
					$responce = array('status' =>2,'message'=>"OTP is send on your mobile");
					echo json_encode($responce);
		    	}
		    	else
		    	{
		    		$responce = array('status' =>3,'message'=>"Some error occured");
					echo json_encode($responce);	
		    	}
	        }
	        else
	        {
	        	$responce = array('status' =>4,'message'=>"please try again");
				echo json_encode($responce);
	        }
		}
	}
	else
	{
		$responce = array('status' =>0,'message'=>"token mismatch");
		echo json_encode($responce);
	}
?>