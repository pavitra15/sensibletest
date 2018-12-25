<?php
	include('../connect.php');
	$deviceid=$_POST['deviceid'];
    $token=$_POST['token'];
    $json=$_POST['json'];
    $d_id=$_POST['d_id'];
    $id=$_POST['id'];
    $status='active';
   
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
     $insert_query=$db->prepare("insert into last_connect_admin(deviceid,city,state, country, ip, last_login)values('$deviceid','$city','$state','$country','$ip','$last_login') ON DUPLICATE KEY UPDATE city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login'");
    $insert_query->execute();
    $status="active";
    $query=$db->prepare("select * from mobile_token_verify where token='$token' and deviceid='$deviceid' and status='$status'");
    $query->execute();
    $token_count=$query->rowCount();
    if($token_count==1)
    {
        $status="active";
        function json_validator($data=NULL) 
        {
            if (!empty($data)) 
            {
                @json_decode($data);
                return (json_last_error() === JSON_ERROR_NONE);
            }
            return false;
        }
        if(json_validator($json))
        {
            $object=json_decode($json);
            $records=$object->records;
            $length=sizeof($records);
            $response='{"status":2,"record":[';
            for($i=0; $i<$length; $i++)
            {
                $category_id=$records[$i]->category_id;
                $status="delete";
                $query=$db->prepare("update category_dtl set status='$status', status_change_date='$current_date', deleted_by_id='$id' where category_id='$category_id'");
                $query->execute();
                $response.='{"category_id":'.$category_id.',"status":"delete"},';
            }
            $response=substr($response, 0,-1);
            $response.=']}';
            echo $response;
        }
        else
        {
            $responce = array('status' =>1,'message'=>"Invalid json");
            echo json_encode($responce);
        }
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }
?>