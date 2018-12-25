<?php
	include('../connect.php');
	$deviceid=$_POST['deviceid'];
	$serial_no=$_POST['serial_no'];
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
	
	$query = $db->prepare("select * from admin_device where serial_no='$serial_no'");
	$query->execute();
	if($query->rowCount())
	{
		$both_query = $db->prepare("select * from device where device.deviceid='$deviceid'");
		$both_query->execute();
		$count=$both_query->rowCount();
		if($count)
		{
			$responce = array('status' =>1,'message'=>'this device already registered');
			echo json_encode($responce);
		}
		else
		{
			$delete_query=$db->prepare("update admin_device set status='delete' where deviceid='$deviceid'");
			$delete_query->execute();
			$update_query=$db->prepare("update admin_device set deviceid='$deviceid' where serial_no='$serial_no'");
			$update_query->execute();
			$update_device=$db->prepare("update device set deviceid='$deviceid' where serial_no='$serial_no'");
			$update_device->execute();
			$responce = array('status' =>2,'serial'=>$serial_no,'message'=>'success');
			echo json_encode($responce);
		}		
	}
	else
	{
		$responce = array('status' =>0,'message'=>'Serial number not exists');
		echo json_encode($responce);
	}
?>