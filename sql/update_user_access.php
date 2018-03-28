<?php
	include("../connect.php");
	if(is_ajax())
   	{
		$date=date('Y-m-d');
		$dashboard=$_POST['dashboard'];
		$print_setting=$_POST['print_setting'];
		$report=$_POST['report'];
		$add_product=$_POST['add_product'];
		$update_product=$_POST['update_product'];
		$update_price=$_POST['update_price'];
		$update_stock=$_POST['update_stock'];
		$edit_bill=$_POST['edit_bill'];
		$id=$_POST['id'];
		$d_id=$_POST['d_id'];
	    
	    $query=$db->prepare("update user_access set dashboard='$dashboard', print_setting='$print_setting', edit_bill='$edit_bill', report='$report', add_product='$add_product', update_product='$update_product', update_price='$update_price', update_stock='$update_stock', updated_by_date='$date', updated_by_id='$id' where d_id='$d_id'");
		$query->execute();
		echo "1";	
	}
	else 
    {
        echo "something wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
?>