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
    $response='{"data":[';
    $product_query=$db->prepare("select bill_date, kot_mst.bill_no, sum(case when state = 0 then 1 else 0 end) active_cnt, sum(case when state = 2 then 1 else 0 end) cancel_cnt from transaction_mst, kot_mst where transaction_mst.device_id='$d_id' and transaction_mst.bill_no=kot_mst.bill_no and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date' group by transaction_mst.transaction_id");
    $product_query->execute();
    if($data=$product_query->fetch())
    {
        do
        {
            $bill_no=$data['bill_no'];
            $response.='{"bill_no":"'.$data['bill_no'].'","bill_date":"'.$data['bill_date'].'","active_cnt":'.$data['active_cnt'].',"cancel_cnt":"'.$data['cancel_cnt'].'","detail":[';

            $count_query=$db->prepare("select kot_id from kot_mst where bill_no='$bill_no'");
            $count_query->execute();
            if($row_cnt = $count_query->fetch())
            {
                do
                {
                    $response.='{"kot_id":"'.$row_cnt['kot_id'].'","kot_data":[';
                    $kot_id=$row_cnt['kot_id'];
                    $kot_query=$db->prepare("select english_name, quantity, (case when state = 0 then 'active' else 'cancel' end)  as status  from kot_dtl, product where product.product_id=kot_dtl.product_id and kot_id='$kot_id'");
                    $kot_query->execute();
                    if($kot_data=$kot_query->fetch())
                    {
                        do
                        {
                             $response.='{"english_name":"'.$kot_data['english_name'].'","quantity":'.$kot_data['quantity'].',"state":"'.$kot_data['status'].'"},';
                        }
                        while($kot_data=$kot_query->fetch());
                    }
                    $response = rtrim($response, ',');
                    $response.=']},';

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
    }
    $response = rtrim($response, ',');
    $response.=']}';

?>

    <script type="text/javascript" src="jszip.js"></script>
    <script type="text/javascript" src="FileSaver.js"></script>
    <script type="text/javascript" src="myexcel.js"></script>
    <script>        
        var myMessage='<?php echo $response; ?>';
        var jsonData = JSON.parse(myMessage);
        
        function go()
        {

            var excel = $JExcel.new("Calibri light 11 #333333");   
            
            excel.set( {sheet:0,value:"Report" } );
            
            var evenRow=excel.addStyle ( {                                                                  
                border: "none,none,none,thin #333333"});              

            var oddRow=excel.addStyle ( {                                                                   // Style for odd ROWS
                fill: "#ECECEC" ,                                     

                border: "none,none,none,thin #333333"}); 
            
    
            var headers=["Sr.No","Bill No","Bill Date","Active Count","Cancel Count","KOT No.", "Product Name", "Quantity","status"];

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

            var k=6;
            var tot_length=jsonData.data.length+6;
                var j=0;
                for (var i = 6; i < tot_length; i++) 
                {
                    
                    var counter = jsonData.data[j];
                    excel.set(0,0,k,j+1,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                                                                       // Get a random date
                    excel.set(0,1,k,counter.bill_no,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));                                                        // Store the random date as STRING

                    excel.set(0,2,k,counter.bill_date,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));


                    excel.set(0,3,k,counter.active_cnt,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,4,k,counter.cancel_cnt,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                n=1;
                for (var m =0; m < counter.detail.length; m++) 
                {
                    excel.set(0,5,k,n,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    for (var c =0; c < counter.detail[m].kot_data.length; c++) 
                    {
                        excel.set(0,6,k,counter.detail[m].kot_data[c].english_name,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        excel.set(0,7,k,counter.detail[m].kot_data[c].quantity,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        excel.set(0,8,k,counter.detail[m].kot_data[c].state,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        k++;
                    }
                    k++;
                    n++;
                }
                j++;
                k++;
            }
        
                 excel.set(0,0,undefined,7);
                 excel.set(0,1,undefined,15);
                 excel.set(0,2,undefined,10);
                 excel.set(0,3,undefined,20);

            excel.generate("KOT_Report.xlsx");
            
        }
    </script>
  </head>
  
</html>
