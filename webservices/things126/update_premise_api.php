<?php
	include('../../connect.php');

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
            $push_query=$db->prepare("select * from premise_dtl where deviceid='$d_id' and status='active'");
            $push_query->execute();
            if($data=$push_query->fetch())
            {
                do
                {
                  $exists_arr[]=$data['premise_name'];
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
                $premise_id=$records[$i]->premise_id;
                $premise_name=ucwords($records[$i]->premise_name);
                $no_of_table=$records[$i]->no_of_table;
                $table_range=$records[$i]->table_range;
                $premise_type=$records[$i]->premise_type;
                if(in_array($premise_name, $exists_arr))
                {
                    $count_query=$db->prepare("select premise_name from premise_dtl where premise_id='$premise_id'");
                    $count_query->execute();
                    if($count_data=$count_query->fetch())
                    {
                        do
                        {
                            $count=$count_data['premise_name'];
                        }
                        while ($count_data=$count_query->fetch());
                    }
                    if($count==$premise_name)
                    {
                        $query=$db->prepare("update premise_dtl set premise_name='$premise_name', no_of_table='$no_of_table', table_range='$table_range', premise_type='$premise_type' where premise_id='$premise_id'");
                        $query->execute();
                        $select_query=$db->prepare("select premise_id, premise_name,no_of_table,table_range, premise_type from premise_dtl where premise_id='$premise_id' and status='$status'");
                        $select_query->execute();
                        if($data=$select_query->fetch())
                        {
                            do
                            {
                                $response.='{"premise_id":'.$data['premise_id'].',';
                                $response.='"premise_name":"'.$data['premise_name'].'",';
                                $response.='"table_range":'.$data['table_range'].',';
                                $response.='"premise_type":"'.$data['premise_type'].'",';
                                $response.='"no_of_table":'.$data['no_of_table'].'},';
                            }
                            while($data=$select_query->fetch());
                        }
                    }
                }
                else
                {
                    $query=$db->prepare("update premise_dtl set premise_name='$premise_name', no_of_table='$no_of_table', table_range='$table_range', premise_type='$premise_type' where premise_id='$premise_id'");
                    $query->execute();
                    $select_query=$db->prepare("select premise_id, premise_name, no_of_table,table_range, premise_type from premise_dtl where premise_id='$premise_id' and status='$status'");
                    $select_query->execute();
                    if($data=$select_query->fetch())
                    {
                        do
                        {
                            $response.='{"premise_id":'.$data['premise_id'].',';
                            $response.='"premise_name":"'.$data['premise_name'].'",';
                            $response.='"table_range":'.$data['table_range'].',';
                            $response.='"premise_type":"'.$data['premise_type'].'",';
                            $response.='"no_of_table":'.$data['no_of_table'].'},';
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