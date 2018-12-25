<?php    
    session_start();
    include('../../connect.php');
    $d_id=$_SESSION['d_id'];
    $start=$_POST['start'];
    $limit=$_POST['limit'];
    // $start=0;
    // $limit=20;
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

    $category_query=$db->prepare("Select category_id, category_name from category_dtl where deviceid='$d_id' and status='$status'");
    $category_query->execute();
    $category="";
    while($category_data=$category_query->fetch())
    {
        $category.="<option value=".$category_data['category_id'].">".$category_data['category_name']."</option>";
    }

    $unit_query=$db->prepare("Select unit_id, abbrevation from unit_mst where status='$status'");
    $unit_query->execute();
    $unit="";
    while($unit_data=$unit_query->fetch())
    {
        $unit.="<option value=".$unit_data['unit_id'].">".$unit_data['abbrevation']."</option>";
    }

    $tax_query=$db->prepare("Select tax_id, tax_name from tax_mst where status='$status'");
    $tax_query->execute();
    $tax="";
    while($tax_data=$tax_query->fetch())
    {
        $tax.="<option value=".$tax_data['tax_id'].">".$tax_data['tax_name']."</option>";
    }

    $yes_no="<option value='Yes'>Yes</option><option value='No'>No</option>";
    
    if($_SESSION['device_type']=="Weighing")
    {
        $customer_query=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
        $customer_query->execute();
        $customer_count=$customer_query->rowCount();
        $k=$customer_count+1;
        $customer_name=array(10);
        if($sk=$customer_query->fetch())
        {
            $i=0;
            do
            {
                $customer_name[$i]=$sk['customer_name'];
                $i++;
            }
            while($sk=$customer_query->fetch());
        }
    
        $product=$db->prepare("select * from product,stock_mst,price_mst where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and  product.status='$status' and product.deviceid='$d_id' order by product.product_id asc LIMIT $start, $limit");
        $product->execute();    
        if($product_data=$product->fetch())
        {
            do
            {
                echo'<tr id="'.$product_data['product_id'].'" class="edit_tr">
                    <td><input type="text" id="english_'.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data['english_name'].'" maxlength="50"> </td>
                    <td><input type="text" id="english_'.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data['regional_name'].'" maxlength="50"> </td>';
                    $category_id=$product_data['category_id'];
                    $category_query=$db->prepare("select category_name from category_dtl where category_id='$category_id' and status='$status'");
                    $category_query->execute();

                    $category_name="";
                    while($category_data=$category_query->fetch())
                    {
                        $category_name=$category_data['category_name'];
                    }
                    
                    echo'<td><select class="form-control type" id="category'.$product_data['product_id'].'">
                            <option value="'.$category_id.'">'.$category_name.'</option>'.$category.'</select></td>';
    
                    echo'<td><select class="form-control type" id="weghtable'.$product_data['product_id'].'">
                            <option value="'.$product_data['weighing'].'">'.$product_data['weighing'].'</option>'.$yes_no.'</select></td>
                    <td><input type="text" id="english_'.$product_data['discount'].'" class="test-input cat search-box" value="'.$product_data['discount'].'" maxlength="50"> </td>';
                    
                    $bucket=$product_data['bucket_id'];
                    $img_url="";
                    $img_query=$db->prepare("select product_id, bucket from product_mst where product_id='$bucket'");
                    $img_query->execute();
                    while($img_data=$img_query->fetch())
                    {
                        $img_url=$img_data['bucket'];
                    }
                    echo'<td><input type="text" id="english_'.$product_data['reorder_level'].'" class="test-input cat search-box" value="'.$product_data['reorder_level'].'" maxlength="50"> </td>
                    <td><input type="text" id="english_'.$product_data['current_stock'].'" class="test-input cat search-box" value="'.$product_data['current_stock'].'" maxlength="50"> </td>
                    <td><select class="form-control type" id="stockable'.$product_data['product_id'].'">
                            <option value="'.$product_data['stockable'].'">'.$product_data['stockable'].'</option>'.$yes_no.'</select></td>';
                   
                    $unit_id=$product_data['unit_id'];
                    $unit_query=$db->prepare("select abbrevation from unit_mst where unit_id='$unit_id' and status='$status'");
                    $unit_query->execute();
                    $unit_name="";
                    while($unit_data=$unit_query->fetch())
                    {
                        $unit_name=$unit_data['abbrevation'];
                    }

                    echo'<td><select class="form-control type" id="unit'.$product_data['product_id'].'">
                            <option value="'.$product_data['unit_id'].'">'.$unit_name.'</option>'.$unit.'</select></td>';
                    
                    $tax_id=$product_data['tax_id'];
                    $tax_query=$db->prepare("select tax_name from tax_mst where tax_id='$tax_id' and status='$status'");
                    $tax_query->execute();
                    $tax_name="";
                    while($tax_data=$tax_query->fetch())
                    {
                        $tax_name=$tax_data['tax_name'];
                    }

                    echo'<td><select class="form-control type" id="tax'.$product_data['product_id'].'">
                            <option value="'.$product_data['tax_id'].'">'.$tax_name.'</option>'.$tax.'</select></td>
                    <td><input type="text" id="price1'.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data['price1'].'" maxlength="50"><input type="text" id="prices1'.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data['prices1'].'" maxlength="50" readonly> </td>';
                    for($i=0;$i<$customer_count;$i++)
                    {
                        $j=$i+2;
                        $price="price".$j;
                        $prices="prices".$j;
                        echo'<td><input type="text" id="'.$price.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data["$price"].'"><input type="text" id="'.$prices.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data["$prices"].'" readonly> </td>';
                    }
                echo'<td id="count" style="display:none;">'.$k.'</td></tr>';

            }
            while($product_data=$product->fetch());
            echo'</table>';
        }
    }
    elseif ($_SESSION['device_type']=="Table") 
    {
        $customer_query=$db->prepare("select * from premise_dtl where deviceid='$d_id' and status='$status'");
        $customer_query->execute();
        $customer_count=$customer_query->rowCount();
        $k=$customer_count+1;
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

            $kitchen_query=$db->prepare("Select kitchen_id, kitchen_name from kitchen_dtl where deviceid='$d_id' and status='$status'");
            $kitchen_query->execute();
            $kitchen="";
            while($kitchen_data=$kitchen_query->fetch())
            {
                $kitchen.="<option value=".$kitchen_data['kitchen_id'].">".$kitchen_data['kitchen_name']."</option>";
            }

            
            $product=$db->prepare("select * from product,stock_mst,price_mst where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and  product.status='$status'  and product.deviceid='$d_id' order by product.product_id asc LIMIT $start, $limit");
            $product->execute();
            if($product_data=$product->fetch())
            {
                do
                {

                    echo'<tr id="'.$product_data['product_id'].'" class="edit_tr">
                    <td><input type="text" id="english_'.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data['english_name'].'" maxlength="50"> </td>
                    <td><input type="text" id="english_'.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data['product_id'].'" maxlength="50"> </td>';
                    $category_id=$product_data['category_id'];
                    $category_query=$db->prepare("select category_name from category_dtl where category_id='$category_id' and status='$status'");
                    $category_query->execute();

                    $category_name="";
                    while($category_data=$category_query->fetch())
                    {
                        $category_name=$category_data['category_name'];
                    }
                    
                    echo'<td><select class="form-control type" id="category'.$product_data['product_id'].'">
                            <option value="'.$category_id.'">'.$category_name.'</option>'.$category.'</select></td>';

                    $kitchen_name="";
                    $kitchen_id=$product_data['kitchen_id'];
                    $kitchen_query=$db->prepare("select kitchen_name from kitchen_dtl where kitchen_id=$kitchen_id and status='$status'");
                    $kitchen_query->execute();
                    while($kitchen_data=$kitchen_query->fetch())
                    {
                        $kitchen_name=$kitchen_data['kitchen_name'];
                    }
                    
                    echo'<td><select class="form-control type" id="kitchen'.$product_data['product_id'].'">
                            <option value="'.$kitchen_id.'">'.$kitchen_name.'</option>'.$kitchen.'</select></td>
                    <td><input type="text" id="english_'.$product_data['discount'].'" class="test-input cat search-box" value="'.$product_data['discount'].'" maxlength="50"> </td>';
                    
                    $bucket=$product_data['bucket_id'];
                    $img_url="";
                    $img_query=$db->prepare("select product_id, bucket from product_mst where product_id='$bucket'");
                    $img_query->execute();
                    while($img_data=$img_query->fetch())
                    {
                        $img_url=$img_data['bucket'];
                    }
                    echo'<td><input type="text" id="english_'.$product_data['reorder_level'].'" class="test-input cat search-box" value="'.$product_data['reorder_level'].'" maxlength="50"> </td>
                    <td><input type="text" id="english_'.$product_data['current_stock'].'" class="test-input cat search-box" value="'.$product_data['current_stock'].'" maxlength="50"> </td>
                    <td><select class="form-control type" id="stockable'.$product_data['product_id'].'">
                            <option value="'.$product_data['stockable'].'">'.$product_data['stockable'].'</option>'.$yes_no.'</select></td>';
                   
                    $unit_id=$product_data['unit_id'];
                    $unit_query=$db->prepare("select abbrevation from unit_mst where unit_id='$unit_id' and status='$status'");
                    $unit_query->execute();
                    $unit_name="";
                    while($unit_data=$unit_query->fetch())
                    {
                        $unit_name=$unit_data['abbrevation'];
                    }

                    echo'<td><select class="form-control type" id="unit'.$product_data['product_id'].'">
                            <option value="'.$product_data['unit_id'].'">'.$unit_name.'</option>'.$unit.'</select></td>';
                    
                    $tax_id=$product_data['tax_id'];
                    $tax_query=$db->prepare("select tax_name from tax_mst where tax_id='$tax_id' and status='$status'");
                    $tax_query->execute();
                    $tax_name="";
                    while($tax_data=$tax_query->fetch())
                    {
                        $tax_name=$tax_data['tax_name'];
                    }

                    echo'<td><select class="form-control type" id="tax'.$product_data['product_id'].'">
                            <option value="'.$product_data['tax_id'].'">'.$tax_name.'</option>'.$tax.'</select></td>
                    <td><input type="text" id="price1'.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data['price1'].'" maxlength="50"><input type="text" id="prices1'.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data['prices1'].'" maxlength="50" readonly> </td>';
                    for($i=0;$i<$customer_count;$i++)
                    {
                        $j=$i+2;
                        $price="price".$j;
                        $prices="prices".$j;
                        echo'<td><input type="text" id="'.$price.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data["$price"].'"><input type="text" id="'.$prices.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data["$prices"].'" readonly> </td>';
                    }
                echo'<td id="count" style="display:none;">'.$k.'</td></tr>';

                }
                while($product_data=$product->fetch());
                echo'</table>';
            }
    }
    else
    {
        $customer_query=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
        $customer_query->execute();
        $customer_count=$customer_query->rowCount();
        $k=$customer_count+1;
        $customer_name=array(10);
        if($sk=$customer_query->fetch())
        {
            $i=0;
            do
            {
                $customer_name[$i]=$sk['customer_name'];
                $i++;
            }
            while($sk=$customer_query->fetch());
        }
    
        $product=$db->prepare("select * from product,stock_mst,price_mst where stock_mst.product_id=product.product_id and price_mst.product_id=product.product_id and  product.status='$status'  and product.deviceid='$d_id' order by product.product_id asc LIMIT $start, $limit");
        $product->execute();    
        if($product_data=$product->fetch())
        {
            do
            {
                echo'<tr id="'.$product_data['product_id'].'" class="edit_tr">
                    <td><input type="text" id="english_'.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data['english_name'].'" maxlength="50"> </td>
                    <td><input type="text" id="english_'.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data['regional_name'].'" maxlength="50"> </td>';
                    $category_id=$product_data['category_id'];
                    $category_query=$db->prepare("select category_name from category_dtl where category_id='$category_id' and status='$status'");
                    $category_query->execute();

                    $category_name="";
                    while($category_data=$category_query->fetch())
                    {
                        $category_name=$category_data['category_name'];
                    }
                    
                    echo'<td><select class="form-control type" id="category'.$product_data['product_id'].'">
                            <option value="'.$category_id.'">'.$category_name.'</option>'.$category.'</select></td>';
    
                    echo'<td><input type="text" id="english_'.$product_data['discount'].'" class="test-input cat search-box" value="'.$product_data['discount'].'" maxlength="50"> </td>';
                    
                    $bucket=$product_data['bucket_id'];
                    $img_url="";
                    $img_query=$db->prepare("select product_id, bucket from product_mst where product_id='$bucket'");
                    $img_query->execute();
                    while($img_data=$img_query->fetch())
                    {
                        $img_url=$img_data['bucket'];
                    }
                    echo'<td><input type="text" id="english_'.$product_data['reorder_level'].'" class="test-input cat search-box" value="'.$product_data['reorder_level'].'" maxlength="50"> </td>
                    <td><input type="text" id="english_'.$product_data['current_stock'].'" class="test-input cat search-box" value="'.$product_data['current_stock'].'" maxlength="50"> </td>
                    <td><select class="form-control type" id="stockable'.$product_data['product_id'].'">
                            <option value="'.$product_data['stockable'].'">'.$product_data['stockable'].'</option>'.$yes_no.'</select></td>';
                   
                    $unit_id=$product_data['unit_id'];
                    $unit_query=$db->prepare("select abbrevation from unit_mst where unit_id='$unit_id' and status='$status'");
                    $unit_query->execute();
                    $unit_name="";
                    while($unit_data=$unit_query->fetch())
                    {
                        $unit_name=$unit_data['abbrevation'];
                    }

                    echo'<td><select class="form-control type" id="unit'.$product_data['product_id'].'">
                            <option value="'.$product_data['unit_id'].'">'.$unit_name.'</option>'.$unit.'</select></td>';
                    
                    $tax_id=$product_data['tax_id'];
                    $tax_query=$db->prepare("select tax_name from tax_mst where tax_id='$tax_id' and status='$status'");
                    $tax_query->execute();
                    $tax_name="";
                    while($tax_data=$tax_query->fetch())
                    {
                        $tax_name=$tax_data['tax_name'];
                    }

                    echo'<td><select class="form-control type" id="tax'.$product_data['product_id'].'">
                            <option value="'.$product_data['tax_id'].'">'.$tax_name.'</option>'.$tax.'</select></td>
                    <td><input type="text" id="price1'.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data['price1'].'" maxlength="50"><input type="text" id="prices1'.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data['prices1'].'" maxlength="50" readonly> </td>';
                    for($i=0;$i<$customer_count;$i++)
                    {
                        $j=$i+2;
                        $price="price".$j;
                        $prices="prices".$j;
                        echo'<td><input type="text" id="'.$price.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data["$price"].'"><input type="text" id="'.$prices.$product_data['product_id'].'" class="test-input cat search-box" value="'.$product_data["$prices"].'" readonly> </td>';
                    }
                    echo'<td id="count" style="display:none;">'.$k.'</td></tr>';

            }
            while($product_data=$product->fetch());
            echo'</table>';
        }
    }
?>