<?php
	session_start();
	include('../connect.php');
	$d_id=$_POST['d_id'];
	$page_no=$_POST['page_no'];
	$next=$page_no+1;

    $status='active';
    $limit=50;
    $start_from = ($page_no-1) * $limit;
    $stock="";
	$stockQuery=$db->prepare("select english_name, regional_name, price1 from product, price_mst,unit_mst,stock_mst where price_mst.product_id=product.product_id and product.deviceid='$d_id' and unit_mst.unit_id=stock_mst.unit_id and stock_mst.product_id=product.product_id and product.status='$status'");

	$stockQuery->execute();
	$count=$stockQuery->rowCount();
	$count=ceil($count/50);
	$ledgerQuery=$db->prepare("select english_name, regional_name, price1, product.created_by_date from product, price_mst,unit_mst,stock_mst where price_mst.product_id=product.product_id and product.deviceid='$d_id' and unit_mst.unit_id=stock_mst.unit_id and stock_mst.product_id=product.product_id and product.status='$status'  LIMIT $start_from, $limit");
	$ledgerQuery->execute();

	if($ledgerData=$ledgerQuery->fetch())
	{
		do{
			$date = new DateTime($ledgerData['created_by_date']);
         	$visit_date=$date->format('Ymd');
			$stock.= '<TALLYMESSAGE xmlns:UDF="TallyUDF"><STOCKITEM NAME="'.$ledgerData['english_name'].'" ACTION="Create"><NAME.LIST><NAME>'.$ledgerData['english_name'].'</NAME></NAME.LIST><ADDITIONALNAME.LIST><ADDITIONALNAME></ADDITIONALNAME></ADDITIONALNAME.LIST><BASEUNITS>'.$ledgerData['abbrevation'].'</BASEUNITS><BATCHALLOCATIONS.LIST><NAME>Primary Batch</NAME><BATCHNAME>Primary Batch</BATCHNAME><GODOWNNAME>Main Location</GODOWNNAME></BATCHALLOCATIONS.LIST><STANDARDPRICELIST.LIST><RATE>'.$ledgerData['price1'].'</RATE><DATE>'.$visit_date.'</DATE></STANDARDPRICELIST.LIST><STANDARDCOSTLIST.LIST><RATE>'.$ledgerData['price1'].'</RATE><DATE>'.$visit_date.'</DATE></STANDARDCOSTLIST.LIST><REORDERBASE>0.000</REORDERBASE><MINIMUMORDERBASE>0.000</MINIMUMORDERBASE></STOCKITEM></TALLYMESSAGE>';
		}
		while($ledgerData=$ledgerQuery->fetch());
	}

	$data_array = array('page'=>$next,'total'=>$count,'stock'=>$stock);
    $response=json_encode($data_array);
    echo $response; 

    ?>


	
