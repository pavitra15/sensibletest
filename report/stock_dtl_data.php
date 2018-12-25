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
    $limit=10;
    $first_date =  new DateTime();
    $clone_first=clone $first_date;
    $start_date = $clone_first->format('Y-m-d 00:00:00');
    
    $report_date_first=$clone_first->format('l jS \of F Y');
    $d_id=$_SESSION['d_id'];
    $stock_from="device";
    $product_query=$db->prepare("select * from product,stock_dtl, user_dtl where product.product_id=stock_dtl.product_id and user_dtl.user_id=stock_dtl.user_id  and stock_dtl.d_id='$d_id' and stock_from='$stock_from' order by log_date DESC");



            
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

    $Product_query=$db->prepare("select english_name, log_date,reason, user_name, stock_added, current_stock, opening_stock, type from product,stock_dtl,user_dtl where product.product_id=stock_dtl.product_id and user_dtl.user_id=stock_dtl.user_id and stock_dtl.d_id='$d_id' and stock_from='$stock_from' order by log_date DESC LIMIT $start_from, $limit");  
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
                <table class="table table-striped">  
                <thead>
                <tr>
                    <th>English Name</th>
                    <th>Date</th>
                    <th>User Name</th>
                    <th>Description</th>
                    <th>Opening Stock</th>
                    <th>Added Stock</th>
                    <th>Reduced Stock</th>
                    <th>Current Stock</th>
                </tr>
            </thead>
                <tbody id="target-content">
                <?php  
                while ($row = $Product_query->fetch()) 
                {
                    if($row['type']==0)
                    {
                        echo'<tr>
                            <td>'.$row['english_name'].'</td>
                            <td>'.$row['log_date'].'</td>
                            <td>'.$row['user_name'].'</td>
                            <td>'.$row['reason'].'</td>
                            <td>'.$row['opening_stock'].'</td>
                            <td>'.$row['stock_added'].'</td>
                            <td>0</td>
                            <td>'.$row['current_stock'].'</td></tr>';
                    }
                    else
                    {
                       echo'<tr>
                            <td><font color="#ff0000">'.$row['english_name'].'</td>
                            <td><font color="#ff0000">'.$row['log_date'].'</td>
                            <td><font color="#ff0000">'.$row['user_name'].'</td>
                            <td><font color="#ff0000">'.$row['reason'].'</td>
                            <td><font color="#ff0000">'.$row['opening_stock'].'</td>
                            <td><font color="#ff0000">0</td>
                            <td><font color="#ff0000">'.$row['stock_added'].'</td>
                            <td><font color="#ff0000">'.$row['current_stock'].'</td></tr>';
                    }
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
                        url: 'stock_dtl_data.php',
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
                        url: 'stock_dtl_data.php',
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
                        url: 'stock_dtl_data.php',
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


