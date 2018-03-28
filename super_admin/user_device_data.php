<?php
    include('../connect.php');
    $Product_query=$db->prepare("select first_name,last_name, email, mobile, city, state,pincode, count(*) total, sum(case when access_control = 'active' then 1 else 0 end) active_cnt, sum(case when access_control = 'denied' then 1 else 0 end) deactive_cnt from device,user_mst where user_mst.id= device.id group by user_mst.id");
    $Product_query->execute();
    if($product_data=$Product_query->fetch())
    { 
        do
        {
            $data[] = $product_data;
        }
        while($product_data=$Product_query->fetch());
    }                
    $json_data = array(
      "draw"            => 1,   
      "recordsTotal"    => intval( $count ),  
      "recordsFiltered" => intval($count),
      "data"            => $data   
      );
    echo json_encode($json_data);