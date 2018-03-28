<?php
	include("../connect.php");
	if(is_ajax())
	{
		$date=date('Y-m-d');
		$user_id=$_POST['user_id'];
		$id=$_POST['id'];
		$d_id=$_POST['d_id'];
		$user_name=strtoupper(trim($_POST['user_name']));
		$user_type=trim($_POST['user_type']);
		$user_mobile=trim($_POST['user_mobile']);
		$exists_admin= array();
		$exists_user= array();
	    $admin_arr=array();
	    $user_arr=array();
		$push_query=$db->prepare("select * from user_dtl, user_type_mst where deviceid='$d_id' and user_dtl.user_type= user_type_mst.id and user_dtl.status='active'");
	    $push_query->execute();
	    if($data=$push_query->fetch())
	    {
	    	do
	        {
	        	if($data['name']=='Administrator')
	            {
	            	$exists_admin[]=$data['user_name'];
	                $admin_arr[]= $data['user_type'];
	                $type=$data['user_type'];
	            }
	            else
	            {
	            	$exists_user[]=$data['user_name'];
	                $user_arr[]= $data['user_type'];
	            }
	        }
	        while($data=$push_query->fetch());
	    }
		if($user_name=="" || $user_mobile=="")
		{
		}
		else
		{
			if($user_type=="1")
			{
				
				if((in_array($user_name, $exists_admin)) || (in_array($user_type, $admin_arr)))
				{
					if(sizeof($admin_arr)==1)
					{
						$count_query=$db->prepare("select user_type from user_dtl where user_id='$user_id' and user_type='1'");
						$count_query->execute();
						$count=$count_query->rowCount();
						if($count==1)
						{
							$query=$db->prepare("update user_dtl set user_name='$user_name', user_type='$user_type', user_mobile='$user_mobile', updated_by_date='$date', updated_by_id='$id' where user_id='$user_id'");
							$query->execute();
							echo "1_";
						}
						else
						{
							echo "3_";
						}
					}
					else
					{
						echo "2_";
					}
				}
				else
				{
					$query=$db->prepare("update user_dtl set user_name='$user_name', user_type='$user_type', user_mobile='$user_mobile', updated_by_date='$date', updated_by_id='$id' where user_id='$user_id'");
					$query->execute();
					echo "1_";
				}

			}
			else
			{
				if((in_array($user_name, $exists_user)))
				{
						$count_query=$db->prepare("select user_type from user_dtl where user_id='$user_id' and user_type='2'");
						$count_query->execute();
						$count=$count_query->rowCount();
						if($count==1)
						{
							$query=$db->prepare("update user_dtl set user_name='$user_name', user_type='$user_type', user_mobile='$user_mobile', updated_by_date='$date', updated_by_id='$id' where user_id='$user_id'");
							$query->execute();
							echo "1_";
						}
						else
						{
							echo "2_";
						}
						
				}
				else
				{
					$query=$db->prepare("update user_dtl set user_name='$user_name', user_type='$user_type', user_mobile='$user_mobile', updated_by_date='$date', updated_by_id='$id' where user_id='$user_id'");
					$query->execute();
					echo "1_";
				}
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