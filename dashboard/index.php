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
                     include('../connect.php');
                        $id=$_SESSION['login_id'];
                        if(isset($_SESSION['d_id']))
                        {
                            $d_id=$_SESSION['d_id'];
                        }
                        else
                        {
                            $d_id=0;
                        }
                        $status="active";
                        $device_name="";
                        $name_query=$db->prepare("select device_name,tax_type,prnt_billno, prnt_billtime, created_by_date from device where d_id='$d_id'");
                        $name_query->execute();
                        while ($name_data=$name_query->fetch()) 
                        {
                            $created_by_date=$name_data['created_by_date'];
                            $device_name=$name_data['device_name'];
                            $tax_type=$name_data['tax_type'];
                            $prnt_billno=$name_data['prnt_billno'];
                            $prnt_billtime=$name_data['prnt_billtime'];
                        }
                        $_SESSION['device_name']=$device_name;
                    ?>
                    <li>
                        <a href="javascript:void(0);" class="device_nm" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <span class="device_nm"><?php echo $device_name; ?></span>
                            <i class="material-icons">expand_more</i>
                        </a>          
                        <ul class="dropdown-menu">
                            <li class="header">SELECT DEVICE</li>
                            <li class="body">
                                <ul class="menu">
                                <?php
                                    $device_query=$db->prepare("select * from device where id='$id' and status='$status'");
                                    $device_query->execute();
                                    while ($device_data=$device_query->fetch())
                                    {
                                        echo'<li>
                                            <a href="javascript:void(0);" onClick="change_device(this.id)" id="'.$device_data['d_id'].'">
                                                <div class="icon-circle bg-light-green">
                                                    <i class="material-icons"> devices_other</i>
                                                </div>
                                                <div class="menu-info">
                                                    <h4>'.$device_data['device_name'].'</h4>
                                                    <p>
                                                        <i class="material-icons">confirmation_number</i> '.$device_data['serial_no'].'
                                                    </p>
                                                </div>
                                            </a>
                                        </li>';
                                    } 
                                ?>
                                </ul>
                            </li>
                        </ul>             
                    </li>
                    <?php 
                        include('../notification/device_notification.php');
                    ?>                    
                    <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <?php 
                include('../user_menu.php');
            ?>
            <div class="menu">
            <?php 
                include('../left_menu.php');
            ?>    
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <?php include('../footer.html'); ?>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        <!-- Right Sidebar -->
        <aside id="rightsidebar" class="right-sidebar">
            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                <li role="presentation" class="active"><a href="#skins" data-toggle="tab">DEVICE</a></li>
                <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>
            </ul>
            <div class="tab-content">
                <?php 
                    if(isset($_SESSION['d_id']))
                    {   
                        include('../right_menu.html');
                    } 
                ?>
                <div role="tabpanel" class="tab-pane fade" id="settings">
                    <?php 
                        if(isset($_SESSION['d_id']))
                        {   
                            include('../setting.php');
                        } 
                    ?>
                </div>
            </div>
        </aside>
        <!-- #END# Right Sidebar -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>DASHBOARD</h2>
            </div>
                <?php
                    if(isset($_SESSION['d_id']))
                    {
                        $d_id=$_SESSION['d_id'];
                    }
                    else
                    {
                        $d_id=0;
                    }
                    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
                    $clone= clone $date;
                    $name="...";
                    $user_name="...";
                    $checkout=0;
                    $start_date = $clone->format( 'Y-m-d 00:00:00' );
                    $status='active';
                    $end_date = $clone->format( 'Y-m-d 23:59:59');
                    $checkout_query=$db->prepare("select sum(bill_amt) as total, count(bill_no) as count from transaction_mst where device_id='$d_id' and transaction_mst.status='$status'");
                    $checkout_query->execute();
                    while($data=$checkout_query->fetch())
                    {
                        $total=$data['total'];
                        $count=$data['count'];
                    }
                    if($count!=0)
                    {
                        $checkout=round(($total/$count),2);
                    }
                    $top_query=$db->prepare("select english_name, sum(transaction_dtl.quantity) as count from transaction_dtl,product, transaction_mst where transaction_dtl.item_id=product.product_id and device_id='$d_id' and transaction_dtl.transaction_id=transaction_mst.transaction_id  and transaction_mst.status='$status' group by item_id Order by sum(transaction_dtl.quantity) desc limit 1");
                    $top_query->execute();
                    while($data_top=$top_query->fetch())
                    {
                        $name=$data_top['english_name'];
                    }

                    $user_query=$db->prepare("select user_name, count(transaction_mst.user_id) as count from user_dtl, transaction_mst where transaction_mst.user_id=user_dtl.user_id and device_id='$d_id' and transaction_mst.status='$status' group by transaction_mst.user_id Order by count(transaction_mst.user_id) desc limit 1");
                    $user_query->execute();
                    while($data=$user_query->fetch())
                    {
                        $user_name=$data['user_name'];
                    }


                    if(strlen($name) >9)
                    {
                      $name=substr($name, 0,8);
                      $name=$name.'...';   
                    }

                    if(strlen($user_name) >9)
                    {
                      $user_name=substr($user_name, 0,8);
                      $user_name=$user_name.'...';   
                    }
                ?>
            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
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
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
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
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box-4">
                        <div class="icon">
                           <i class="material-icons col-indigo">show_chart</i>
                        </div>
                        <div class="content">
                            <div class="text">Average Checkout</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $checkout; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $checkout; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box-4">
                        <div class="icon">
                            <i class="material-icons col-red">face</i>
                        </div>
                        <div class="content">
                            <div class="text">Best User</div>
                            <div class="number"><?php echo $user_name; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            
            <!-- #END# Widgets -->
            <!-- CPU Usage -->
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2>Sale Statistics</h2>
                                </div>
                           </div>
                        </div>
                        <div class="body">
                            <head>
                                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                <?php
                                    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
                                    $end_date=$date->format('Y-m-d');
                                    $end_month=date("m", strtotime($end_date));
                                    $sk="[
                                      ['Month', 'Sales']";
                                    for($i=1;$i<=$end_month;$i++)
                                    {
                                        $start_date=$date->format('Y-'.$i.'-01');
                                        $end_date=$date->format('Y-'.$i.'-31');
                                        $first_query=$db->prepare("select sum(bill_amt) as total from transaction_mst where device_id='$d_id' and transaction_mst.status='$status' and  bill_date between '$start_date' and '$end_date'");
                                        $first_query->execute();
                                        $month=date ("M", mktime(0,0,0,$i,1,0));
                                        while ($first_data=$first_query->fetch()) 
                                        {   
                                            $first=$first_data['total'];
                                            if($first=="")
                                            {
                                                $first=0;
                                            }
                                        }
                                        $sk.=",['".$month."', ".$first." ]";

                                    }
                                    $sk.="]";
                                 ?>
                                <script type="text/javascript">
                                google.charts.load('current', {packages: ['corechart', 'bar']});
                                google.charts.setOnLoadCallback(drawBasic);
                                function drawBasic() {

                                      var data = google.visualization.arrayToDataTable(<?php echo $sk;?>);
                                      var options = {
                                        hAxis: {
                                          title: 'Month'
                                        },
                                        vAxis: {
                                          title: 'Sale in rupees',
                                          viewWindow: {
                                          min:0
                                        }
                                        }
                                      };

                                      var chart = new google.visualization.ColumnChart(
                                        document.getElementById('curve_chart'));

                                      chart.draw(data, options);
                                    }
                            </script>
                              </head>
                        <div id="curve_chart" style="width: 100%; height: 400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <?php
                    if(isset($_SESSION['d_id']))
                    {
                        $d_id=$_SESSION['d_id'];
                    }
                    else
                    {
                        $d_id=0;
                    }
                    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
                    $clone= clone $date;
                    $daily_name="...";
                    $daily_user_name="...";
                    $daily_checkout=0;
                    $start_date = $clone->format( 'Y-m-d 00:00:00' );
                    $end_date = $clone->format( 'Y-m-d 23:59:59' );
                    $daily_checkout_query=$db->prepare("select sum(bill_amt) as total, count(bill_no) as count from transaction_mst where device_id='$d_id' and transaction_mst.status='$status' and  bill_date between '$start_date' and '$end_date'");
                    $daily_checkout_query->execute();
                    while($daily_data=$daily_checkout_query->fetch())
                    {
                        $daily_total=$daily_data['total'];
                        $daily_count=$daily_data['count'];
                    }
                    if($daily_count!=0)
                    {
                        $daily_checkout=$daily_total/$daily_count;
                    }

                    $daily_top_query=$db->prepare("select english_name, sum(transaction_dtl.quantity) as count from transaction_dtl,product, transaction_mst where transaction_dtl.item_id=product.product_id and device_id='$d_id' and transaction_mst.status='$status' and transaction_dtl.transaction_id=transaction_mst.transaction_id and  bill_date between '$start_date' and '$end_date' group by item_id Order by sum(transaction_dtl.quantity) desc limit 1");
                    $daily_top_query->execute();
                    while($daily_data_top=$daily_top_query->fetch())
                    {
                        $daily_name=$daily_data_top['english_name'];
                    }

                    $daily_user_query=$db->prepare("select user_name, count(transaction_mst.user_id) as count from user_dtl, transaction_mst where transaction_mst.user_id=user_dtl.user_id and transaction_mst.status='$status' and device_id='$d_id' and  bill_date between '$start_date' and '$end_date' group by transaction_mst.user_id Order by count(transaction_mst.user_id) desc limit 1");
                    $daily_user_query->execute();
                    while($daily_data=$daily_user_query->fetch())
                    {
                        $daily_user_name=$daily_data['user_name'];
                    }


                     if(strlen($daily_name) >9)
                    {
                      $daily_name=substr($daily_name, 0,8);
                      $daily_name=$daily_name.'...';   
                    }

                    if(strlen($daily_user_name) >9)
                    {
                      $daily_user_name=substr($daily_user_name, 0,8);
                      $daily_user_name=$daily_user_name.'...';   
                    }
                ?>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box-4">
                        <div class="icon">
                            <i class="material-icons col-indigo">insert_chart</i>
                        </div>
                        <div class="content">
                            <div class="text">Daily Total Sale</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $daily_total; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $daily_total; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box-4">
                        <div class="icon">
                            <i class="material-icons col-red">shopping_cart</i>
                        </div>
                        <div class="content">
                            <div class="text">Daily Top Sale Item</div>
                            <div class="number"><?php echo $daily_name; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box-4">
                        <div class="icon">
                           <i class="material-icons col-indigo">show_chart</i>
                        </div>
                        <div class="content">
                            <div class="text">Daily Average Checkout</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $daily_checkout; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $daily_checkout; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box-4">
                        <div class="icon">
                            <i class="material-icons col-red">face</i>
                        </div>
                        <div class="content">
                            <div class="text">Daily Best User</div>
                            <div class="number"><?php echo $daily_user_name; ?></div>
                        </div>
                    </div>
                </div>
            </div>
                
            <!-- #END# CPU Usage -->
            <div class="row clearfix">
                <!-- Visitors -->
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="header">
                            <h2>WEEKLY SALE REPORT</h2>
                        </div>
                        <div class="body">
                            <?php
                            $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
                            $current_date=$date->format('Y-m-d');
                            $ecurrent_date=date("d", strtotime($current_date));
                            $daily="[
                                ['Date', 'Sale']";
                            for($i=6;$i>=0;$i--)
                            {           
                                $clone= clone $date;
                                $clone->modify( '-'.$i.' day' );
                                $print_date=$clone->format('d');
                                $start_date=$clone->format( 'Y-m-d 00:00:00');
                                $end_date=$clone->format( 'Y-m-d 23:59:59');
                                $first_query=$db->prepare("select sum(bill_amt) as total from transaction_mst where device_id='$d_id' and transaction_mst.status='$status' and  bill_date between '$start_date' and '$end_date'");
                                $first_query->execute();
                                while ($first_data=$first_query->fetch()) 
                                {   
                                    $first=$first_data['total'];
                                    if($first=="")
                                    {
                                        $first=0;
                                    }
                                }
                                $daily.=",['".$print_date."', ".$first." ]";
                            }
                            $daily.="]";
                        ?>
                        <div id="piechart" style="height: 400px;">
                            <script type="text/javascript">
                                var d = new Date();
                                var month = new Array();
                                month[0] = "January";
                                month[1] = "February";
                                month[2] = "March";
                                month[3] = "April";
                                month[4] = "May";
                                month[5] = "June";
                                month[6] = "July";
                                month[7] = "August";
                                month[8] = "September";
                                month[9] = "October";
                                month[10] = "November";
                                month[11] = "December";
                                var n = month[d.getMonth()];
                                google.charts.load('current', {packages: ['corechart', 'bar']});
                                google.charts.setOnLoadCallback(drawBasic);
                                function drawBasic() {

                                      var data = google.visualization.arrayToDataTable(<?php echo $daily;?>);
                                      var options = {
                                        hAxis: {
                                          title: 'Date(Month of '+n+')'
                                        },
                                        vAxis: {
                                          title: 'Sale in rupees',
                                          viewWindow: {
                                          min:0
                                        }
                                        }
                                      };

                                      var chart = new google.visualization.ColumnChart(
                                        document.getElementById('piechart'));

                                      chart.draw(data, options);
                                    }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="header">
                        <h2>CATEGORY WISE SALE REPORT</h2>
                    </div>
                    <div class="body">
                        <?php
                        $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
                        $current_date=$date->format('Y-m-d');
                        $ecurrent_date=date("d", strtotime($current_date));
                        $cat="[
                            ['Category', 'Sale']";          
                            $clone= clone $date;
                            $clone->modify( '-7 day' );
                            $start_date=$clone->format( 'Y-m-d 00:00:00');
                            $end_date=$clone->format( 'Y-m-d 23:59:59');
                            $category_query=$db->prepare("select category_name, sum(transaction_dtl.total_amt) as total from category_dtl,product, transaction_dtl where category_dtl.deviceid='$d_id' and product.category_id= category_dtl.category_id and transaction_dtl.item_id=product.product_id group by(category_dtl.category_id)");
                            $category_query->execute();
                            while ($category_data=$category_query->fetch()) 
                            {   
                                $category=$category_data['total'];
                                $category_name=$category_data['category_name'];
                                if($category=="")
                                {
                                    $category=0;
                                }
                                $cat.=",['".$category_name."', ".$category." ]";
                            }
                        $cat.="]";
                    ?>
                        <div id="piechar" style="height: 400px;">
                            <script type="text/javascript">
                                google.charts.load('current', {'packages':['corechart']});
                                 google.charts.setOnLoadCallback(drawChart);

                                  function drawChart() {

                                    var data = google.visualization.arrayToDataTable(<?php echo $cat;?>);

                                    var options = {
                                    };

                                    var chart = new google.visualization.PieChart(document.getElementById('piechar'));

                                    chart.draw(data, options);
                                  }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <!-- Visitors -->
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="header">
                            <h2>Billing Overview (Remaining Days)</h2>
                        </div>
                        <div class="body" style="text-align: center;">
                            <script type="text/javascript" src="../js/TimeCircles.js"></script>
                            <link rel="stylesheet" href="../css/TimeCircles.css"/>
                            <?php
                                $date = new DateTime($created_by_date, new DateTimeZone('Asia/Kolkata') );
                                $clone= clone $date;
                                $clone->modify( '+1000 day' );
                                $future_date=$clone->format( 'Y-m-d H:i:s' );

                            ?>
                            <div id="DateCountdown" data-date="<?php echo $future_date; ?>" style="height: 70%; box-sizing: border-box;"></div>
                            <script>
                                $("#DateCountdown").TimeCircles();
                                $("#CountDownTimer").TimeCircles({ time: { Days: { show: false }, Hours: { show: false } }});
                                $("#PageOpenTimer").TimeCircles();
                                
                                var updateTime = function(){
                                    var date = $("#date").val();
                                    var time = $("#time").val();
                                    var datetime = date + ' ' + time + ':00';
                                    $("#DateCountdown").data('date', datetime).TimeCircles().start();
                                }
                                $("#date").change(updateTime).keyup(updateTime);
                                $("#time").change(updateTime).keyup(updateTime);
                                
                                $(".startTimer").click(function() {
                                    $("#CountDownTimer").TimeCircles().start();
                                });
                                $(".stopTimer").click(function() {
                                    $("#CountDownTimer").TimeCircles().stop();
                                });

                                $(".fadeIn").click(function() {
                                    $("#PageOpenTimer").fadeIn();
                                });
                                $(".fadeOut").click(function() {
                                    $("#PageOpenTimer").fadeOut();
                                });

                                $(document).ready(function()
                                {
                                    $('#left_home').addClass('active');
                                });
                            </script>
                        </div>
                    </div>
                </div>
                
