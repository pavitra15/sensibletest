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
    $page_no=$_POST['page_no'];
    $start=(($page_no-1)*40);
    $record=40;
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

        $row_query=$db->prepare("select kot_mst.bill_no from transaction_mst, kot_mst where transaction_mst.device_id='$d_id' and transaction_mst.bill_no=kot_mst.bill_no and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date' group by transaction_mst.transaction_id");
        $row_query->execute();
        $total_records=$row_query->rowCount();
        $total_page=ceil($total_records/40);

        $query=$db->prepare("select kot_mst.bill_no from transaction_mst, kot_mst where transaction_mst.device_id='$d_id' and transaction_mst.bill_no=kot_mst.bill_no and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date' group by transaction_mst.transaction_id order by transaction_mst.transaction_id desc limit $start, $record");

        $query->execute();
        $arr=array();
        if($bill_data=$query->fetch())
        {
            do{
                $bill_no=$bill_data['bill_no'];
                $product_query=$db->prepare("select kot_id, bill_no, state, created_date_time, kitchen_name from kot_mst, kitchen_dtl where bill_no='$bill_no' and kitchen_dtl.kitchen_id=kot_mst.kitchen_id");
                $product_query->execute();
                if($data=$product_query->fetch(PDO::FETCH_ASSOC))
                {
                    do
                    {
                        $kot_id=$data['kot_id'];
                        $count_query=$db->prepare("select english_name, quantity, state from kot_dtl, product where product.product_id=kot_dtl.product_id and kot_id='$kot_id'");
                        $count_query->execute();
                        $count_data=$count_query->fetchAll(PDO::FETCH_ASSOC);
                        array_push($data,$count_data);
                        array_push($arr, $data);
                    }
                    while($data=$product_query->fetch(PDO::FETCH_ASSOC));
                } 
            }
            while($bill_data=$query->fetch());
        }
        $data_array = array('status' =>1 ,'kot_data'=>$arr,'current_page'=>$page_no, 'total_records'=>$total_records, 'total_page'=>$total_page);
        echo array_to_json($data_array);
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }
?>