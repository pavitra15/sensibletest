<?php
    include('../connect.php');
    $query=$db->prepare("select mobile_no from back_act_otp where id=1");
    $query->execute();
    if($data=$query->fetch())
    {
        do
        {
            $mobile=$data['mobile_no'];
        }
        while ($data=$query->fetch());
    }
    $deviceid=$_POST['deviceid'];
    $otp= rand(100000, 999999);
    $authKey = "153437AHNd3Hcat5923dae5";
    $mobileNumber = $mobile;
   	$senderId = "SENSBL";
    $message   = " POSiBILL device back activity verification code is ".$otp; 
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
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    if(curl_exec($ch))
    {
    	$dtm=date("Y-m-d h:i:s");
       	curl_close($ch);
        $query=$db->prepare("insert into back_act_verify(deviceid,otp,dtm) values('$deviceid','$otp','$dtm')");
        if($query->execute())
        {
        	$responce = array('status' =>1,'message'=>"OTP send to admin mobile no");
            echo json_encode($responce);
        }
        else
        {
        	$responce = array('status' =>0,'message'=>"unsuccess");
            echo json_encode($responce);
       	}
    }
    else
    {
    	$responce = array('status' =>2 ,'message'=>"Contact to dealer");
        echo json_encode($responce);   
    }
?>