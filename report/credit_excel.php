
<?php
// session_start();
// include('../connect.php');
    $first_date =  new DateTime();
    $clone_first=clone $first_date;
    $start_date = $clone_first->format('Y-m-d 00:00:00');
    
    $report_date_first=$clone_first->format('l jS \of F Y');
    
    $d_id=$_SESSION['d_id'];

    $select_query=$db->prepare("Select device_name, serial_no from device where d_id='$d_id'");
    $select_query->execute();
    while ($device_data=$select_query->fetch()) 
    {
        $device_name=$device_data['device_name'];
        $serial_no=$device_data['serial_no'];
    }

    $status='active';
    $count_query=$db->prepare("select customer_name,customer_contact, sum(credit) AS total from( Select DISTINCT bill_no, customer_id, customer_name, customer_contact, credit from customer_mst, transaction_mst where customer_mst.customer_id=transaction_mst.customer_id and transaction_mst.status='$status' and customer_mst.deviceid='$d_id' ) T1 group by (customer_id)");
    $count_query->execute();
    $response='{"data":[';
    while($data=$count_query->fetch())
    {
        $response.='{"customer_name":"'.$data['customer_name'].'","customer_contact":"'.$data['customer_contact'].'","total":'.$data['total'].'},';
    }
    $response=substr($response, 0,-1);
    $response.=']}';
    
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
            
            var evenRow=excel.addStyle ( {                                                                  // Style for even ROWS
                border: "none,none,none,thin #333333"});              

            var oddRow=excel.addStyle ( {                                                                   // Style for odd ROWS
                fill: "#ECECEC" ,                                     

                border: "none,none,none,thin #333333"}); 
            
    
            var headers=["Sr.No","Customer Name","Customer Contact","Credit Amount"];                           // This array holds the 

            var formatHeader=excel.addStyle ( {                                                             
                    border: "thin #333333,thin #333333,thin #333333,thin #333333",                                                  
                    font: "Calibri 13 #333333 B"});    

                    excel.set(0,1,0,"Report Date", excel.addStyle({font: "Calibri 13 #333333 B"}));

                    excel.set(0,2,0,"<?php echo $report_date_first; ?>");

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
                excel.set(0,1,i,counter.customer_name,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));                                                        // Store the random date as STRING
                excel.set(0,2,i,counter.customer_contact,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                excel.set(0,3,i,counter.total,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));

               
                       j++;
                    }
            
                 excel.set(0,0,undefined,7);
                 excel.set(0,3,undefined,10);
                 excel.set(0,4,undefined,15);  
                                                                           // Set COLUMN 3 to 20 chars width
           
            excel.generate("Credit_Report.xlsx");
            
        }
    </script>
  </head>
  
</html>
