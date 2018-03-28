<?php
    include('../../connect.php');
    if(is_ajax())
    {
        $did=$_POST['id'];
        $login_id=$_POST['login_id'];
        $model=$_POST['model'];
        $dealer_id=$_POST['dealer_id'];
        $status="active";
        $date=date('Y-m-d');
        $sth=$db->prepare("update admin_device set status='$status', approved_by_id='$login_id', approved_date='$date', dealer_id='$dealer_id', model='$model', status_change_date='$date' where id='$did'");
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