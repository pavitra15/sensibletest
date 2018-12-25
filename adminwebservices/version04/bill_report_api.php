<?php
    include('../../connect.php');
    require_once('../../array_to_json.php');
    $deviceid=$_POST['deviceid'];
    $d_id=$_POST['d_id'];
    $token=$_POST['token'];
    $page_no=$_POST['page_no'];
    $settle_type=$_POST['settle_type'];
	$start=(($page_no-1)*40);
	$record=40;
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
    	if($settle_type==3)
    	{
	        $row_query=$db->prepare("select bill_no, T1.transaction_id, bill_amt, tax_amt, bill_date, tax_state, discount, parcel_amt, settled,book_dtl.premise_name,book_dtl.table_no, round_off from (select DISTINCT bill_no, transaction_id, bill_amt,tax_amt, bill_date, tax_state, discount, parcel_amt, settled, round_off from transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date')T1 LEFT JOIN book_dtl on T1.transaction_id=book_dtl.transaction_id");

	        $row_query->execute();
	    	$total_records=$row_query->rowCount();

	        $query=$db->prepare("select sum(bill_amt) as total, sum(tax_amt) as total_tax, sum(cash) as total_cash, sum(credit) as total_credit, sum(digital) as total_digital , sum(discount) as total_discount,  sum(round_off) as total_round_off ,  sum(parcel_amt) as total_parcel_amt from( select distinct bill_no, bill_amt, tax_amt, cash, credit, digital, discount, round_off, parcel_amt from transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date')T1");
	        $query->execute();
	        $summary_data=$query->fetchAll(PDO::FETCH_ASSOC);

	        $settle_query=$db->prepare("select sum(case when settled = '0' then 1 else 0 end) unsettled_cnt, sum(case when settled = '1' then 1 else 0 end) settled_cnt from (select distinct bill_no, settled  from transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date')T1");

        	$settle_query->execute();

        	$settle_summary=$settle_query->fetchAll(PDO::FETCH_ASSOC);


	        $product_query=$db->prepare("select bill_no, T1.transaction_id, bill_amt, tax_amt, bill_date, tax_state, discount, parcel_amt, settled,book_dtl.premise_name,book_dtl.table_no, round_off from (select DISTINCT bill_no, transaction_id, bill_amt,tax_amt, bill_date, tax_state, discount, parcel_amt, settled, round_off from transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date' order by transaction_id desc limit $start, $record)T1 LEFT JOIN book_dtl on T1.transaction_id=book_dtl.transaction_id order by transaction_id desc");

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
	        $total_page=ceil($total_records/40); 

	        $sync_time_query=$db->prepare("select sync_datetime from last_sync where d_id='$d_id' order by id desc LIMIT 1");
	        $sync_time_query->execute();
	        while($row=$sync_time_query->fetch())
	        {
	            $sync_time=$row['sync_datetime'];
	        }

	        $data_array = array('status' =>1, 'last_sync'=>$sync_time, 'current_page'=>$page_no, 'total_records'=>$total_records, 'total_page'=>$total_page, 'bill_summary'=>$summary_data, 'settle_summary'=>$settle_summary, 'bill_data'=>$arr);
	        echo array_to_json($data_array);
	        // echo json_encode($data_array,JSON_NUMERIC_CHECK);
	    }
	    else
	    {
	    	$row_query=$db->prepare("select bill_no, T1.transaction_id, bill_amt, tax_amt, bill_date, tax_state, discount, parcel_amt, settled,book_dtl.premise_name,book_dtl.table_no, round_off from (select DISTINCT bill_no, transaction_id, bill_amt,tax_amt, bill_date, tax_state, discount, parcel_amt, settled, round_off from transaction_mst where transaction_mst.device_id='$d_id' and settled='$settle_type' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date')T1 LEFT JOIN book_dtl on T1.transaction_id=book_dtl.transaction_id");


	        $row_query->execute();
	    	$total_records=$row_query->rowCount();

	        $query=$db->prepare("select sum(bill_amt) as total, sum(tax_amt) as total_tax, sum(cash) as total_cash, sum(credit) as total_credit, sum(digital) as total_digital , sum(discount) as total_discount,  sum(round_off) as total_round_off ,  sum(parcel_amt) as total_parcel_amt from( select distinct bill_no, bill_amt, tax_amt, cash, credit, digital, discount, round_off, parcel_amt from transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date')T1");
	        $query->execute();
	        $summary_data=$query->fetchAll(PDO::FETCH_ASSOC);

	        $settle_query=$db->prepare("select sum(case when settled = '0' then 1 else 0 end) unsettled_cnt, sum(case when settled = '1' then 1 else 0 end) settled_cnt from (select distinct bill_no, settled  from transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date')T1");

        	$settle_query->execute();

        	$settle_summary=$settle_query->fetchAll(PDO::FETCH_ASSOC);


	        $product_query=$db->prepare("select bill_no, T1.transaction_id, bill_amt, tax_amt, bill_date, tax_state, discount, parcel_amt, settled,book_dtl.premise_name,book_dtl.table_no, round_off from (select DISTINCT bill_no, transaction_id, bill_amt,tax_amt, bill_date, tax_state, discount, parcel_amt, settled, round_off from transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date'  and settled='$settle_type' order by transaction_id desc limit $start, $record)T1 LEFT JOIN book_dtl on T1.transaction_id=book_dtl.transaction_id order by transaction_id desc");

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
	        $total_page=ceil($total_records/40); 

	        $sync_time_query=$db->prepare("select sync_datetime from last_sync where d_id='$d_id' order by id desc LIMIT 1");
	        $sync_time_query->execute();
	        while($row=$sync_time_query->fetch())
	        {
	            $sync_time=$row['sync_datetime'];
	        }

	        $data_array = array('status' =>1, 'last_sync'=>$sync_time, 'current_page'=>$page_no, 'total_records'=>$total_records, 'total_page'=>$total_page, 'bill_summary'=>$summary_data, 'settle_summary'=>$settle_summary, 'bill_data'=>$arr);
	        echo array_to_json($data_array);
	    }
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }
?>