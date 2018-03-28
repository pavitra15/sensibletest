<?php
	include('connect.php');
	$deviceid=$_POST['deviceid'];
	$otp=$_POST['otp'];
	$id=$_POST['id'];
	$password=$_POST['password'];
	$url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
   
    $update_query=$db->prepare("update last_connect set city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login' where deviceid='$deviceid'");
    $update_query->execute();

    $access_query = $db->prepare("select * from device where deviceid='$deviceid'");
    $access_query->execute();
    if ($access_data = $access_query->fetch()) 
    {
        do 
        {
        	$d_id=$access_data['d_id'];
    	} 
        while ($access_data = $access_query->fetch());
    }

	$sk=$db->prepare("delete from token_verify where deviceid='$deviceid'");
	$sk->execute();
	$sth = $db->prepare("select * from temp_user where device='$deviceid' AND otp='$otp' AND uid='$id'");
	$sth->execute();
	$count = $sth->rowCount(); 
	if($count>0) 
	{
		if($data=$sth->fetch())
		{
			do
			{
				$dtm=$data['dtm'];
			}
			while ($data=$sth->fetch());
		}
		$current=date("Y-m-d h:i:s");
		$first=strtotime($current);
		$second=strtotime($dtm);
		if(($first-$second)<300)
		{
			$status="active";
			$date=date('Y-m-d');
			$query=$db->prepare("update user_dtl set password='$password', status='$status',status_change_date='$date' where deviceid='$d_id' and user_id='$id'");
			if($query->execute())
			{
				$sk=$db->prepare("select * from user_dtl, user_type_mst where user_id='$id' and user_dtl.user_type=user_type_mst.id and user_dtl.status='$status'");
				$sk->execute();
				if($d=$sk->fetch())
				{
					do{
						$username=$d['user_name'];
						$skt=$d['password'];
						$type=$d['name'];
						$mobile=$d['user_mobile'];
					}while($d=$sk->fetch());
				}
				$token=md5(uniqid(mt_rand(),true));
				$query=$db->prepare("insert into token_verify(deviceid,token)values('$deviceid','$token')");
				$query->execute();
				$st=$db->prepare("delete * from temp_user where device='$deviceid' and uid='$id'");
				$st->execute();
				$responce=array('status'=>1,'token'=>$token,'userid'=>$id, 'username'=>$username,'password'=>$skt,'type'=>$type,'mobile'=>$mobile,'message'=>"Successfully created password");
				echo json_encode($responce);
			}
			else
			{
				$responce = array('status' =>2,'message'=>'Error occured');
				echo json_encode($responce);
			}
		}
		else
		{
			$responce = array('status' =>3,'message'=>'time out');
			echo json_encode($responce);
		}
	}
	else
	{
		$responce = array('status' =>4,'message'=>"invalid otp" );
		echo json_encode($responce);
	}
?>