<?php
    include('../connect.php');
    $date =  new DateTime();
    $clone_date=clone $date;
    $updated_date=$clone_date->format('Y-m-d h:m:s');
    if(is_ajax())
    {
        $rowcount=0;
        $status="active";
        $d_id=$_POST['d_id'];
        $date=date('Y-m-d');
        $id=$_POST['id'];
        $product_type=$_SESSION['device_type'];
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

        $english = $_POST["english"];
        $regional = $_POST["regional"];
        if(isset($_POST["weightable"]))
        {
            $weightable = $_POST["weightable"];
        }
        else
        {
            $size=count($english);
            $weightable=new SplFixedArray($size);
        }

        if(isset($_POST["kitchen"]))
        {
            $kitchen = $_POST["kitchen"];
        }
        else
        {
            $size=count($english);
            $kitchen=new SplFixedArray($size);
        }

        if(isset($_POST["discount"]))
        {
            $discount = $_POST["discount"];
        }
        else
        {
            $size=count($english);
            $discount=new SplFixedArray($size);
        }

        if(isset($_POST["bucket"]))
        {
            $bucket = $_POST["bucket"];
        }
        else
        {
            $size=count($english);
            $bucket=new SplFixedArray($size);
        }

        if(isset($_POST["comission"]))
        {
            $comission = $_POST["comission"];
        }
        else
        {
            $size=count($english);
            $comission=new SplFixedArray($size);
        }

        $category = $_POST["category"];
        for($count = 0; $count<count($english); $count++)
        {
            $english_clean = strtoupper(trim($english[$count]));
            $regional_clean = $regional[$count];
            if($weightable[$count]=="")
            {
                $weightable_clean="No";
            }
            else
            {
                $weightable_clean=$weightable[$count];
            }

            if($kitchen[$count]=="")
            {
                $kitchen_clean=0;
            }
            else
            {
                $kitchen_clean=$kitchen[$count];
            }



            if($bucket[$count]=="")
            {
                $bucket_clean=0;
            }
            else
            {
                $bucket_clean=$bucket[$count];
            }

            if($comission[$count]=="")
            {
                $comission_clean=0;
            }
            else
            {
                if($comission[$count]>100)
                {
                    $comission_clean=0;
                }
                else
                {
                    $comission_clean=$comission[$count];
                }
            }


            if($discount[$count]=="")
            {
                $discount_clean=0;
            }
            else
            {
                if($discount[$count]>100)
                {
                    $discount_clean=0;
                }
                else
                {
                    $discount_clean=$discount[$count];
                }
            }

            $category_clean = $category[$count];
            if($english_clean=="" || $category_clean=="")
            {
            }
            else
            {
                if(in_array($english_clean, $exists_arr))
                {

                }
                else
                {
                    $exists_arr[]=$english_clean;
                    try
                    {
                        $db->beginTransaction();
                        $query=$db->prepare("insert into product (english_name, regional_name, weighing,category_id, discount, kitchen_id,comission, bucket_id, deviceid, product_type, status, created_by_date, created_by_id) values('$english_clean', '$regional_clean', '$weightable_clean', $category_clean, '$discount_clean', '$kitchen_clean', '$comission_clean',  $bucket_clean, '$d_id','$product_type', '$status' , '$date', '$id')");
                        $query->execute();
                        $product_id=$db->lastInsertId();
                        $stock_query=$db->prepare("insert into stock_mst(product_id,unit_id,stockable,current_stock,updated_date)values($product_id,'3','No',0,'$updated_date')");
                        $stock_query->execute();
                        $price_query=$db->prepare("insert into price_mst(product_id,tax_id,price1, price2, price3, price4, price5, price6, price7, price8, price9, prices1, prices2, prices3, prices4, prices5, prices6, prices7,prices8, prices9) values('$product_id',1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)");
                        $price_query->execute();
                        $db->commit();
                        $rowcount++;
                        
                    }
                    catch (Exception $e) 
                    {
                        $db->rollBack();
                        echo $e;
                    }
                }   
            }
        }
        echo "1_".$rowcount;
    }
    else 
    {
        echo "something wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
  ?>