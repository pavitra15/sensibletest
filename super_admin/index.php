<?php
    include('super_admin_verify.php');
?>  

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome | Sensible Connect - Admin</title>
    <!-- Favicon-->
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
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="dashboard">SENSIBLE CONNECT</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Call Search -->
                    <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                    
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">notifications</i>
                            <span class="label-count">7</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">NOTIFICATIONS</li>
                            <li class="body">
                                <ul class="menu">
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">person_add</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>12 new members joined</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 14 mins ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <p id="aviator" data-letters="sk">
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['name']; ?></div>
                    <div class="email"><?php echo $_SESSION['email_id']; ?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="../account/profile"><i class="material-icons">person</i>Profile</a></li>
                            <li><a href="../account/change_password"><i class="material-icons">vpn_key</i>Change Password</a></li>
                            <li role="seperator" class="divider"></li>
                            <li><a href="../super_admin/logout"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="active">
                        <a href="../super_admin/index.php">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">settings</i>
                            <span>Master</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="../super_admin/category_master">Category</a>
                            </li>
                            <li>
                                <a href="../super_admin/product_master">Product</a>
                            </li>
                            <li>
                                <a href="../super_admin/tax_master">Tax</a>
                            </li>
                            <li>
                                <a href="../super_admin/unit_master">Unit</a>
                            </li>
                            <li>
                                <a href="../super_admin/user_type_master">User Type</a>
                            </li>
                            <li>
                                <a href="../super_admin/state_master">State</a>
                            </li>
                            <li>
                                <a href="../super_admin/language_master">Language</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">accessibility</i>
                            <span>Access Control</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="../super_admin/allow_access">Allow Access</a>
                            </li>
                            <li>
                                <a href="../super_admin/deny_access">Deny Access</a>
                            </li>
                            <li>
                                <a href="../super_admin/make_demo">Make Demo</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">settings</i>
                            <span>Data</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="../super_admin/login_id">Login Info</a>
                            </li>
                            <li>
                                <a href="../super_admin/token">Device Token</a>
                            </li>
                             <li>
                                <a href="../super_admin/user_device">User Device</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="../super_admin/upload">
                            <i class="material-icons">cloud_upload</i>
                            <span>Upload</span>
                        </a>
                    </li>
                    <li>
                        <a href="../super_admin/approve">
                            <i class="material-icons">home</i>
                            <span>Approval Pending</span>
                        </a>
                    </li>
                    <li>
                        <a href="../super_admin/back_act_mobile">
                            <i class="material-icons">system_update_alt</i>
                            <span>Update Back Activity Number</span>
                        </a>
                    </li>  
                </ul>
            </div>
            <?php include('../footer.html'); ?>
            <!-- #Footer -->
        </aside>
        <aside id="rightsidebar" class="right-sidebar">
            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                <li role="presentation" class="active"><a href="#skins" data-toggle="tab">DEVICE</a></li>
                <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>
            </ul>
            <div class="tab-content">
            </div>
        </aside>
    </section>
    <?php
        $total_query=$db->prepare("select count(id)as total from admin_device");
        $total_query->execute();
        while($total_data=$total_query->fetch())
        {
            $total=$total_data['total'];
        }

        $useract_query=$db->prepare("select count(id)as total from device where access_control='active' and status='active'");
        $useract_query->execute();
        while($useract_data=$useract_query->fetch())
        {
            $useract_total=$useract_data['total'];
        }

        $userblock_query=$db->prepare("select count(id)as total from device where access_control='denied' and status='active'");
        $userblock_query->execute();
        while($userblock_data=$userblock_query->fetch())
        {
            $userblock_total=$userblock_data['total'];
        }

        $user_query=$db->prepare("select count(id)as total from login_mst where type='user' and status='active'");
        $user_query->execute();
        while($user_data=$user_query->fetch())
        {
            $user_total=$user_data['total'];
        }

        $dealer_query=$db->prepare("select count(id)as total from login_mst where type='dealer' and status='active'");
        $dealer_query->execute();
        while($dealer_data=$dealer_query->fetch())
        {
            $dealer_total=$dealer_data['total'];
        }

        $use_query=$db->prepare("select count(id)as total from admin_device where used='use'");
        $use_query->execute();
        while($use_data=$use_query->fetch())
        {
            $total_use=$use_data['total'];
        }

        $nuse_query=$db->prepare("select count(id)as total from admin_device where used='no'");
        $nuse_query->execute();
        while($nuse_data=$nuse_query->fetch())
        {
            $ntotal_use=$nuse_data['total'];
        }


        $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
        $last_login=$date->format('Y-m-d H:i:s');

        $clone= clone $date;
        $clone->modify( '-1 day' );

        $yesterday=$clone->format( 'Y-m-d H:i:s' );


        $active_query=$db->prepare("select count(id)as total from last_connect where last_login between '$yesterday' and '$last_login'");
        $active_query->execute();
        while($active_data=$active_query->fetch())
        {
            $total_active=$active_data['total'];
        }
    ?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>DASHBOARD</h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
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
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
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
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
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
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box-4">
                        <div class="icon">
                            <i class="material-icons col-indigo">network_wifi</i>
                        </div>
                        <div class="content">
                            <div class="text">Online Device</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $total_active; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $total_active; ?></div>
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
                                    <h2>Sale Statastics</h2>
                                </div>
                           </div>
                        </div>
                        <div class="body">
                            <head>
                                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <?php
        $end_date=$date->format('Y-m-d');
        $end_month=date("m", strtotime($end_date));
        $sk="[
          ['Month', 'Sales'],
          ['',  0]";
        for($i=1;$i<=$end_month;$i++)
        {
            $start_date=$date->format('Y-'.$i.'-01');
            $end_date=$date->format('Y-'.$i.'-31');
            $first_query=$db->prepare("select count(id) as total from admin_device where test_date between '$start_date' and '$end_date'");
            $first_query->execute();
            $month=date ("M", mktime(0,0,0,$i,1,0));
            while ($first_data=$first_query->fetch()) 
            {
                $first=$first_data['total'];
            }
            $sk.=",['".$month."', ".$first." ]";

        }
        $sk.="]";
     ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php echo $sk;?>);
        var options = {
          curveType: 'function',
          legend: { position: 'bottom' }
        };
        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

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

    <!-- Jquery Core Js -->
    <script src="../plugins/jquery/jquery.min.js"></script>

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

    <!-- ChartJs -->
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