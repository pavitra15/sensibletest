<?php
    include("../connect.php");
    if(is_ajax())
    {
        $date=date('Y-m-d');
        $id=$_POST['id'];
        $d_id=$_POST['d_id'];
        $product_id=$_POST['product_id'];
        $english=strtoupper(trim($_POST['english']));
        $regional=trim($_POST['regional']);
        $weightable=$_POST['weightable'];
        $kitchen=$_POST['kitchen'];
        $category=$_POST['category'];
        $comission=$_POST['comission'];
        $discount=$_POST['discount'];
        $exists_arr= array();
        $push_query=$db->prepare("select * from product where deviceid='$d_id' and status='active'");
        $push_query->execute();
        if($data=$push_query->fetch())
        {
            do
            {
                $exists_arr[]=$data['english_name'];
            }
            while($data=$push_query->fetch());
        }
        if($category=="")
        {
            $category=0;
        }
        if($kitchen=="")
        {
            $kitchen=0;
        }
        if($english=="" && $regional=="")
        {

        }
        else
        {
            if(in_array($english, $exists_arr))
            {
                $count_query=$db->prepare("select english_name from product where product_id='$product_id'");
                $count_query->execute();
                if($count_data=$count_query->fetch())
                {
                    do
                    {
                        $count=$count_data['english_name'];
                    }
                    while ($count_data=$count_query->fetch());
                }
                if($count==$english)
                {
                    $query=$db->prepare("update product set english_name='$english', regional_name='$regional', weighing='$weightable', category_id='$category', discount='$discount', kitchen_id='$kitchen', comission='$comission', updated_by_date='$date', updated_by_id='$id' where product_id='$product_id'");
                    $query->execute();
                    echo "1_";
                }
                else
                {
                    $select_query=$db->prepare("select english_name, regional_name from product where product_id='$product_id'");
                    $select_query->execute();
                    if($select_data=$select_query->fetch())
                    {
                        do
                        {
                            $name=$select_data['english_name'];
                            $mobile=$select_data['regional_name'];
                        }
                        while($select_data=$select_query->fetch());
                    }
                    echo "2_".$name."_".$mobile;            
                }
            }
            else
            {
                $query=$db->prepare("update product set english_name='$english', regional_name='$regional', weighing='$weightable', category_id='$category', discount='$discount', kitchen_id='$kitchen', comission='$comission', updated_by_date='$date', updated_by_id='$id' where product_id='$product_id'");
                $query->execute();
                echo "1_";
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