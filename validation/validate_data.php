<?php
	include('../connect.php');
	$correct=1;
	$d_id=$_POST['d_id'];
	$device_type=$_POST['device_type'];
	$status='active';
	if($device_type=="Table")
	{
		$query=$db->prepare("select * from premise_dtl where deviceid='$d_id' and status='$status'");
		$query->execute();
		$count=$query->rowCount();
		if($count==0)
		{
			echo "Application Setting=> Premise Setting => Add data<br>";
			$correct=0;
		}
	}
	else
	{
		$query=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
		$query->execute();
		$count=$query->rowCount();
		if($count==0)
		{
			// echo "Application Setting=> Customer Type Setting => Add data<br>";
			// $correct=0;
		}
	}
	$user_query=$db->prepare("select * from user_dtl where deviceid='$d_id' and status='$status'");
	$user_query->execute();
	$user_count=$user_query->rowCount();
	if($user_count==0)
	{
		echo "User Setting=> Add data<br>";
		$correct=0;
	}
	$category_query=$db->prepare("select * from category_dtl where deviceid='$d_id' and status='$status'");
	$category_query->execute();
	$category_count=$category_query->rowCount();
	if($category_count==0)
	{
		echo "Application Setting=> Category Setting => Add data<br>";
		$correct=0;
	}

	$product_query=$db->prepare("select * from product where deviceid='$d_id' and status='$status'");
	$product_query->execute();
	$product_count=$product_query->rowCount();
	if($product_count==0)
	{
		echo "Product Setting=> product => Add data<br>";
		$correct=0;
	}
	else
	{
		$product_query=$db->prepare("select english_name, regional_name, weighing, category_id from product where deviceid='$d_id' and status='$status'");
		$product_query->execute();
		if($data=$product_query->fetch())
		{
			do
			{
				$english_name=$data['english_name'];
				$regional_name=$data['regional_name'];
				$weighing=$data['weighing'];
				$category_id=$data['category_id'];
				if($device_type=="Weighing")
				{
					if(($regional_name!="") && ($weighing!="") && ($category_id!=""))
					{
					}
					else
					{
						echo "Product Setting=> product=>".$english_name." => Some value is empty<br>";
						$correct=0;
					}
				}
				else
				{
					if(($category_id!=""))
					{
					}
					else
					{
						echo "Product Setting=> product=>".$english_name." =>Some value is empty<br>";
						$correct=0;
					}	
				}
			}
			while ($data=$product_query->fetch());
		}
		$stock_query=$db->prepare("select stockable, unit_id,english_name from product, stock_mst where product.deviceid='$d_id' and product.status='$status' and stock_mst.product_id=product.product_id ");
		$stock_query->execute();
		if($stock_data=$stock_query->fetch())
		{
			do
			{
				$stockable=$stock_data['stockable'];
				$unit_id=$stock_data['unit_id'];
				$english_name=$stock_data['english_name'];
				if(($stockable!="") && ($unit_id!=""))
				{
				}
				else
				{
					echo "Product Setting=> Stock=>".$english_name." => Some value is empty<br>";
					$correct=0;
				}	
			}
			while ($stock_data=$stock_query->fetch());
		}
		$price_query=$db->prepare("select tax_id, price1,price2, price3, price4, price5, price6, price7,price8,price9, english_name from product, price_mst where product.deviceid='$d_id' and product.status='$status' and price_mst.product_id=product.product_id ");
		$price_query->execute();
		if($price_data=$price_query->fetch())
		{
			do
			{
				$tax_id=$price_data['tax_id'];
				$price1=$price_data['price1'];
				$price2=$price_data['price2'];
				$price3=$price_data['price3'];
				$price4=$price_data['price4'];
				$price5=$price_data['price5'];
				$price6=$price_data['price6'];
				$price7=$price_data['price7'];
				$price8=$price_data['price8'];
				$price9=$price_data['price9'];
				$english_name=$price_data['english_name'];
				

				if($tax_id=="")
				{
					echo "Product Setting=> Price=>".$english_name." => tax is not selected<br>";
					$correct=0;
				}
				else
				{
					for($i=1;$i<=$count;$i++)
					{
						$name='price'.$i;
						if(is_numeric($$name))
						{
						 	
						}
						else
						{
						 	echo "Product Setting=> Price=>".$english_name." => price is wrong<br>";
						 	$correct=0;
						 	break;
						}
					}
				}	
			}
			while ($price_data=$price_query->fetch());
		}
	}

	if($correct==1)
	{
		echo "All data is valid<br>";
	}

?>