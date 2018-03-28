<?php
	include('../connect.php');
	if(is_ajax())
    {
		$transaction_id=$_POST['transaction_id'];
		echo'<table class="table table-striped">  
	        <thead>  
	        	<tr>
	        		<th>Sr</th>  
	                <th>Bill No</th>
	                <th>Product Name</th>
	                <th>Qty</th>
	            </tr>  
	        </thead>  
	        <tbody id="target-content">';
		$product_query=$db->prepare("select kot_id,bill_no from kot_mst where transaction_id='$transaction_id'");
		$product_query->execute();
		if($row=$product_query->fetch())
		{
			$sr=0;
            do
            {
            	$sr++;
             	$kot_id=$row['kot_id'];
                $count_query=$db->prepare("select id, english_name, quantity, state from kot_dtl, product where product.product_id=kot_dtl.product_id and kot_id='$kot_id'");
                $count_query->execute();
                $cnt=$count_query->rowCount();
               	echo'<tr>
               		<td rowspan="'.$cnt.'">'.$sr.'</td>  
                    <td rowspan="'.$cnt.'">'.$row['bill_no'].'</td>';
                while ($row_cnt = $count_query->fetch()) 
           	    {
           	    	if($row_cnt["state"]==2)
           	    	{
                  		echo'<td><font color="#ff0000">'.$row_cnt["english_name"].'</td>
                        	<td><font color="#ff0000">'.$row_cnt["quantity"].'</td></tr>';
                    }
                    else
                    {
                    	echo'<td>'.$row_cnt["english_name"].'</td>
                        	<td>'.$row_cnt["quantity"].'</td></tr>';
                    }
                  
                }
            }
            while ($row=$product_query->fetch());
        }
            echo'</tbody> 
        </table>';	
	}	
	else 
    {
        echo "something wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
	?>