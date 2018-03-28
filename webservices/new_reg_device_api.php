<?php
	include('../connect.php');
    $login_id=$_POST['login_id'];
	$serial_no=$_POST['serial_no'];
    $device_name=$_POST['device_name'];
    $device_type=$_POST['device_type'];
    $language_id=$_POST['language_id'];
    $deviceid=$_POST['deviceid'];
    $token=$_POST['token'];
	
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $log_date=$date->format('Y-m-d H:i:s');
    
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $current_date=$date->format('Y-m-d');

    $url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
    $update_query=$db->prepare("update last_connect set city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login' where deviceid='$deviceid'");
    $update_query->execute();
    $status="active";
    $query=$db->prepare("select * from token_verify where token='$token' and deviceid='$deviceid'");
    $query->execute();
    $token_count=$query->rowCount();
    if($token_count==1)
    {
        $sth=$db->prepare("select * from admin_device where serial_no='$serial_no'");
        $sth->execute();
        $cnt=$sth->rowCount();
        if($cnt==0)
        {
            $responce = array('status' =>1,'message'=>"Invalid serial number");
            echo json_encode($responce);
        }
        else
        {
            $in_status='inactive';
            $st=$db->prepare("select deviceid,model from admin_device where serial_no='$serial_no' and used='no' and status='$in_status'");
            $st->execute();
            $cn=$st->rowCount();
            if($cn>0)
            {
                $responce = array('status' =>2,'message'=>"Device not approved, please contact to customer support");
                echo json_encode($responce);
            }
            else
            {
                $status='active';
                $st=$db->prepare("select deviceid,model from admin_device where serial_no='$serial_no' and used='no' and status='$status'");
                $st->execute();
                $cn=$st->rowCount();
                if($cn>0)
                {
                    if($data=$st->fetch())
                    {
                        do
                        {
                            $deviceid=$data['deviceid'];
                            $model=$data['model'];
                        }
                         while ($data=$st->fetch());
                    }
                   
                    $type='on';
                    $config_type='user';
                    $query=$db->prepare("insert into device(deviceid,serial_no,device_name,id,language_id,device_type,model,tax_type,prnt_billno,prnt_billtime,access_control, config_type,created_by_id,created_by_date,status)values('$deviceid', '$serial_no','$device_name','$login_id','$language_id','$device_type','$model','$type','$type','$type','$status','$config_type','$login_id','$current_date','$status')");

                    $query->execute();
                    $count=$query->rowCount();
                    if($count>0)
                    {
                        $_SESSION['d_id']=$deviceid;
                        $update=$db->prepare("update admin_device set used='use',use_by='$login_id',used_by_date='$current_date' where serial_no='$serial_no'");
                        if($update->execute())
                        {
                            $status="active";
                            $sth=$db->prepare("select * from user_mst,login_mst where user_mst.id=login_mst.id and login_mst.id='$login_id' and login_mst.status='$status'");
                            $sth->execute();
                            while ($data=$sth->fetch())
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
                                                        <p>You are receiving this email because you register your device successfully on POSiBILL</p>
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
                                setSubject('Device Registration inforamation')->
                                setText($message)->
                                setHtml($message);
                            $mailin->send();

                            $device_query=$db->prepare("select d_id, deviceid, serial_no, device_name, language_id, device_type, tax_type from device where deviceid='$deviceid'");
                            $device_query->execute();
                            $responce.='{"status":5,"device":{';
                            if($device_data=$device_query->fetch())
                            {
                                do
                                {
                                    $d_id=$device_data['d_id'];
                                    $device_type=$device_data['device_type'];
                                    $responce.='"d_id":'.$device_data['d_id'].',"deviceid":"'.$device_data['deviceid'].'","serial_no":"'.$device_data['serial_no'].'","device_name":"'.$device_data['device_name'].'","language_id":'.$device_data['language_id'].',"device_type":"'.$device_data['device_type'].'","tax_type":"'.$device_data['tax_type'].'","config_type":"'.$device_data['config_type'].'"';
                                }
                                while($device_data=$device_query->fetch());
                            }
                            $responce.='}}'; 

                            $print_query=$db->prepare("insert into print_setup(d_id,title,sub_title,address,contact,gstn,tax_invoice,bill_name,sr_no,prnt_bill_no,prnt_sr_col, prnt_base_col,prnt_bill_time,prnt_disc_col,footer,consolidated_tax,payment_mode,decimal_point,status,created_by_id,created_by_date)values('$d_id','','','','','',1,'TAX INVOICE','SR. NO.',1,1,0,1,0,'',0,0,3,'$status','$login_id',$current_date)");
                            $print_query->execute();
                            if ($device_type=="Table") 
                            {
                                $person_config="Waiter";
                                $configuration_query=$db->prepare("insert into configuration(d_id, person_config, status, created_by_id, created_by_date)values('$d_id','$person_config','$status','$login_id','$current_date')");
                                $configuration_query->execute();
                            }
                            else
                            {
                                $person_config="Sales Man";
                                $configuration_query=$db->prepare("insert into configuration(d_id, person_config, status, created_by_id, created_by_date)values('$d_id','$person_config','$status','$login_id','$current_date')");
                                $configuration_query->execute();
                            }           
                            echo $responce;
                        }
                    }
                    else
                    {
                        $responce = array('status' =>4,'message'=>"Some technical error, occured please try again");
                        echo json_encode($responce);
                    }           
                }
                else
                {
                    $responce = array('status' =>3,'message'=>"Device already registerd, please contact to customer support");
                    echo json_encode($responce);
                }
            }                   
        }
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }
?>