<?php
	include('../connect.php');
	$username=$_POST['username'];
    $query=$db->prepare("select id from login_mst where username='$username'");
    $query->execute();
    $count=$query->rowCount();
    $status='active';
	if($count==1)
    {
        $select_query=$db->prepare("select * from login_mst where username='$username' and status='$status' and access_control='$status'");
        $select_query->execute();
        $count=$select_query->rowCount();
        if ($count==1)
        {
            if($data=$select_query->fetch())
            {
                do
                {
                    $password=$data['password'];
                }
                while ($data=$select_query->fetch());
            }
            if($password!="")
            {
            	$authKey = "153437AHNd3Hcat5923dae5";
                $mobileNumber = $username;
                $senderId = "SENSBL";
                $message   =  $password." is your POSiBILL account password"; 
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
                    $responce = array('status' =>3,'message'=>"Password send to your mobile");
                    echo json_encode($responce);
                }
                else
                {
                	$responce = array('status' =>2,'message'=>"Some technical error occured");
                    echo json_encode($responce);
                }
        	}
        }
        else
        {
            $responce = array('status' =>1,'message'=>"Contact to customer support");
            echo json_encode($responce);
        }
    }
    else
    {
    	$responce = array('status' =>0,'message'=>"Invalid user name");
        echo json_encode($responce);
    }
?>