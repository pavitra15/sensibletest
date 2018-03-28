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

		$sth = $db->prepare("select email, mobile  from user_mst where id=$user_id");
		$sth->execute();
		if($data=$sth->fetch())
		{
			do
			{
				$mobile=$data['mobile'];
				$email=$data['email'];
			}
			while ($data=$sth->fetch());
		}
		if($mobile=="")
		{
			$responce = array('status' =>1,'message'=>'please update mobile number into website');
			echo json_encode($responce);	
		}
		elseif ($email=="") 
		{
			$responce = array('status' =>2,'message'=>'please update email address into website');
			echo json_encode($responce);
		}
		else
		{
	        $mobile_otp= rand(100000, 999999);
	        $authKey = "153437AHNd3Hcat5923dae5";
	        $mobileNumber = $mobile;
	        $senderId = "SENSBL";
	        $message   = " POSiBILL device hard reset verification code is ".$mobile_otp; 
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
	        	$otp_mobile=1;
	        }
	        $email_otp= rand(100000, 999999);
	        include ('../mailin-smtp-api-master/Mailin.php');
            $message='<div width="100%" style="background: #eceff4; padding: 50px 20px; color: #514d6a;">
                <div style="max-width: 700px; margin: 0px auto; font-size: 14px">
                	<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                    	<tr>
                        	<td style="vertical-align: top;">
                            	<a href="https://www.sensibleconnect.com"><img src="https://glass-approach-179716.appspot.com/img/logo-sensible.png" alt="Sensible Connect" style="height: 70px" /></a>
                            </td>
                            <td style="text-align: right; vertical-align: middle;">
                            	<span style="color: #a09bb9;">
                                	POSiBILL
                                </span>
                            </td>
                        </tr>
                    </table>
                    <div style="padding: 40px 40px 20px 40px; background: #fff;">
                    	<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                        	<tbody>
                            	<tr>
                                	<td>
                                    	<p>Hi,</p>
                                        <p>You are receiving this email because you requested a hard reset for POSiBILL device</p>
                                        <p>OTP valid for 10 minute only</p>
                                        <p>OTP : '.$email_otp.'</p>
                                        <p>Thank you,<br>The Sensible Connect Solutions Team</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                   	<div style="text-align: center; font-size: 12px; color: #a09bb9; margin-top: 20px">
                        <p>
                        	Sensible Connect solutions Pvt Ltd, Pune, 411009
                    	    <br />
                            © 2017 Sensible Connect solutions pvt Ltd. All Rights Reserved.
                        </p>
                    </div>
                </div>
            </div>';
            $mailin = new Mailin('info@sensibleconnect.com', 'QUT6g8qdZ7XmVn49');
            $mailin->
            	addTo($email, 'Sensible Connect')->
                setFrom('info@sensibleconnect.com', 'Sensible Connect')->
                setReplyTo('info@sensibleconnect.com','Sensible Connect')->
                setSubject('Reset POS Device')->
                setText($message)->
                setHtml($message);
            if($mailin->send())
            {   
            	$otp_email=1;
            }
	        if($otp_email==1 && $otp_email==1)
	        {
	        	$dtm=date("Y-m-d h:i:s");
	        	$query=$db->prepare("insert into temp_hrd_reset(email_otp,mobile_otp,deviceid,dtm) values('$email_otp','$mobile_otp','$deviceid','$dtm')");
		   		if($query->execute())
		   		{
					$responce = array('status' =>3,'message'=>"OTP is send on your mobile and email");
					echo json_encode($responce);
		    	}
		    	else
		    	{
		    		$responce = array('status' =>4,'message'=>"Some error occured");
					echo json_encode($responce);	
		    	}
	        }
	        else
	        {
	        	$responce = array('status' =>5,'message'=>"please try again");
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