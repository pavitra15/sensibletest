<?php
	include('../connect.php');
	$username=$_POST['username'];
	$pass=$_POST['password'];
	$deviceid=$_POST['deviceid'];
	$status="active";
    $type="user";
    $key='123acd1245120954';
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $password = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $pass, MCRYPT_MODE_ECB, $iv));
    $query=$db->prepare("select * from login_mst where username='$username' and password='$password' and status='$status'");
    $query->execute();
    $count=$query->rowCount();
    if ($count==1)
    {
    	$select_query=$db->prepare("select login_mst.id, first_name, last_name, mobile, email, state, city, pincode from login_mst, user_mst where user_mst.id=login_mst.id and username='$username' and password='$password' and status='$status' and access_control='$status' and type='$type'");
        $select_query->execute();
        $count=$select_query->rowCount();
        if($count==1)
        {
            $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
            $visit_date=$date->format('Y-m-d');
            
        	$responce='{"status":2,';
            $delete_query=$db->prepare("delete from temp_mobile_verification where login_id='$login_id' AND otp='$otp'");
            $delete_query->execute();
            $status='active';
            $token=md5(uniqid(mt_rand(),true));
            $responce.='"token":"'.$token.'",';
        	if($data=$select_query->fetch())
        	{
        		$id=$data['id'];
        		$query=$db->prepare("insert into token_verify(deviceid,token)values('$deviceid','$token')");
                $query->execute();
            	do
	            {
	            	$id=$data['id'];
                    if($data['state']=="")
                    {
                        $state=0;
                    }
                    else
                    {
                        $state=$data['state'];
                    }
                    if($data['pincode']=="")
                    {
                        $pincode=0;
                    }
                    else
                    {
                        $pincode=$data['pincode'];
                    }
	            	$responce.='"user":{"id":'.$data['id'].',"first_name":"'.$data['first_name'].'","last_name":"'.$data['last_name'].'","mobile":'.$data['mobile'].',"email":"'.$data['email'].'","state":'.$state.',"city":"'.$data['city'].'","pincode":'.$pincode.'},';
	            }
	            while ($data=$select_query->fetch());
        	}

        	$device_query=$db->prepare("select d_id, deviceid, serial_no, device_name, language_id, device_type, tax_type from device where id='$id' and deviceid='$deviceid'");
            $device_query->execute();
        	$responce.='"device":{';
        	if($device_data=$device_query->fetch())
        	{
        		do
        		{
        			$responce.='"d_id":'.$device_data['d_id'].',"deviceid":"'.$device_data['deviceid'].'","serial_no":"'.$device_data['serial_no'].'","device_name":"'.$device_data['device_name'].'","language_id":'.$device_data['language_id'].',"device_type":"'.$device_data['device_type'].'","tax_type":"'.$device_data['tax_type'].'","config_type":"'.$device_data['config_type'].'"';
        		}
        		while($device_data=$device_query->fetch());
        	}
            $responce.='}}';            
            echo $responce;
        }
        else
        {
            $responce = array('status' =>1,'message'=>"please contact to customer support, access denied");
			echo json_encode($responce);
        }
    }
    else
    {
        $inactive_status='inactive';
        $query=$db->prepare("select * from login_mst where username='$username' and password='$password' and status='$inactive_status'");
        $query->execute();
        $count=$query->rowCount();
        if ($count==1)
        {
            if($data=$query->fetch())
            {
                do
                {
                    $login_id=$data['id'];
                }
                while($data=$query->fetch());
            }
            $otp= rand(100000, 999999);
            $authKey = "153437AHNd3Hcat5923dae5";
            $mobileNumber = $username;
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
                    $responce='{"status":3,"login_id":'.$login_id.',"message":"OTP send on your mobile"}';
                    echo $responce;
                }
                else
                {
                    $responce = array('status' =>4,'message'=>"Some technical error occured");
                    echo json_encode($responce);
                }
            }
        
        }
        else
        {
            $responce = array('status' =>0,'message'=>"Invalid username or password");
            echo json_encode($responce);
        }
    }
?>