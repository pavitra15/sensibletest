<?php
  session_start();
  include('../connect.php');
    $query=$db->prepare("select login_mst.id, user_mst.first_name, user_mst.last_name, login_mst.username,login_mst.password, login_mst.type, login_mst.status from login_mst,user_mst where user_mst.id=login_mst.id");
  $query->execute();
  $count=$query->rowCount();
  if($count==0)
  {
       
  }
  else
  {
      $Product_query=$db->prepare("select login_mst.id, user_mst.first_name, user_mst.last_name, login_mst.username,login_mst.password,login_mst.type, login_mst.status from login_mst,user_mst where user_mst.id=login_mst.id");
        $Product_query->execute();
        if($product_data=$Product_query->fetch())
        { 
          do
            {
              $data[] = $product_data;
            }
            while($product_data=$Product_query->fetch());
      }                
  }
  $json_data = array(
      "draw"            => 1,   
      "recordsTotal"    => intval( $count ),  
      "recordsFiltered" => intval($count),
      "data"            => $data   
      );
  echo json_encode($json_data);