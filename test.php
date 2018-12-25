
<?php
	include('connect.php');
	
	$page_no=$_POST['page_no'];
	$next=$page_no+1;
	echo $next;
	$limit=30;
	$start_from = ($page_no-1) * $limit;
	$product_query=$db->prepare("select product_id, english_name from product  LIMIT $start_from , $limit");
	$product_query->execute();
	if($product_data=$product_query->fetch())
	{
		do
		{
			$product_id=$product_data['product_id'];
			$text=$product_data['english_name'];

			try {
				$db->beginTransaction();
				$query=$db->prepare("update transaction_dtl set item_name='$text' where item_id='$product_id'");
				$query->execute();
				$db->commit();
			} catch (Exception $e) {
				$db->rollBack();
				// echo $e;

			}
			
			}
			while($product_data=$product_query->fetch());
		}

?>
