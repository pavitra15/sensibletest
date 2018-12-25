<?php
	session_start();
    include('../connect.php'); 
	$user_id=$_SESSION['login_id'];
	$sth = $db->prepare("select email, mobile  from user_mst where id=$user_id");
    $sth->execute();
    if($data=$sth->fetch())
    {
     	do
        {
        	$mobile=$data['mobile'];
        }
        while ($data=$sth->fetch());
    }
    $deviceid=$_SESSION['d_id'];
    $mobile_otp= rand(100000, 999999);
    $authKey = "153437AHNd3Hcat5923dae5";
    $mobileNumber = $mobile;
    $senderId = "SENSBL";
    $message   = " POSiBILL delete device verification code is ".$mobile_otp; 
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
        	$response=array('status' => 1);
        	echo json_encode($response);
        }
        else
        {
        	$response=array('status' => 2);
        	echo json_encode($response);
        }
    }
    else
    {
    	$response=array('status' => 2);
        echo json_encode($response);
    }
?>