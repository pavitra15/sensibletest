<?php
	include('../connect.php');

    $d_id=$_POST['d_id'];
	$deviceid=$_POST['deviceid'];
    $token=$_POST['token'];
    $json=$_POST['json'];
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
    $update_query=$db->prepare("update last_connect set city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login' where deviceid='$deviceid'");
    $update_query->execute();
    $status="active";
    $query=$db->prepare("select * from token_verify where token='$token' and deviceid='$deviceid'");
    $query->execute();
    $token_count=$query->rowCount();
    if($token_count==1)
    {
        $type_query=$db->prepare("select id from device where deviceid='$deviceid' and status='$status'");
        $type_query->execute();
        if($data=$type_query->fetch())
        {
            do
            {
                $id=$data['id'];
            }
            while($data=$type_query->fetch());
        }
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
            $exists_arr= array();
            $push_query=$db->prepare("select category_name from category_dtl where deviceid='$d_id' and status='active'");
            $push_query->execute();
            if($data=$push_query->fetch())
            {
                do
                {
                    $exists_arr[]=$data['category_name'];
                }
                while($data=$push_query->fetch());
            }

            $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
            $created_by_date=$date->format('Y-m-d');
            $object=json_decode($json);
            $records=$object->records;
            $length=sizeof($records);
            $response='{"status":2,"record":[';
            for($i=0; $i<$length; $i++)
            {
                $category_name=ucwords($records[$i]->category_name);
                if(in_array($category_name, $exists_arr))
                {
                }
                else
                {
                    $exists_arr[]=$category_name;
                    $query=$db->prepare("insert into category_dtl(category_name,category_type, status, created_by_date, created_by_id,deviceid) values('$category_name','$category_type' ,'$status' , '$current_date', '$id', '$d_id')");
                    $query->execute();
                    $select_query=$db->prepare("select category_id, category_name from category_dtl where category_name='$category_name' and status='$status' and deviceid='$d_id'");
                    $select_query->execute();
                    if($data=$select_query->fetch())
                    {
   	                    do
    					{
                            $response.='{"category_id":'.$data['category_id'].',';
                            $response.='"category_name":"'.$data['category_name'].'"},';
           	            }
       	                while($data=$select_query->fetch());
                    }
                }
            }
            $response = rtrim($response, ',');
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