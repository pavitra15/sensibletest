<?php
    include('../connect.php');
    if(isset($_POST["language_name"]))
    {
        $status="active";
        $date=date('Y-m-d');
        $id=$_POST['id'];
        $language_name = $_POST["language_name"];
        $exists_arr= array();
        $push_query=$db->prepare("select * from language_mst where status='active'");
        $push_query->execute();
        if($data=$push_query->fetch())
        {
            do
            {
               $exists_arr[]=$data['language_name'];
            }
            while($data=$push_query->fetch());
        }

        for($count = 0; $count<count($language_name); $count++)
        {
            $language_name_clean = $language_name[$count];
            if($language_name_clean=="")
            {
            }
            else
            {
                if((in_array($language_name_clean, $exists_arr)))
                {
                }
                else
                {
                    $exists_arr[]=$language_name_clean;
                    $query=$db->prepare("insert into language_mst(language_name, status, created_by_date, created_by_id) values('$language_name_clean', '$status' , '$date', '$id')");
                    echo "insert into language_mst(language_name, status, created_by_date, created_by_id) values('$language_name_clean', '$status' , '$date', '$id')";
                    $query->execute();
                }
            }
       }
    }
?>