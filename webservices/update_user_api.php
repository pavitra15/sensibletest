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
            $exists_admin= array();
            $admin_arr=array();
            $user_arr=array();
            $exists_user=array();
            $push_query=$db->prepare("select * from user_dtl, user_type_mst where deviceid='$d_id' and user_dtl.user_type= user_type_mst.id and user_dtl.status='active'");
            $push_query->execute();
            if($data=$push_query->fetch())
            {
                do
                {
                   
                    if($data['name']=='Administrator')
                    {
                        $admin_arr[]= $data['user_type'];
                        $exists_admin[]=$data['user_name'];
                        $type=$data['user_type'];
                    }
                    else
                    {
                        $user_arr[]= $data['user_type'];
                        $exists_user[]=$data['user_name'];
                    }
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
                $user_id=$records[$i]->user_id;
                $user_name=ucwords($records[$i]->user_name);
                $user_type=$records[$i]->user_type;
                $user_mobile=$records[$i]->user_mobile;
                    
                if($user_type=="1")
                {  
                    if((in_array($user_name, $exists_admin)) || (in_array($user_type, $admin_arr)))
                    {
                        if(sizeof($admin_arr)==1)
                        {
                            $count_query=$db->prepare("select user_type from user_dtl where user_id='$user_id' and user_type='1'");
                            $count_query->execute();
                            $count=$count_query->rowCount();
                            if($count==1)
                            {
                                $query=$db->prepare("update user_dtl set user_name='$user_name', user_type='$user_type', user_mobile='$user_mobile', updated_by_date='$current_date', updated_by_id='$id' where user_id='$user_id'");
                                $query->execute();
                                $select_query=$db->prepare("select user_id, user_name, user_type, user_mobile from user_dtl where user_id='$user_id'");
                                $select_query->execute(); 
                                if($data=$select_query->fetch())
                                {
                                    do
                                    {
                                        $response.='{"user_id":'.$data['user_id'].',';
                                        $response.='"user_name":"'.$data['user_name'].'",';
                                        $response.='"user_mobile":"'.$data['user_mobile'].'",';
                                        $response.='"user_type":'.$data['user_type'].'},';
                                    }
                                    while($data=$select_query->fetch());
                                }       
                            }
                        }
                    }
                    else
                    {
                        $query=$db->prepare("update user_dtl set user_name='$user_name', user_type='$user_type', user_mobile='$user_mobile', updated_by_date='$current_date', updated_by_id='$id' where user_id='$user_id'");
                        $query->execute();
                        $select_query=$db->prepare("select user_id, user_name, user_type, user_mobile from user_dtl where user_id='$user_id'");
                        $select_query->execute(); 
                        if($data=$select_query->fetch())
                        {
                            do
                            {
                                $response.='{"user_id":'.$data['user_id'].',';
                                $response.='"user_name":"'.$data['user_name'].'",';
                                $response.='"user_mobile":"'.$data['user_mobile'].'",';
                                $response.='"user_type":'.$data['user_type'].'},';
                            }
                            while($data=$select_query->fetch());
                        }
                    }
                }
                else
                {
                    if((in_array($user_name, $exists_user)))
                    {
                        $count_query=$db->prepare("select user_type from user_dtl where user_id='$user_id' and user_type='2'");
                        $count_query->execute();
                        $count=$count_query->rowCount();
                        if($count==1)
                        {
                            $query=$db->prepare("update user_dtl set user_name='$user_name', user_type='$user_type', user_mobile='$user_mobile', updated_by_date='$current_date', updated_by_id='$id' where user_id='$user_id'");
                            $query->execute();
                            $select_query=$db->prepare("select user_id, user_name, user_type, user_mobile from user_dtl where user_id='$user_id'");
                            $select_query->execute(); 
                            if($data=$select_query->fetch())
                            {
                                do
                                {
                                    $response.='{"user_id":'.$data['user_id'].',';
                                    $response.='"user_name":"'.$data['user_name'].'",';
                                    $response.='"user_mobile":"'.$data['user_mobile'].'",';
                                    $response.='"user_type":'.$data['user_type'].'},';
                                }
                                while($data=$select_query->fetch());
                            }
                        }
                    }
                    else
                    {
                        $query=$db->prepare("update user_dtl set user_name='$user_name', user_type='$user_type', user_mobile='$user_mobile', updated_by_date='$current_date', updated_by_id='$id' where user_id='$user_id'");
                        $query->execute();
                        $select_query=$db->prepare("select user_id, user_name, user_type, user_mobile from user_dtl where user_id='$user_id'");
                        $select_query->execute(); 
                        if($data=$select_query->fetch())
                        {
                            do
                            {
                                $response.='{"user_id":'.$data['user_id'].',';
                                $response.='"user_name":"'.$data['user_name'].'",';
                                $response.='"user_mobile":"'.$data['user_mobile'].'",';
                                $response.='"user_type":'.$data['user_type'].'},';
                            }
                            while($data=$select_query->fetch());
                        }
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