<iframe
    width="350"
    height="430"
    src="https://console.dialogflow.com/api-client/demo/embedded/603d3d73-937d-4cce-acb6-29ca3e67a898">
</iframe>
      
            </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
            <script type="text/javascript">
                $(document).ready(function()
                {
                    $.ajax({
                        type: 'POST',
                        url: '../sql/check_version.php',
                        data: { "d_id":<?php echo $_SESSION['d_id']; ?>},
                        cache: false,
                        success: function(data)
                        {
                            if(data>0)
                            {
                                showNotification("bg-black", "New version is available, please update your POS Device", "top", "center",'animated zoomInUp', 'animated zoomOutUp');
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

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <!-- <script src="../plugins/bootstrap-material-datetimepicker/js/bootstrap-datetimepicker.js"></script> -->
    <script src="../js/pages/ui/notifications.js"></script>
    
    <script src="../plugins/jquery/jquery.min.js"></script>

     <script src="../js/pages/ui/dialogs.js"></script>
     <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
     <script src="../plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="../plugins/bootstrap-select/js/bootstrap-select.js"></script>

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

    <!-- ChartJs -->
    <script src="../plugins/chartjs/Chart.bundle.js"></script>


    <script src="../plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <script src="../js/admin.js"></script>

    <script src="../js/demo.js"></script>
    
</body>

</html>