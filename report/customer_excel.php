<?php
    session_start();
    include ('../connect.php');
    $first_date =  new DateTime();
    $clone_first=clone $first_date;
    $report_date_first=$clone_first->format('l jS \of F Y');
    
    $d_id=$_SESSION['d_id'];

    $query=$db->prepare("Select device_name, serial_no from device where d_id='$d_id'");
    $query->execute();
    while ($device_data=$query->fetch()) 
    {
        $device_name=$device_data['device_name'];
        $serial_no=$device_data['serial_no'];
    }

    $product_query=$db->prepare("select customer_id, customer_name, customer_contact from  customer_mst where customer_mst.deviceid='$d_id'");
    $product_query->execute();
    $response='{"data":[';
    if($data=$product_query->fetch())
    {
        do
        {
            $customer_id=$data['customer_id'];
            $response.='{"customer_name":"'.$data['customer_name'].'","mobile":"'.$data['customer_contact'].'","detail":[';

            $count_query=$db->prepare("select DISTINCT bill_no, bill_date, bill_amt, cash, credit, digital from transaction_mst where transaction_mst.customer_id='$customer_id'");
            $count_query->execute();
            if($row_cnt = $count_query->fetch())
            {
                do
                {
                    $response.='{"bill_no":"'.$row_cnt['bill_no'].'","bill_date":"'.$row_cnt['bill_date'].'","bill_amt":'.$row_cnt['bill_amt'].',"cash":'.$row_cnt['cash'].',"credit":'.$row_cnt['credit'].',"digital":'.$row_cnt['digital'].'},';
                }
                while ($row_cnt = $count_query->fetch());
                $response=substr($response, 0,-1); 
                $response.=']},';
            }
            else
            {
                $response.=']},';
            }       
        }
        while($data=$product_query->fetch());
        $response=substr($response, 0,-1);
        $response.=']}';
    }
    else
    {
        $response.=']}';
    }
?>

    <script type="text/javascript" src="jszip.js"></script>
    <script type="text/javascript" src="FileSaver.js"></script>
    <script type="text/javascript" src="myexcel.js"></script>
    <script>
        
        var myMessage='<?php echo $response; ?>';
        console.log(myMessage);
        var jsonData = JSON.parse(myMessage);

        function go(){

            var excel = $JExcel.new("Calibri light 11 #333333");   
            
            excel.set( {sheet:0,value:"Report" } );
            
            var evenRow=excel.addStyle ( {                                                                  
                border: "none,none,none,thin #333333"});              

            var oddRow=excel.addStyle ( {                                                                   // Style for odd ROWS
                fill: "#ECECEC" ,                                     

                border: "none,none,none,thin #333333"}); 
            
    
            var headers=["Sr.No","Customer Name","Mobile","Bill No","Bill Date","Bill Amount", "Cash", "Credit", "Digital"];

            var formatHeader=excel.addStyle ( {                                                             
                    border: "thin #333333,thin #333333,thin #333333,thin #333333",                                                  
                    font: "Calibri 13 #333333 B"});    

                    excel.set(0,1,0,"Report From", excel.addStyle({font: "Calibri 13 #333333 B"}));

                    excel.set(0,2,0,"<?php echo $report_date_first; ?>");

                    excel.set(0,1,2,"Device Name", excel.addStyle({font: "Calibri 13 #333333 B"}));

                    excel.set(0,2,2,"<?php echo $device_name; ?>");

                    excel.set(0,1,3,"Serial Number",excel.addStyle({font: "Calibri 13 #333333 B"}));                                                      //      
                    excel.set(0,2,3,"<?php echo $serial_no; ?>");


            for (var i=0;i<headers.length;i++){                                                             // Loop all the haders
                excel.set(0,i,5,headers[i],formatHeader);                                                   // Set CELL with header text, using header
                excel.set(0,i,undefined,"auto");                    
            }
            
                var k=1,j=0,n=6;

                for (var i = 0; i < jsonData.data.length; i++) 
                {
                    var counter = jsonData.data[j];
                    excel.set(0,0,n,k,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,1,n,counter.customer_name,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,2,n,counter.mobile,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    for (var m =0; m < counter.detail.length; m++) 
                    {
                        excel.set(0,3,n,counter.detail[m].bill_no,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        excel.set(0,4,n,counter.detail[m].bill_date,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        excel.set(0,5,n,counter.detail[m].bill_amt,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        excel.set(0,6,n,counter.detail[m].cash,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        excel.set(0,7,n,counter.detail[m].credit,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        excel.set(0,8,n,counter.detail[m].digital,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                        n++;
                    }
                    j++;
                    k++;
                }
            
                 excel.set(0,0,undefined,7);
                 excel.set(0,1,undefined,15);
                 excel.set(0,2,undefined,20);
                 excel.set(0,3,undefined,20);
                 excel.set(0,4,undefined,20);
                 excel.set(0,5,undefined,10);
                 excel.set(0,6,undefined,10);
                 excel.set(0,7,undefined,15);  
                                                                           // Set COLUMN 3 to 20 chars width
           
            excel.generate("Customer_Report.xlsx");
            
        }
    </script>
  </head>
  
</html>
