<?php    
    session_start();
    include('../connect.php');
    $d_id=$_SESSION['d_id'];
    $status="active";
    $name_query=$db->prepare("select device_name,tax_type,prnt_billno, prnt_billtime, model from device where d_id='$d_id'");
    $name_query->execute();
    while ($name_data=$name_query->fetch()) 
    {
        $device_name=$name_data['device_name'];
        $device_model=$name_data['model'];
        $tax_type=$name_data['tax_type'];
        $prnt_billno=$name_data['prnt_billno'];
        $prnt_billtime=$name_data['prnt_billtime'];
    }
    
    if($_SESSION['device_type']=="Weighing")
    {

    }
    elseif ($_SESSION['device_type']=="Table") 
    {
        $customer_query=$db->prepare("select * from premise_dtl where deviceid='$d_id' and status='$status'");
            $customer_query->execute();
            $customer_count=$customer_query->rowCount();
            $customer_name=array(10);
            if($sk=$customer_query->fetch())
            {
                $i=0;
                do
                {
                    $customer_name[$i]=$sk['premise_name'];
                    $i++;
                }
                while($sk=$customer_query->fetch());
            }

            $product=$db->prepare("select * from product,stock_mst,price_mst where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and  product.status='$status' and product.deviceid='$d_id' order by product.product_id asc");
            $product->execute();
            if($product_data=$product->fetch())
            {
                $response='[';
                do
                {
                    $response.='{"english_name":"'.$product_data['english_name'].'",';
                    $response.='"regional_name":"'.$product_data['regional_name'].'",';
                    $response.='"barcode":"'.$product_data['barcode'].'",';
                    $category_id=$product_data['category_id'];
                    $category_query=$db->prepare("select category_name from category_dtl where category_id='$category_id' and status='$status'");
                    $category_query->execute();
                    $category_name="";
                    while($category_data=$category_query->fetch())
                    {
                        $category_name=$category_data['category_name'];
                    }
            
                    $response.='"category_name":"'.$category_name.'",';
                    $kitchen_name="";
                    $kitchen_id=$product_data['kitchen_id'];
                    $kitchen_query=$db->prepare("select kitchen_name from kitchen_dtl where kitchen_id='$kitchen_id' and status='$status'");
                    $kitchen_query->execute();
                    while($kitchen_data=$kitchen_query->fetch())
                    {
                        $kitchen_name=$kitchen_data['kitchen_name'];
                    }
            
                    $response.='"kitchen_name":"'.$kitchen_name.'",';
                    $response.='"discount":'.$product_data['discount'].',';
                    
                    $bucket=$product_data['bucket_id'];
                    $img_url="";
                    $img_query=$db->prepare("select product_id, bucket from product_mst where product_id='$bucket'");
                    $img_query->execute();
                    while($img_data=$img_query->fetch())
                    {
                        $img_url=$img_data['bucket'];
                    }
                    $response.='"reorder_level":'.$product_data['reorder_level'].',';

                    $response.='"stockable":"'.$product_data['stockable'].'",';
                    $response.='"current_stock":'.$product_data['current_stock'].',';

                    $unit_id=$product_data['unit_id'];
                    $unit_query=$db->prepare("select unit_name from unit_mst where unit_id='$unit_id' and status='$status'");
                    $unit_query->execute();
                    $unit_name="";
                    while($unit_data=$unit_query->fetch())
                    {
                        $unit_name=$unit_data['unit_name'];
                    }

                    $response.='"unit_name":"'.$unit_name.'",';
                    
                    $tax_id=$product_data['tax_id'];
                    $tax_query=$db->prepare("select tax_name from tax_mst where tax_id='$tax_id' and status='$status'");
                    $tax_query->execute();
                    $tax_name="";
                    while($tax_data=$tax_query->fetch())
                    {
                        $tax_name=$tax_data['tax_name'];
                    }

                    $response.='"tax_name":"'.$tax_name.'",';
                    
                    $response.='"parcel price":'.$product_data['price1'].',';
                    for($i=0;$i<$customer_count;$i++)
                    {
                        $j=$i+2;
                        $price="price".$j;
                        $response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
                    }
                    $response=substr($response, 0,-1);
                    
                    $response.='},';


                }
                while($product_data=$product->fetch());
            }
            $response=substr($response, 0,-1);
            $response.=']';
    }
    else
    {
    
    }
    echo $response;
    // echo '{"sachin":"sachin"}';
?>