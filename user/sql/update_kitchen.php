<?php
	include("../connect.php");
	if(is_ajax())
	{
		$date=date('Y-m-d');
		$kitchen_id=$_POST['kitchen_id'];
		$d_id=$_POST['d_id'];
		$id=$_POST['id'];
		$firstname=strtoupper(trim($_POST['firstname']));
		$exists_arr= array();
	    $push_query=$db->prepare("select * from kitchen_dtl where deviceid='$d_id' and status='active'");
	    $push_query->execute();
	    if($data=$push_query->fetch())
	    {
	        do
	        {
	      	$exists_arr[]=$data['kitchen_name'];
	        }
	       	while($data=$push_query->fetch());
	    }

		if($firstname=="")
		{
			$select_query=$db->prepare("select kitchen_name from kitchen_dtl where kitchen_id='$kitchen_id' ");
	       	$select_query->execute();
	       	if($select_data=$select_query->fetch())
			{
				do
			    {
					$kitchen_name=$select_data['kitchen_name'];
			    }
			    while($select_data=$select_query->fetch());
			}
	       	echo "3_".$kitchen_name;
		}
		else
		{
			if(in_array($firstname, $exists_arr))
	        {
	        	$select_query=$db->prepare("select kitchen_name from kitchen_dtl where kitchen_id='$kitchen_id'");
		       	$select_query->execute();
		       	if($select_data=$select_query->fetch())
				{
					do
				    {
						$kitchen_name=$select_data['kitchen_name'];
				    }
				    while($select_data=$select_query->fetch());
				}
		       	echo "2_".$kitchen_name;
	        }
	        else
	        {
				$query=$db->prepare("update kitchen_dtl set kitchen_name='$firstname', updated_by_date='$date', updated_by_id='$id' where kitchen_id='$kitchen_id'");
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