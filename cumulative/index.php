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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="daily">
                            <div class="info-box-44">
                                <div class="icon">
                                    <i class="material-icons" style="color: #008744">insert_chart</i>
                                </div>
                                <div class="content">
                                    <div class="numbers">Daily Report</div>
                                    <div class="text"></div>
                                </div>  
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="weekly">
                            <div class="info-box-44">
                                <div class="icon">
                                    <i class="material-icons" style="color: #0057e7">insert_chart</i>
                                </div>
                                <div class="content">
                                    <div class="numbers">Weekly Report</div>
                                    <div class="text"></div>
                                </div>  
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="monthly">
                            <div class="info-box-44">
                                <div class="icon">
                                    <i class="material-icons" style="color: #d62d20">insert_chart</i>
                                </div>
                                <div class="content">
                                    <div class="numbers">Monthly Report</div>
                                    <div class="text"></div>
                                </div>  
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="yearly">
                            <div class="info-box-44">
                                <div class="icon">
                                    <i class="material-icons" style="color: #ffa700">insert_chart</i>
                                </div>
                                <div class="content">
                                    <div class="numbers">Yearly Report</div>
                                    <div class="text"></div>
                                </div>  
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <?php include('../footer.html'); ?>
        </aside>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>DASHBOARD</h2>
            </div>
                <?php
                    if(isset($_SESSION['login_id']))
                    {
                        $id=$_SESSION['login_id'];
                    }
                    else
                    {
                        $id=0;
                    }
                    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
                    $clone= clone $date;
                    $name="...";
                    $user_name="...";
                      $device_name="";
                    $checkout=0;
                    $start_date = $clone->format( 'Y-m-d 00:00:00' );
                    $status='active';
                    $end_date = $clone->format( 'Y-m-d 23:59:59');
                    $checkout_query=$db->prepare("select sum(if(tax_state=0,parcel_amt+tax_amt+bill_amt-discount+round_off,parcel_amt+bill_amt-discount+round_off)) as total, count(bill_no) as count from ( select distinct bill_no, tax_state,tax_amt, parcel_amt, bill_amt, discount,round_off from device, transaction_mst where transaction_mst.device_id=device.d_id and transaction_mst.status='$status' and device.id='$id' and device.status='$status')T1");
                    $checkout_query->execute();
                    while($data=$checkout_query->fetch())
                    {
                        $total=$data['total'];
                        $count=$data['count'];
                    }
                    if($count!=0)
                    {
                        $checkout=$total/$count;
                    }

                    $top_query=$db->prepare("select english_name, sum(quantity) as count from ( select distinct bill_no, english_name, transaction_dtl.quantity, item_id from transaction_dtl,product, transaction_mst, device where transaction_dtl.item_id=product.product_id and transaction_mst.device_id=device.d_id and transaction_dtl.transaction_id=transaction_mst.transaction_id  and transaction_mst.status='$status' and device.id='$id'  and device.status='$status')T1 group by item_id Order by sum(quantity) desc limit 1");
                    $top_query->execute();
                    while($data_top=$top_query->fetch())
                    {
                        $name=$data_top['english_name'];
                    }

                    $user_query=$db->prepare("select device_name from ( select distinct bill_no,device_name, tax_state, tax_amt, bill_amt, parcel_amt, discount, transaction_mst.device_id  from device, transaction_mst where transaction_mst.device_id=device.d_id and device.id='$id' and transaction_mst.status='$status'  and device.status='$status') T1 group by device_id Order by sum(if(tax_state=0,parcel_amt+tax_amt+bill_amt-discount,parcel_amt+bill_amt-discount)) desc limit 1");

                    $user_query->execute();
                    while($data=$user_query->fetch())
                    {
                        $device_name=$data['device_name'];
                    }


                    if(strlen($name) >16)
                    {
                      $name=substr($name, 0,14);
                      $name=$name.'...';   
                    }

                    if(strlen($user_name) >16)
                    {
                      $device_name=substr($device_name, 0,14);
                      $device_name=$device_name.'...';   
                    }
                ?>
            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box-4">
                        <div class="icon">
                            <i class="material-icons col-indigo">insert_chart</i>
                        </div>
                        <div class="content">
                            <div class="text">Total Sales</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $total; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $total; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box-4">
                        <div class="icon">
                            <i class="material-icons col-red">shopping_cart</i>
                        </div>
                        <div class="content">
                            <div class="text">Top Sale Item</div>
                            <div class="number"><?php echo $name; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box-4">
                        <div class="icon">
                            <i class="material-icons col-red">shopping_cart</i>
                        </div>
                        <div class="content">
                            <div class="text">Best Store</div>
                            <div class="number"><?php echo $device_name; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="report">
                
            
            </div>

            <div class="row clearfix">
                <?php
                    $id=$_SESSION['login_id'];
                    $query=$db->prepare("select * from device where id='$id' and status='active'");
                    $query->execute();
                    if($data=$query->fetch())
                    {
                        do
                        {
                            echo'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 devices" id="'.$data['d_id'].'">
                                <div class="info-box-4">
                                    <div class="icon">
                                        <i class="material-icons col-indigo">dvr</i>
                                    </div>
                                    <div class="content">
                                        
                                        <div class="numbers serial_name">'.$data['device_name'].'</div>
                                        <div class="text serial">'.$data['serial_no'].'</div>
                                    </div>
                                </div>
                            </div>';
                        }
                        while($data=$query->fetch());
                    }
                    echo' <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" id="registration">
                        <div class="info-box-4">
                            <div class="icon">
                                <i class="material-icons col-teal">add</i>
                            </div>
                            <div class="content">
                                <div class="numbers">Device Registration</div>
                                <div class="text">Click here</div>
                            </div>
                        </div>
                    </div>';
                ?>
            </div>

            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script type="text/javascript">
            
        $(document).ready(function() 
        {  
            var id= <?php echo $_SESSION['login_id']; ?>;
            $('.page-loader-wrapper').show();
            $.ajax({
                type: 'POST',
                url: 'cumulative_report_all.php',
                data: { "id": id},
                cache: false,
                success: function(data)
                {
                    $('.page-loader-wrapper').hide();
                    $('#report').html(data);
                }
            });
        });

        $(document).ready(function() 
        {         
            $('#daily').click(function()
            {
                var id= <?php echo $_SESSION['login_id']; ?>;
                var start_date= "<?php $date = new DateTime("now", new DateTimeZone('Asia/Kolkata')); echo $date->format('Y-m-d 00:00:00');?>";
                var end_date= "<?php $date = new DateTime("now", new DateTimeZone('Asia/Kolkata')); echo $date->format('Y-m-d 23:59:59'); ?>";
                var report="TODAY : ";
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: 'cumulative_report.php',
                    data: { "id":id,"start_date":start_date, "end_date":end_date, "report":report },
                    cache: false,
                    success: function(data)
                    {
                        $('.page-loader-wrapper').hide();
                        $('#report').html(data);
                    }
                });    
            });
        });

        $(document).ready(function() 
        {         
            $('#weekly').click(function()
            {
                var id= <?php echo $_SESSION['login_id']; ?>;
                var start_date= "<?php $date = new DateTime("last monday", new DateTimeZone('Asia/Kolkata')); echo $date->format('Y-m-d 00:00:00');?>";
                var end_date= "<?php $date = new DateTime("next Sunday", new DateTimeZone('Asia/Kolkata')); echo $date->format('Y-m-d 23:59:59'); ?>";
                var report="WEEK : ";
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: 'cumulative_report.php',
                    data: { "id":id,"start_date":start_date, "end_date":end_date, "report":report },
                    cache: false,
                    success: function(data)
                    {
                        $('.page-loader-wrapper').hide();
                        $('#report').html(data);
                    }
                });    
            });
        });

        $(document).ready(function() 
        {         
            $('#monthly').click(function()
            {
                var id= <?php echo $_SESSION['login_id']; ?>;
                var start_date= "<?php $date = new DateTime("last monday", new DateTimeZone('Asia/Kolkata')); echo $date->format('Y-m-01 00:00:00');?>";
                var end_date= "<?php $date = new DateTime("next Sunday", new DateTimeZone('Asia/Kolkata')); echo $date->format('Y-m-t 23:59:59'); ?>";
                var report="MONTH : ";
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: 'cumulative_report.php',
                    data: { "id":id,"start_date":start_date, "end_date":end_date, "report":report },
                    cache: false,
                    success: function(data)
                    {
                        $('.page-loader-wrapper').hide();
                        $('#report').html(data);
                    }
                });    
            });
        });

        $(document).ready(function() 
        {         
            $('#yearly').click(function()
            {
                var id= <?php echo $_SESSION['login_id']; ?>;
                var start_date= "<?php $date = new DateTime("last monday", new DateTimeZone('Asia/Kolkata')); echo $date->format('Y-01-01 00:00:00');?>";
                var end_date= "<?php $date = new DateTime("next Sunday", new DateTimeZone('Asia/Kolkata')); echo $date->format('Y-12-31 23:59:59'); ?>";
                var report="YEAR : ";
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: 'cumulative_report.php',
                    data: { "id":id,"start_date":start_date, "end_date":end_date, "report":report },
                    cache: false,
                    success: function(data)
                    {
                        $('.page-loader-wrapper').hide();
                        $('#report').html(data);
                    }
                });    
            });
        });

        $(document).ready(function() 
        {         
            $('.devices').click(function()
            {
                var deviceid = this.id;
                console.log(this.id);
                //$('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: '../change/change_device.php',
                    data: { "q":deviceid},
                    cache: false,
                    success: function(data)
                    {
                        window.location.href = "../dashboard/index";
                    }
                });    
            });
        });

        $(document).ready(function() 
        {         
            $('#registration').click(function()
            {
                window.location.href = "../cumulative/register";
            });
        });

        $(document).ready(function()
        {
            $.ajax({
                type: 'POST',
                url: '../sql/expiry_count.php',
                data: { "login_id":<?php echo $_SESSION['login_id']; ?>},
                cache: false,
                success: function(data)
                {
                    if(data>90)
                    {
                        showNotification("bg-black", "Last password changed before "+data+" days", "top", "center",'animated zoomInUp', 'animated zoomOutUp');
                    }
                }
            });
        });

         </script>
            </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="../js/change_device.js"></script>
    <script src="../js/avatar.js"></script>
    
    <script src="../js/pages/ui/dialogs.js"></script>
    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <!-- <script src="../plugins/bootstrap-material-datetimepicker/js/bootstrap-datetimepicker.js"></script> -->
    <script src="../plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>
    <!-- Select Plugin Js -->
    <script src="../plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <script src="../plugins/node-waves/waves.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="../plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- Morris Plugin Js -->
    <script src="../plugins/raphael/raphael.min.js"></script>
    <script src="../plugins/morrisjs/morris.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>



    <script src="../plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <script src="../js/admin.js"></script>

    <script src="../js/demo.js"></script>
    
</body>

</html>