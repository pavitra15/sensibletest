<?php
    include('../connect.php');
    $rowcount=0;
    if(is_ajax())
    {
        $status="active";
        $d_id=$_POST['d_id'];
        $date=date('Y-m-d');
        $id=$_POST['id'];
        $customer_name = $_POST["customer_name"];
        $exists_arr= array();
        $push_query=$db->prepare("select customer_name from customer_dtl where deviceid='$d_id' and status='active'");
        $push_query->execute();
        if($data=$push_query->fetch())
        {
            do
            {
                $exists_arr[]=$data['customer_name'];
            }
            while($data=$push_query->fetch());
        }
        for($count = 0; $count<count($customer_name); $count++)
        {
            $customer_name_clean = strtoupper(trim($customer_name[$count]));
            if($customer_name_clean=="")
            {
            }
            else
            {
                if(in_array($customer_name_clean, $exists_arr))
                {
                }
                else
                {
                    $count_query=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='active'");
                    $count_query->execute();
                    $r_count=$count_query->rowcount();
                    if($r_count<5)
                    {
                        $exists_arr[]=$customer_name_clean; 
                        $query=$db->prepare("insert into customer_dtl (customer_name, deviceid, status, created_by_date, created_by_id) values('$customer_name_clean', '$d_id', '$status' , '$date', '$id')");
                        $query->execute();
                        $rowcount++;
                    }
                    else
                    {
                        echo "2_";
                    }
                }
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