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
    	$object=json_decode($json);
    	$count=sizeof($object);
    	$arr=array();
    	for($i=0;$i<$count;$i++)
    	{
    		$product_id=0;
    		$stock_added=0;
    		$id=$object[$i]->id;
	    	$log_type=$object[$i]->type;
	    	$log_date=$object[$i]->date;
	    	$extra=$object[$i]->extra;
	    	$print_lines=0;
	    	if($log_type==6)
	    	{
	    		$stock_from='device';
	    		$product_id=$extra->product_id;
	    		$opening_stock=$extra->opening_stock;
	    		$current_stock=$extra->current_stock;
	    		$added_stock=$extra->added_stock;
	    		$user_id=$extra->user_id;
	    		$reason=$extra->reason;
	    		$type=$extra->type;
	    		
	    		$check_query=$db->prepare("select * from stock_dtl where d_id='$d_id' and log_date='$log_date' and stock_from='$stock_from' and product_id='$product_id' and stock_added='$added_stock' and current_stock='$current_stock' and opening_stock='$opening_stock'");
	    		$check_query->execute();
	    		$count_tot=$check_query->rowCount();
	    		if($count_tot>0)
	    		{
	    			$responce = array('status' =>1,'id'=>$id);
		            array_push($arr, $responce);
	    		}
	    		else
	    		{
		    		$query=$db->prepare("insert into stock_dtl(d_id, log_date, stock_from, product_id,stock_added, current_stock,opening_stock,user_id,reason, type) values('$d_id', '$log_date', '$stock_from', '$product_id','$added_stock','$current_stock','$opening_stock','$user_id','$reason','$type')");
			        $query->execute();
			        if($query->rowCount())
			        {
			            $responce = array('status' =>1,'id'=>$id);
			            array_push($arr, $responce);
			        }
			        else
			        {
			            $responce = array('status' =>2,'id'=>$id);
			            array_push($arr, $responce);
			        }
			    }
	    	}
	    	else
	    	{
		    	if(isset($extra->lines))
		    	{
		    		$print_lines=$extra->lines;
		    	}
		        $query=$db->prepare("insert into device_log(d_id, log_date, log_type, print_lines) values('$d_id', '$log_date', '$log_type', '$print_lines')");
		        $query->execute();
		        if($query->rowCount())
		        {
		            $responce = array('status' =>1,'id'=>$id);
		            array_push($arr, $responce);
		        }
		        else
		        {
		            $responce = array('status' =>2,'id'=>$id);
		            array_push($arr, $responce);
		        }
		    }	
    	}
    	echo json_encode($arr);
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }
?>