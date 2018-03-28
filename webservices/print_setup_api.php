<?php
	include('../connect.php');
	$token=$_POST['token'];
	$deviceid=$_POST['deviceid'];
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
		$print_query=$db->prepare("select print_setup.id, title, sub_title, address, contact, gstn, tax_invoice, bill_name, sr_no, prnt_bill_no, prnt_sr_col, prnt_base_col, prnt_bill_time,prnt_disc_col,footer, consolidated_tax,payment_mode,decimal_point from print_setup ,device where device.deviceid='$deviceid' and print_setup.d_id=device.d_id and print_setup.status='active'");
		$print_query->execute();
		if($data=$print_query->fetch(PDO::FETCH_ASSOC))
		{
			do
			{
				$value=$data;
			}
			while ($data=$print_query->fetch(PDO::FETCH_ASSOC));
		}
		$responce = array('status' =>1,'print_data'=>$value);
		echo json_encode($responce);
	}
	else
	{
		$responce = array('status' =>0,'message'=>"token mismatch");
		echo json_encode($responce);
	}
?>