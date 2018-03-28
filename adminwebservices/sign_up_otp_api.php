<?php
	include('../connect.php');
	$login_id=$_POST['login_id'];
	$otp=$_POST['otp'];
	$deviceid=$_POST['deviceid'];
	$status="active";
	$type="user";
	$responce="";
	$sth = $db->prepare("select * from temp_mobile_verification where login_id='$login_id' AND otp='$otp'");
	$sth->execute();
	$count = $sth->rowCount(); 
	if ($count>0) 
	{
		if($data=$sth->fetch())
		{
			do
			{
				$dtm=$data['dtm'];
			}
			while ($data=$sth->fetch());
		}
		$date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    	$current=$date->format('Y-m-d H:i:s');
    	$status_change_date=$date->format('Y-m-d');
		$first=strtotime($current);
		$second=strtotime($dtm);
		if(($first-$second)<300)
		{
			$verify='Yes';
			$query=$db->prepare("update login_mst set username_verified='$verify', access_control='$status', status='$status',status_change_date='$status_change_date' where id='$login_id'");
			$query->execute();

			$token=md5(uniqid(mt_rand(),true));
			$query=$db->prepare("insert into mobile_token_verify(deviceid,token, status, created_by_id, created_by_date)values('$deviceid','$token','$status','$login_id','$status_change_date')");
        	$query->execute();
        	$responce.='"token":"'.$token.'",';
        	$select_query=$db->prepare("select login_mst.id, first_name, last_name, mobile, email, state, city, pincode from login_mst, user_mst where user_mst.id=login_mst.id and login_mst.id='$login_id' and status='$status' and access_control='$status' and type='$type'");
        	$select_query->execute();
        	$data=$select_query->fetchAll(PDO::FETCH_ASSOC);
        	
			$delete_query=$db->prepare("delete from temp_mobile_verification where login_id='$login_id' AND otp='$otp'");
        	$delete_query->execute();

        	$responce = array('status' => 2,'token'=>$token,'user'=>$data);
        	echo json_encode($responce);
		}
		else
		{
			$responce = array('status' =>1,'message'=>"time out" );
			echo json_encode($responce);
			$delete_query=$db->prepare("delete from temp_mobile_verification where login_id='$login_id' AND otp='$otp'");
        	$delete_query->execute();
		}
	}
	else
	{
		$responce = array('status' =>0,'message'=>"invalid otp");
		echo json_encode($responce);
	}
?>