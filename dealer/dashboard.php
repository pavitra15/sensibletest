<?php
    include('dealer_verify.php'); 
?>  

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome | Sensible Connect - Dealer</title>
    <link rel="icon" href="../favicon.png" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <link href="../plugins/morrisjs/morris.css" rel="stylesheet" />

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
                <a class="navbar-brand" href="dashboard">SENSIBLE CONNECT</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <?php 
                        include('notification.php');
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <?php 
                include('user_menu.php');
            ?>
            <div class="menu">
                <ul class="list">
                    <li class="active">
                        <a href="../admin/dashboard">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li> 
                </ul>
            </div>
            <?php include('../footer.html'); ?>
        </aside>
    </section>
    <?php
        $dealer_id=$_SESSION['dealer_id'];
        $total_query=$db->prepare("select count(id)as total from admin_device where dealer_id='$dealer_id'");
        $total_query->execute();
        while($total_data=$total_query->fetch())
        {
            $total=$total_data['total'];
        }

        $use_query=$db->prepare("select count(id)as total from admin_device where used='use' and dealer_id='$dealer_id'");
        $use_query->execute();
        while($use_data=$use_query->fetch())
        {
            $total_use=$use_data['total'];
        }

        $nuse_query=$db->prepare("select count(id)as total from admin_device where used='no' and dealer_id='$dealer_id'");
        $nuse_query->execute();
        while($nuse_data=$nuse_query->fetch())
        {
            $ntotal_use=$nuse_data['total'];
        }

    ?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>DASHBOARD</h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box-4">
                        <div class="icon">
                            <i class="material-icons col-teal">devices</i>
                        </div>
                        <div class="content">
                            <div class="text">Total Device</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $total; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $total; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md4 col-sm-6 col-xs-12">
                    <div class="info-box-4">
                        <div class="icon">
                            <i class="material-icons col-indigo">devices</i>
                        </div>
                        <div class="content">
                            <div class="text">Use Device</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $total_use; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $total_use; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box-4">
                        <div class="icon">
                            <i class="material-icons col-red">devices</i>
                        </div>
                        <div class="content">
                            <div class="text">Unused Device</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $ntotal_use; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $ntotal_use; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            
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
                                        $first_query=$db->prepare("select count(id) as total from admin_device where dealer_id='$dealer_id' and test_date between '$start_date' and '$end_date'");
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
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="../js/avatar.js"></script>

    <script src="../plugins/jquery/jquery.min.js"></script>

    <script src="../plugins/bootstrap/js/bootstrap.js"></script>
    <script src="../plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <script src="../plugins/node-waves/waves.js"></script>

    <script src="../plugins/jquery-countto/jquery.countTo.js"></script>

    <script src="../plugins/raphael/raphael.min.js"></script>
    <script src="../plugins/morrisjs/morris.js"></script>

    <script src="../plugins/chartjs/Chart.bundle.js"></script>

    <!-- Flot Charts Plugin Js -->
    <script src="../plugins/flot-charts/jquery.flot.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.resize.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.pie.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.categories.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.time.js"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="../plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <!-- Custom Js -->
    <script src="../js/admin.js"></script>
    <script src="../js/pages/index.js"></script>

    <!-- Demo Js -->
    <script src="../js/demo.js"></script>
</body>

</html>