<?php
	include("../connect.php");
	if(is_ajax())
	{
		$date=date('Y-m-d');
		$customer_id=$_POST['customer_id'];
		$d_id=$_POST['d_id'];
		$id=$_POST['id'];
		$customer_name=strtoupper(trim($_POST['customer_name']));
		$exists_arr= array();
        $push_query=$db->prepare("select customer_name from customer_dtl where deviceid='$d_id' and status='active'");
        $push_query->execute();
        if($data=$push_query->fetch())
        {
            do
            {
                $exists_arr[]=$data['customer_name'];
            }
            while($data=$push_query->fetch());
        }
		if($customer_name=="")
		{
			$select_query=$db->prepare("select customer_name from customer_dtl where customer_id='$customer_id' ");
		    $select_query->execute();
		    if($select_data=$select_query->fetch())
			{
				do
			    {
					$customer_name=$select_data['customer_name'];
			    }
			    while($select_data=$select_query->fetch());
			}
		    echo "3_".$customer_name;
		}
		else
		{
			if(in_array($customer_name, $exists_arr))
            {
            	$select_query=$db->prepare("select customer_name from customer_dtl where customer_id='$customer_id' ");
		       	$select_query->execute();
		       	if($select_data=$select_query->fetch())
				{
					do
				    {
						$customer_name=$select_data['customer_name'];
				    }
				    while($select_data=$select_query->fetch());
				}
		       	echo "2_".$customer_name;
            }
            else
            {
				$query=$db->prepare("update customer_dtl set customer_name='$customer_name', updated_by_date='$date', updated_by_id='$id' where customer_id='$customer_id'");
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