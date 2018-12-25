<?php
    session_start();
    include('../connect.php');
    $bill_no = $_POST['bill_no'];
    
    $d_id=$_SESSION['d_id'];

    $status='active';

    $Product_query=$db->prepare("select transaction_id, bill_no, bill_amt, tax_state, discount, parcel_amt, tax_amt,parcel_amt, bill_date from  transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_no like '$bill_no%'");  
    $Product_query->execute();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    </head>
    <body>
        <?php
            if($Product_query->rowCount())
            {
                echo'   
            <table class="table table-striped">  
                <thead>  
                    <tr>  
                        <th>Bill No</th>
                        <th>Amount</th>
                        <th>Tax</th>
                        <th>Discount</th>
                        <th>Net</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th> 
                    </tr>  
                </thead>  
                <tbody id="target-content">';
                    while ($row = $Product_query->fetch()) 
                    {
                        $transaction_id=$row['transaction_id'];
                        $count_query=$db->prepare("select item_name as english_name, quantity, price, total_amt, transaction_dtl.status from transaction_dtl where transaction_dtl.transaction_id='$transaction_id'");
                        $count_query->execute();
                        $cnt=$count_query->rowCount();
                        echo'<tr>  
                            <td rowspan="'.$cnt.'">'.$row['bill_no'].'</td>
                            <td rowspan="'.$cnt.'">'.round($row["bill_amt"]).'</td>  
                            <td rowspan="'.$cnt.'">'.$row["tax_amt"].'</td> 
                            <td rowspan="'.$cnt.'">'.$row["discount"].'</td>';
                            echo'<td rowspan="'.$cnt.'">';
                            if($row["tax_state"]==0)
                            {
                                echo round($row["parcel_amt"]+$row["tax_amt"]+$row["bill_amt"]-$row["discount"]);
                            }
                            else
                            {
                               echo round($row["parcel_amt"]+$row["bill_amt"]-$row["discount"]);
                            }
                            echo'</td><td rowspan="'.$cnt.'">'.$row["bill_date"].'</td> '; 
                                
                            while ($row_cnt = $count_query->fetch()) 
                            {
                               if($row_cnt['status']=="cancel")
                               {
                                    echo'<td><font color="#ff0000">'.$row_cnt["english_name"].'</td>
                                    <td><font color="#ff0000">'.$row_cnt["quantity"].'</td>
                                    <td><font color="#ff0000">'.$row_cnt["price"].'</td>  
                                    <td><font color="#ff0000">'.$row_cnt["total_amt"].'</td>';  
                                }
                                else
                                {
                                    echo'<td>'.$row_cnt["english_name"].'</td>
                                    <td>'.$row_cnt["quantity"].'</td>
                                    <td>'. $row_cnt["price"].'</td>  
                                    <td>'.$row_cnt["total_amt"].'</td>';
                                }
                                echo'</tr>'; 
                            }
                             
                    }  
                    echo'</tbody> 
                    </table>';
                }
                else
                {
                    echo "No record found";
                }
            ?>
    </div>
    </body>

    
                </script>

                <script type="text/javascript" src="jszip.js"></script>
    <script type="text/javascript" src="FileSaver.js"></script>
    <script type="text/javascript" src="myexcel.js"></script>

                     <script src="../plugins/sweetalert/sweetalert.min.js"></script>

<script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>


