<?php
include("../connect.php");
if($_POST['id'])
{
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $log_date=$date->format('Y-m-d H:i:s');

    $id=$_POST['id'];
    $d_id=$_POST['d_id'];
    $notification=$_POST['notification'];
    $priority=$_POST['priority'];
    $device_name=$_POST['device_name'];
    $see=0;
    
    $query=$db->prepare("insert into notification_mst (d_id, user_id, notification, device_name, priority,see,notification_time) values('$d_id', '$id', '$notification', '$device_name', '$priority', '$see', '$log_date')");
    $query->execute();
}
?>