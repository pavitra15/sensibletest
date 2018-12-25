<?php
	session_start();
	include("../connect.php");
	if(is_ajax())
	{
		$date=date('Y-m-d');
		$id=$_POST['id'];
		$d_id=$_POST['d_id'];
		$login_id=$_SESSION['login_id'];
		$firstname=strtoupper(trim($_POST['firstname']));
		$lastname=trim($_POST['lastname']);
		$table_range=trim($_POST['table_range']);
		$exists_arr= array();
	    $push_query=$db->prepare("select * from premise_dtl where deviceid='$d_id' and status='active'");
	    $push_query->execute();
	    if($data=$push_query->fetch())
	    {
	        do
	        {
	        	$exists_arr[]=$data['premise_name'];
			}
	        while($data=$push_query->fetch());
	    }
		if($firstname=="" || $lastname=="")
		{
			$select_query=$db->prepare("select premise_name, no_of_table from premise_dtl where premise_id='$id' ");
	       	$select_query->execute();
	       	if($select_data=$select_query->fetch())
			{
				do
			    {
					$premise_name=$select_data['premise_name'];
					$no_of_table=$select_data['no_of_table'];
			    }
			    while($select_data=$select_query->fetch());
			}
	       	echo "3_".$premise_name."_".$no_of_table;
		}
		else
		{
			if(in_array($firstname, $exists_arr))
	        {
	        	$count_query=$db->prepare("select premise_name from premise_dtl where premise_id='$id'");
				$count_query->execute();
				if($count_data=$count_query->fetch())
				{
					do
					{
						$count=$count_data['premise_name'];
					}
					while ($count_data=$count_query->fetch());
				}
				if($count==$firstname)
				{
					$query=$db->prepare("update premise_dtl set premise_name='$firstname', no_of_table='$lastname', table_range='$table_range' where premise_id='$id'");
					$query->execute();
					echo "1_";
				}
				else
				{
					$select_query=$db->prepare("select premise_name, no_of_table from premise_dtl where premise_id='$id' ");
			       	$select_query->execute();
			       	if($select_data=$select_query->fetch())
					{
						do
					    {
							$premise_name=$select_data['premise_name'];
							$no_of_table=$select_data['no_of_table'];
					    }
					    while($select_data=$select_query->fetch());
					}
			       	echo "2_".$premise_name."_".$no_of_table;
				}
	        }
	        else
	        {
				$query=$db->prepare("update premise_dtl set premise_name='$firstname', no_of_table='$lastname', table_range='$table_range' where premise_id='$id'");
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