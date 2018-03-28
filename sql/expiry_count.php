<?php
	include('../connect.php');
	if(is_ajax())
	{
		$login_id=$_POST['login_id'];
		$count_query=$db->prepare("select password_updated_date from login_mst where id='$login_id'");
		$count_query->execute();
		if($data=$count_query->fetch())
		{
			do
			{
				$password_updated_date=$data['password_updated_date'];
			}
			while ($data=$count_query->fetch());
		}

		$date =  new DateTime();
    	$clone_date=clone $date;
    	$updated_date=$clone_date->format('Y-m-d');

		$datediff = strtotime($updated_date) - strtotime($password_updated_date);

		echo round($datediff / (60 * 60 * 24));
	}
	else 
    {
        echo "something wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
?>