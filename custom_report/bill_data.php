<?php
    session_start();
    include('../connect.php');
    $limit=30;
    $d_id=$_POST['d_id'];

    $query=$db->prepare("Select device_name, serial_no from device where d_id='$d_id'");
    $query->execute();
    while ($device_data=$query->fetch()) 
    {
        $device_name=$device_data['device_name'];
        $serial_no=$device_data['serial_no'];
    }

    $status='active';

     $product_query=$db->prepare("select sum(if(tax_state=0,parcel_amt+tax_amt+bill_amt-discount,parcel_amt+bill_amt-discount)) as total, sum(tax_amt) as total_tax, sum(cash) as total_cash, sum(credit) as total_credit, sum(digital) as total_digital, sum(discount) as total_discount, sum(round_off) as total_round_off from( Select DISTINCT bill_no, tax_state, bill_amt, tax_amt, cash, credit, digital, discount, parcel_amt, round_off from  transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status')T1");
    $product_query->execute();
    if($data=$product_query->fetch())
    {
        do
        {
            $bill_total=$data['total'];
            $bill_round_off=$data['total_round_off'];
            $cgst=$data['total_tax']/2;
        }
        while($data=$product_query->fetch());
    }

    $product_query=$db->prepare("select DISTINCT bill_no, bill_amt, bill_date from  transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' ");

    $product_query->execute();
    $total_records=$product_query->rowCount();  

    $total_pages = ceil($total_records / $limit);
    $total_pagess = ceil($total_records / 50);

    if (isset($_POST["page"])) 
    { 
        $page  = $_POST["page"]; 
    }
    else 
    {
        $page=1; 
    }

    $start_from = ($page-1) * $limit;

    $Product_query=$db->prepare("select DISTINCT bill_no, bill_amt, tax_state, discount, parcel_amt, tax_amt,parcel_amt, bill_date from  transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' LIMIT $start_from, $limit");  
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
                echo'   
            <div class="col-sm-2">
                <div class="button-demo">
                    <input type="button" name="" class="btn btn-info waves-effect m-r-20"  value="DOWNLOAD EXCEL" id="excel">
                </div>
            </div>
            <table class="table table-striped">  
                <thead>  
                    <tr>  
                        <th>Bill No</th>
                        <th>Amount</th>
                        <th>Tax</th>
                        <th>Discount</th>
                        <th>Net</th>
                        <th>Date</th> 
                    </tr>  
                </thead>  
                <tbody id="target-content">';
                    while ($row = $Product_query->fetch()) 
                    {
                        $bill_no=$row['bill_no'];
                        $trans_query=$db->prepare("select transaction_id from transaction_mst where transaction_mst.device_id='$d_id' and bill_no='$bill_no'");
                        $trans_query->execute();
                        while($trans_data=$trans_query->fetch())
                        {
                            $transaction_id=$trans_data['transaction_id'];
                        }

                        $count_query=$db->prepare("select DISTINCT item_name as english_name, quantity, price, total_amt, transaction_dtl.status from transaction_dtl where transaction_dtl.transaction_id='$transaction_id'");
                        $count_query->execute();
                        $cnt=$count_query->rowCount();
                        echo'<tr>  
                            <td>'.$row['bill_no'].'</td>
                            <td>'.round($row["bill_amt"]).'</td>  
                            <td>'.$row["tax_amt"].'</td> 
                            <td>'.$row["discount"].'</td>';
                            echo'<td>';
                            if($row["tax_state"]==0)
                            {
                                echo round($row["parcel_amt"]+$row["tax_amt"]+$row["bill_amt"]-$row["discount"]);
                            }
                            else
                            {
                               echo round($row["parcel_amt"]+$row["bill_amt"]-$row["discount"]);
                            }
                            echo'</td><td>'.$row["bill_date"].'</td> '; 
                        echo'</tr>';          
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
                var d_id=<?php echo $d_id; ?>;
                var page=$(this).text();
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: 'bill_data.php',
                    data: { "d_id":d_id,"page":page },
                    cache: false,
                    success: function(data)
                    {
                        $('.page-loader-wrapper').hide();
                        $('#data-display').html(data);
                    }
                });
            });
        });

        $(document).ready(function() {         
            $('.prev').click(function(){
                var d_id=<?php echo $d_id; ?>;
                var page= <?php echo $page-1;?>;
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: 'bill_data.php',
                    data: { "d_id":d_id, "page":page },
                    cache: false,
                    success: function(data)
                    {
                        $('.page-loader-wrapper').hide();
                        $('#data-display').html(data);
                    }
                });
            });
        });

        $(document).ready(function() {         
            $('.next').click(function(){
                var d_id=<?php echo $d_id; ?>;
                var page= <?php echo $page+1;?>;
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: 'bill_data.php',
                    data: { "d_id":d_id, "page":page },
                    cache: false,
                    success: function(data)
                    {
                        $('.page-loader-wrapper').hide();
                        $('#data-display').html(data);
                    }
                });
            });
        });

        $(document).ready(function()
        {         
            $('#excel').click(function(){
                var d_id=<?php echo $d_id; ?>;
                var jsonData=[];
                var tempData = [];
                var total_page=<?php echo $total_pagess; ?>;
                recursiveAjaxCall(total_page,1);
                function recursiveAjaxCall(aNum,currentNum)
                {
                    $('.page-loader-wrapper').show();
                    $.ajax({
                        type: 'POST',
                        url: 'bill_excel.php',
                        data: {page_no: currentNum,d_id:d_id},
                        success: function(data)
                        {
                            var sk = JSON.parse(data);
                            var next=sk.page;
                            for (var i = 0; i < sk.data.length; i++) 
                            {
                                tempData.push(sk.data[i]);
                            }
                            // console.log(tempData);
                            if(next<=aNum)
                            {
                                recursiveAjaxCall(aNum,next);
                            }
                            else
                            {
                                $('.page-loader-wrapper').hide();
                                go();
                            }
                        },
                        async:   true
                      });
                }
               
                function go(){

            var excel = $JExcel.new("Calibri light 11 #333333");   
            
            excel.set( {sheet:0,value:"Report" } );
            
            var evenRow=excel.addStyle ( {                                                                  
                border: "none,none,none,thin #333333"});              

            var oddRow=excel.addStyle ( {                                                                   // Style for odd ROWS
                fill: "#ECECEC" ,                                     

                border: "none,none,none,thin #333333"}); 
            
    
            var headers=["Sr.No","Bill No","Amount","Tax","Discount","Parcel Amount","Net","Bill Date","English Name","Quantity", "Price", "Total Amount"];

            var formatHeader=excel.addStyle ( {                                                             
                    border: "thin #333333,thin #333333,thin #333333,thin #333333",                                                  
                    font: "Calibri 13 #333333 B"});    

                    excel.set(0,1,0,"Report From", excel.addStyle({font: "Calibri 13 #333333 B"}));

                    excel.set(0,2,0,"<?php echo $report_date_first; ?>");

                    excel.set(0,1,1,"Report To",excel.addStyle({font: "Calibri 13 #333333 B"}));                                                      //      
                    excel.set(0,2,1,"<?php echo $report_date_second; ?>");

                    excel.set(0,1,2,"Device Name", excel.addStyle({font: "Calibri 13 #333333 B"}));

                    excel.set(0,2,2,"<?php echo $device_name; ?>");

                     excel.set(0,1,3,"Serial Number",excel.addStyle({font: "Calibri 13 #333333 B"}));                                                      //      
                     excel.set(0,2,3,"<?php echo $serial_no; ?>");

                    excel.set(0,1,4,"Bill Amount",excel.addStyle({font: "Calibri 13 #333333 B"}));                                                      //      
                    excel.set(0,2,4,"<?php echo $bill_total+$bill_round_off; ?>");

                    excel.set(0,1,5,"CGST",excel.addStyle({font: "Calibri 13 #333333 B"}));                                                      //      
                    excel.set(0,2,5,"<?php echo $cgst; ?>");

                    excel.set(0,1,6,"SGST",excel.addStyle({font: "Calibri 13 #333333 B"}));                                                      //      
                    excel.set(0,2,6,"<?php echo $cgst; ?>");

                    // excel.set(0,1,7,"Total Amount",excel.addStyle({font: "Calibri 13 #333333 B"}));                                                      //      
                    // excel.set(0,2,7,"<?php //echo $total_amt; ?>");


            for (var i=0;i<headers.length;i++){                                                             // Loop all the haders
                excel.set(0,i,9,headers[i],formatHeader);                                                   // Set CELL with header text, using header
                excel.set(0,i,undefined,"auto");                    
            }
            
                var k=1,j=0,n=10;

                

                for (var i = 0; i < tempData.length; i++) 
                {
                    var counter = tempData[j];
                    excel.set(0,0,n,k,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,1,n,counter.bill_no,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,2,n,counter.bill_amt,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,3,n,counter.tax_amt,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,4,n,counter.discount,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,5,n,counter.parcel_amt,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,6,n,counter.net,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,7,n,counter.bill_date,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));

                    for (var m =0; m < counter.details.length; m++) 
                    {

                        excel.set(0,8,n,counter.details[m].english_name,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        excel.set(0,9,n,counter.details[m].quantity,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        excel.set(0,10,n,counter.details[m].price,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        excel.set(0,11,n,counter.details[m].total_amt,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        n++;
                    }
                    j++;
                    k++;
                }
            
                 excel.set(0,0,undefined,7);
                 excel.set(0,1,undefined,15);
                 excel.set(0,2,undefined,10);
                 excel.set(0,3,undefined,20);
                 excel.set(0,4,undefined,20);
                 excel.set(0,5,undefined,20);
                 excel.set(0,6,undefined,20);
                 excel.set(0,7,undefined,20);
                 excel.set(0,8,undefined,15);
                 excel.set(0,9,undefined,10);
                 excel.set(0,10,undefined,10);
                 excel.set(0,11,undefined,15);  
                                                                           // Set COLUMN 3 to 20 chars width
            excel.generate("Bill_Report.xlsx");
            
        }

        });
    });
                </script>

                <script type="text/javascript" src="jszip.js"></script>
    <script type="text/javascript" src="FileSaver.js"></script>
    <script type="text/javascript" src="myexcel.js"></script>

                     <script src="../plugins/sweetalert/sweetalert.min.js"></script>

<script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>


