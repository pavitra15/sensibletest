<?php
	include('../connect.php');
	$deviceid=$_POST['deviceid'];
	$otp=$_POST['otp'];
	$sth = $db->prepare("select * from back_act_verify where deviceid='$deviceid' AND otp='$otp'");
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
		$current=date("Y-m-d h:i:s");
		$first=strtotime($current);
		$second=strtotime($dtm);
		if(($first-$second)<600)
		{
			$query=$db->prepare("update back_act_verify set access='success' where deviceid='$deviceid' and otp='$otp'");
        	$query->execute();
			$responce = array('status' =>2,'message'=>"success" );
			echo json_encode($responce);
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