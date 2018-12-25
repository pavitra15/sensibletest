<?php
	session_start();
    include('../connect.php'); 
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
    $status_change_date=$date->format('Y-m-d');
	$user_id=$_SESSION['login_id'];
    $mobile_otp=$_POST['otp'];
	$sth = $db->prepare("select email, mobile, first_name, last_name from user_mst where id=$user_id");
    $sth->execute();
    if($data=$sth->fetch())
    {
     	do
        {
        	$email=$data['email'];
            $name=$data['first_name']." ".$data['last_name'];
        }
        while ($data=$sth->fetch());
    }
    $deviceid=$_SESSION['d_id'];

    $query=$db->prepare("select * from temp_sft_reset where mobile_otp='$mobile_otp' and deviceid='$deviceid'");
    $query->execute();
    $count=$query->rowCount();
    if($count==1)
    {
        do
        {
            $dtm=$data['dtm'];
        }
        while ($data=$query->fetch()); 
        $current=date('Y-m-d h:i:s');
        $first=strtotime($current);
        $second=strtotime($dtm);
        if(($first-$second)<420)
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
                    $insert_query=$db->prepare("insert into delete_device (d_id,u_id, deleted_by_id, deleted_by_date) values('$deviceid','$u_id','$user_id','$last_login')");
                    $insert_query->execute();

                    $select_query=$db->prepare("select deviceid from device where d_id='$deviceid'");
                    $select_query->execute();
                    while($select_data=$select_query->fetch())
                    {
                        $admin_deviceid=$select_data['deviceid'];
                    }

                    $update_admin=$db->prepare("update admin_device set used='no' where deviceid='$admin_deviceid'");
                    $update_admin->execute();

                    $update_query=$db->prepare("update device set status='delete', deleted_by_id='$user_id', status_change_date='$status_change_date' where d_id='$deviceid'");
                    $update_query->execute();
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
                            $response=array('status' => 1);
            echo json_encode($response);

        }
        else
        {
            $response=array('status' => 2);
            echo json_encode($response);
        }  
    }
    else
    {
        $response=array('status' => 2);
        echo json_encode($response);
    }

?>