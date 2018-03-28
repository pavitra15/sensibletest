<?php
    session_start();
    include('../connect.php');  
    if(is_ajax())
    {
        $mobile=$_SESSION['mobile'];
        if($mobile!="")
        {
            $otp= rand(100000, 999999);
            $authKey = "153437AHNd3Hcat5923dae5";
            $mobileNumber = $_SESSION['mobile'];
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
                $dtm=date('Y-m-d h:i:s');
                curl_close($ch);
                $sth=$db->prepare("delete from temp_verification where mobile='$mobile'");
                $sth->execute();
                $query=$db->prepare("insert into temp_verification(mobile,otp,dtm) values('$mobile','$otp','$dtm')");
                $query->execute();
                if($query->rowCount())
                {
                   echo 1;
                }
                else
                {
                    echo 2;
                }
            }
            else
            {
                echo 2;
            }
        }
        else
        {
            echo 3;
        }
    }
    else
    {
        echo "Something goes wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
?>