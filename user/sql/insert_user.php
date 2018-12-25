<?php
    include('../connect.php');
    if(is_ajax())
    {
        $status="active";
        $rowcount=0;
        $d_id=$_POST['d_id'];
        $date=date('Y-m-d');
        $id=$_POST['id'];
        $user_name = $_POST["user_name"];
        $user_type = $_POST['user_type'];
        $user_mobile = $_POST['user_mobile'];

        $exists_admin= array();
        $admin_arr=array();
        $user_arr=array();
        $exists_user=array();
        $push_query=$db->prepare("select * from user_dtl, user_type_mst where deviceid='$d_id' and user_dtl.user_type= user_type_mst.id and user_dtl.status='active'");
        $push_query->execute();
        if($data=$push_query->fetch())
        {
            do
            {
               
                if($data['name']=='Administrator')
                {
                    $admin_arr[]= $data['user_type'];
                    $exists_admin[]=$data['user_name'];
                }
                else
                {
                    $user_arr[]= $data['user_type'];
                    $exists_user[]=$data['user_name'];
                }
            }
            while($data=$push_query->fetch());
        }
        for($count = 0; $count<count($user_name); $count++)
        {
            $user_name_clean = strtoupper(trim($user_name[$count]));
            $user_mobile_clean = trim($user_mobile[$count]);
            $user_type_clean = trim($user_type[$count]);  
            if($user_name_clean=="" || $user_mobile_clean=="" || strlen($user_mobile_clean)!=10)
            {
            }
            else
            {
                $count_query=$db->prepare("select * from user_dtl where deviceid='$d_id' and status='active'");
                $count_query->execute();
                $r_count=$count_query->rowcount();
                if($r_count<5)
                {
                    if($user_type_clean=="1")
                    {
                        if((in_array($user_name_clean, $exists_admin)) || (in_array($user_type_clean, $admin_arr)))
                        {

                        }
                        else
                        {
                            $exists_admin[]=$user_name_clean;
                            $admin_arr[]=$user_type_clean;
                            $query=$db->prepare("insert into user_dtl (user_name, user_type, user_mobile,deviceid, status, created_by_date, created_by_id) values('$user_name_clean', '$user_type_clean', '$user_mobile_clean', '$d_id', '$status' , '$date', '$id')");
                            $query->execute();
                            $rowcount++;
                        }
                    }
                    else
                    {
                        if((in_array($user_name_clean, $exists_user)))
                        {
                            echo "insert into user_dtl (user_name, user_type, user_mobile,deviceid, status, created_by_date, created_by_id) values('$user_name_clean', '$user_type_clean', '$user_mobile_clean', '$d_id', '$status' , '$date', '$id')";
                        }
                        else
                        {
                            $exists_user[]=$user_name_clean;
                            $query=$db->prepare("insert into user_dtl (user_name, user_type, user_mobile,deviceid, status, created_by_date, created_by_id) values('$user_name_clean', '$user_type_clean', '$user_mobile_clean', '$d_id', '$status' , '$date', '$id')");
                            $query->execute();
                            $rowcount++;
                        }
                    }
                }
                else
                {
                    echo "2_";
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