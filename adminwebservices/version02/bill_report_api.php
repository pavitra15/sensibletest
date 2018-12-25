<?php
    include('../../connect.php');
    require_once('../../array_to_json.php');
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
        $query=$db->prepare("select sum(bill_amt) as total, sum(tax_amt) as total_tax, sum(cash) as total_cash, sum(credit) as total_credit, sum(digital) as total_digital from( select distinct bill_no, bill_amt, tax_amt, cash, credit, digital from  transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date')T1");
        $query->execute();
        $summary_data=$query->fetchAll(PDO::FETCH_ASSOC);
        $product_query=$db->prepare("select transaction_id, distinct bill_no, bill_amt,tax_amt, bill_date, tax_state, discount, parcel_amt, settled from  transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date'");
        $product_query->execute();
        $arr=array();
        if($data=$product_query->fetch(PDO::FETCH_ASSOC))
        {
            do
            {
                $transaction_id=$data['transaction_id'];
                $count_query=$db->prepare("select english_name, quantity, price, total_amt,transaction_dtl.status from transaction_dtl, product where transaction_dtl.transaction_id='$transaction_id' and product.product_id=transaction_dtl.item_id");
                $count_query->execute();
                $count_data=$count_query->fetchAll(PDO::FETCH_ASSOC);
                array_push($data,$count_data);
                array_push($arr, $data);
            }
            while($data=$product_query->fetch(PDO::FETCH_ASSOC));
        } 
        $data_array = array('status' =>1 ,'bill_summary'=>$summary_data,'bill_data'=>$arr);
        echo array_to_json($data_array);
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }
?>