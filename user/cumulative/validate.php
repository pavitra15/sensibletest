<?php
    session_start();
    include('../validate.php');     
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome | Sensible Connect - Admin</title>
    <link rel="icon" href="../favicon.png" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    
    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <link href="../plugins/morrisjs/morris.css" rel="stylesheet" />

    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />
        <link href="../plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <link href="../css/style.css" rel="stylesheet">
    
    <link href="../css/aviator.css" rel="stylesheet">

    <link href="../css/themes/all-themes.css" rel="stylesheet" />
</head>

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
                        include('../notification/notification.php');
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <section>
        <aside id="leftsidebar" class="sidebar">
            <?php 
                include('../user_menu.php');
            ?>
             <div class="menu">
                <ul class="list">
                    <li class="active">
                        
                    </li>
                </ul>
            </div>
            <?php include('../footer.html'); ?>
        </aside>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    DATA VALIDATION
                </h2>
            </div>
            <div class="row card">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="body">
                        <?php
                                include('../connect.php');
                                $correct=1;
                                $d_id=$_SESSION['d_id'];
                                $device_type=$_SESSION['device_type'];
                                $status='active';
                                if($device_type=="Table")
                                {
                                    $query=$db->prepare("select * from premise_dtl where deviceid='$d_id' and status='$status'");
                                    $query->execute();
                                    $count=$query->rowCount();
                                    if($count==0)
                                    {
                                        echo "Application Setting=> Premise Setting => Add data<br>";
                                        $correct=0;
                                    }
                                }
                                else
                                {
                                    $query=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
                                    $query->execute();
                                    $count=$query->rowCount();
                                    if($count==0)
                                    {
                                        // echo "Application Setting=> Customer Type Setting => Add data =>  <a href='customer'> Click here </a><br>";
                                        // $correct=0;
                                    }
                                }
                                $user_query=$db->prepare("select * from user_dtl where deviceid='$d_id' and status='$status'");
                                $user_query->execute();
                                $user_count=$user_query->rowCount();
                                if($user_count==0)
                                {
                                    echo "User Setting=> Add data  => <a href='user'> Click here </a><br>";
                                    $correct=0;
                                }
                                $category_query=$db->prepare("select * from category_dtl where deviceid='$d_id' and status='$status'");
                                $category_query->execute();
                                $category_count=$category_query->rowCount();
                                if($category_count==0)
                                {
                                    echo "Application Setting=> Category Setting => Add data =>  => <a href='category'> Click here </a><br>";
                                    $correct=0;
                                }

                                $product_query=$db->prepare("select * from product where deviceid='$d_id' and status='$status'");
                                $product_query->execute();
                                $product_count=$product_query->rowCount();
                                if($product_count==0)
                                {
                                    echo "Product Setting=> product => Add data =>  => <a href='product'> Click here </a><br>";
                                    $correct=0;
                                }
                                else
                                {
                                    $product_query=$db->prepare("select english_name, regional_name, weighing, category_id from product where deviceid='$d_id' and status='$status'");
                                    $product_query->execute();
                                    if($data=$product_query->fetch())
                                    {
                                        do
                                        {
                                            $english_name=$data['english_name'];
                                            $regional_name=$data['regional_name'];
                                            $weighing=$data['weighing'];
                                            $category_id=$data['category_id'];
                                            if($device_type=="Weighing")
                                            {
                                                if(($regional_name!="") && ($weighing!="") && ($category_id!=""))
                                                {
                                                }
                                                else
                                                {
                                                    echo "Product Setting=> product=>".$english_name." => Some value is empty  => <a href='product'> Click here </a><br>";
                                                    $correct=0;
                                                }
                                            }
                                            else
                                            {
                                                if(($category_id!=""))
                                                {
                                                }
                                                else
                                                {
                                                    echo "Product Setting=> product=>".$english_name." =>Some value is empty => <a href='product'> Click here </a><br>";
                                                    $correct=0;
                                                }   
                                            }
                                        }
                                        while ($data=$product_query->fetch());
                                    }
                                    $stock_query=$db->prepare("select stockable, unit_id,english_name from product, stock_mst where product.deviceid='$d_id' and product.status='$status' and stock_mst.product_id=product.product_id ");
                                    $stock_query->execute();
                                    if($stock_data=$stock_query->fetch())
                                    {
                                        do
                                        {
                                            $stockable=$stock_data['stockable'];
                                            $unit_id=$stock_data['unit_id'];
                                            $english_name=$stock_data['english_name'];
                                            if(($stockable!="") && ($unit_id!=""))
                                            {
                                            }
                                            else
                                            {
                                                echo "Product Setting=> Stock=>".$english_name." => Some value is empty => <a href='stock'> Click here </a><br><br>";
                                                $correct=0;
                                            }   
                                        }
                                        while ($stock_data=$stock_query->fetch());
                                    }
                                    $price_query=$db->prepare("select tax_id, price1,price2, price3, price4, price5, price6, price7,price8,price9, english_name from product, price_mst where product.deviceid='$d_id' and product.status='$status' and price_mst.product_id=product.product_id ");
                                    $price_query->execute();
                                    if($price_data=$price_query->fetch())
                                    {
                                        do
                                        {
                                            $tax_id=$price_data['tax_id'];
                                            $price1=$price_data['price1'];
                                            $price2=$price_data['price2'];
                                            $price3=$price_data['price3'];
                                            $price4=$price_data['price4'];
                                            $price5=$price_data['price5'];
                                            $price6=$price_data['price6'];
                                            $price7=$price_data['price7'];
                                            $price8=$price_data['price8'];
                                            $price9=$price_data['price9'];
                                            $english_name=$price_data['english_name'];
                                            

                                            if($tax_id=="")
                                            {
                                                echo "Product Setting=> Price=>".$english_name." => tax is not selected<br>";
                                                $correct=0;
                                            }
                                            else
                                            {
                                                for($i=1;$i<=$count;$i++)
                                                {
                                                    $name='price'.$i;
                                                    if(is_numeric($$name))
                                                    {
                                                        
                                                    }
                                                    else
                                                    {
                                                        echo "Product Setting=> Price=>".$english_name." => price is wrong => <a href='price'> Click here </a><br><br>";
                                                        $correct=0;
                                                        break;
                                                    }
                                                }
                                            }   
                                        }
                                        while ($price_data=$price_query->fetch());
                                    }
                                }

                                if($correct==1)
                                {
                                    echo "Great Job, All data is valid, now you can import data easily <br>";
                                }

                            ?>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 " style="margin-bottom: 15px">
                        <button class="btn waves-effect" id="skip">SKIP</button>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 15px; text-align: right;">
                       	<button class="btn btn-primary waves-effect" id="save">CONTINUE</button>
                    </div>
                </div>
            </div>
        </section>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script type="text/javascript">
    	
  		$(document).ready(function() 
        {         
            $('#skip').click(function()
            {
             	window.location.href = "../cumulative/index";     
            });
        });

        $(document).ready(function() 
        {         
            $('#save').click(function()
            {
                window.location.href = "../dashboard/index";     
            });
        });


         </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="../js/change_device.js"></script>
    <script src="../js/avatar.js"></script>

    
    <script src="../js/pages/ui/dialogs.js"></script>
    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../plugins/node-waves/waves.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="../plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- Morris Plugin Js -->
    <script src="../plugins/raphael/raphael.min.js"></script>
    <script src="../plugins/morrisjs/morris.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>


    <script src="../js/admin.js"></script>
    
    <script src="../js/demo.js"></script>
    
</body>

</html>         