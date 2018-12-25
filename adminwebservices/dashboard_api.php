<?php
    include('../connect.php');
    require_once('../array_to_json.php');
    $deviceid=$_POST['deviceid'];
    $d_id=$_POST['d_id'];
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
        $total_query=$db->prepare("select sum(if(tax_state=0,parcel_amt+tax_amt+bill_amt-discount+round_off,parcel_amt+bill_amt-discount+round_off)) as total, count(bill_no) as count from ( select distinct bill_no, tax_state,tax_amt, parcel_amt, bill_amt, discount, round_off from transaction_mst where device_id='$d_id' and transaction_mst.status='$status')T1");
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

        $top_query=$db->prepare("select english_name, sum(quantity) as count from( select distinct bill_no, item_id, english_name, transaction_dtl.quantity from transaction_dtl,product, transaction_mst where transaction_dtl.item_id=product.product_id and device_id='$d_id' and transaction_dtl.transaction_id=transaction_mst.transaction_id  and transaction_mst.status='$status') T1 group by item_id Order by sum(quantity) desc limit 1");
        $top_query->execute();
        while($data_top=$top_query->fetch())
        {
            $product_name=$data_top['english_name'];
        }

        $user_query=$db->prepare("select user_name, count(transaction_mst.user_id) as count from user_dtl, transaction_mst where transaction_mst.user_id=user_dtl.user_id and device_id='$d_id' and transaction_mst.status='$status' group by transaction_mst.user_id Order by count(transaction_mst.user_id) desc limit 1");
        $user_query->execute();
        while($data=$user_query->fetch())
        {
            $user_name=$data['user_name'];
        }

        $total_report = array('total_amount' => $total,'checkout' => $checkout,'product_name'=>$product_name,'user_name'=>$user_name);

        $end_date=$date->format('Y-m-d');
        $end_month=date("m", strtotime($end_date));
        $sk= array();
        for($i=1;$i<=$end_month;$i++)
        {
            $start_date=$date->format('Y-'.$i.'-01');
            $end_date=$date->format('Y-'.$i.'-31');
            $first_query=$db->prepare("select sum(if(tax_state=0,parcel_amt+tax_amt+bill_amt-discount+round_off,parcel_amt+bill_amt-discount+round_off)) as total from(select distinct bill_no, tax_amt, tax_state, parcel_amt, bill_amt, discount, round_off from transaction_mst where device_id='$d_id' and transaction_mst.status='$status' and  bill_date between '$start_date' and '$end_date')T1");
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
        $daily_checkout_query=$db->prepare("select sum(if(tax_state=0,parcel_amt+tax_amt+bill_amt-discount+round_off,parcel_amt+bill_amt-discount+round_off)) as total, count(bill_no) as count from ( select distinct bill_no, tax_state, tax_amt, parcel_amt, bill_amt, discount, round_off from transaction_mst where device_id='$d_id' and transaction_mst.status='$status' and  bill_date between '$start_date' and '$end_date') T1");
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

        $daily_top_query=$db->prepare("select english_name, sum(quantity) as count from( select item_id, english_name, quantity  from transaction_dtl,product, transaction_mst where transaction_dtl.item_id=product.product_id and device_id='$d_id' and transaction_mst.status='$status' and transaction_dtl.transaction_id=transaction_mst.transaction_id and  bill_date between '$start_date' and '$end_date')T1 group by item_id Order by sum(quantity) desc limit 1");
        $daily_top_query->execute();
        while($daily_data_top=$daily_top_query->fetch())
        {
            $daily_product_name=$daily_data_top['english_name'];
        }

        $daily_user_query=$db->prepare("select user_name, count(user_id) as count from (select distinct bill_no, user_name, transaction_mst.user_id from user_dtl, transaction_mst where transaction_mst.user_id=user_dtl.user_id and transaction_mst.status='$status' and device_id='$d_id' and  bill_date between '$start_date' and '$end_date' group by transaction_mst.user_id) T1 Order by count(user_id) desc limit 1");
        $daily_user_query->execute();
        while($daily_data=$daily_user_query->fetch())
        {
            $daily_user_name=$daily_data['user_name'];
        }

        $daily_report = array('daily_total' => $daily_total,'daily_checkout' => $daily_checkout,'daily_product_name'=>$daily_product_name,'daily_user_name'=>$daily_user_name);


        $weekly_report= array();
        for($i=6;$i>=0;$i--)
        {           
            $clone= clone $date;
            $clone->modify( '-'.$i.' day' );
            $print_date=$clone->format('d');
            $start_date=$clone->format( 'Y-m-d 00:00:00');
            $end_date=$clone->format( 'Y-m-d 23:59:59');
            $first_query=$db->prepare("select sum(if(tax_state=0,parcel_amt+tax_amt+bill_amt-discount+round_off,parcel_amt+bill_amt-discount+round_off)) as total from( select distinct bill_no, tax_state, parcel_amt, tax_amt, bill_amt, discount, round_off from transaction_mst where device_id='$d_id' and transaction_mst.status='$status' and  bill_date between '$start_date' and '$end_date')T1");

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

        $category_query=$db->prepare("select category_name, sum(total_amt) as total from( select distinct bill_no, category_dtl.category_id, category_name, transaction_dtl.total_amt from category_dtl,product, transaction_dtl, transaction_mst where category_dtl.deviceid='$d_id' and product.category_id= category_dtl.category_id and transaction_dtl.item_id=product.product_id and transaction_mst.device_id='$d_id' and transaction_mst.transaction_id=transaction_dtl.transaction_id and transaction_mst.status='active') T1 group by(category_id)");
            $category_query->execute();
            
        // $category_query=$db->prepare("select category_name, sum(transaction_dtl.total_amt) as total from category_dtl,product, transaction_dtl where category_dtl.deviceid='$d_id' and product.category_id= category_dtl.category_id and transaction_dtl.item_id=product.product_id group by(category_dtl.category_id)");
        // $category_query->execute();
        $category_report= array();
        while ($category_data=$category_query->fetch()) 
        {   
            $category_total=$category_data['total'];
            $category_name=$category_data['category_name'];
            if($category_total=="")
            {
                $category_total=0;
            }
            $category = array('category_name' => $category_name,'category_total' => $category_total);
            array_push($category_report, $category);
        }

        $sync_time_query=$db->prepare("select sync_datetime from last_sync where d_id='$d_id' order by id desc LIMIT 1");
        $sync_time_query->execute();
        while($row=$sync_time_query->fetch())
        {
            $sync_time=$row['sync_datetime'];
        }


        $data_array = array('status' =>1 , 'last_sync'=>$sync_time,'total_report'=>$total_report,'yearly_report'=>$sk,'daily_report'=>$daily_report,'weekly_report'=>$weekly_report,'category_report'=>$category_report);
        // echo json_encode($data_array, JSON_NUMERIC_CHECK);
        echo array_to_json($data_array);
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }
?>