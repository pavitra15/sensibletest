<?php
    $first_date =  new DateTime($_POST['start_date']);
    $second_date = new DateTime($_POST['end_date']);
    $clone_first=clone $first_date;
    $clone_second=clone $second_date;
    $start_date = $clone_first->format('Y-m-d 00:00:00');
    $end_date = $second_date->format('Y-m-d 23:59:59');

    $report_date_first=$clone_first->format('l jS \of F Y');
    $report_date_second=$clone_second->format('l jS \of F Y');

    $d_id=$_SESSION['d_id'];

    $select_query=$db->prepare("Select device_name, serial_no from device where d_id='$d_id'");
    $select_query->execute();
    while ($device_data=$select_query->fetch()) 
    {
        $device_name=$device_data['device_name'];
        $serial_no=$device_data['serial_no'];
    }

$status='active';
    $count_query=$db->prepare("select english_name,regional_name, sum(quantity) AS total, sum(total_amt) as total_amt from( select DISTINCT bill_no, product.product_id, english_name, regional_name, transaction_dtl.quantity, transaction_dtl.total_amt from product, transaction_dtl,transaction_mst where transaction_dtl.transaction_id=transaction_mst.transaction_id and product.product_id=transaction_dtl.item_id and transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date'  and transaction_dtl.status='active') T1 group by (product_id)");
    $count_query->execute();
    $response='{"data":[';
    while($data=$count_query->fetch())
    {
        $response.='{"english_name":"'.$data['english_name'].'","regional_name":"'.$data['regional_name'].'","total":'.$data['total'].',"total_amt":'.$data['total_amt'].'},';
    }
    $response = rtrim($response, ',');
    $response.=']}';
    
?>

    <script type="text/javascript" src="jszip.js"></script>
    <script type="text/javascript" src="FileSaver.js"></script>
    <script type="text/javascript" src="myexcel.js"></script>
    <script>
        
        var myMessage='<?php echo $response; ?>';
        console.log(myMessage);
        var jsonData = JSON.parse(myMessage);
        console.log(jsonData);

        function go(){

            var excel = $JExcel.new("Calibri light 11 #333333");   
            
            excel.set( {sheet:0,value:"Report" } );
            
            var evenRow=excel.addStyle ( {                                                                  // Style for even ROWS
                border: "none,none,none,thin #333333"});              

            var oddRow=excel.addStyle ( {                                                                   // Style for odd ROWS
                fill: "#ECECEC" ,                                     

                border: "none,none,none,thin #333333"}); 
            
    
            var headers=["Sr.No","English Name","Regional Name","Quantity","Total Ammount"];                           // This array holds the 

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



            for (var i=0;i<headers.length;i++){                                                             // Loop all the haders
                excel.set(0,i,5,headers[i],formatHeader);                                                   // Set CELL with header text, using header
                excel.set(0,i,undefined,"auto");                    
            }
            
                var tot_length=jsonData.data.length+6;
                var j=0;
                for (var i = 6; i < tot_length; i++) 
                {
                    var counter = jsonData.data[j];
                    excel.set(0,0,i,j+1,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                                                                       // Get a random date
                excel.set(0,1,i,counter.english_name,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));                                                        // Store the random date as STRING
                excel.set(0,2,i,counter.regional_name,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                excel.set(0,3,i,counter.total,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));

                excel.set(0,4,i,counter.total_amt,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        

                       j++;
                    }
            
                 excel.set(0,0,undefined,7);
                 excel.set(0,3,undefined,10);
                 excel.set(0,4,undefined,15);  
                                                                           // Set COLUMN 3 to 20 chars width
           
            excel.generate("Product_Report.xlsx");
            
        }
    </script>
  </head>
  
</html>
