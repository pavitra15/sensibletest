<?php
	include("../connect.php");
	if(is_ajax())
   	{
		$date=date('Y-m-d');
		$waiter_id=$_POST['waiter_id'];
		$id=$_POST['id'];
		$d_id=$_POST['d_id'];
		$waiter_name=strtoupper(trim($_POST['waiter_name']));
		$waiter_mobile=trim($_POST['waiter_mobile']);
		$exists_waiter= array();
		$exists_mobile= array();
	    $push_query=$db->prepare("select waiter_name, waiter_mobile from waiter_dtl where deviceid='$d_id' and status='active'");
	    $push_query->execute();
	    if($data=$push_query->fetch())
	    {
	    	do
	        {
	            $exists_waiter[]=$data['waiter_name'];
	            $exists_mobile[]=$data['waiter_mobile'];
	       	}
	        while($data=$push_query->fetch());
	    }
		if($waiter_name=="" || $waiter_mobile=="")
		{
		}
		else
		{
			if((in_array($waiter_name, $exists_waiter)))
			{

				$count_query=$db->prepare("select waiter_name from waiter_dtl where waiter_id='$waiter_id'");
				$count_query->execute();
				if($count_data=$count_query->fetch())
				{
					do
					{
						$count=$count_data['waiter_name'];
					}
					while ($count_data=$count_query->fetch());
				}
				if($count==$waiter_name)
				{
					$query=$db->prepare("update waiter_dtl set waiter_name='$waiter_name', waiter_mobile='$waiter_mobile', updated_by_date='$date', updated_by_id='$id' where waiter_id='$waiter_id'");
					$query->execute();
					echo "1_";
				}
				else
				{
					$select_query=$db->prepare("select waiter_name, waiter_mobile from waiter_dtl where waiter_id='$waiter_id'");
				    $select_query->execute();
				    if($select_data=$select_query->fetch())
				    {
				    	do
				        {
				            $name=$select_data['waiter_name'];
				            $mobile=$select_data['waiter_mobile'];
				       	}
				        while($select_data=$select_query->fetch());
				    }
				    echo "2_".$name."_".$mobile;			
				}
				
			}
			else
			{
				$query=$db->prepare("update waiter_dtl set waiter_name='$waiter_name', waiter_mobile='$waiter_mobile', updated_by_date='$date', updated_by_id='$id' where waiter_id='$waiter_id'");
				$query->execute();
				echo "1_";
			}
		}	
	}
	else 
    {
        echo "something wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
?>