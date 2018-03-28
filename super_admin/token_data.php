<?php
  session_start();
  include('../connect.php');
    $query=$db->prepare("select token_verify.token_id,token_verify.deviceid, device.serial_no, device.device_name,token_verify.token FROM token_verify,device where token_verify.deviceid=device.deviceid");
  $query->execute();
  $count=$query->rowCount();
  if($count==0)
  {
       
  }
  else
  {
      $Product_query=$db->prepare("select token_verify.token_id,token_verify.deviceid, device.serial_no, device.device_name,token_verify.token FROM token_verify,device where token_verify.deviceid=device.deviceid");
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