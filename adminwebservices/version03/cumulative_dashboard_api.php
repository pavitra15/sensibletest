<?php
    include('../../connect.php');
    require_once('../../array_to_json.php');
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
        $total_query=$db->prepare("select sum(if(tax_state=0,parcel_amt+tax_amt+bill_amt-discount,parcel_amt+bill_amt-discount)) as total, count(bill_no) as count from device, transaction_mst where transaction_mst.status='$status' and  transaction_mst.device_id=device.d_id and  device.id='$login_id' and device.status='$status' ");
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

        $top_query=$db->prepare("select english_name, sum(transaction_dtl.quantity) as count from from transaction_dtl,product, transaction_mst, device where transaction_dtl.item_id=product.product_id and transaction_mst.device_id=device.d_id and transaction_dtl.transaction_id=transaction_mst.transaction_id  and transaction_mst.status='$status' and device.id='$login_id' and device.status='$status' group by item_id Order by sum(transaction_dtl.quantity) desc limit 1");
        $top_query->execute();
       	while($data_top=$top_query->fetch())
        {
        	$product_name=$data_top['english_name'];
        }

        $user_query=$db->prepare("select device_name, count(transaction_mst.device_id) as count from device, transaction_mst where transaction_mst.device_id=device.d_id and device.id='$login_id' and device.status='$status' and transaction_mst.status='$status' group by transaction_mst.device_id Order by count(transaction_mst.device_id) desc limit 1");
        $user_query->execute();
        while($data=$user_query->fetch())
        {
            $device_name=$data['device_name'];
        }

        $total_report = array('total_amount' => $total,'checkout' => $checkout,'product_name'=>$product_name,'device_name'=>$device_name);

        $end_date=$date->format('Y-m-d');
        $end_month=date("m", strtotime($end_date));
       	$sk= array();
        for($i=1;$i<=$end_month;$i++)
       	{
        	$start_date=$date->format('Y-'.$i.'-01');
        	$end_date=$date->format('Y-'.$i.'-31');
            $first_query=$db->prepare("select sum(if(tax_state=0,parcel_amt+tax_amt+bill_amt-discount+round_off,parcel_amt+bill_amt-discount+round_off)) as total from transaction_mst, device where transaction_mst.status='$status' and  transaction_mst.device_id=device.d_id  and  device.id='$login_id' and device.status='$status' and  bill_date between '$start_date' and '$end_date'");
            $first_query->execute();
            $month=date ("M", mktime(0,0,0,$i,1,0));
            while ($first_data=$first_query->fetch()) 
            {   
            	$first=$first_data['total'];
                if($first=="")
                {
                	$first=0;
                }
            }
            $month_data = array('month' => $month, 'amount'=>$first);
            array_push($sk, $month_data);
		}

       
        $clone= clone $date;
        $daily_product_name="";
        $daily_user_name="";
        $daily_checkout=0;
        $start_date = $clone->format( 'Y-m-d 00:00:00' );
        $end_date = $clone->format( 'Y-m-d 23:59:59' );
        $daily_checkout_query=$db->prepare("select sum(if(tax_state=0,parcel_amt+tax_amt+bill_amt-discount+round_off,parcel_amt+bill_amt-discount+round_off)) as total, count(bill_no) as count from transaction_mst, device where transaction_mst.status='$status' and  transaction_mst.device_id=device.d_id and  device.id='$login_id' and device.status='$status' and  bill_date between '$start_date' and '$end_date'");
        $daily_checkout_query->execute();
        while($daily_data=$daily_checkout_query->fetch())
        {
        	$daily_total=$daily_data['total'];
            $daily_count=$daily_data['count'];
        }
        if($daily_count!=0)
        {
        	$daily_checkout=$daily_total/$daily_count;
        }

        $daily_top_query=$db->prepare("select english_name, sum(transaction_dtl.quantity) as count from transaction_dtl,product, transaction_mst,device where transaction_dtl.item_id=product.product_id and transaction_mst.device_id=device.d_id and  device.id='$login_id' and device.status='$status' and transaction_mst.status='$status' and transaction_dtl.transaction_id=transaction_mst.transaction_id and  bill_date between '$start_date' and '$end_date' group by item_id Order by sum(transaction_dtl.quantity) desc limit 1");
        $daily_top_query->execute();
        while($daily_data_top=$daily_top_query->fetch())
        {
        	$daily_product_name=$daily_data_top['english_name'];
        }


         $daily_user_query=$db->prepare("select device_name, count(transaction_mst.device_id) as count from device, transaction_mst where transaction_mst.device_id=device.d_id and device.id='$login_id' and device.status='$status'and transaction_mst.status='$status' group by transaction_mst.device_id and  bill_date between '$start_date' and '$end_date' Order by count(transaction_mst.device_id) desc limit 1");
        $daily_user_query->execute();
        while($data=$daily_user_query->fetch())
        {
            $device_name=$data['device_name'];
        }

        
        $daily_report = array('daily_total' => $daily_total,'daily_checkout' => $daily_checkout,'daily_product_name'=>$daily_product_name,'daily_device_name'=>$device_name);


        $weekly_report= array();
        for($i=6;$i>=0;$i--)
        {           
        	$clone= clone $date;
            $clone->modify( '-'.$i.' day' );
            $print_date=$clone->format('d');
            $start_date=$clone->format( 'Y-m-d 00:00:00');
            $end_date=$clone->format( 'Y-m-d 23:59:59');
            $first_query=$db->prepare("select sum(if(tax_state=0,parcel_amt+tax_amt+bill_amt-discount+round_off,parcel_amt+bill_amt-discount+round_off)) as total, count(bill_no) as count from transaction_mst, device where transaction_mst.status='$status' and  transaction_mst.device_id=device.d_id and  device.id='$login_id' and device.status='$status' and  bill_date between '$start_date' and '$end_date'");
                                $first_query->execute();
            while ($first_data=$first_query->fetch()) 
            {   
	            $first=$first_data['total'];
	            if($first=="")
	            {
	            	$first=0;
	            }
            }
            $week = array('date' =>$print_date ,'amount' =>$first );
            array_push($weekly_report, $week);
        }

        $data_array = array('status' =>1 ,'total_report'=>$total_report,'yearly_report'=>$sk,'daily_report'=>$daily_report,'weekly_report'=>$weekly_report);
        // echo json_encode($data_array, JSON_NUMERIC_CHECK);
        echo array_to_json($data_array);
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }
?>