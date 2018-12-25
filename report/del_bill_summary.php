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

    $excl_product_query=$db->prepare("select sum(bill_amt) as total, sum(tax_amt) as total_tax, sum(parcel_amt) as total_parcel_amt, sum(cash) as total_cash, sum(credit) as total_credit, sum(digital) as total_digital, sum(discount) as total_discount from( select distinct bill_no, bill_amt, tax_amt, parcel_amt, cash, credit, digital, discount from  transaction_mst where transaction_mst.device_id='$d_id'  and tax_state=0 and bill_date between '$start_date' and '$end_date')T1");
    $excl_product_query->execute();
    if($excl_data=$excl_product_query->fetch())
    {
        $excl_final_data=$excl_data['total']+$excl_data['total_parcel_amt']+$excl_data['total_tax']-$excl_data['total_discount'];
    }

    $incl_product_query=$db->prepare("select sum(bill_amt) as total, sum(tax_amt) as total_tax, sum(cash) as total_cash, sum(credit) as total_credit,sum(parcel_amt) as total_parcel_amt, sum(digital) as total_digital, sum(discount) as total_discount from( select distinct bill_no, bill_amt, tax_amt, parcel_amt, cash, credit, digital, discount from  transaction_mst where transaction_mst.device_id='$d_id'  and tax_state!=0 and bill_date between '$start_date' and '$end_date')T1");
    $incl_product_query->execute();
    if($incl_data=$incl_product_query->fetch())
    {
        $incl_final_data=$incl_data['total']+$incl_data['total_parcel_amt']-$incl_data['total_discount'];
    }

    $final_data=$excl_final_data+$incl_final_data;


    $product_query=$db->prepare("select sum(bill_amt) as total, sum(tax_amt) as total_tax, sum(cash) as total_cash,sum(parcel_amt) as total_parcel_amt, sum(credit) as total_credit, sum(digital) as total_digital, sum(discount) as total_discount, sum(round_off) as total_round_off from( select distinct bill_no, bill_amt, tax_amt, parcel_amt, cash, credit, digital, discount, round_off from  transaction_mst where transaction_mst.device_id='$d_id'  and bill_date between '$start_date' and '$end_date')T1");
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
                        <strong>Bill Amount : </strong>
                    </div>
                <div class="col-sm-4">';
                    echo $data['total'];
                echo'</div>
                <div class="col-sm-2">
                   <strong>Total cash : </strong> 
                </div>
                <div class="col-sm-4">';
                    echo $data['total_cash'];
                echo'</div></div> 
                <div class="row">
                    <div class="col-sm-2">
                        <strong>Total Tax : </strong>
                    </div>
                <div class="col-sm-4">';
                    echo $data['total_tax'];
                echo'</div>
                <div class="col-sm-2">
                   <strong>Total Credit : </strong> 
                </div>
                <div class="col-sm-4">';
                    echo $data['total_credit'];
                echo'</div></div>
                <div class="row">
                    <div class="col-sm-2">
                        <strong>Total Discount : </strong>
                    </div>
                <div class="col-sm-4">';
                    echo $data['total_discount'];
                echo'</div>
                <div class="col-sm-2">
                   <strong>Total Digital Payment : </strong> 
                </div>
                <div class="col-sm-4">';
                    echo $data['total_digital'];
                    echo'</div></div>
                <div class="row">
                    <div class="col-sm-2">
                        <strong>Round Off : </strong>
                    </div>
                <div class="col-sm-4">';
                    echo $data['total_round_off'];
                echo'</div>
                <div class="col-sm-2">
                 <strong>Final Amount :</strong>
                </div>
                <div class="col-sm-4">';
                echo $final_data+$data['total_round_off'];
                    echo'</div></div>
                    <div class="row">
                    <div class="col-sm-2">
                        <strong>Parcel Charge : </strong>
                    </div>
                <div class="col-sm-4">';
                    echo $data['total_parcel_amt'];
                echo'</div>
                <div class="col-sm-2">
                 <strong></strong>
                </div>
                <div class="col-sm-4">';
                    echo'</div></div>';
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
                        url: 'del_bill_data.php',
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
   