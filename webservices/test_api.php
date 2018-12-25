<?php
	include('../connect.php');
	$deviceid=$_POST['deviceid'];
	$mobile_otp=$_POST['mobile_otp'];
	$email_otp=$_POST['email_otp'];
	$token=$_POST['token'];
	$url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
    $status_change_date=$date->format('Y-m-d');
   
    $update_query=$db->prepare("update last_connect set city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login' where deviceid='$deviceid'");
    $update_query->execute();

    $query=$db->prepare("select * from token_verify where token='$token' and deviceid='$deviceid'");
	$query->execute();
	$token_count=$query->rowCount();
	if($token_count==1)
	{
    	$did_query=$db->prepare("select d_id,id from device where deviceid='$deviceid'");
    	$did_query->execute();
	    while($did_data=$did_query->fetch())
	    {
	    	$d_id=$did_data['d_id'];
	    	$id=$did_data['id'];
	    }
		$sth = $db->prepare("select * from temp_hrd_reset where deviceid='$deviceid' AND mobile_otp='$mobile_otp' and email_otp='$email_otp'");
		$sth->execute();
		$count = $sth->rowCount(); 
		if ($count>0)
		{
			if($data=$sth->fetch())
			{
				do
				{
					$dtm=$data['dtm'];
				}
				while ($data=$sth->fetch());
			}
			$current=date("Y-m-d h:i:s");
			$first=strtotime($current);
			$second=strtotime($dtm);
			if(($second-$first)<420)
			{
				uId:
				$u_id=md5(uniqid(mt_rand(),true));
				$check_query=$db->prepare("select * from delete_device where u_id='$u_id'");
				$check_query->execute();
				if($check_query->rowCount())
				{
					goto uId;
				}
				else
				{
					$insert_query=$db->prepare("insert into delete_device (d_id,u_id, deleted_by_id, deleted_by_date) values('$d_id','$u_id','$id','$last_login')");
					$insert_query->execute();

					$update_admin=$db->prepare("update admin_device set used='no' where deviceid='$deviceid'");
                    $update_admin->execute();

					$update_query=$db->prepare("update device set status='delete', deleted_by_id='$id', status_change_date='$status_change_date' where d_id='$d_id'");
					$update_query->execute();
				}

				$sth=$db->prepare("select * from user_mst,login_mst where user_mst.id=login_mst.id and login_mst.id='$id' and login_mst.status='active'");
                $sth->execute();
                while ($data=$sth->fetch())
                {
                	$email=$data['email'];
                    $name=$data['first_name']." ".$data['last_name'];
                }
                $link='https://app.sensibleconnect.com/custom_report/dashboard?u_id='.$u_id;
                include ('../mailin-smtp-api-master/Mailin.php');
                            $message='<div width="100%" style="background: #eceff4; padding: 50px 20px; color: #514d6a;">
                                <div style="max-width: 700px; margin: 0px auto; font-size: 14px">
                                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                                        <tr>
                                            <td style="vertical-align: top;">
                                                <a href="https://www.sensibleconnect.com"><img src="https://www.sensibleconnect.com/img/logo-sensible.png" alt="Sensible Connect" style="height: 70px" /></a>
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
                                                        <p>Hi '.$name.',</p>
                                                        <p>You are receiving this email because you requested to delete your device on POSiBILL</p>
                                                        <p>to get all reports please visit following link and download</p>
                                                        <p>'.$link.'</p>
                                                        <p>You will be taken to POSiBILL where you will manage your devices.</p>
                                                        <p>If you continue to have problems, or if you did not request this email, please contact our Customer Support Heroes.</p>
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
                                            Dont like these emails? <a href="javascript: void(0);" style="color: #a09bb9; text-decoration: underline;">Unsubscribe</a>
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
                                setSubject('Device Deletion inforamation')->
                                setText($message)->
                                setHtml($message);
                            $mailin->send();

				$responce = array('status' =>1,'message'=>'success');
				echo json_encode($responce);
			}
			else
			{
				$responce = array('status' =>2,'message'=>"time out" );
				echo json_encode($responce);
			}
		}
		else
		{
			$responce = array('status' =>3,'message'=>"Invalid otp");
			echo json_encode($responce);
		}
	}
	else
	{
		$responce = array('status' =>0,'message'=>"Token Mismatch");
		echo json_encode($responce);
	}
?>

