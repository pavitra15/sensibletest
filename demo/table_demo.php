<?php
	session_start();
	include('../connect.php');
	$device_type=$_POST['device_type'];
	$d_id=$_POST['d_id'];
	$id=$_SESSION['login_id'];
    $date=date('Y-m-d');
	$query=$db->prepare("update device set device_type='$device_type', updated_by_id='$id', updated_date='$date' where d_id='$d_id'");
    $query->execute();

    if($device_type=="Table")
    {
    	$premise_query=$db->prepare("update premise_dtl set status='active' where deviceid='$d_id'");
    	$premise_query->execute();

    	$customer_query=$db->prepare("update customer_dtl set status='delete' where deviceid='$d_id'");
    	$customer_query->execute();

    	$category_query=$db->prepare("update category_dtl set status='delete' where deviceid='$d_id'");
    	$category_query->execute();

    	$update_category_query=$db->prepare("update category_dtl set status='active' where deviceid='$d_id' and category_type='Table'");
    	$update_category_query->execute();

    	$product_query=$db->prepare("update product set status='delete' where deviceid='$d_id'");
    	$product_query->execute();

    	$update_product_query=$db->prepare("update product set status='active' where deviceid='$d_id' and product_type='Table'");
    	$update_product_query->execute();

    	$_SESSION['device_type']="Table";
    }
    elseif($device_type=="Non-Table")
    {
    	$customer_query=$db->prepare("update customer_dtl set status='active' where deviceid='$d_id'");
    	$customer_query->execute();

    	$premise_query=$db->prepare("update premise_dtl set status='delete' where deviceid='$d_id'");
    	$premise_query->execute();

    	$category_query=$db->prepare("update category_dtl set status='delete' where deviceid='$d_id'");
    	$category_query->execute();

    	$update_category_query=$db->prepare("update category_dtl set status='active' where deviceid='$d_id' and category_type='Non-Table'");
    	$update_category_query->execute();

    	$product_query=$db->prepare("update product set status='delete' where deviceid='$d_id'");
    	$product_query->execute();

    	$update_product_query=$db->prepare("update product set status='active' where deviceid='$d_id' and product_type='Non-Table'");
    	$update_product_query->execute();

    	$_SESSION['device_type']="Non-Table";
    }
    elseif($device_type=="Weighing")
    {
    	$customer_query=$db->prepare("update customer_dtl set status='active' where deviceid='$d_id'");
    	$customer_query->execute();

    	$premise_query=$db->prepare("update premise_dtl set status='delete' where deviceid='$d_id'");
    	$premise_query->execute();

    	$category_query=$db->prepare("update category_dtl set status='delete' where deviceid='$d_id'");
    	$category_query->execute();

    	$update_category_query=$db->prepare("update category_dtl set status='active' where deviceid='$d_id' and category_type='Weighing'");
    	$update_category_query->execute();

    	$product_query=$db->prepare("update product set status='delete' where deviceid='$d_id'");
    	$product_query->execute();

    	$update_product_query=$db->prepare("update product set status='active' where deviceid='$d_id' and product_type='Weighing'");
    	$update_product_query->execute();

    	$_SESSION['device_type']="Weighing";
    }

?>