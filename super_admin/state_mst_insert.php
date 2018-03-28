<?php
    include('../connect.php');
    if(isset($_POST["state_name"]))
    {
        $status="active";
        $date=date('Y-m-d');
        $id=$_POST['id'];
        $state_name = $_POST["state_name"];
        $exists_arr= array();
        $push_query=$db->prepare("select * from state_mst where status='active'");
        $push_query->execute();
        if($data=$push_query->fetch())
        {
            do
            {
               $exists_arr[]=$data['state_name'];
            }
            while($data=$push_query->fetch());
        }

        for($count = 0; $count<count($state_name); $count++)
        {
            $state_name_clean = $state_name[$count];
            if($state_name_clean=="")
            {
            }
            else
            {
                if((in_array($state_name_clean, $exists_arr)))
                {
                }
                else
                {
                    $exists_arr[]=$state_name_clean;
                    $query=$db->prepare("insert into state_mst(state_name, status, created_by_date, created_by_id) values('$state_name_clean', '$status' , '$date', '$id')");
                    $query->execute();
                }
            }
       }
    }
?>