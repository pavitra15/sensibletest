<?
    include('../connect.php');
    $deviceid=$_POST['deviceid'];
    $serial_no=$_POST['serial_no'];

    $url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
   
    $update_query=$db->prepare("update last_connect set city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login' where deviceid='$deviceid'");
    $update_query->execute();
    $in_status='inactive';
    $st=$db->prepare("select deviceid,model from admin_device where serial_no='$serial_no' and used='no' and status='$in_status'");
    $st->execute();
    $cn=$st->rowCount();
    if($cn>0)
    {
        $responce = array('status' =>4,'message'=>"Device not approved, please contact to customer support");
        echo json_encode($responce);
    }
    else
    {
        $sth = $db->prepare("select * from device,login_mst where device.id=login_mst.id AND deviceid='$deviceid'");
        $sth->execute();
        $count = $sth->rowCount(); 
        if ($count>0) 
        {
            $status="active";
            $st = $db->prepare("select * from device,user_mst,login_mst where user_mst.id=login_mst.id and device.id=login_mst.id AND deviceid='$deviceid' and device.status='$status'");
            $st->execute();
            $cnt = $st->rowCount(); 
            if ($cnt>0) 
            {
    	       if($data=$st->fetch())
    	       {
    	            do
    		        {
                        $mobile=$data['mobile'];
                    }
                    while ($data=$st->fetch());
                }
                $otp= rand(100000, 999999);
                $authKey = "153437AHNd3Hcat5923dae5";
                $mobileNumber = $mobile;
                $senderId = "SENSBL";
                $message   = " POSiBILL device verification code is ".$otp; 
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
            	   $query=$db->prepare("insert into temp_device(device,otp,dtm) values('$deviceid','$otp','$dtm')");
            	   if($query->execute())
            	   {
                        $responce = array('status' =>1,'message'=>"OTP send on your mobile");
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
                $responce = array('status' =>2 ,'message'=>"Contact to dealer");
                echo json_encode($responce);   
            }
        }
        else
        {
        	$responce = array('status' =>3 ,'message'=>"Please complete profile on web");
        	echo json_encode($responce);
        }
    }
?>