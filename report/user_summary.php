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
    $product_query=$db->prepare("select sum(if(tax_state=0,parcel_amt+tax_amt+bill_amt-discount,parcel_amt+bill_amt-discount)) as total, user_name  from( select DISTINCT bill_no, tax_amt, tax_state, parcel_amt, bill_amt, discount, user_name from  transaction_mst, user_dtl where transaction_mst.user_id=user_dtl.user_id and transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date') T1 group by user_name");
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
            do
            {
                echo'<div class="row">
                    <div class="col-sm-2">
                        <strong>User Name : </strong>
                    </div>
                <div class="col-sm-4">';
                    echo $data['user_name'];
                echo'</div>
                <div class="col-sm-2">
                   <strong>Total Amount : </strong> 
                </div>
                <div class="col-sm-4">';
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
                        url: 'user_data.php',
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