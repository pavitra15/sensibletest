<?php
	include('../../connect.php');
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
	$response='{"records":{"unit":[';
	if($token_count==1)
	{
		$unit_query=$db->prepare("select unit_id,unit_name,abbrevation from unit_mst where status='active'");
		$unit_query->execute();
		while($unit_data=$unit_query->fetch())
		{
			$response.='{"Unit_Id" : '.$unit_data['unit_id'].',"Unit_Name" : "'.$unit_data['unit_name'].'","Abbrevation" : "'.$unit_data['abbrevation'].'"},';
		}

		$response=substr($response, 0,-1);
		$response.='],"premise":[';
		$type_query=$db->prepare("select d_id, device_type from device where deviceid='$deviceid' and status='$status'");
		$type_query->execute();
		if($data=$type_query->fetch())
		{
			do
			{
				$d_id=$data['d_id'];
				$device_type=$data['device_type'];
			}
			while($data=$type_query->fetch());
		}

		if($device_type=="Table")
		{
			$premise_query=$db->prepare("select * from premise_dtl where deviceid='$d_id' and status='$status'");
			$premise_query->execute();
			if($data=$premise_query->fetch())
			{
				do
				{
					$response.='{"Name":"'.$data['premise_name'].'",';
					$response.='"Id":'.$data['premise_id'].',';
					$response.='"No":'.$data['no_of_table'].'},';
				}
					while($data=$premise_query->fetch());
			}

			$response=substr($response, 0,-1);
			$response.='],';
			$response.='"kitchen":[';
			$kitchen_query=$db->prepare("select * from kitchen_dtl where deviceid='$d_id' and status='$status'");
			$kitchen_query->execute();
			if($data=$kitchen_query->fetch())
			{
				do
				{
					$response.='{"Name":"'.$data['kitchen_name'].'","Id":'.$data['kitchen_id'].',"kitchen_printer_ip":"'.$data['kitchen_printer_ip'].'","kitchen_printer_port":'.$data['kitchen_printer_port'].',"printer_type":'.$data['printer_type'].',"path":"'.$data['path'].'","paper_size":'.$data['paper_size'].'},';
				}
				while($data=$kitchen_query->fetch());
				$response=substr($response, 0,-1);
				$response.='],';
			}
			else
			{
				$response.='{"Name":"","Id":""},';	
				$response=substr($response, 0,-1);
				$response.='],';
			}
			$response.='"waiter":[';
			$kitchen_query=$db->prepare("select * from waiter_dtl where deviceid='$d_id' and status='$status'");
			$kitchen_query->execute();
			if($data=$kitchen_query->fetch())
			{
				do
				{
					$response.='{"waiter_id":"'.$data['waiter_id'].'", "Name":"'.$data['waiter_name'].'", "Mobile":"'.$data['waiter_mobile'].'"},';
				}
				while($data=$kitchen_query->fetch());
				$response=substr($response, 0,-1);
				$response.='],';
			}
			else
			{
				$response.='{"waiter_id":"","Name":"","Mobile":""},';	
				$response=substr($response, 0,-1);
				$response.='],';
			}
		}
		else
		{

			$customer_query=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
			$customer_query->execute();
			if($data=$customer_query->fetch())
			{
				do
				{
					$response.='{"Name":"'.$data['customer_name'].'","Id":'.$data['customer_id'].'},';
				}
				while($data=$customer_query->fetch());
				$response=substr($response, 0,-1);
			}
			else
			{
			}
			$response.='],';
		}

		$response.='"category": {"category": [';
		$category_query=$db->prepare("select * from category_dtl where deviceid='$d_id' and status='$status'");
		$category_query->execute();
		if($category_data=$category_query->fetch())
		{
			do
			{
				$response.='{"Category_Name":"'.$category_data['category_name'].'",';
				$response.='"Category_id":'.$category_data['category_id'].'},';	
			}
			while($category_data=$category_query->fetch());
			$response=substr($response, 0,-1);
		}
		$response.=']},';


		$response.='"tax": {"TAX": [';

		$tax_query=$db->prepare("select * from tax_mst where status='$status'");
		$tax_query->execute();
		if($tax_data=$tax_query->fetch())
		{
			do
			{
				$response.='{"Tax_Index":'.$tax_data['tax_id'].',';
				$response.='"Tax_Name":"'.$tax_data['tax_name'].'",';
				$response.='"Tax_Type":"'.$tax_data['tax_type'].'",';
				$response.='"Tax (%)":'.$tax_data['tax_perc'].'},';		
			}
			while($tax_data=$tax_query->fetch());
			$response=substr($response, 0,-1);
		}
		$response.='],';


		$response.='"kitchen":[';
			$kitchen_query=$db->prepare("select * from kitchen_dtl where deviceid='$d_id' and status='$status'");
			$kitchen_query->execute();
			if($data=$kitchen_query->fetch())
			{
				do
				{
					$response.='{"Name":"'.$data['kitchen_name'].'","Id":'.$data['kitchen_id'].',"kitchen_printer_ip":"'.$data['kitchen_printer_ip'].'","kitchen_printer_port":'.$data['kitchen_printer_port'].',"printer_type":'.$data['printer_type'].',"path":"'.$data['path'].'","paper_size":'.$data['paper_size'].'},';
				}
				while($data=$kitchen_query->fetch());
				$response=substr($response, 0,-1);
				$response.='],';
			}
			else
			{
				$response.='{"Name":"","Id":"","kitchen_printer_ip":"","kitchen_printer_port":"","printer_type":"","path":"","paper_size":""},';	
				$response=substr($response, 0,-1);
				$response.='],';
			}

		

		$tax_type_query=$db->prepare("select tax_type, prnt_billno, prnt_billtime from device where d_id='$d_id'");
		$tax_type_query->execute();
		if($tax_type_data=$tax_type_query->fetch())
		{
			do
			{
				$tax_type=$tax_type_data['tax_type'];
				$print_billno=$tax_type_data['prnt_billno'];
				$print_billtime=$tax_type_data['prnt_billtime'];
			}
			while($tax_type_data=$tax_type_query->fetch());
		}
		$response.='"tax_type":"'.$tax_type.'","print_billno":"'.$print_billno.'","print_billtime":"'.$print_billtime.'",';


		$config_query=$db->prepare("select person_config from configuration where d_id='$d_id'");
		$config_query->execute();
		if($data=$config_query->fetch())
		{
			do
			{
				$person_config=$data['person_config'];
			}
			while($data=$config_query->fetch());
		}

		$response.='"person_config":"'.$person_config.'"}}}';

		function json_validator($data=NULL) 
		{
    		if (!empty($data)) 
    		{
            	@json_decode($data);
                return (json_last_error() === JSON_ERROR_NONE);
        	}
        	return false;
		}
		if(json_validator($response))
		{
			echo $response;
		}
		else
		{
			$responce = array('status' =>0,'message'=>'Properly add data');
			echo json_encode($responce);
			echo $response;
		}
	}
	else
	{
		$responce = array('status' =>1,'message'=>"token mismatch");
		echo json_encode($responce);
	}
?>