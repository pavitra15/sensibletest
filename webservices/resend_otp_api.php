<?php
    include('../connect.php');
    $login_id=$_POST['login_id'];
    $select_query=$db->prepare("select username from login_mst where id='$login_id'");
    $select_query->execute();
    $count=$select_query->rowCount();
    if($count==0)
    {
         $responce = array('status' =>0,'message'=>"User not exist");
            echo json_encode($responce);
    }
    else
    {
        while ($login_data=$select_query->fetch()) 
        {
            $mobile=$login_data['username'];
        } 
        $otp= rand(100000, 999999);
        $authKey = "153437AHNd3Hcat5923dae5";
        $mobileNumber = $mobile;
        $senderId = "SENSBL";
        $message   =  $otp." is your POSiBILL account verification code"; 
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
            $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
            $dtm=$date->format('Y-m-d H:i:s');
            curl_close($ch);
            $query=$db->prepare("insert into temp_mobile_verification(login_id,otp,dtm) values('$login_id','$otp','$dtm')");
            $query->execute();
            $count=$query->rowCount();
            if($count==1)
            {
                $responce='{"status":2,"login_id":'.$login_id.',"message":"OTP send on your mobile"}';
                echo $responce;
            }
            else
            {
            $responce = array('status' =>1,'message'=>"Some technical error occured");
            echo json_encode($responce);
            }
        }
        else
        {
            $responce = array('status' =>1,'message'=>"Some technical error occured");
            echo json_encode($responce);
        }
    }
?>