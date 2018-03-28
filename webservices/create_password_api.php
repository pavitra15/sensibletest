<?php
	include('../connect.php');
	$deviceid=$_POST['deviceid'];
	$user_id=$_POST['user_id'];
	$username=$_POST['username'];
	$password=$_POST['password'];
	$url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
   
    $update_query=$db->prepare("update last_connect set city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login' where deviceid='$deviceid'");
    $update_query->execute();
	$admin='Administrator';
	$admin_flag=0;
	$admin_mobile;
	$status="active";
	$id;

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

    $sth=$db->prepare("select * from user_dtl where user_name='$username' and user_id='$user_id' and deviceid='$d_id' and status='$status'");
    // $sth=$db->prepare("select * from user_dtl where user_name='$username' and deviceid='$d_id' and status='$status'");
    $sth->execute();
	$count=$sth->rowCount();
	if($count==1)
	{
		$query=$db->prepare("select * from user_dtl, user_type_mst where deviceid='$d_id' and user_dtl.user_type=user_type_mst.id and user_dtl.status='$status'");
		$query->execute();
		if($data=$query->fetch())
		{
			do
			{
				if($data['name']==$admin)
				{
					$admin_mobile=$data['user_mobile'];
				}
			}while($data=$query->fetch());
		}
		$st=$db->prepare("select * from user_dtl where deviceid='$d_id' AND user_name='$username' and user_id='$user_id' ");
		// $st=$db->prepare("select * from user_dtl where deviceid='$d_id' AND user_name='$username'");
		$st->execute();
		if($data=$st->fetch())
		{
			do
			{
				$id=$data['user_id'];
			}while($data=$st->fetch());	
		}
		$otp= rand(100000, 999999);
    	$authKey = "153437AHNd3Hcat5923dae5";
    	$mobileNumber = $admin_mobile;
    	$senderId = "SENSBL";
    	$message   = "POSiBILL device password verification code is ".$otp; 
    	$route = "default";
    	$postData = array(
	            'authkey' => $authKey,
	            'mobiles' => $mobileNumber,
	            'message' => $message,
	            'sender' => $senderId,
	            'route' => $route,
	            'otp'=>$otp
	            );
	    $url="https://control.msg91.com/api/sendotp.php";
	    $ch = curl_init();
	    curl_setopt_array($ch, array(
	    CURLOPT_URL => $url,
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_POST => true,
	    CURLOPT_POSTFIELDS => $postData
	    ));
	                //Ignore SSL certificate verification
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    if(curl_exec($ch))
	    {
		   	$dtm=date("Y-m-d h:i:s");
		   	curl_close($ch);
		   	$query=$db->prepare("insert into temp_user(device,uid,otp,dtm) values('$deviceid','$id','$otp','$dtm')");
		   	if($query->execute())
		   	{
				$responce = array('status' =>1,'id'=>$id,'password'=>$password,'message'=>"OTP is send on your mobile");
				echo json_encode($responce);
		    }
		    else
		    {
		    	$responce = array('status' =>0,'message'=>"unsuccess");
				echo json_encode($responce);
		    }
		}
	}
	else
	{
		$responce = array('status' =>2,'message'=>"user not exist");
		echo json_encode($responce);
	}
?>