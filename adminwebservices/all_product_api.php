<?php
    include('../connect.php');
    $deviceid=$_POST['deviceid'];
    $d_id=$_POST['d_id'];
    $token=$_POST['token'];
    $page_no=$_POST['page_no'];
    $start=(($page_no-1)*40);
    $record=40;
    $url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
    $insert_query=$db->prepare("insert into last_connect_admin(deviceid,city,state, country, ip, last_login)values('$deviceid','$city','$state','$country','$ip','$last_login') ON DUPLICATE KEY UPDATE city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login'");
    $insert_query->execute();
    $status="active";
    $query=$db->prepare("select * from mobile_token_verify where token='$token' and deviceid='$deviceid' and status='$status'");
    $query->execute();
    $token_count=$query->rowCount();
    $response='{"records":{';
    if($token_count==1)
    {
        $type_query=$db->prepare("select device_type from device where d_id='$d_id' and status='$status'");
        $type_query->execute();
        if($data=$type_query->fetch())
        {
            do
            {
                $device_type=$data['device_type'];
            }
            while($data=$type_query->fetch());
        }
        if($device_type=="Weighing")
        {
            $access_key='{"name":"default price"},';
            $customer_query=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
            $customer_query->execute();
            $customer_count=$customer_query->rowCount();
            $customer_name=array(10);
            if($sk=$customer_query->fetch())
            {
                $i=0;
                do
                {
                    $access_key.='{"name":"'.$sk['customer_name'].'"},';
                    $customer_name[$i]=$sk['customer_name'];
                    $i++;
                }
                while($sk=$customer_query->fetch());
            }
            $access_key=substr($access_key, 0,-1);
            $product=$db->prepare("select * from product,stock_mst,price_mst, category_dtl, unit_mst , tax_mst  where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and product.deviceid='$d_id' and category_dtl.category_id=product.category_id and  tax_mst.tax_id=price_mst.tax_id and unit_mst.unit_id=stock_mst.unit_id and product.status='$status' order by product.product_id asc limit $start, $record");
            $product->execute();
            if($product_data=$product->fetch())
            {
                $response.='"product":[';
                do
                {
                    $response.='{"product_id":'.$product_data['product_id'].',';
                    $response.='"english_name":"'.$product_data['english_name'].'",';
                    $response.='"regional_name":"'.$product_data['regional_name'].'",';
                    $response.='"barcode":"'.$product_data['barcode'].'",';
                    $response.='"category_name":"'.$product_data['category_name'].'",';
                    $kitchen_id=$product_data['kitchen_id'];
                    $kitchen_name="";
                    $kitchen_query=$db->prepare("select kitchen_name from kitchen_dtl where kitchen_id='$kitchen_id' and status='$status'");
                    $kitchen_query->execute();
                    if($kitchen_data=$kitchen_query->fetch())
                    {
                        do{
                            $kitchen_name=$kitchen_data['kitchen_name'];
                        }
                        while ($kitchen_data=$kitchen_query->fetch());
                    }

                    $response.='"kitchen_name":"'.$product_data['kitchen_name'].'",';
                    $response.='"discount":'.$product_data['discount'].',';
                    
                    $response.='"reorder_level":'.$product_data['reorder_level'].',';

                    $response.='"stockable":"'.$product_data['stockable'].'",';
                    $response.='"current_stock":'.$product_data['current_stock'].',';
                    $response.='"unit_name":"'.$product_data['unit_name'].'",';
                    $response.='"weighing":"'.$product_data['weighing'].'",';
                    $response.='"tax_name":"'.$product_data['tax_name'].'",';
                    $response.='"price":{';
                    $response.='"default price":'.$product_data['price1'].',';
                    for($i=0;$i<$customer_count;$i++)
                    {
                        $j=$i+2;
                        $price="price".$j;
                        $response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
                    }
                    $response=substr($response, 0,-1);
                    $response.='},';

                    $response.='"calculate_price":{';
                    $response.='"default price":'.$product_data['prices1'].',';
                    for($i=0;$i<$customer_count;$i++)
                    {
                        $j=$i+2;
                        $price="prices".$j;
                        $response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
                    }
                    $response=substr($response, 0,-1);
                    $response.='}},';


                }
                while($product_data=$product->fetch());
            }
            $response=substr($response, 0,-1);
            $response.=']';
        }
        elseif($device_type=="Non-Table")
        {
            $access_key='{"name":"default price"},';
            $customer_query=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
            $customer_query->execute();
            $customer_count=$customer_query->rowCount();
            $customer_name=array(10);
            if($sk=$customer_query->fetch())
            {

                $i=0;
                do
                {
                     $access_key.='{"name":"'.$sk['customer_name'].'"},';
                    $customer_name[$i]=$sk['customer_name'];
                    $i++;
                }
                while($sk=$customer_query->fetch());
            }
            $access_key=substr($access_key, 0,-1);
            $product=$db->prepare("select * from product,stock_mst,price_mst, category_dtl, unit_mst , tax_mst  where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and product.deviceid='$d_id' and category_dtl.category_id=product.category_id and tax_mst.tax_id=price_mst.tax_id and unit_mst.unit_id=stock_mst.unit_id and product.status='$status' order by product.product_id asc limit $start, $record");
            $product->execute();
            if($product_data=$product->fetch())
            {
                $response.='"product":[';
                do
                {
                    $response.='{"product_id":'.$product_data['product_id'].',';
                    $response.='"english_name":"'.$product_data['english_name'].'",';
                    $response.='"regional_name":"'.$product_data['regional_name'].'",';
                    $response.='"barcode":"'.$product_data['barcode'].'",';
                    $response.='"Category_name":"'.$product_data['category_name'].'",';

                    $kitchen_id=$product_data['kitchen_id'];
                    $kitchen_name="";
                    $kitchen_query=$db->prepare("select kitchen_name from kitchen_dtl where kitchen_id='$kitchen_id' and status='$status'");
                    $kitchen_query->execute();
                    if($kitchen_data=$kitchen_query->fetch())
                    {
                        do{
                            $kitchen_name=$kitchen_data['kitchen_name'];
                        }
                        while ($kitchen_data=$kitchen_query->fetch());
                    }


                    $response.='"kitchen_name":"'.$product_data['kitchen_name'].'",';
                    $response.='"discount":'.$product_data['discount'].',';

                    $response.='"reorder_level":'.$product_data['reorder_level'].',';

                    $response.='"stockable":"'.$product_data['stockable'].'",';
                    $response.='"current_stock":'.$product_data['current_stock'].',';
                    $response.='"unit_name":"'.$product_data['unit_name'].'",';
                    $response.='"tax_name":"'.$product_data['tax_name'].'",';
                    $response.='"price":{';
                    $response.='"default price":'.$product_data['price1'].',';
                    for($i=0;$i<$customer_count;$i++)
                    {
                        $j=$i+2;
                        $price="price".$j;
                        $response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
                    }
                    $response=substr($response, 0,-1);
                    $response.='},';

                    $response.='"calculate_price":{';
                    $response.='"default price":'.$product_data['prices1'].',';
                    for($i=0;$i<$customer_count;$i++)
                    {
                        $j=$i+2;
                        $price="prices".$j;
                        $response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
                    }
                    $response=substr($response, 0,-1);
                    $response.='}},';

                }
                while($product_data=$product->fetch());
            }
            $response=substr($response, 0,-1);
            $response.=']';
        }
        elseif($device_type=="Table")
        {
            $access_key='{"name":"default price"},';
            $customer_query=$db->prepare("select * from premise_dtl where deviceid='$d_id' and status='$status'");
            $customer_query->execute();
            $customer_count=$customer_query->rowCount();
            $customer_name=array(10);
            if($sk=$customer_query->fetch())
            {
                $i=0;
                do
                {
                     $access_key.='{"name":"'.$sk['premise_name'].'"},';
                    $customer_name[$i]=$sk['premise_name'];
                    $i++;
                }
                while($sk=$customer_query->fetch());
            }
            $access_key=substr($access_key, 0,-1);
            $product=$db->prepare("select * from product,stock_mst,price_mst, category_dtl, unit_mst , tax_mst  where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and product.deviceid='$d_id' and category_dtl.category_id=product.category_id  and tax_mst.tax_id=price_mst.tax_id and unit_mst.unit_id=stock_mst.unit_id and product.status='$status' order by product.product_id asc limit $start, $record");
            $product->execute();
            if($product_data=$product->fetch())
            {
                $response.='"product":[';
                do
                {
                    $response.='{"product_id":'.$product_data['product_id'].',';
                    $response.='"english_name":"'.$product_data['english_name'].'",';
                    $response.='"regional_name":"'.$product_data['regional_name'].'",';
                    $response.='"barcode":"'.$product_data['barcode'].'",';
                    $response.='"category_name":"'.$product_data['category_name'].'",';

                    $kitchen_id=$product_data['kitchen_id'];
                    $kitchen_name="";
                    $kitchen_query=$db->prepare("select kitchen_name from kitchen_dtl where kitchen_id='$kitchen_id' and status='$status'");
                    $kitchen_query->execute();
                    if($kitchen_data=$kitchen_query->fetch())
                    {
                        do{
                            $kitchen_name=$kitchen_data['kitchen_name'];
                        }
                        while ($kitchen_data=$kitchen_query->fetch());
                    }

                    $response.='"kitchen_name":"'.$product_data['kitchen_name'].'",';
                    $response.='"discount":'.$product_data['discount'].',';

                    
                    $response.='"reorder_level":'.$product_data['reorder_level'].',';

                    $response.='"stockable":"'.$product_data['stockable'].'",';
                    $response.='"current_stock":'.$product_data['current_stock'].',';
                    $response.='"unit_name":"'.$product_data['unit_name'].'",';
                    $response.='"tax_name":"'.$product_data['tax_name'].'",';
                    $response.='"price":{';
                    $response.='"default price":'.$product_data['price1'].',';
                    for($i=0;$i<$customer_count;$i++)
                    {
                        $j=$i+2;
                        $price="price".$j;
                        $response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
                    }
                    $response=substr($response, 0,-1);
                    $response.='},';

                    $response.='"calculate_price":{';
                    $response.='"default price":'.$product_data['prices1'].',';
                    for($i=0;$i<$customer_count;$i++)
                    {
                        $j=$i+2;
                        $price="prices".$j;
                        $response.='"'.$customer_name[$i].' price":'.$product_data["$price"].',';
                    }
                    $response=substr($response, 0,-1);
                    $response.='}},';

                }
                while($product_data=$product->fetch());
            }
            $response=substr($response, 0,-1);
            $response.=']';
        }
        $status="active";
        $product=$db->prepare("select * from product,stock_mst,price_mst, category_dtl, unit_mst , tax_mst  where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and product.deviceid='$d_id' and category_dtl.category_id=product.category_id and tax_mst.tax_id=price_mst.tax_id and unit_mst.unit_id=stock_mst.unit_id and product.status='$status'");
        $product->execute();
        $total_record=$product->rowCount();
        $total_page=ceil($total_record/40);
        $response.=',"current_page":'.$page_no.',"total_record":'.$total_record.',"total_page":'.$total_page.'},"access_key":['.$access_key.']}';

        function json_validator($data=NULL) 
        {
            if (!empty($data)) 
            {
                @json_decode($data);
                return (json_last_error() === JSON_ERROR_NONE);
            }
            return false;
        }
        if(json_validator($response))
        {
            echo $response;
        }
        else
        {
            $responce = array('status' =>0,'message'=>'Properly add data');
            echo json_encode($responce);
            echo $response;
        }
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }
?>
