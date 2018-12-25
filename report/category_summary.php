<?php
session_start();
    include('../connect.php');
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
    $product_query=$db->prepare("select sum(total_amt) as total, category_name from( select DISTINCT bill_no, total_amt, category_name from  transaction_mst, product, transaction_dtl, category_dtl where transaction_dtl.transaction_id= transaction_mst.transaction_id and transaction_dtl.item_id=product.product_id and transaction_mst.device_id='$d_id' and product.category_id= category_dtl.category_id and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date') T1 group by category_name");
    $product_query->execute();
    if($data=$product_query->fetch())
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
            </div>';
            echo'<div class="row">
                    <div class="col-sm-6">
                    <strong>Category Name</strong>   
                    </div>
                    <div class="col-sm-6">
                        <strong>Category Total</strong>
                    </div>      
            </div>';
            do
            {
                echo'<div class="row">
                    <div class="col-sm-6">';
                        echo $data['category_name'];
                echo'</div>
                <div class="col-sm-6">';
                    echo $data['total'];
                echo'</div>      
            </div>';
            }
            while($data=$product_query->fetch());
        }
?>
        <div class="col-sm-2">
            <div class="button-demo">
                <input type="button" name="" class="btn btn-info waves-effect m-r-20"  value="Click here to see details" id="details">
            </div>
        </div>
    <script type="text/javascript">
        $(document).ready(function() {         
            $('#details').click(function(){
                 $('.page-loader-wrapper').show();
                $('#data-display').show();
                var start_date=$('#start_date').val();
                var end_date=$('#end_date').val();
                if(start_date.length>0 && end_date.length>0)
                {
                    $.ajax({
                        type: 'POST',
                        url: 'category_data.php',
                        data: { "start_date":start_date,"end_date":end_date,'page':1 },
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