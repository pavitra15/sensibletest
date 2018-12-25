<?php
	include("../connect.php");
	if(is_ajax())
	{
		$date=date('Y-m-d');
		$category_id=$_POST['category_id'];
		$d_id=$_POST['d_id'];
		$id=$_POST['id'];
		$category_name=strtoupper(trim($_POST['category_name']));
		$exists_arr= array();
	    $push_query=$db->prepare("select category_name from category_dtl where deviceid='$d_id' and status='active'");
	    $push_query->execute();
	    if($data=$push_query->fetch())
	    {
	    	do
	        {
	        	$exists_arr[]=$data['category_name'];
	        }
	        while($data=$push_query->fetch());
	    }
		if($category_name=="")
		{
			$select_query=$db->prepare("select category_name from category_dtl where category_id='$category_id' and status='active'");
	       	$select_query->execute();
	       	if($select_data=$select_query->fetch())
			{
				do
			    {
					$category_name=$select_data['category_name'];
			    }
			    while($select_data=$select_query->fetch());
			}
	       	echo "3_".$category_name;
		}
		else
		{
			if(in_array($category_name, $exists_arr))
	       	{
	       		$select_query=$db->prepare("select category_name from category_dtl where category_id='$category_id' and status='active'");
	       		$select_query->execute();
	       		if($select_data=$select_query->fetch())
			    {
			    	do
			        {
			        	$category_name=$select_data['category_name'];
			        }
			        while($select_data=$select_query->fetch());
			    }
	       		echo "2_".$category_name;
	        }
	        else
	        {
				$query=$db->prepare("update category_dtl set category_name='$category_name', updated_by_date='$date', updated_by_id='$id' where category_id='$category_id'");
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