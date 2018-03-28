<style>
    a.disabled 
    {
       pointer-events: none;
       cursor: pointer;
    }
</style>
<?php
    include('../connect.php');
    include('user_excel.php');
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

    $status='active';
    $product_query=$db->prepare("select transaction_id, bill_no, bill_amt, bill_date, user_name from  transaction_mst, user_dtl where transaction_mst.user_id=user_dtl.user_id and transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date'");
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

    $Product_query=$db->prepare("select bill_no, bill_amt, bill_date, user_name from  transaction_mst, user_dtl where transaction_mst.user_id=user_dtl.user_id and transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date' LIMIT $start_from, $limit");  
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
                echo'<div class="row">
                    <div class="col-sm-2">
                        <strong>Report From : </strong>
                    </div>
                <div class="col-sm-4">';
                    echo $report_date_first;
                echo'</div>
                <div class="col-sm-2">
                   <strong>Report To : </strong> 
                </div>
                <div class="col-sm-4">';
                    echo $report_date_second;
                echo'</div>      
            </div>
            <div class="col-sm-2">
                <div class="button-demo">
                    <input type="button" name="" class="btn btn-info waves-effect m-r-20"  value="DOWNLOAD EXCEL" id="excel" onclick="go();">
                </div>
            </div>
            <table class="table table-striped">  
                <thead>  
                    <tr> 
                        <th>User Name</th> 
                        <th>Bill No</th>
                        <th>Amount</th>
                        <th>Bill Date</th> 
                    </tr>  
                </thead>  
                <tbody id="target-content">';
                    while ($row = $Product_query->fetch()) 
                    {
                        echo'<tr>
                            <td>'.$row['user_name'].'</td>  
                            <td>'.$row['bill_no'].'</td>
                            <td>'.$row["bill_amt"].'</td>  
                            <td>'.$row["bill_date"].'</td></tr>'; 
                    }             
                echo'</tbody> 
                    </table>';
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
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                var start_date=$('#start_date').val();
                var end_date=$('#end_date').val();
                if(start_date.length>0 && end_date.length>0)
                {
                    $.ajax({
                        type: 'POST',
                        url: 'user_data.php',
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
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                var start_date=$('#start_date').val();
                var end_date=$('#end_date').val();
                if(start_date.length>0 && end_date.length>0)
                {
                    $.ajax({
                        type: 'POST',
                        url: 'user_data.php',
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
                    $('.page-loader-wrapper').hide();
                }
            } );
        });

        $(document).ready(function() {         
            $('.next').click(function(){
                var page= <?php echo $page+1;?>;
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                var start_date=$('#start_date').val();
                var end_date=$('#end_date').val();
                if(start_date.length>0 && end_date.length>0)
                {
                    $.ajax({
                        type: 'POST',
                        url: 'user_data.php',
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
                </script>

    <script src="../plugins/sweetalert/sweetalert.min.js"></script>
    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>


