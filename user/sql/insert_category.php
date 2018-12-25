<?php
    session_start();
    $rowcount=0;
    $_SESSION['rowcount']=0;
    include('../connect.php');
    if(is_ajax())
    {
        $status="active";
        $d_id=$_POST['d_id'];
        $date=date('Y-m-d');
        $id=$_POST['id'];
        $category_type=$_SESSION['device_type'];
        $category_name = $_POST["category_name"];
        $exists_arr= array();
        $push_query=$db->prepare("select category_name from category_dtl where deviceid='$d_id' and status='active'");
        $push_query->execute();
        if($data=$push_query->fetch())
        {
            do
            {
                $exists_arr[]=$data['category_name'];
            }
            while($data=$push_query->fetch());
        }
        for($count = 0; $count<count($category_name); $count++)
        {
            $category_name_clean = strtoupper(trim($category_name[$count]));
            if($category_name_clean=="")
            {
            }
            else
            {
                if(in_array($category_name_clean, $exists_arr))
                {
                }
                else
                {
                    $exists_arr[]=$category_name_clean;
                    $query=$db->prepare("insert into category_dtl(category_name,category_type, status, created_by_date, created_by_id,deviceid) values('$category_name_clean','$category_type' ,'$status' , '$date', '$id', '$d_id')");
                    $query->execute();
                    $rowcount++;
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