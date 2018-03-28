<?php
    include('../connect.php');
    $token=$_POST['token'];
    $deviceid=$_POST['deviceid'];
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
        $sth = $db->prepare("select * from device where deviceid='$deviceid'");
        $sth->execute();
        if ($data = $sth->fetch()) 
        {
            do 
            {
                $access_control=$data['access_control'];
            } 
            while ($data = $sth->fetch());
            $responce = array('status' =>1,'message'=>$access_control);
            echo json_encode($responce);
        
        }
        else
        {
          	$responce = array('status' =>0,'message'=>'Error Occured');
            echo json_encode($responce);
        }
    }
    else
    {
        $responce = array('status' =>2,'message'=>"token mismatch");
        echo json_encode($responce);    
    }

 ?>