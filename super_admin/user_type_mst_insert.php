<?php
    include('../connect.php');
    if(isset($_POST["name"]))
    {
        $status="active";
        $date=date('Y-m-d');
        $id=$_POST['id'];
        $name = $_POST["name"];
        $exists_arr= array();
        $push_query=$db->prepare("select * from user_type_mst where status='active'");
        $push_query->execute();
        if($data=$push_query->fetch())
        {
            do
            {
               $exists_arr[]=$data['name'];
            }
            while($data=$push_query->fetch());
        }

        for($count = 0; $count<count($name); $count++)
        {
            $name_clean = $name[$count];
            if($name_clean=="")
            {
            }
            else
            {
                if((in_array($name_clean, $exists_arr)))
                {
                }
                else
                {
                    $exists_arr[]=$name_clean;
                    $query=$db->prepare("insert into user_type_mst(name, status, created_by_date, created_by_id) values('$name_clean', '$status' , '$date', '$id')");
                    $query->execute();
                }
            }
       }
    }
?>