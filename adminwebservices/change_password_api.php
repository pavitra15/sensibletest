<?php
    include('../connect.php');
    $token=$_POST['token'];
    $deviceid=$_POST['deviceid'];
    $login_id=$_POST['login_id'];
    $old_pass=$_POST['old_password'];
    $new_pass=$_POST['new_password'];
    $url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
    $update_date=$date->format('Y-m-d');
    $insert_query=$db->prepare("insert into last_connect_admin(deviceid,city,state, country, ip, last_login)values('$deviceid','$city','$state','$country','$ip','$last_login') ON DUPLICATE KEY UPDATE city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login'");
    $insert_query->execute();
    $status="active";
    $query=$db->prepare("select * from mobile_token_verify where token='$token' and deviceid='$deviceid' and status='$status'");
    $query->execute();
    $token_count=$query->rowCount();
    if($token_count==1)
    {
        $key='123acd1245120954';
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $old_password = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $old_pass, MCRYPT_MODE_ECB, $iv));
            
        $password_query=$db->prepare("select * from login_mst where id='$login_id' and password='$old_password' and status='$status'");
        $password_query->execute();
        $count=$password_query->rowCount();
        if($count==1)
        {
            $new_password=base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $new_pass, MCRYPT_MODE_ECB, $iv));
            $update_query=$db->prepare("update login_mst set password='$new_password', password_updated_date='$update_date', updated_by_id='$login_id', updated_by_date='$update_date' where id='$login_id'");
            $update_query->execute();
            $update_count=$update_query->rowCount();
            if($update_count>0)
            {
                $mail_query=$db->prepare("select first_name,last_name, email from user_mst where id='$login_id'");
                $mail_query->execute();
                while($data=$mail_query->fetch())
                {
                    $email=$data['email'];
                    $name=$data['first_name']." ".$data['last_name'];
                }
                include ('../mailin-smtp-api-master/Mailin.php');
                $message='<div width="100%" style="background: #eceff4; padding: 50px 20px; color: #514d6a;">
                    <div style="max-width: 700px; margin: 0px auto; font-size: 14px">
                        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                            <tr>
                                <td style="vertical-align: top;">
                                    <img src="https://www.sensibleconnect.com/img/logo-sensible.png" alt="Sensible Connect" style="height: 70px" />
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
                                            <p>Your Password has been updated</p>
                                            <p>If you continue to have problems,or if you did not request this email, please contact our Customer Support Heroes.</p>
                                            <p>Thank you,<br/>The Sensible Connect team</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div style="text-align: center; font-size: 12px; color: #a09bb9; margin-top: 20px">
                            <p>
                                Sensible Connect solutions Pvt Ltd, Pune, 411038
                            <br />
                                Dont like these emails? <a href="javascript: void(0);" style="color: #a09bb9; text-decoration: underline;">Unsubscribe</a>
                                <br />
                                © 2018 Sensible Connect solutions pvt Ltd. All Rights Reserved.
                            </p>
                        </div>
                    </div>
                </div>';
                $mailin = new Mailin('info@sensibleconnect.com', 'QUT6g8qdZ7XmVn49');
                $mailin->
                    addTo($email, 'Sensible Connect')->
                    setFrom('info@sensibleconnect.com', 'Sensible Connect')->
                    setReplyTo('info@sensibleconnect.com','Sensible Connect')->
                    setSubject('Password updated successfully')->
                    setText($message)->setHtml($message);
                    $res = $mailin->send();
                        
                $query=$db->prepare("insert into notification_mst (d_id, user_id, notification, device_name, priority,see,notification_time) values('0', '$login_id', 'Password updated successfully', '', '0', '0', '$last_login')");
                $query->execute();
                $responce = array('status' =>3,'message'=>"Password updated successfully");
                echo json_encode($responce);
            }
            else
            {
                $responce = array('status' =>2,'message'=>"Fail to update password");
                echo json_encode($responce);
            }
        }
        else
        {
            $responce = array('status' =>1,'message'=>"Old password mismatch");
            echo json_encode($responce);
        }
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }
?>