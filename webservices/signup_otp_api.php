<?php
	include('../connect.php');
	$login_id=$_POST['login_id'];
	$otp=$_POST['otp'];
	$deviceid=$_POST['deviceid'];
	$status="active";
	$type="user";
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
			$responce='{"status":2,';
			$verify='Yes';
			$query=$db->prepare("update login_mst set username_verified='$verify', access_control='$status', status='$status',status_change_date='$status_change_date' where id='$login_id'");
			$query->execute();

			 // $query=$db->prepare("insert into login_mst (username, username_verified, password, password_updated_date, google_status, gid, access_level, status, type, created_by_date, updated_by_id, updated_by_date, status_change_date, deleted_by_id) select username, username_verified, password, password_updated_date, google_status,gid, access_level, status, type, created_by_date, updated_by_id, updated_by_date, status_change_date, deleted_by_id from temp_login where username='$mobile' and status='$status' and id='$login_id'");
    //                 $query->execute();

            // $query=$db->prepare("select id from login_mst where username='$mobile'");
            // $query->execute();
            // while ($login_data=$query->fetch()) 
            // {
            // 	$temp_id=$login_data['id'];
            // }

            // $update_query=$db->prepare("update user_mst set id='$temp_id' where id='$login_id' and mobile='$mobile'");
            // $update_query->execute();

			$token=md5(uniqid(mt_rand(),true));
			$query=$db->prepare("insert into token_verify(deviceid,token)values('$deviceid','$token')");
        	$query->execute();
        	$responce.='"token":"'.$token.'",';
        	$select_query=$db->prepare("select login_mst.id, first_name, last_name, mobile, email, state, city, pincode from login_mst, user_mst where user_mst.id=login_mst.id and login_mst.id='$login_id' and status='$status' and access_control='$status' and type='$type'");
        	$select_query->execute();
        	if($data=$select_query->fetch())
        	{
        		do
	            {
	            	$id=$data['id'];
	            	$responce.='"user":{"id":'.$data['id'].',"first_name":"'.$data['first_name'].'","last_name":"'.$data['last_name'].'","mobile":'.$data['mobile'].',"email":"'.$data['email'].'","state":'.$data['state'].',"city":"'.$data['city'].'","pincode":'.$data['pincode'].'},';
	            }
	            while ($data=$select_query->fetch());
        	}

        	echo $responce;
        	$delete_query=$db->prepare("delete from temp_mobile_verification where login_id='$login_id' AND otp='$otp'");
        	$delete_query->execute();
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