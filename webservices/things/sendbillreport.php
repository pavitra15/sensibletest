<?php
 	include('../../connect.php');
	$token=$_POST['token'];
	$deviceid=$_POST['deviceid'];
	$json=$_POST['json'];
	$flag=0;
	$access_control="";
	$url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
   
    $update_query=$db->prepare("update last_connect set city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login' where deviceid='$deviceid'");
    $update_query->execute();
	$access_query = $db->prepare("select * from device where deviceid='$deviceid'");
    $access_query->execute();
    if ($access_data = $access_query->fetch()) 
    {
        do 
        {
        	$d_id=$access_data['d_id'];
    	    $access_control=$access_data['access_control'];
	    } 
        while ($access_data = $access_query->fetch());
    }
    $sync_api='sendbill_report';
    $last_sync_query=$db->prepare("insert into last_sync(d_id,sync_datetime,sync_api)values('$d_id','$last_login','$sync_api')");
    $last_sync_query->execute();

    
	function json_validator($data=NULL) 
	{
    	if (!empty($data)) 
    	{
        	@json_decode($data);
            return (json_last_error() === JSON_ERROR_NONE);
    	}
    	return false;
	}
	if(json_validator($json))
	{
		$query=$db->prepare("select * from token_verify where token='$token' and deviceid='$deviceid'");
		$query->execute();
		$token_count=$query->rowCount();
		if($token_count==1)
		{
			$response='{"records":[';
			$transaction_id;
			$object=json_decode($json);
			$sync_by=$object->user_id;
			$created_sync_date=$object->created_sync_date;	
			$bill_data=$object->bill_data;
			$length=sizeof($bill_data);
			if($length>0)
			{
				for($i=0; $i<$length; $i++)
				{
					// if($deviceid=="7273930eef7a1f76")
					// {
					// 	$bill_no=$bill_data[$i]->bill_no;
					// 	$response.='{"bill_no":"'.$bill_no.'","status":1},';
					// }
					// else
					// {
						if($bill_data[$i]->bill_state==3)
						{
							$parcel_amt=$bill_data[$i]->parcel_amt;
							$bill_amt=$bill_data[$i]->bill_amt;
							$tax_amt=$bill_data[$i]->tax_amt;

							$payment_mode=$bill_data[$i]->payment_mode;
							$cash=$payment_mode->cash;
							$credit=$payment_mode->credit;
							$digital=$payment_mode->digital;

							$customer_id=$bill_data[$i]->customer_id;
							$waiter_id=$bill_data[$i]->waiter_id;

							$user_id=$bill_data[$i]->user_id;
							$date=$bill_data[$i]->date;
							$bill_no=$bill_data[$i]->bill_no;
							$discount=$bill_data[$i]->discount;
							$tax_state=$bill_data[$i]->tax_state;
							$bill_type_id=$bill_data[$i]->bill_type_id;
							$bill_items=$bill_data[$i]->bill_items;

							// $response.='{"bill_no":"'.$bill_no.'","status":1},';

							$query=$db->prepare("update transaction_mst set parcel_amt='$parcel_amt',discount='$discount', tax_amt= '$tax_amt', bill_amt='$bill_amt', tax_state='$tax_state', cash='$cash', credit='$credit', digital='$digital', customer_id='$customer_id', waiter_id='$waiter_id', bill_type_id='$bill_type_id', updated_by='$user_id', updated_date='$date',updated_sync_by='$sync_by', updated_sync_date='$created_sync_date' where device_id='$d_id' and bill_no='$bill_no'");
							if($query->execute())
							{
								$response.='{"bill_no":"'.$bill_no.'","status":1},';		
								$sth=$db->prepare("SELECT * FROM transaction_mst where device_id='$d_id' and bill_no='$bill_no' ");
								$sth->execute();
								if($sth->rowCount())
								{	
									if($data=$sth->fetch())
									{
										do
										{
											$transaction_id=$data['transaction_id'];
										}
										while ($data=$sth->fetch());
									}
									$cnt=sizeof($bill_items);
									for ($j=0; $j <$cnt ; $j++) 
									{ 
										$total=$bill_items[$j]->total;
										$quantity=$bill_items[$j]->quantity;
										$item_id=$bill_items[$j]->item_id;
										$item_name=$bill_items[$j]->item_name;
										$price=$bill_items[$j]->price;
										$tax_index=$bill_items[$j]->tax_index;
										$item_state=$bill_items[$j]->state;
										if($item_state==5)
										{
											$status='cancel';
											$st=$db->prepare("update transaction_dtl set quantity='$quantity', item_name='$item_name',price='$price',tax_index='$tax_index',total_amt='$total', status='$status' where transaction_id='$transaction_id' and item_id='$item_id'");
											$st->execute();
											$rs=$st->rowCount();
											$stockable="Yes";
											$update_stock=$db->prepare("update stock_mst set current_stock= (current_stock+$quantity) where product_id='$item_id' and stockable='$stockable'");
											$update_stock->execute();
										}
										else
										{
											$status='active';
											$st=$db->prepare("update transaction_dtl set quantity='$quantity', item_name='$item_name', price='$price',tax_index='$tax_index',total_amt='$total', status='$status' where transaction_id='$transaction_id' and item_id='$item_id'");
											$st->execute();
											$rs=$st->rowCount();
										}	
									}
								}
							}
							else
							{
								$response.='{"bill_no":"'.$bill_no.'","status":0},';
							}
						}
						elseif($bill_data[$i]->bill_state==6)
						{
							$exists_arr= array();
							$parcel_amt=$bill_data[$i]->parcel_amt;
							$bill_amt=$bill_data[$i]->bill_amt;
							$tax_amt=$bill_data[$i]->tax_amt;

							$payment_mode=$bill_data[$i]->payment_mode;
							$cash=$payment_mode->cash;
							$credit=$payment_mode->credit;
							$digital=$payment_mode->digital;

							$customer_id=$bill_data[$i]->customer_id;
							$waiter_id=$bill_data[$i]->waiter_id;

							$user_id=$bill_data[$i]->user_id;
							$date=$bill_data[$i]->date;
							$bill_no=$bill_data[$i]->bill_no;
							$discount=$bill_data[$i]->discount;
							$tax_state=$bill_data[$i]->tax_state;
							$bill_type_id=$bill_data[$i]->bill_type_id;
							$bill_items=$bill_data[$i]->bill_items;

							// $response.='{"bill_no":"'.$bill_no.'","status":1},';

							$exist_bl_query=$db->prepare("select bill_no from transaction_mst where device_id='$d_id' and bill_no='$bill_no'");
							$exist_bl_query->execute();
							if($exist_data=$exist_bl_query->fetch())
					        {
					            do
					            {
					                $exists_arr[]=$exist_data['bill_no'];
					            }
					            while($exist_data=$exist_bl_query->fetch());
					        }
					        if(in_array($bill_no, $exists_arr))
			                {
			                	$response.='{"bill_no":"'.$bill_no.'","status":1},';
			                }
			                else
			                {
			                	$status='active';
								$query=$db->prepare("insert into transaction_mst (device_id,bill_no,user_id,parcel_amt,tax_amt,bill_amt,tax_state,discount, cash, credit, digital, customer_id, waiter_id, bill_type_id, bill_date,sync_by,created_sync_date,status) values('$d_id','$bill_no','$user_id','$parcel_amt','$tax_amt','$bill_amt','$tax_state', '$discount','$cash','$credit','$digital', '$customer_id','$waiter_id', '$bill_type_id', '$date','$sync_by','$created_sync_date','$status')");
								$query->execute();
								$rs=$query->rowCount();
								if($rs>0)
								{
									$sth=$db->prepare("SELECT * FROM transaction_mst where bill_no='$bill_no' and device_id='$d_id' ORDER BY transaction_id DESC LIMIT 1");
									$sth->execute();
									$rs=$sth->rowCount();
									if($rs>0)
									{	
										if($data=$sth->fetch())
										{
											do
											{
												$transaction_id=$data['transaction_id'];
											}
											while ($data=$sth->fetch());
										}
										
										$cnt=sizeof($bill_items);
										$sk=1;
										for ($j=0; $j <$cnt ; $j++) 
										{ 
											$total=$bill_items[$j]->total;
											$quantity=$bill_items[$j]->quantity;
											$item_id=$bill_items[$j]->item_id;
											$item_name=$bill_items[$j]->item_name;
											$price=$bill_items[$j]->price;
											$tax_index=$bill_items[$j]->tax_index;
											$item_state=$bill_items[$j]->state;
											if($item_state==5)
											{
												$status='cancel';
												$sm=$db->prepare("insert into transaction_dtl (transaction_id,item_id,item_name, quantity,price,tax_index,total_amt,status) values('$transaction_id','$item_id','$item_name','$quantity','$price','$tax_index','$total','$status')");
												if($sm->execute())
												{
												}
												else
												{
													$sk=0;
												}
												
											}
											else
											{
												$status='active';
												$sm=$db->prepare("insert into transaction_dtl (transaction_id,item_id,item_name,quantity,price,tax_index,total_amt,status) values('$transaction_id','$item_id','$item_name','$quantity','$price','$tax_index','$total','$status')");
												if($sm->execute())
												{
													$stockable="Yes";
													$update_stock=$db->prepare("update stock_mst set current_stock= (current_stock-$quantity) where product_id='$item_id' and stockable='$stockable'");
													$update_stock->execute();
												}
												else
												{
													$sk=0;
												}
											}
										}
										if($sk>0)
										{
											$response.='{"bill_no":"'.$bill_no.'","status":1},';
										}
										else
										{
											$delete_query=$db->prepare("delete from transaction_mst where transaction_id='$transaction_id'");
											$delete_query->execute();
											$delete_query=$db->prepare("delete from transaction_dtl  where transaction_id='$transaction_id'");
											$delete_query->execute();
											$response.='{"bill_no":"'.$bill_no.'","status":0},';
										}
										$book_table=$bill_data[$i]->book_table;
										if(!is_null($book_table))
										{
											$premise_name=$book_table->premise_name;
											$table_no=$book_table->table_no;
											$sk=$db->prepare("insert into book_dtl (transaction_id,premise_name,table_no) values('$transaction_id','$premise_name','$table_no')");
											$sk->execute();
											$rs=$sk->rowCount();
											if($rs)
											{

											}
											else
											{	
									
											}
										}
									}
									else
									{
										$response.='{"bill_no":"'.$bill_no.'","status":0},';
									}
								}
								else
								{
									$response.='{"bill_no":"'.$bill_no.'","status":0},';
								}
							}	
						}
						else
						{
							$exists_arr= array();
							$parcel_amt=$bill_data[$i]->parcel_amt;
							$bill_amt=$bill_data[$i]->bill_amt;
							$tax_amt=$bill_data[$i]->tax_amt;

							$payment_mode=$bill_data[$i]->payment_mode;
							$cash=$payment_mode->cash;
							$credit=$payment_mode->credit;
							$digital=$payment_mode->digital;

							$customer_id=$bill_data[$i]->customer_id;
							$waiter_id=$bill_data[$i]->waiter_id;

							$user_id=$bill_data[$i]->user_id;
							$date=$bill_data[$i]->date;
							$bill_no=$bill_data[$i]->bill_no;
							$discount=$bill_data[$i]->discount;
							$tax_state=$bill_data[$i]->tax_state;
							$bill_type_id=$bill_data[$i]->bill_type_id;
							$bill_items=$bill_data[$i]->bill_items;
							// $response.='{"bill_no":"'.$bill_no.'","status":1},';
							$exist_bl_query=$db->prepare("select bill_no from transaction_mst where device_id='$d_id' and bill_no='$bill_no'");
							$exist_bl_query->execute();
							if($exist_data=$exist_bl_query->fetch())
					        {
					            do
					            {
					                $exists_arr[]=$exist_data['bill_no'];
					            }
					            while($exist_data=$exist_bl_query->fetch());
					        }
					        if(in_array($bill_no, $exists_arr))
			                {
			                	$response.='{"bill_no":"'.$bill_no.'","status":1},';
			                }
			                else
			                {
			                	$status='active';
								$query=$db->prepare("insert into transaction_mst (device_id,bill_no,user_id,parcel_amt,tax_amt,bill_amt,tax_state, discount, cash, credit, digital,customer_id, waiter_id, bill_type_id, bill_date,sync_by,created_sync_date,status) values('$d_id','$bill_no','$user_id','$parcel_amt','$tax_amt','$bill_amt','$tax_state', '$discount', '$cash','$credit','$digital','$customer_id', '$waiter_id', '$bill_type_id', '$date','$sync_by','$created_sync_date','$status')");
								$query->execute();
								$rs=$query->rowCount();
								if($rs>0)
								{
									$sth=$db->prepare("SELECT * FROM transaction_mst where bill_no='$bill_no' and device_id='$d_id' ORDER BY transaction_id DESC LIMIT 1");
									$sth->execute();
									$rs=$sth->rowCount();
									if($rs>0)
									{	
										if($data=$sth->fetch())
										{
											do
											{
												$transaction_id=$data['transaction_id'];
											}
											while ($data=$sth->fetch());
										}
										
										$cnt=sizeof($bill_items);
										for ($j=0; $j <$cnt ; $j++) 
										{ 
											$total=$bill_items[$j]->total;
											$quantity=$bill_items[$j]->quantity;
											$item_id=$bill_items[$j]->item_id;
											$item_name=$bill_items[$j]->item_name;
											$price=$bill_items[$j]->price;
											$tax_index=$bill_items[$j]->tax_index;
											$status='active';
											$sm=$db->prepare("insert into transaction_dtl (transaction_id,item_id,item_name,quantity,price,tax_index,total_amt,status) values('$transaction_id','$item_id','$item_name','$quantity','$price','$tax_index','$total','$status')");
											$sm->execute();
											$rs=$sm->rowCount();
											if(1)
											{
												$rk=1;
												$stockable="Yes";
												$update_stock=$db->prepare("update stock_mst set current_stock= (current_stock-$quantity) where product_id='$item_id' and stockable='$stockable'");
												$update_stock->execute();
												
											}
											else
											{
												$delete_query=$db->prepare("delete from transaction_mst where transaction_id='$transaction_id'");
												$delete_query->execute();
												$delete_query=$db->prepare("delete from transaction_dtl  where transaction_id='$transaction_id'");
												$delete_query->execute();
												$rk=0;
											}
											if($rk==0)
												break;

										}
										if($rk==0)
										{
											$response.='{"bill_no":"'.$bill_no.'","status":0},';
										}
										else
										{
											$response.='{"bill_no":"'.$bill_no.'","status":1},';
										}
										$book_table=$bill_data[$i]->book_table;
										if(!is_null($book_table))
										{
											$premise_name=$book_table->premise_name;
											$table_no=$book_table->table_no;
											$sk=$db->prepare("insert into book_dtl (transaction_id,premise_name,table_no) values('$transaction_id','$premise_name','$table_no')");
											$sk->execute();
											$rs=$sk->rowCount();
											if($rs)
											{

											}
											else
											{	
											}
										}
									}
									else
									{
										$response.='{"bill_no":"'.$bill_no.'","status":0},';
									}
								}
								else
								{
									$response.='{"bill_no":"'.$bill_no.'","status":0},';
								}
							}	
						}
					// }	
				}
			}
			else
			{
				$response.=']';
			}
				$response=substr($response, 0,-1);
				$response.='],"access":"'.$access_control.'"}';
				echo $response;
		}
		else
		{
			$responce = array('status' =>2,'message'=>"token mismatch",'access'=>$access_control);
			echo json_encode($responce);
		}
	}
	else
	{
		$responce = array('status' =>0,'message'=>'Json format is wrong','access'=>$access_control);
		echo json_encode($responce);
	}
?>