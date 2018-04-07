<?php
    include('../connect.php');
    require_once('../array_to_json.php');
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
    	$status='active';
    $response='{"data":[';
    $product_query=$db->prepare("select bill_date, kot_mst.bill_no, sum(case when state = 0 then 1 else 0 end) active_cnt, sum(case when state = 2 then 1 else 0 end) cancel_cnt from transaction_mst, kot_mst where transaction_mst.device_id='$d_id' and transaction_mst.bill_no=kot_mst.bill_no and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date' group by transaction_mst.transaction_id");
    $product_query->execute();
    if($data=$product_query->fetch())
    {
        do
        {
            $bill_no=$data['bill_no'];
            $response.='{"bill_no":"'.$data['bill_no'].'","bill_date":"'.$data['bill_date'].'","active_cnt":'.$data['active_cnt'].',"cancel_cnt":"'.$data['cancel_cnt'].'","detail":[';

            $count_query=$db->prepare("select kot_id from kot_mst where bill_no='$bill_no'");
            $count_query->execute();
            if($row_cnt = $count_query->fetch())
            {
                do
                {
                    $response.='{"kot_id":"'.$row_cnt['kot_id'].'","kot_data":[';
                    $kot_id=$row_cnt['kot_id'];
                    $kot_query=$db->prepare("select english_name, quantity, (case when state = 0 then 'active' else 'cancel' end)  as status  from kot_dtl, product where product.product_id=kot_dtl.product_id and kot_id='$kot_id'");
                    $kot_query->execute();
                    if($kot_data=$kot_query->fetch())
                    {
                        do
                        {
                             $response.='{"english_name":"'.$kot_data['english_name'].'","quantity":'.$kot_data['quantity'].',"state":"'.$kot_data['status'].'"},';
                        }
                        while($kot_data=$kot_query->fetch());
                    }
                    $response = rtrim($response, ',');
                    $response.=']},';

                }
                while ($row_cnt = $count_query->fetch());
                $response = rtrim($response, ',');
                $response.=']},';
            }
            else
            {
                $response.=']},';
            }       
        }
        while($data=$product_query->fetch());
    }
    $response = rtrim($response, ',');
    $response.=']}';
    echo $response;
        // $query=$db->prepare("select sum(case when state = 0 then 1 else 0 end) active_cnt, sum(case when state =2 then 1 else 0 end) cancel_cnt from  transaction_mst, kot_mst where transaction_mst.device_id='$d_id' and transaction_mst.bill_no=kot_mst.bill_no and bill_date between '$start_date' and '$end_date'");
        // $query->execute();
        // $summary_data=$query->fetchAll(PDO::FETCH_ASSOC);


        // $product_query=$db->prepare("select bill_date, kot_mst.bill_no, sum(case when state = 0 then 1 else 0 end) active_cnt, sum(case when state = 2 then 1 else 0 end) cancel_cnt from transaction_mst, kot_mst where transaction_mst.device_id='$d_id' and transaction_mst.bill_no=kot_mst.bill_no and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date' group by transaction_mst.transaction_id");
        // $product_query->execute();
        // $arr=array();
        // if($data=$product_query->fetch(PDO::FETCH_ASSOC))
        // {
        //     $data_arr=array();
        //     do
        //     {
        //         $bill_no=$data['bill_no'];
        //         $count_query=$db->prepare("select kot_id from kot_mst where bill_no='$bill_no'");
        //         $count_query->execute();
        //         if($row_cnt = $count_query->fetch(PDO::FETCH_ASSOC))
        //         {
        //             do
        //             {
        //                 $kot_id=$row_cnt['kot_id'];
        //                 $kot_query=$db->prepare("select english_name, quantity, (case when state = 0 then 'active' else 'cancel' end)  as status  from kot_dtl, product where product.product_id=kot_dtl.product_id and kot_id='$kot_id'");
        //                 $kot_query->execute();
        //                 if($kot_data=$kot_query->fetch())
        //                 {
        //                     do
        //                     {
        //                         array_push($row_cnt, $kot_data);
        //                     }   
        //                     while($kot_data=$kot_query->fetch(PDO::FETCH_ASSOC));
        //                 }
        //                 array_push($data_arr, $row_cnt);
        //             }
        //             while ($row_cnt = $count_query->fetch(PDO::FETCH_ASSOC));
        //             array_push($data,$data_arr);
        //             array_push($arr, $data);
        //         }
        //     }
        //     while($data=$product_query->fetch(PDO::FETCH_ASSOC)); 
        // }
        // $data_array = array('status' =>1 ,'bill_summary'=>$summary_data,'bill_data'=>$arr);
        // echo array_to_json($data_array);
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }
?>