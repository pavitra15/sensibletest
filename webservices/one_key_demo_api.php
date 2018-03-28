<?php
	include('../connect.php');
	$device_type=$_POST['device_type'];
	$d_id=$_POST['d_id'];
	$id=$_POST['login_id'];
    $deviceid=$_POST['deviceid'];
    $token=$_POST['token'];
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
    	$check_demo=$db->prepare("select * from device where config_type='demo' and status='active' and d_id='$d_id'");
        $check_demo->execute();
        $count=$check_demo->rowCount();
        if($count==1)
        {
        	$query=$db->prepare("update device set device_type='$device_type', updated_by_id='$id', updated_date='$current_date' where d_id='$d_id'");
			if($query->execute())
			{


				if($device_type=="Table")
	            {
	            	$premise_query=$db->prepare("update premise_dtl set status='active' where deviceid='$d_id'");
	            	$premise_query->execute();

	            	$customer_query=$db->prepare("update customer_dtl set status='delete' where deviceid='$d_id'");
	            	$customer_query->execute();

	            	$category_query=$db->prepare("update category_dtl set status='delete' where deviceid='$d_id'");
	            	$category_query->execute();

	            	$update_category_query=$db->prepare("update category_dtl set status='active' where deviceid='$d_id' and category_type='Table'");
	            	$update_category_query->execute();

	            	$product_query=$db->prepare("update product set status='delete' where deviceid='$d_id'");
	            	$product_query->execute();

	            	$update_product_query=$db->prepare("update product set status='active' where deviceid='$d_id' and product_type='Table'");
	            	$update_product_query->execute();

	            }
	            elseif($device_type=="Non-Table")
	            {
	            	$customer_query=$db->prepare("update customer_dtl set status='active' where deviceid='$d_id'");
	            	$customer_query->execute();

	            	$premise_query=$db->prepare("update premise_dtl set status='delete' where deviceid='$d_id'");
	            	$premise_query->execute();

	            	$category_query=$db->prepare("update category_dtl set status='delete' where deviceid='$d_id'");
	            	$category_query->execute();

	            	$update_category_query=$db->prepare("update category_dtl set status='active' where deviceid='$d_id' and category_type='Non-Table'");
	            	$update_category_query->execute();

	            	$product_query=$db->prepare("update product set status='delete' where deviceid='$d_id'");
	            	$product_query->execute();

	            	$update_product_query=$db->prepare("update product set status='active' where deviceid='$d_id' and product_type='Non-Table'");
	            	$update_product_query->execute();

	            }
	            elseif($device_type=="Weighing")
	            {
	            	$customer_query=$db->prepare("update customer_dtl set status='active' where deviceid='$d_id'");
	            	$customer_query->execute();

	            	$premise_query=$db->prepare("update premise_dtl set status='delete' where deviceid='$d_id'");
	            	$premise_query->execute();

	            	$category_query=$db->prepare("update category_dtl set status='delete' where deviceid='$d_id'");
	            	$category_query->execute();

	            	$update_category_query=$db->prepare("update category_dtl set status='active' where deviceid='$d_id' and category_type='Weighing'");
	            	$update_category_query->execute();

	            	$product_query=$db->prepare("update product set status='delete' where deviceid='$d_id'");
	            	$product_query->execute();

	            	$update_product_query=$db->prepare("update product set status='active' where deviceid='$d_id' and product_type='Weighing'");
	            	$update_product_query->execute();
	            }
	            $responce = array('status' =>2,'message'=>"success");
	            echo json_encode($responce);
	        }
        }
        else
        {
            $responce = array('status' =>1,'message'=>"Not configured for demo purpose");
            echo json_encode($responce);
        }
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }

?>