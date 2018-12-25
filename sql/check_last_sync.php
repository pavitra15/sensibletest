<?php
	include('../connect.php');
	if(is_ajax())
	{
		$d_id=$_POST['d_id'];
		$sync_time_query=$db->prepare("select sync_datetime from last_sync where d_id='$d_id' order by id desc LIMIT 1");
        $sync_time_query->execute();
        while($row=$sync_time_query->fetch())
        {
            $sync_time=$row['sync_datetime'];
        }

        if($sync_time)
        {
        	$date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
            $log_date=$date->format('Y-m-d H:i:s');
            $date1=date_create($log_date);
            $date2=date_create($sync_time);
            $hourdiff =round((strtotime($log_date) - strtotime($sync_time))/3600,2);
            if($hourdiff>24)
            {
            	$diff=date_diff($date2,$date1);
                $show_time = $diff->format("%R%a days");
            }
            elseif($hourdiff>1)
            {
            	$diff=date_diff($date2,$date1);
                $show_time = $diff->format("%R%h hours");   
            }
            else
            {
            	$diff=date_diff($date2,$date1);
                $show_time = ($hourdiff*60)." minutes";
            }
            echo $show_time." ago" ;
        }
		else
		{
			echo "not yet connect to internet";
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