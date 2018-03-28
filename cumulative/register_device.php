<?php
    session_start();
    include('../connect.php');
    $serial_no=$_POST['serial_no'];
    $device_name=$_POST['device_name'];
    $language_id=$_POST['device_language'];
    $device_type=$_POST['device_type'];
    $login_id=$_POST['id'];
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $current_date=$date->format('Y-m-d');
    $created_by_date=$date->format('Y-m-d h:m:s');
    $sth=$db->prepare("select * from admin_device where serial_no='$serial_no'");
    $sth->execute();
    $cnt=$sth->rowCount();
    if($cnt==0)
    {
            echo 0;
    }
    else
    {
        $in_status='inactive';
        $st=$db->prepare("select deviceid,model from admin_device where serial_no='$serial_no' and used='no' and status='$in_status'");
        $st->execute();
        $cn=$st->rowCount();
        if($cn>0)
        {
            echo 1;
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
                            $mobile=$data['mobile'];
                        }
                        $user_type="1";
                        $status="active";
                        include ('../mailin-smtp-api-master/Mailin.php');
                        $message='<div width="100%" style="background: #eceff4; padding: 50px 20px; color: #514d6a;">
                            <div style="max-width: 700px; margin: 0px auto; font-size: 14px">
                                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                                    <tr>
                                        <td style="vertical-align: top;">
                                            <a href="https://www.sensibleconnect.com"><img src="https://www.sensibleconnect.com/img/logo-sensible.png" alt="Sensible Connect" style="height: 70px" /></a>
                                        </td>
                                        <td style="text-align: right; vertical-align: middle;">
                                            <span style="color: #a09bb9;">POSiBILL</span>
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
                        $query=$db->prepare("select * from device where serial_no='$serial_no'");
                        $query->execute();
                        $count=$query->rowCount();
                        if($count>0)
                        {
                            while($data=$query->fetch())
                            {
                                $deviceid=$data['deviceid'];
                                $d_id=$data['d_id'];
                                $device_type=$data['device_type'];
                                $device_name=$data['device_name'];
                            }
                            $_SESSION['deviceid']=$deviceid;
                            $_SESSION['d_id']=$d_id;
                            $_SESSION['device_type']=$device_type;
                            $_SESSION['device_name']=$device_name;
                            $query=$db->prepare("insert into user_dtl (user_name, user_type, user_mobile,deviceid, status, created_by_date, created_by_id) values('$name', '$user_type', '$mobile', '$d_id', '$status' , '$current_date', '$login_id')");
                            $query->execute();
                            $print_query=$db->prepare("insert into print_setup(d_id,title,sub_title,address,contact,gstn,tax_invoice,bill_name,sr_no,prnt_bill_no,prnt_sr_col, prnt_base_col,prnt_bill_time,prnt_disc_col,footer,consolidated_tax,payment_mode,decimal_point,status,created_by_id,created_by_date)values('$d_id','','','','','',1,'TAX INVOICE','SR. NO.',1,1,0,1,0,'',0,0,3,'$status','$login_id',$created_by_date)");
                            $print_query->execute();

                            if ($device_type=="Table") 
                            {
                                $person_config="Waiter";
                                $configuration_query=$db->prepare("insert into configuration(d_id, person_config, status, created_by_id, created_by_date)values('$d_id','$person_config','$status','$login_id','$created_by_date')");
                                $configuration_query->execute();
                            }
                            else
                            {
                                $person_config="Sales Man";
                                $configuration_query=$db->prepare("insert into configuration(d_id, person_config, status, created_by_id, created_by_date)values('$d_id','$person_config','$status','$login_id','$created_by_date')");
                                $configuration_query->execute();
                            }
                        echo 2;
                        }
                    } 
                }
                else
                {
                    echo 3;
                }          
            }
            else
            {
                echo 4;
            }                   
        }
    }