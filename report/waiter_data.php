<style>
a.disabled {
   pointer-events: none;
   cursor: pointer;
}
</style>
<?php
    session_start();
    include('../connect.php');
    include('waiter_excel.php');
    $limit=10;
    $first_date =  new DateTime($_POST['start_date']);
    $second_date = new DateTime($_POST['end_date']);
    $clone_first=clone $first_date;
    $clone_second=clone $second_date;
    $start_date = $clone_first->format('Y-m-d 00:00:00');
    $end_date = $second_date->format('Y-m-d 23:59:59');

    $report_date_first=$clone_first->format('l jS \of F Y');
    $report_date_second=$clone_second->format('l jS \of F Y');

    $d_id=$_SESSION['d_id'];
    $status='$status';
    $count_query=$db->prepare("select waiter_name, sum(total_amt*comission/100) as total from waiter_dtl, transaction_mst, product, transaction_dtl where transaction_mst.waiter_id=waiter_dtl.waiter_id and waiter_dtl.deviceid='$d_id' and transaction_mst.status='active' and transaction_mst.transaction_id=transaction_dtl.transaction_id and transaction_dtl.item_id=product.product_id and bill_date between '$start_date' and '$end_date' group by waiter_dtl.waiter_id ");
    $count_query->execute();
    $total_records=$count_query->rowCount();    

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
    $sql_query = $db->prepare("select waiter_name, sum(total_amt*comission/100) as total,waiter_mobile from waiter_dtl, transaction_mst, product, transaction_dtl where transaction_mst.waiter_id=waiter_dtl.waiter_id and waiter_dtl.deviceid='$d_id' and transaction_mst.status='active' and transaction_mst.transaction_id=transaction_dtl.transaction_id and transaction_dtl.item_id=product.product_id and bill_date between '$start_date' and '$end_date' group by waiter_dtl.waiter_id LIMIT $start_from, $limit");  
    $sql_query->execute();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    <script src="../dist/jquery.simplePagination.js"></script>
    </head>
    <body>
        <?php
            if($sql_query->rowCount())
            {
        ?>
         <div class="row">
            <div class="col-sm-2">
                <strong>Report From : </strong>
            </div>
            <div class="col-sm-4">
                <?php echo $report_date_first; ?>
            </div>
            <div class="col-sm-2">
               <strong>Report To : </strong> 
            </div>
            <div class="col-sm-4">
                <?php echo $report_date_second; ?>
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
                        <th>Waiter Name</th>  
                        <th>Mobile</th>
                        <th>Total Amount</th>
                    </tr>  
                </thead>  
            <tbody id="target-content">
            <?php  
            while ($row = $sql_query->fetch()) {
            ?>  
                        <tr>  
                        <td><?php echo $row["waiter_name"]; ?></td>
                        <td><?php echo $row["waiter_mobile"]; ?></td>  
                        <td><?php echo $row["total"]; ?></td>  
                        </tr>  
            <?php  
            };  
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
                var start_date=$('#start_date').val();
                var end_date=$('#end_date').val();
                if(start_date.length>0 && end_date.length>0)
                {
                    $.ajax({
                        type: 'POST',
                        url: 'waiter_data.php',
                        data: { "start_date":start_date,"end_date":end_date, "page":page },
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
            $('.prev').click(function(){
                var page= <?php echo $page-1;?>;
                 $('.page-loader-wrapper').show();
                $('#data-display').show();
                var start_date=$('#start_date').val();
                var end_date=$('#end_date').val();
                if(start_date.length>0 && end_date.length>0)
                {
                    $.ajax({
                        type: 'POST',
                        url: 'waiter_data.php',
                        data: { "start_date":start_date,"end_date":end_date, "page":page },
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
                var start_date=$('#start_date').val();
                var end_date=$('#end_date').val();
                if(start_date.length>0 && end_date.length>0)
                {
                    $.ajax({
                        type: 'POST',
                        url: 'waiter_data.php',
                        data: { "start_date":start_date,"end_date":end_date, "page":page },
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
                    $('#data-display').hide();
                }
            } );
        });
                </script>

                     <script src="../plugins/sweetalert/sweetalert.min.js"></script>
<script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>


