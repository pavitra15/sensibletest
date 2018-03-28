<?php
	include('../../connect.php');
	$deviceid=$_POST['deviceid'];
	$otp=$_POST['otp'];
	$url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
   
    $update_query=$db->prepare("update last_connect set city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login' where deviceid='$deviceid'");
    $update_query->execute();

    $did_query=$db->prepare("select d_id from device where deviceid='$deviceid'");
    $did_query->execute();
    while($did_data=$did_query->fetch())
    {
    	$d_id=$did_data['d_id'];
    }

	$sth = $db->prepare("select * from temp_device where device='$deviceid' AND otp='$otp'");
	$sth->execute();
	$count = $sth->rowCount(); 
	if ($count>0) //
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
		if(($first-$second)<300)//
		{
			$status="active";
			$date=date('Y-m-d');
			$st=$db->prepare("select * from user_dtl, user_type_mst where deviceid='$d_id' and user_dtl.user_type=user_type_mst.id and user_dtl.status='$status'");
			$st->execute();
			if($da=$st->fetch())
			{
				$user='{"user":[';
				do
				{
					$user.='{"userid":"'.$da['user_id'].'",';
					$user.='"user_name":"'.$da['user_name'].'",';
					$user.='"user_type":"'.$da['name'].'",';
					$user.='"password":"'.$da['password'].'",';
					$user.='"mobile":"'.$da['user_mobile'].'"},';
				}	
				while ($da=$st->fetch());
			}
			$user = rtrim($user, ',');
			$user.='],';
			$query=$db->prepare("update device set device_activated_date='$date' where d_id='$d_id'");
			if($query->execute())
			{
				$st=$db->prepare("delete from temp_device where device='$deviceid'");
				$st->execute();
				$s = $db->prepare("select d_id,device_type, language_name, device_name, config_type from device,language_mst where d_id='$d_id' and device.language_id=language_mst.language_id");
				$s->execute();
				$cunt = $s->rowCount(); 
				if ($cunt==1) 
				{
					if ($dta=$s->fetch()) 
					{
						do
						{
							$device_type=$dta['device_type'];
							$user.='"device":{"D_id":'.$dta['d_id'].',"device_type":"'.$dta['device_type'].'","search_language":"'.$dta['language_name'].'","device_name":"'.$dta['device_name'].'","config_type":"'.$dta['config_type'].'"},"premise":[';
						}
						while($dta=$sth->fetch());
					}
				}
				if($device_type=="Table")
				{
					$premise_query=$db->prepare("select * from premise_dtl where deviceid='$d_id' and status='$status'");
					$premise_query->execute();
					if($data=$premise_query->fetch())
					{
						do
						{
							$user.='{"premise_id":'.$da['premise_id'].',';
							$user.='"no_of_table":'.$da['no_of_table'].',';
							$user.='"table_range":'.$da['table_range'].',';
							$user.='"premise_name":"'.$da['premise_name'].'"},';
						}
						while($data=$premise_query->fetch());
					}

					$user=substr($user, 0,-1);
					$user.='],';
					$user.='"kitchen":[';
					$kitchen_query=$db->prepare("select * from kitchen_dtl where deviceid='$d_id' and status='$status'");
					$kitchen_query->execute();
					if($data=$kitchen_query->fetch())
					{
						while($data=$kitchen_query->fetch())
						{
							$user.='{"kitchen_id":"'.$da['kitchen_id'].'",';
							$user.='"kitchen_name":"'.$da['kitchen_name'].'"},';
						}
					}
				}
				else
				{
					$customer_query=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
					$customer_query->execute();
					if($data=$customer_query->fetch())
					{
						while($data=$customer_query->fetch())
						{
							$user.='{"customer_id":'.$da['customer_id'].',';
							$user.='"customer_name":"'.$da['customer_name'].'"},';
						}
						
					}
				}
				$user = rtrim($user, ',');
				$user.=']}';
				
				function json_validator($data=NULL) 
				{
    				if (!empty($data)) 
    				{
                		@json_decode($data);
                		return (json_last_error() === JSON_ERROR_NONE);
        			}
        			return false;
				}
				if(json_validator($user))
				{
					echo $user;
				}
				else
				{
					$responce = array('status' =>3,'message'=>'Properly add data');
					echo json_encode($responce);
					echo $user;
				}
			}
			else
			{
				$responce = array('status' =>2,'message'=>'device not configured');
				echo json_encode($responce);
			}
		}
		else
		{
			$responce = array('status' =>1,'message'=>"time out" );
			echo json_encode($responce);
		}
	}
	else
	{
		$responce = array('status' =>0,'message'=>"invalid otp");
		echo json_encode($responce);
	}
?>