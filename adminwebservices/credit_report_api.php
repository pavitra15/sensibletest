<?php
    include('../connect.php');
    $deviceid=$_POST['deviceid'];
    $d_id=$_POST['d_id'];
    $token=$_POST['token'];
    $first_date =  new DateTime($_POST['start_date']);
    $second_date = new DateTime($_POST['end_date']);
    $clone_first=clone $first_date;
    $clone_second=clone $second_date;
    $start_date = $clone_first->format('Y-m-d 00:00:00');
    $end_date = $second_date->format('Y-m-d 23:59:59');
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
        $query=$db->prepare("select customer_name,customer_contact, sum(credit) AS total from customer_mst, transaction_mst where customer_mst.customer_id=transaction_mst.customer_id and transaction_mst.status='$status' and customer_mst.deviceid='$d_id' group by (customer_mst.customer_id)");
        $query->execute();
        $data=$query->fetchAll(PDO::FETCH_ASSOC);
        $responce = array('status' =>1 ,'credit_report' =>$data);   
        echo json_encode($responce, JSON_NUMERIC_CHECK);
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }
?>