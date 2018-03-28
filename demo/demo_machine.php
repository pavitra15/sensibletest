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

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Colorpicker Css -->
    <link href="../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" />

    <!-- Dropzone Css -->
    <link href="../plugins/dropzone/dropzone.css" rel="stylesheet">

    <!-- Multi Select Css -->
    <link href="../plugins/multi-select/css/multi-select.css" rel="stylesheet">

    <!-- Bootstrap Spinner Css -->
    <link href="../plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

    <!-- Bootstrap Tagsinput Css -->
    <link href="../plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <link href="../plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- noUISlider Css -->
    <link href="../plugins/nouislider/nouislider.min.css" rel="stylesheet" />

    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/aviator.css" rel="stylesheet">

    <link href="../css/themes/all-themes.css" rel="stylesheet" />
</head>

<body class="theme-teal">
    <!-- Page Loader -->
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
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
   
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="../cumulative/index">SENSIBLE CONNECT</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Call Search -->
                    <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                    <?php
                     include('../connect.php');
                        $id=$_SESSION['login_id'];
                        $d_id=$_SESSION['d_id'];
                        $status="active";
                        $name_query=$db->prepare("select device_name from device where d_id='$d_id'");
                        $name_query->execute();
                        while ($name_data=$name_query->fetch()) 
                        {
                            $device_name=$name_data['device_name'];
                        }
                    ?>
                    <li>
                        <a href="javascript:void(0);" class="device_nm" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">expand_more</i>
                            <span class="device_nm"><?php echo $device_name; ?></span>
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
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <?php 
                include('../user_menu.php');
            ?>
            <div class="menu">
                <ul class="list">
                    <li class="active">
                        <a href="../dashboard/index">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                    
        <?php
            if(isset($_SESSION['d_id']))
            {
                echo'<li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">settings_applications</i>
                            <span>Application Setting</span>
                        </a>
                        <ul class="ml-menu">';
                if($_SESSION['device_type']=="Table")
                {
                    echo'<li>
                        <a href="../dashboard/premise">Premise Setting</a>
                    </li>
                    <li>
                        <a href="../dashboard/kitchen">Kitchen Setting</a>
                    </li>';
                }
                else
                {
                    echo'<li>
                        <a href="../dashboard/customer">Customer Type Setting</a>
                    </li>';
                }
                echo'<li>
                    <a href="../dashboard/waiter">'.$_SESSION['person_config'].'</a>
                </li>
                <li>
                    <a href="../dashboard/category">Category Setting</a>
                </li>
                <li>
                    <a href="../dashboard/tax">Tax</a>
                </li>
            </ul>
        </li>
                <li>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">settings_applications</i>
                            <span>Product Setting</span>
                    </a>
                    <ul class="ml-menu">
                        <li>
                            <a href="../dashboard/product">Product</a>
                        </li>
                        <li>
                            <a href="../dashboard/stock">Stock</a>
                        </li>
                        <li>
                            <a href="../dashboard/price">Price</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="../dashboard/user">
                        <i class="material-icons">verified_user</i>
                        <span>User Setting</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">report</i>
                            <span>Report</span>
                    </a>
                    <ul class="ml-menu">
                        <li>
                            <a href="../report/stock_report">Stock Report</a>
                        </li>
                        <li>
                            <a href="../report/out_of_stock_report">Out Of Stock Report</a>
                        </li>
                         <li>
                            <a href="../report/reorder_stock__report">Reorder Product Report</a>
                        </li>
                        <li>
                            <a href="../report/bill_report">Bill Wise Report</a>
                        </li>
                        <li>
                           <a href="../report/product_report">Product Wise Report</a>
                        </li>
                        <li>
                            <a href="../report/category_report">Category Wise Report</a>
                        </li>
                        <li>
                            <a href="../report/user_report">User Wise Bill Report</a>
                        </li>
                        <li>
                            <a href="../report/customer_report">Customer Report</a>
                        </li>
                        <li>
                            <a href="../report/credit_report">Credit Report</a>
                        </li>
                        <li>
                            <a href="../report/waiter_report">'.$_SESSION['person_config'].' Report</a>
                        </li>
                    </ul>
                </li>';
        }
        echo'<li>
                    <a href="../dashboard/register_device">
                        <i class="material-icons">note_add</i>
                        <span>Register Device</span>
                    </a>
                </li>
            </ul>';
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
                <h2>ONE KEY DEMO</h2>
            </div>
            <div class="row clearfix">
                <div class="card">
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?php
                                    $d_id=$_SESSION['d_id'];
                                    $check_demo=$db->prepare("select * from device where config_type='demo' and status='active' and d_id='$d_id'");
                                    $check_demo->execute();
                                    $count=$check_demo->rowCount();
                                    if($count==1)
                                    {
                                ?>
                            <div class="col-lg-4">
                                    <div class="margin-bottom-50">
                                        <button class="btn bg-cyan btn-block btn-lg waves-effect" id="table">Table</button>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="margin-bottom-50">
                                        <button class="btn bg-cyan btn-block btn-lg waves-effect" id="general">General</button>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="margin-bottom-50">
                                        <button class="btn bg-cyan btn-block btn-lg waves-effect" id="weighing">Weighing</button>
                                    </div>
                                </div>
                                <?php
                                    }
                                    else
                                    {
                                ?>
                                    <p>Sorry!. Your machine is not configured for demo. your are trying to unauthorize access</p>
                                <?php
                                    }
                                ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="../js/change_device.js"></script>
    <script src="../js/avatar.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {         
            $('#table').click(function(){
                var device_type="Table";
                $.ajax({
                    type: 'POST',
                    url: 'table_demo.php',
                    data: { "login_id":<?php echo $_SESSION['login_id'];?>,"device_type":device_type,"d_id":<?php echo $_SESSION['d_id'];?>},
                    cache: false,
                    success: function(data)
                    {
                        window.location="../dashboard/index";
                    }
                });
            } );
        });

        $(document).ready(function() {         
            $('#general').click(function(){
                var device_type="Non-Table";
                $.ajax({
                    type: 'POST',
                    url: 'table_demo.php',
                    data: { "login_id":<?php echo $_SESSION['login_id'];?>,"device_type":device_type,"d_id":<?php echo $_SESSION['d_id'];?>},
                    cache: false,
                    success: function(data)
                    {
                        window.location="../dashboard/index";
                    }
                });
            } );
        });

        $(document).ready(function() {         
            $('#weighing').click(function(){
                var device_type="Weighing";
                $.ajax({
                    type: 'POST',
                    url: 'table_demo.php',
                    data: { "login_id":<?php echo $_SESSION['login_id'];?>,"device_type":device_type,"d_id":<?php echo $_SESSION['d_id'];?>},
                    cache: false,
                    success: function(data)
                    {
                        window.location="../dashboard/index";
                    }
                });
            } );
        });
    </script>
    
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

    <script src="../js/admin.js"></script>
    <script src="../js/pages/forms/basic-form-elements.js"></script>
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