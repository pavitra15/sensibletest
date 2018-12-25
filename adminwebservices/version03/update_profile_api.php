<?php
    include('../connect.php');
    $token=$_POST['token'];
    $deviceid=$_POST['deviceid'];
    $login_id=$_POST['login_id'];
    $first_name=$_POST['first_name'];
    $last_name=$_POST['last_name'];
    $email=$_POST['email'];
    $city=$_POST['city'];
    $state_id=$_POST['state_id'];
    $pin_code=$_POST['pin_code'];
    $url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
    $insert_query=$db->prepare("insert into last_connect_admin(deviceid,city,state, country, ip, last_login)values('$deviceid','$city','$state','$country','$ip','$last_login') ON DUPLICATE KEY UPDATE city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login'");
    $insert_query->execute();
    $status="active";
    $query=$db->prepare("select * from mobile_token_verify where token='$token' and deviceid='$deviceid' and status='$status'");
    $query->execute();
    $token_count=$query->rowCount();
    if($token_count==1)
    {
        $update_query=$db->prepare("update user_mst set first_name='$first_name', last_name='$last_name', email='$email', state='$state_id', city='$city', pincode='$pin_code' where id='$login_id'");
        $update_query->execute();
        $count=$update_query->rowCount();
        if($count>0)
        {
            $query=$db->prepare("insert into notification_mst (d_id, user_id, notification, device_name, priority,see,notification_time) values('0', '$login_id', 'profile updated successfully', '', '0', '0', '$last_login')");
            $query->execute();
            $responce = array('status' =>2,'message'=>"Profile updated successfully");
            echo json_encode($responce);
        }
        else
        {
            $responce = array('status' =>1,'message'=>"Fail to update");
            echo json_encode($responce);
        }
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }
?>