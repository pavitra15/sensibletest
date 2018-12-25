<?php
    include('../connect.php');
    if(is_ajax())
    {
        $rowcount=0;
        $status="active";
        $d_id=$_POST['d_id'];
        $date=date('Y-m-d');
        $id=$_POST['id'];
        $kitchen_name = $_POST["kitchen_name"];

        $exists_arr= array();
        $push_query=$db->prepare("select * from kitchen_dtl where deviceid='$d_id' and status='active'");
        $push_query->execute();
        if($data=$push_query->fetch())
        {
            do
            {
              $exists_arr[]=$data['kitchen_name'];
            }
            while($data=$push_query->fetch());
        }
        for($count = 0; $count<count($kitchen_name); $count++)
        {
            $kitchen_name_clean = strtoupper(trim($kitchen_name[$count]));
            if($kitchen_name_clean=="")
            {
            }
            else
            {
                if(in_array($kitchen_name_clean, $exists_arr))
                {
                }
                else
                {
                    $count_query=$db->prepare("select * from kitchen_dtl where deviceid='$d_id' and status='active'");
                    $count_query->execute();
                    $r_count=$count_query->rowcount();
                    if($r_count<5)
                    {
                        $exists_arr[]=$kitchen_name_clean;   
                        $query=$db->prepare("insert into kitchen_dtl (kitchen_name, deviceid, status, created_by_date, created_by_id) values('$kitchen_name_clean', '$d_id', '$status' , '$date', '$id')");
                        $query->execute();
                        $sk=$query->rowCount();
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