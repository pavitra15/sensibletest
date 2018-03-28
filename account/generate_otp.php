<?php
	include('../connect.php');
	$mobile=$_POST['mobile'];
	$sth=$db->prepare("select * from login_mst where username='$mobile'");
    $sth->execute();
    $cnt=$sth->rowCount();
    if($cnt==0)
    {
     	$otp= rand(100000, 999999);
    	$authKey = "153437AHNd3Hcat5923dae5";
    	$senderId = "SENSBL";
    	$message   =  $otp." is your POSiBILL account verification code"; 
    	$route = "default";
    	$postData = array(
               'authkey' => $authKey,
               'mobiles' => $mobile,
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
            $dtm=date('Y-m-d h:i:s');
            curl_close($ch);
            $query=$db->prepare("insert into temp_verification(mobile,otp,dtm) values('$mobile','$otp','$dtm')");
            $query->execute();
            $count=$query->rowCount();
            if($count>0)
            {
                echo "1";
            }
            else
            {
            	echo "3";
            }
        }
        else
        {
            echo "4";
        }   
    }
    else
    {
    	echo "2";
	}
?>