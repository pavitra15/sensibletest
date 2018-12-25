<?php
    include('../connect.php');
    $deviceid=$_POST['deviceid'];
    $d_id=$_POST['d_id'];
    $token=$_POST['token'];
    $first_date =  new DateTime($_POST['start_date']);
    $second_date = new DateTime($_POST['end_date']);

    $page_no=$_POST['page_no'];
    $start=(($page_no-1)*40);
    $record=40;

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
        $row_query=$db->prepare("select english_name,regional_name, sum(transaction_dtl.quantity) AS total, sum(transaction_dtl.total_amt) as total_amt from product, transaction_dtl,transaction_mst where transaction_dtl.transaction_id=transaction_mst.transaction_id and product.product_id=transaction_dtl.item_id and transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date'  and transaction_dtl.status='active' group by (product.product_id)");
        $row_query->execute();

        $query=$db->prepare("select sum(bill_amt) as total, sum(tax_amt) as total_tax, sum(cash) as total_cash, sum(credit) as total_credit, sum(digital) as total_digital , sum(discount) as total_discount,  sum(round_off) as total_round_off( select distinct bill_no, bill_amt, tax_amt,cash, credit, digital, discount, round_off from  transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date')T1");
        $query->execute();
        $summary_data=$query->fetchAll(PDO::FETCH_ASSOC);

        $query=$db->prepare("select english_name,regional_name, sum(quantity) AS total, sum(total_amt) as total_amt from ( select distinct bill_no, product_id, english_name, regional_name, transaction_dtl.quantity, transaction_dtl.total_amt from product, transaction_dtl,transaction_mst where transaction_dtl.transaction_id=transaction_mst.transaction_id and product.product_id=transaction_dtl.item_id and transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$start_date' and transaction_dtl.status='active')T1 group by (product_id)");

        $query->execute();
        $data=$query->fetchAll(PDO::FETCH_ASSOC);

        $total_records=$row_query->rowCount();
        $total_page=ceil($total_records/40);
        $responce = array('status' =>1 ,'current_page'=>$page_no, 'total_records'=>$total_records, 'total_page'=>$total_page, 'summary'=>$summary_data,'product_report' =>$data);   
        echo json_encode($responce, JSON_NUMERIC_CHECK);
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }
?>