<?php
    include('../connect.php');
    $deviceid=$_POST['deviceid'];
    $d_id=$_POST['d_id'];
    $token=$_POST['token'];
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
         $row_query=$db->prepare("select english_name, regional_name, category_name, current_stock from product,category_dtl,stock_mst,price_mst where stock_mst.product_id=product.product_id  and product.deviceid='$d_id' and product.product_id= price_mst.product_id and product.product_id=stock_mst.product_id and product.category_id= category_dtl.category_id");
         $row_query->execute();
        $total_records=$row_query->rowCount();

        $query=$db->prepare("select english_name, regional_name, category_name, current_stock from product,category_dtl,stock_mst,price_mst where stock_mst.product_id=product.product_id  and product.deviceid='$d_id' and product.product_id= price_mst.product_id and product.product_id=stock_mst.product_id and product.category_id= category_dtl.category_id limit $start, $record");
        $query->execute();
        $data=$query->fetchAll(PDO::FETCH_ASSOC);
        
        $total_page=ceil($total_records/40);
        $responce = array('status' =>1 ,'current_page'=>$page_no, 'total_records'=>$total_records, 'total_page'=>$total_page,'stock_report' =>$data);   
        echo json_encode($responce, JSON_NUMERIC_CHECK);
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }
?>