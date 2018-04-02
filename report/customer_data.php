<style>
    a.disabled 
    {
       pointer-events: none;
       cursor: pointer;
    }
</style>
<?php
    include('../connect.php');
    include('customer_excel.php');
    $limit=10;
   $first_date =  new DateTime();
    $clone_first=clone $first_date;
    $start_date = $clone_first->format('Y-m-d 00:00:00');
   
    $report_date_first=$clone_first->format('l jS \of F Y');

    $d_id=$_SESSION['d_id'];

    $product_query=$db->prepare("select customer_id, customer_name, customer_contact from  customer_mst where customer_mst.deviceid='$d_id'");


            
    $product_query->execute();
    $total_records=$product_query->rowCount();  


    $total_pages = ceil($total_records / $limit);

    if (isset($_POST["page"])) 
    { 
        $page  = $_POST["page"]; 
    }
    else 
    {
        $page=1; 
    }

    $start_from = ($page-1) * $limit;

    $Product_query=$db->prepare("select customer_id, customer_name, customer_contact from  customer_mst where customer_mst.deviceid='$d_id' LIMIT $start_from, $limit");  
    $Product_query->execute();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    <script src="../dist/jquery.simplePagination.js"></script>
    </head>
    <body>
        <?php
            if($Product_query->rowCount())
            {
        ?>
        <div class="row">
            <div class="col-sm-2">
                <strong>Report From : </strong>
            </div>
            <div class="col-sm-4">
                <?php echo $report_date_first; ?>
            </div>  
        </div>
       
            <div class="col-sm-2">
                <div class="button-demo">
                    <input type="button" name="" class="btn btn-info waves-effect m-r-20"  value="DOWNLOAD EXCEL" id="excel" onclick="go();">
                </div>
            </div>
            <table class="table table-striped">  
            <thead>  
            <tr>  
                <th>Customer Name</th>
                <th>Contact</th>
                <th>Bill No</th>
                <th>Date and Time</th>
                <th>Amount</th>
                <th>Cash</th>
                <th>Credit</th>
                <th>Digital</th>
            </tr>  
            </thead>  
            <tbody id="target-content">
            <?php  
            while ($row = $Product_query->fetch()) 
            {
                $customer_id=$row['customer_id'];

                $count_query=$db->prepare("select bill_no, bill_date, bill_amt, cash, credit, digital from transaction_mst where transaction_mst.customer_id='$customer_id'");

                $count_query->execute();
                $cnt=$count_query->rowCount();

                ?>
                <tr>  
                        <td rowspan="<?php echo $cnt ?>"><?php echo $row["customer_name"]; ?></td>
                        <td rowspan="<?php echo $cnt ?>"><?php echo $row["customer_contact"]; ?></td>  
                        <?php

                while ($row_cnt = $count_query->fetch()) {
               
            ?>  
                        
                        <td><?php echo $row_cnt["bill_no"]; ?></td>
                        <td><?php echo $row_cnt["bill_date"]; ?></td>  
                        <td><?php echo $row_cnt["bill_amt"]; ?></td>  
                        <td><?php echo $row_cnt["cash"]; ?></td>  
                        <td><?php echo $row_cnt["credit"]; ?></td>  
                        <td><?php echo $row_cnt["digital"]; ?></td>  
                        </tr>  
            <?php
            }  
            echo'</tr>';
            }  
            ?>
            </tbody> 
            </table>

           <?php
            }
            else
            {
                echo "No record found";
            }
           ?>
            <nav>
                <ul class="pagination">
                   <?php include('../pagination/pagination.php'); ?>    
                </ul>
            </nav>
    </div>
    </body>

    <script type="text/javascript">

        $(document).ready(function() {         
            $('.gen').click(function(){
                var page=$(this).text();
                 $('.page-loader-wrapper').show();
                $('#data-display').show();
                if(1)
                {
                    $.ajax({
                        type: 'POST',
                        url: 'bill_data.php',
                        data: { "page":page },
                        cache: false,
                        success: function(data)
                        {
                             $('.page-loader-wrapper').show();
                            $('#data-display').html(data);
                        }
                    });
                }
                else
                {
                    alert("please select date");
                }
            } );
        });

        $(document).ready(function() {         
            $('.prev').click(function(){
                var page= <?php echo $page-1;?>;
                 $('.page-loader-wrapper').show();
                $('#data-display').show();
                if(1)
                {
                    $.ajax({
                        type: 'POST',
                        url: 'bill_data.php',
                        data: { "page":page },
                        cache: false,
                        success: function(data)
                        {
                             $('.page-loader-wrapper').hide();
                            $('#data-display').html(data);
                        }
                    });
                }
                else
                {
                    alert("please select date");
                }
            } );
        });

        $(document).ready(function() {         
            $('.next').click(function(){
                var page= <?php echo $page+1;?>;
                 $('.page-loader-wrapper').show();
                $('#data-display').show();
                if(1)
                {
                    $.ajax({
                        type: 'POST',
                        url: 'bill_data.php',
                        data: {  "page":page },
                        cache: false,
                        success: function(data)
                        {
                             $('.page-loader-wrapper').hide();
                            $('#data-display').html(data);
                        }
                    });
                }
                else
                {
                    alert("please select date");
                }
            } );
        });
                </script>

                     <script src="../plugins/sweetalert/sweetalert.min.js"></script>

<script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>


