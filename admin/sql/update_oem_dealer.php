<?php
    include('../../connect.php');
    if(is_ajax())
    {
        $dealer_id=$_POST['dealer_id'];
        $branding=$_POST['branding'];
        $date=date('Y-m-d');
        $id=$_POST['login_id'];
        $sth=$db->prepare("update dealer_mst set branding='$branding', updated_by_date='$date' , updated_by_id='$id' where dealer_id='$dealer_id'");
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