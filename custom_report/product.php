<?php
    include('../connect.php');
    $u_id=$_GET['u_id']; 

    $select_query=$db->prepare("select d_id from delete_device where u_id='$u_id'");
    $select_query->execute();
    if($select_query->rowCount())
    {
        while($data=$select_query->fetch())
        {
            $d_id=$data['d_id'];
        }
    }   
    else
    {
        echo '<script >window.location="invalid";</script>';
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>Welcome | Sensible Connect - Admin</title>
        <link rel="icon" href="../favicon.png" type="image/x-icon">
        
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

        <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

        <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

        <link href="../plugins/animate-css/animate.css" rel="stylesheet" />
        <link href="../plugins/sweetalert/sweetalert.css" rel="stylesheet" />

        <link href="../plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

        <link href="../css/style.css" rel="stylesheet">
        <link href="../css/custom.css" rel="stylesheet">
        <link href="../css/themes/all-themes.css" rel="stylesheet" />
        <body class="theme-teal">
            <div class="page-loader-wrapper">
                <div class="loader">
                    <div class="preloader">
                        <div class="spinner-layer pl-red">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div>
                            <div class="circle-clipper right">
                                <div class="circle"></div>
                            </div>
                        </div>
                    </div>
                    <p>Please wait...</p>
                </div>
            </div>
            <div class="overlay"></div>
    
            <nav class="navbar">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                        <a href="javascript:void(0);" class="bars"></a>
                        <a class="navbar-brand" href="../cumulative/index">SENSIBLE CONNECT</a>
                    </div>
                    <div class="collapse navbar-collapse" id="navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <?php
                                $status="active";
                                $name_query=$db->prepare("select device_name,tax_type,prnt_billno, prnt_billtime, model from device where d_id='$d_id'");
                                $name_query->execute();
                                while ($name_data=$name_query->fetch()) 
                                {
                                    $device_name=$name_data['device_name'];
                                    $device_model=$name_data['model'];
                                    $tax_type=$name_data['tax_type'];
                                    $prnt_billno=$name_data['prnt_billno'];
                                    $prnt_billtime=$name_data['prnt_billtime'];
                                }

                                $total_query=$db->prepare("select product.product_id from product,price_mst, stock_mst where status='$status' and deviceid='$d_id' and product.product_id= price_mst.product_id and product.product_id=stock_mst.product_id");
                                $total_query->execute();
                                $total_records=$total_query->rowCount();    
                                $total_pagess = ceil($total_records / 50);
                            ?>
                            <li>
                                <a href="javascript:void(0);" class="device_nm" class="dropdown-toggle" data-toggle="dropdown" role="button">
                                    <span class="device_nm"><?php echo $device_name; ?></span>
                                </a>                       
                            </li>
                    </ul>
                </div>
            </div>
        </nav>
        <section>
            <aside id="leftsidebar" class="sidebar">
                <div class="menu">
                    <?php 
                        include('left_menu.php');
                    ?>
                </div>
               <?php include('../footer.html'); ?>
            </aside>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="block-header">
                    <h2>
                        PRODUCTS
                    </h2>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 card">
                            <div class="header">
                                <div class="row clearfix">
                                    <div class="col-xs-12 col-sm-6">
                                        <button type="button" name="save" id="download" class="btn btn-info save">Download</button>
                                    </div>
                                </div>
                            </div>
                           
                        <div class="body table-responsive">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " id="data-display" style="display: none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   
    <script type="text/javascript">
        $(document).ready(function() 
        {  
            var page="1";
            var d_id= <?php echo $d_id; ?>;
            $('#data-display').show();
            $('.page-loader-wrapper').show();
            $.ajax({
                type: 'POST',
                url: 'product_data.php',
                data: { "page":page ,"d_id": d_id},
                cache: false,
                success: function(data)
                {
                    $('.page-loader-wrapper').hide();
                    $('#data-display').html(data);
                }
            });
        });

    $(document).ready(function()
    {
        $('#left_product').addClass('active');
    });

    $(document).ready(function()
        {         
            $('#download').click(function(){
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
                        url: 'product_excel.php',
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
            
    
            var headers=["Sr.No","English Name","Regional Name","Weightable","Category Name","Discount","Unit Name","Stockable","Current Stock","Reorder Level", "Tax Name", "Price"];

            var formatHeader=excel.addStyle ( {                                                             
                    border: "thin #333333,thin #333333,thin #333333,thin #333333",                                                  
                    font: "Calibri 13 #333333 B"});    

                    excel.set(0,1,2,"Device Name", excel.addStyle({font: "Calibri 13 #333333 B"}));

                    excel.set(0,2,2,"<?php echo $device_name; ?>");

                     excel.set(0,1,3,"Serial Number",excel.addStyle({font: "Calibri 13 #333333 B"}));                                                      //      
                     excel.set(0,2,3,"<?php echo $serial_no; ?>");

            for (var i=0;i<headers.length;i++){                                                             // Loop all the haders
                excel.set(0,i,5,headers[i],formatHeader);                                                   // Set CELL with header text, using header
                excel.set(0,i,undefined,"auto");                    
            }
            
                var k=1,j=0,n=6;
                for (var i = 0; i < tempData.length; i++) 
                {
                    var counter = tempData[j];
                    excel.set(0,0,n,k,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,1,n,counter.english_name,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,2,n,counter.regional_name,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,3,n,counter.weighing,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,4,n,counter.category_name,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,5,n,counter.discount,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,6,n,counter.unit_name,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,7,n,counter.stockable,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,8,n,counter.current_stock,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,9,n,counter.reorder_level,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,10,n,counter.tax_name,excel.addStyle( {align:"L", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    excel.set(0,11,n,counter.price1,excel.addStyle( {align:"R", border: "thin #333333,thin #333333,thin #333333,thin #333333"}));
                    j++;
                    k++;
                    n++;
                }
            
                 excel.set(0,0,undefined,7);
                 excel.set(0,1,undefined,30);
                 excel.set(0,2,undefined,30);
                 excel.set(0,3,undefined,20);
                 excel.set(0,4,undefined,20);
                 excel.set(0,5,undefined,20);
                 excel.set(0,6,undefined,20);
                 excel.set(0,7,undefined,20);
                 excel.set(0,8,undefined,15);
                 excel.set(0,9,undefined,10);
                 excel.set(0,10,undefined,10);
                 excel.set(0,11,undefined,15);  
            excel.generate("Product.xlsx");
            
        }

        });
    });
 
</script>

<script type="text/javascript" src="jszip.js"></script>
    <script type="text/javascript" src="FileSaver.js"></script>
    <script type="text/javascript" src="myexcel.js"></script>

    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>
    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <script src="../plugins/node-waves/waves.js"></script>
    <script src="../js/admin.js"></script>
    <script src="../js/demo.js"></script>
</body>

</html>