<?php
    include('../connect.php');
    $deviceid=$_POST['deviceid'];
    $login_id=$_POST['login_id'];
    $token=$_POST['token'];
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
        $checkout=0;
        $product_name="";
        $device_name="";
        $total_query=$db->prepare("select sum(bill_amt) as total, count(bill_no) as count from transaction_mst, device where transaction_mst.device_id=device.d_id and transaction_mst.status='$status' and device.id='login_id'");
        $total_query->execute();
        while($data=$total_query->fetch())
       	{
            $total=$data['total'];
        	$count=$data['count'];
        }
        if($count!=0)
        {
        	$checkout=round(($total/$count),2);
        }

        $top_query=$db->prepare("select english_name, sum(transaction_dtl.quantity) as count from transaction_dtl,product, transaction_mst, device where transaction_dtl.item_id=product.product_id and transaction_mst.device_id=device.d_id and transaction_dtl.transaction_id=transaction_mst.transaction_id  and transaction_mst.status='$status' and device.id='login_id' group by item_id Order by sum(transaction_dtl.quantity) desc limit 1");
        $top_query->execute();
       	while($data_top=$top_query->fetch())
        {
        	$product_name=$data_top['english_name'];
        }

        $user_query=$db->prepare("select device_name, count(transaction_mst.device_id) as count from device, transaction_mst where transaction_mst.device_id=device.d_id and device.id='login_id' and transaction_mst.status='$status' group by transaction_mst.device_id Order by count(transaction_mst.device_id) desc limit 1");
        $user_query->execute();
        while($data=$user_query->fetch())
        {
        	$device_name=$data['device_name'];
        }

        $total_report = array('total_amount' => $total,'checkout' => $checkout,'product_name'=>$product_name,'device_name'=>$device_name);

        

       	$device_query=$db->prepare("select d_id, device_name, sum(transaction_mst.bill_amt) as total from device, transaction_mst where transaction_mst.device_id=device.d_id and  device.id='login_id' group by(device.d_id)");
        $device_query->execute();
        $device_report= array();
        while ($device_data=$device_query->fetch()) 
        {   
        	$device_total=$device_data['total'];
            $device_name=$device_data['device_name'];
            if($device_total=="")
            {
            	$device_total=0;
            }
            $device = array('device_name' => $device_name,'device_total' => $device_total);
            array_push($device_report, $device);
       	}

        $data_array = array('status' =>1 ,'total_report'=>$total_report,'device_report'=>$device_report);
        echo json_encode($data_array, JSON_NUMERIC_CHECK);
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }
?>