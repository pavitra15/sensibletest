<style>
    a.disabled 
    {
       pointer-events: none;
       cursor: pointer;
    }
</style>
<?php
    session_start();
    include('../connect.php');
    include('stock_excel.php');
    $limit=10;
    $first_date =  new DateTime();
    $clone_first=clone $first_date;
    $start_date = $clone_first->format('Y-m-d 00:00:00');
    
    $report_date_first=$clone_first->format('l jS \of F Y');
    $d_id=$_SESSION['d_id'];
    $product_query=$db->prepare("select * from product,category_dtl,stock_mst,price_mst where stock_mst.product_id=product.product_id  and product.deviceid='$d_id' and product.product_id= price_mst.product_id and product.product_id=stock_mst.product_id and product.category_id= category_dtl.category_id");



            
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

    $Product_query=$db->prepare("select * from product,category_dtl,stock_mst,price_mst where stock_mst.product_id=product.product_id  and product.deviceid='$d_id' and product.product_id= price_mst.product_id and product.product_id=stock_mst.product_id and product.category_id= category_dtl.category_id LIMIT $start_from, $limit");  
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
                    <strong>Report Date : </strong>
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
                    <th>English Name</th>
                    <th>Regional Name</th>
                    <th>Category</th>
                    <th>Current Stock</th>
                </tr>
            </thead>
                <tbody id="target-content">
                <?php  
                while ($row = $Product_query->fetch()) 
                {
                   echo'<tr>
                        <td>'.$row['english_name'].'</td>
                        <td>'.$row['regional_name'].'</td>
                        <td>'.$row['category_name'].'</td>
                        <td>'.$row['current_stock'].'</td></tr>';
                }
            }
            else
            {
                echo "No record found";
            }
           ?>
       </tbody>
   </table>
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
                $('#quarterly_display').hide();
                $('#data-display').show();
                    $.ajax({
                        type: 'POST',
                        url: 'stock_data.php',
                        data: {"page":page },
                        cache: false,
                        success: function(data)
                        {
                             $('.page-loader-wrapper').hide();
                            $('#data-display').html(data);
                        }
                    });
            } );
        });

        $(document).ready(function() {         
            $('.prev').click(function(){
                var page= <?php echo $page-1;?>;
                 $('.page-loader-wrapper').show();
                $('#quarterly_display').hide();
                $('#data-display').show();
                
                    $.ajax({
                        type: 'POST',
                        url: 'stock_data.php',
                        data: { "page":page },
                        cache: false,
                        success: function(data)
                        {
                             $('.page-loader-wrapper').hide();
                            $('#data-display').html(data);
                        }
                    });
               
            } );
        });

        $(document).ready(function() {         
            $('.next').click(function(){
                var page= <?php echo $page+1;?>;
                 $('.page-loader-wrapper').show();
                $('#quarterly_display').hide();
                $('#data-display').show();
               
                    $.ajax({
                        type: 'POST',
                        url: 'stock_data.php',
                        data: { "page":page },
                        cache: false,
                        success: function(data)
                        {
                             $('.page-loader-wrapper').hide();
                            $('#data-display').html(data);
                        }
                    });
                
            } );
        });
                </script>

                <script src="../plugins/sweetalert/sweetalert.min.js"></script>

<script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>


