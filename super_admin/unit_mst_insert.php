<?php
    include('../connect.php');
    if(isset($_POST["unit_name"]))
    {
        $status="active";
        $date=date('Y-m-d');
        $id=$_POST['id'];
        $unit_name = $_POST["unit_name"];
        $abbrevation = $_POST["abbrevation"];
        $exists_arr= array();
        $exists_abbr= array();
        $push_query=$db->prepare("select * from unit_mst where status='active'");
        $push_query->execute();
        if($data=$push_query->fetch())
        {
            do
            {
               $exists_arr[]=$data['unit_name'];
               $exists_abbr[]=$data['abbrevation'];
            }
            while($data=$push_query->fetch());
        }

        for($count = 0; $count<count($unit_name); $count++)
        {
            $unit_name_clean = $unit_name[$count];
            $abbrevation_clean = $abbrevation[$count];
            if($unit_name_clean=="")
            {
            }
            else
            {
                if((in_array($unit_name_clean, $exists_arr))||(in_array($abbrevation_clean, $exists_abbr)))
                {
                }
                else
                {
                    $exists_arr[]=$unit_name_clean;
                    $exists_abbr[]=$abbrevation_clean;
                    $query=$db->prepare("insert into unit_mst(unit_name, abbrevation, status, created_by_date, created_by_id) values('$unit_name_clean','$abbrevation_clean', '$status' , '$date', '$id')");
                    $query->execute();
                    $sk=$query->rowCount();
                }
            }
       }
    }
?>