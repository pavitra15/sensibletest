  <?php
	include('../connect.php');
	$first_name=$_POST['first_name'];
    $last_name=$_POST['last_name'];
    $mobile=$_POST['mobile'];
    $password=$_POST['password'];
    $deviceid=$_POST['deviceid'];
    $email=$_POST['email'];
    $terms=$_POST['terms'];
	$user="user";
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $log_date=$date->format('Y-m-d H:i:s');
    
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $current_date=$date->format('Y-m-d');
    $query=$db->prepare("select * from login_mst where username='$mobile'");
    $query->execute();
    $count=$query->rowCount();
    if($count==0)
    {
        try
        {
            $db->beginTransaction();
            $status='inactive';
            $username_verified='No';
            $insert_query=$db->prepare("insert into login_mst (username,username_verified,password,password_updated_date,status,type,created_by_date) values('$mobile','$username_verified',md5('$password'),'$current_date','$status','$user','$current_date')");
            $insert_query->execute();
            $insert_count=$insert_query->rowCount();
            if($insert_count==1)
            {
                $select_query=$db->prepare("select id from login_mst where username='$mobile' and password=md5('$password')");
                $select_query->execute();
                while ($login_data=$select_query->fetch()) 
                {
                    $login_id=$login_data['id'];
                }
                $status='active';
                $insert_query=$db->prepare("insert into user_mst(id, first_name,last_name, mobile, email, created_date,created_by_id ) values('$login_id','$first_name','$last_name','$mobile', '$email', '$current_date','$login_id')");
                $insert_query->execute();

                if($terms=="Accept")
                {
                    $accept="Accept";
                    $term_query=$db->prepare("insert into termsndcon(login_id, deviceid, created_date, status)values('$login_id','$deviceid','$current_date','$accept')");
                    $term_query->execute();    
                }
                else
                {
                    $accept="Decline";
                    $term_query=$db->prepare("insert into termsndcon(login_id, deviceid, created_date, status)values('$login_id','$deviceid','$current_date','$accept')");
                    $term_query->execute();    
                }
                if($db->commit())
                {
                    $otp= rand(100000, 999999);
                    $authKey = "153437AHNd3Hcat5923dae5";
                    $mobileNumber = $mobile;
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
                            $responce='{"status":2,"login_id":'.$login_id.',"message":"OTP send on your mobile"}';
                            echo $responce;
                        }
                        else
                        {
                            $responce = array('status' =>1,'message'=>"Some technical error occured");
                            echo json_encode($responce);
                        }
                    }
                }
                else
                {
                    $db->rollBack();
                    $responce = array('status' =>1,'message'=>"Some technical error occured");
                    echo json_encode($responce); 
                }
            }
        }
        catch(Exception $e)
        {
            echo $e;
        }
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Username already exists");
        echo json_encode($responce);
    }
?>