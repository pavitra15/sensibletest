<?php
    include('../../connect.php');
    if(is_ajax())
    {
        $d_id=$_POST['d_id'];
        $access_control=$_POST['access_control'];
        $date=date('Y-m-d');
        $id=$_POST['login_id'];
        $sth=$db->prepare("update device set access_control='$access_control', status_change_date='$date' , updated_by_id='$id' where d_id='$d_id'");
        if($sth->execute())
        {
            echo 1;
        }
        else
        {
            echo 2;
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