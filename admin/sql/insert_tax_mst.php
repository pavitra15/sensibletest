<?php
    include('../../connect.php');
    if(is_ajax())
    {
        $status="active";
        $date=date('Y-m-d');
        $id=$_POST['id'];
        $tax_name = $_POST["tax_name"];
        $tax_type = $_POST["tax_type"];
        $perc = $_POST["tax_perc"];
        $exists_arr= array();
        $exists_perc= array();
        $exists_type= array();
        $push_query=$db->prepare("select * from tax_mst where status='active'");
        $push_query->execute();
        if($data=$push_query->fetch())
        {
            do
            {
               $exists_arr[]=$data['tax_name'];
               $exists_perc[]=$data['tax_perc'];
               $exists_type[]=$data['tax_type'];
            }
            while($data=$push_query->fetch());
        }

        for($count = 0; $count<count($tax_name); $count++)
        {
            $tax_name_clean = $tax_name[$count];
            $tax_type_clean = $tax_type[$count];
            $perc_clean = $perc[$count];
            if($tax_name_clean=="")
            {
            }
            else
            {
                if(((in_array($tax_name_clean, $exists_type))||(in_array($tax_type_clean, $exists_arr))||(in_array($perc_clean, $exists_arr)))||((in_array($perc_clean, $exists_arr))&&(in_array($tax_type_clean, $exists_arr))))
                {
                }
                else
                {
                    $exists_arr[]=$tax_name_clean;
                    $exists_perc[]=$perc_clean;
                    $exists_type[]=$tax_type_clean;
                    $query=$db->prepare("insert into tax_mst (tax_type, tax_name, tax_perc , status, created_by_date, created_by_id) values('$tax_type_clean', '$tax_name_clean', '$perc_clean', '$status' , '$date', '$id')");
                    $query->execute();
                    $sk=$query->rowCount();
                }
            }
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