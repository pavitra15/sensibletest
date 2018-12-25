<?php
    include('../connect.php');
    $rowcount=0;
    if(is_ajax())
    {
        $status="active";
        $d_id=$_POST['d_id'];
        $date=date('Y-m-d');
        $id=$_POST['id'];
        $waiter_name = $_POST["waiter_name"];
        $waiter_mobile = $_POST['waiter_mobile'];
        for($count = 0; $count<count($waiter_name); $count++)
        {
            $waiter_name_clean = strtoupper(trim($waiter_name[$count]));
            $waiter_mobile_clean = $waiter_mobile[$count];
            if($waiter_name_clean=="" || $waiter_mobile_clean=="" || strlen($waiter_mobile_clean)!=10)
            {
            }
            else
            {
                $query=$db->prepare("insert into waiter_dtl (waiter_name, waiter_mobile,deviceid, status, created_by_date, created_by_id) SELECT '$waiter_name_clean', '$waiter_mobile_clean', '$d_id', '$status' , '$date', '$id' WHERE NOT EXISTS (SELECT * FROM waiter_dtl WHERE waiter_name='$waiter_name_clean'and deviceid='$d_id')");
                $query->execute();
                $rowcount++;
            }
        }
        echo "1_".$rowcount;
    }
    else 
    {
        echo "something wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
?>