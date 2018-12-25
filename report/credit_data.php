<style>
a.disabled {
   pointer-events: none;
   cursor: pointer;
}
</style>
<?php
    session_start();
    include('../connect.php');
    include('credit_excel.php');
    $limit=10;
    $first_date =  new DateTime();
    $clone_first=clone $first_date;
    $report_date_first=$clone_first->format('l jS \of F Y');
    
    $d_id=$_SESSION['d_id'];

    $status='active';
    $count_query=$db->prepare("select customer_name,customer_contact, sum(credit) AS total from( select DISTINCT bill_no, customer_id, customer_name, customer_contact, credit from customer_mst, transaction_mst where customer_mst.customer_id=transaction_mst.customer_id and transaction_mst.status='$status' and customer_mst.deviceid='$d_id') T1 group by (customer_id)");
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

    $sql_query = $db->prepare("select customer_name,customer_contact, sum(credit) AS total from( select DISTINCT bill_no, customer_id, customer_name, customer_contact, credit from customer_mst, transaction_mst where customer_mst.customer_id=transaction_mst.customer_id and transaction_mst.status='$status' and customer_mst.deviceid='$d_id')T1 group by (customer_id) LIMIT $start_from, $limit");  
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
            <th>Amount</th>  
            </tr>  
            </thead>  
            <tbody id="target-content">
            <?php  
            while ($row = $sql_query->fetch()) {
            ?>  
                        <tr>  
                        <td><?php echo $row["customer_name"]; ?></td>
                        <td><?php echo $row["customer_contact"]; ?></td>  
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
                if(1)
                {
                    $.ajax({
                        type: 'POST',
                        url: 'credit_data.php',
                        data: {"page":page },
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
                if(1)
                {
                    $.ajax({
                        type: 'POST',
                        url: 'credit_data.php',
                        data: {"page":page },
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
                        url: 'credit_data.php',
                        data: {"page":page },
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

