<?php
	include("../../connect.php");
	if(is_ajax())
	{
		$date=date('Y-m-d');
		$price_id=$_POST['price_id'];
		$id=$_POST['id'];
		$tax_id=$_POST['tax'];
		$price1=trim($_POST['price1']);
		$price2=trim($_POST['price2']);
		$price3=trim($_POST['price3']);
		$price4=trim($_POST['price4']);
		$price5=trim($_POST['price5']);
		$price6=trim($_POST['price6']);
		$price7=trim($_POST['price7']);
		$price8=trim($_POST['price8']);
		$price9=trim($_POST['price9']);

		$prices1=trim($_POST['prices1']);
		$prices2=trim($_POST['prices2']);
		$prices3=trim($_POST['prices3']);
		$prices4=trim($_POST['prices4']);
		$prices5=trim($_POST['prices5']);
		$prices6=trim($_POST['prices6']);
		$prices7=trim($_POST['prices7']);
		$prices8=trim($_POST['prices8']);
		$prices9=trim($_POST['prices9']);

		if($price1=="" && $price2=="" && $price3=="" && $price4=="" && $price5=="" && $price6=="" && $price7=="" && $price8=="" && $price9=="")
		{

		}
		else
		{
			$query=$db->prepare("update price_mst set tax_id='$tax_id', price1='$price1', prices1='$prices1', price2='$price2', prices2='$prices2', price3='$price3', prices3='$prices3', price4='$price4', prices4='$prices4', price5='$price5',  prices5='$prices5', price6='$price6', prices6='$prices6', prices7='$prices7', price8='$price8', prices8='$prices8', price9='$price9', prices9='$prices9', updated_by_id='$id', updated_by_date='$date' where price_id='$price_id'");

			echo "update price_mst set tax_id='$tax_id', price1='$price1', prices1='$prices1', price2='$price2', prices2='$prices2', price3='$price3', prices3='$prices3', price4='$price4', prices4='$prices4', price5='$price5',  prices5='$prices5', price6='$price6', prices6='$prices6', prices7='$prices7', price8='$price8', prices8='$prices8', price9='$price9', prices9='$prices9', updated_by_id='$id', updated_by_date='$date' where price_id='$price_id'";

			$query->execute();
			echo "1_";
		}
	}
	else 
    {
        echo "something wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }