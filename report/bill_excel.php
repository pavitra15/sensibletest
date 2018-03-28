<?php
    session_start();
    include ('../connect.php');
    $first_date =  new DateTime($_POST['start_date']);
    $second_date = new DateTime($_POST['end_date']);
    $clone_first=clone $first_date;
    $clone_second=clone $second_date;
    $start_date = $clone_first->format('Y-m-d 00:00:00');
    $end_date = $second_date->format('Y-m-d 23:59:59');

    $report_date_first=$clone_first->format('l jS \of F Y');
    $report_date_second=$clone_second->format('l jS \of F Y');

    $d_id=$_SESSION['d_id'];

    $query=$db->prepare("Select device_name, serial_no from device where d_id='$d_id'");
    $query->execute();
    while ($device_data=$query->fetch()) 
    {
        $device_name=$device_data['device_name'];
        $serial_no=$device_data['serial_no'];
    }

    $status='active';
    $product_query=$db->prepare("select transaction_id, bill_no, bill_amt, bill_date from  transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date'");

    $product_query->execute();
    $response='{"data":[';
    if($data=$product_query->fetch())
    {
        do
        {
            $transaction_id=$data['transaction_id'];
            $response.='{"bill_no":"'.$data['bill_no'].'","bill_amt":'.$data['bill_amt'].',"bill_date":"'.$data['bill_date'].'","detail":[';

            $count_query=$db->prepare("select english_name, quantity, price, total_amt from transaction_dtl, product where transaction_dtl.transaction_id='$transaction_id' and product.product_id=transaction_dtl.item_id");
            $count_query->execute();
            if($row_cnt = $count_query->fetch())
            {
                do
                {
                    $response.='{"english_name":"'.$row_cnt['english_name'].'","quantity":'.$row_cnt['quantity'].',"price":'.$row_cnt['price'].',"total_amt":'.$row_cnt['total_amt'].'},';
                }
                while ($row_cnt = $count_query->fetch());
                $response = rtrim($response, ',');
                $response.=']},';
            }
            else
            {
                $response.=']},';
            }       
        }
        while($data=$product_query->fetch());
        $response = rtrim($response, ',');
        $response.=']}';
    }
    else
    {
        $response.=']}';
    }


    $status='active';
    $product_query=$db->prepare("select sum(bill_amt) as total, sum(tax_amt) as total_tax, sum(cash) as total_cash, sum(credit) as total_credit, sum(digital) as total_digital from  transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date'");
    $product_query->execute();
    if($data=$product_query->fetch())
    {
        do
        {
            $bill_total=$data['total'];
            $cgst=$data['total_tax']/2;
            $total_amt=$data['total']+$data['total_tax'];
        }
        while($data=$product_query->fetch());
    }
?>

    <script type="text/javascript" src="jszip.js"></script>
    <script type="text/javascript" src="FileSaver.js"></script>
    <script type="text/javascript" src="myexcel.js"></script>
    <script>
        
        var myMessage='<?php echo $response; ?>';
        var jsonData = JSON.parse(myMessage);
        
        function go(){

            var excel = $JExcel.new("Calibri light 11 #333333");   
            
            excel.set( {sheet:0,value:"Report" } );
            
            var evenRow=excel.addStyle ( {                                                                  
                border: "none,none,none,thin #333333"});              

            var oddRow=excel.addStyle ( {                                                                   // Style for odd ROWS
                fill: "#ECECEC" ,                                     

                border: "none,none,none,thin #333333"}); 
            
    
            var headers=["Sr.No","Bill No","Bill Amount","Bill Date","English Name","Quantity", "Price", "Total Amount"];

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
                    excel.set(0,2,4,"<?php echo $bill_total; ?>");

                    excel.set(0,1,5,"CGST",excel.addStyle({font: "Calibri 13 #333333 B"}));                                                      //      
                    excel.set(0,2,5,"<?php echo $cgst; ?>");

                    excel.set(0,1,6,"SGST",excel.addStyle({font: "Calibri 13 #333333 B"}));                                                      //      
                    excel.set(0,2,6,"<?php echo $cgst; ?>");

                    excel.set(0,1,7,"Total Amount",excel.addStyle({font: "Calibri 13 #333333 B"}));                                                      //      
                    excel.set(0,2,7,"<?php echo $total_amt; ?>");


            for (var i=0;i<headers.length;i++){                                                             // Loop all the haders
                excel.set(0,i,9,headers[i],formatHeader);                                                   // Set CELL with header text, using header
                excel.set(0,i,undefined,"auto");                    
            }
            
                var k=1,j=0,n=10;

                for (var i = 0; i < jsonData.data.length; i++) 
                {
                    var counter = jsonData.data[j];
                    excel.set(0,0,n,k,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,1,n,counter.bill_no,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,2,n,counter.bill_amt,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,3,n,counter.bill_date,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));

                    for (var m =0; m < counter.detail.length; m++) 
                    {

                        excel.set(0,4,n,counter.detail[m].english_name,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        excel.set(0,5,n,counter.detail[m].quantity,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        excel.set(0,6,n,counter.detail[m].price,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        excel.set(0,7,n,counter.detail[m].total_amt,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        n++;
                    }
                    j++;
                    k++;
                }
            
                 excel.set(0,0,undefined,7);
                 excel.set(0,1,undefined,15);
                 excel.set(0,2,undefined,10);
                 excel.set(0,3,undefined,20);
                 excel.set(0,4,undefined,15);
                 excel.set(0,5,undefined,10);
                 excel.set(0,6,undefined,10);
                 excel.set(0,7,undefined,15);  
                                                                           // Set COLUMN 3 to 20 chars width
           
            excel.generate("Bill_Report.xlsx");
            
        }
    </script>
  </head>
  
</html>
