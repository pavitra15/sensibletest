<?php
    session_start();
    include ('../connect.php');
    require_once('../array_to_json.php');

    $d_id=$_POST['d_id'];
    $page_no=$_POST['page_no'];
    $next=$page_no+1;

    $status='active';
    $limit=50;
    $start_from = ($page_no-1) * $limit;
    $product_query=$db->prepare("select english_name, regional_name, weighing, category_name, discount, current_stock, stockable, reorder_level, tax_name, price1, unit_name from product,price_mst, stock_mst, category_dtl, tax_mst, unit_mst where product.status='$status' and category_dtl.category_id=product.category_id and unit_mst.unit_id=stock_mst.unit_id and tax_mst.tax_id=price_mst.tax_id and product.deviceid='$d_id' and product.product_id= price_mst.product_id and product.product_id=stock_mst.product_id LIMIT $start_from, $limit");
    $product_query->execute();
    $data=$product_query->fetchAll(PDO::FETCH_ASSOC);
    $data_array = array('page'=>$next,'data'=>$data);
    $response=array_to_json($data_array);
    $status='active';
   
    echo $response; 
?>
