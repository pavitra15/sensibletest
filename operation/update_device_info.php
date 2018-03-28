<?php
    session_start();
    include('../connect.php');
    $d_id=$_SESSION['d_id'];
    $device_name=trim($_POST['device_name']);
    if($device_name!="")
    {
        $update_query=$db->prepare("update device set device_name='$device_name' where d_id='$d_id'");
        $update_query->execute();
        $count=$update_query->rowCount();
        if($count>0)
        {
            echo "1";
        }
        else
        {
            echo "2";
        }
    }
    else
    {
        echo "3";
    }
?>