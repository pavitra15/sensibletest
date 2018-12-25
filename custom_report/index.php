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
                <a class="navbar-brand">SENSIBLE CONNECT</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
    
                    <?php
                     include('../connect.php');
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
                <h2>DASHBOARD</h2>
            </div>
                <?php
                    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
                    $clone= clone $date;
                    $name="...";
                    $user_name="...";
                    $checkout=0;
                    $start_date = $clone->format('Y-m-d 00:00:00' );
                    $status='active';
                    $end_date = $clone->format( 'Y-m-d 23:59:59');
                    $checkout_query=$db->prepare("select sum(if(tax_state=0,parcel_amt+tax_amt+bill_amt-discount+round_off,parcel_amt+bill_amt-discount+round_off)) as total, count(bill_no) as count from ( select distinct bill_no, tax_state,tax_amt, parcel_amt, bill_amt, discount, round_off from transaction_mst where device_id='$d_id' and transaction_mst.status='$status')T1");
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
                    $top_query=$db->prepare("select english_name, sum(quantity) as count from( select distinct bill_no, english_name, item_id, transaction_dtl.quantity from transaction_dtl,product, transaction_mst where transaction_dtl.item_id=product.product_id and device_id='$d_id' and transaction_dtl.transaction_id=transaction_mst.transaction_id  and transaction_mst.status='$status')T1 group by item_id Order by sum(quantity) desc limit 1");
                    $top_query->execute();
                    while($data_top=$top_query->fetch())
                    {
                        $name=$data_top['english_name'];
                    }

                    $user_query=$db->prepare("select user_name from( select distinct bill_no, user_name, transaction_mst.user_id, tax_state, tax_amt, parcel_amt,bill_amt, discount  from user_dtl, transaction_mst where transaction_mst.user_id=user_dtl.user_id and device_id='$d_id' and transaction_mst.status='$status') T1 group by user_id Order by sum(if(tax_state=0,parcel_amt+tax_amt+bill_amt-discount,parcel_amt+bill_amt-discount)) desc limit 1");
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
                                        $first_query=$db->prepare("select sum(if(tax_state=0,parcel_amt+tax_amt+bill_amt-discount+round_off,parcel_amt+bill_amt-discount+round_off)) as total, count(bill_no) as count from ( select distinct bill_no, tax_state,tax_amt, parcel_amt, bill_amt, discount, round_off from transaction_mst where device_id='$d_id' and transaction_mst.status='$status' and  bill_date between '$start_date' and '$end_date')T1");
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
    
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $('#left_home').addClass('active');
        });
    </script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>
    
    <script src="../plugins/jquery/jquery.min.js"></script>

     <script src="../js/pages/ui/dialogs.js"></script>
     <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
     <script src="../plugins/sweetalert/sweetalert.min.js"></script>

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