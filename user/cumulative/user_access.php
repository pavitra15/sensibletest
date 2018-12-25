<?php
    session_start();
    include('../validate.php'); 
    $flag=0;         
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

    <!-- Custom Css -->
    <link href="../css/aviator.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">

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
                        $device_name="";
                        $status="active";
                        $name_query=$db->prepare("select device_name,tax_type,prnt_billno, prnt_billtime from device where d_id='$d_id'");
                        $name_query->execute();
                        while ($name_data=$name_query->fetch()) 
                        {
                            $device_name=$name_data['device_name'];
                            $tax_type=$name_data['tax_type'];
                            $prnt_billno=$name_data['prnt_billno'];
                            $prnt_billtime=$name_data['prnt_billtime'];
                        }
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
                    </li>
                    <li>
                        <a href="../dashboard/waiter">Waiter</a>
                    </li>';
                }
                else
                {
                    echo'<li>
                        <a href="../dashboard/customer">Customer Type Setting</a>
                    </li>';
                }
                echo'<li>
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
                        </li>';
                        if($_SESSION['device_type']=="Table")
                        {
                            echo ' <li>
                                <a href="../report/waiter_report">Waiter Report</a>
                            </li>';
                        }
                    echo'</ul>
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

                <h2>USER ACCESS SETTING</h2>
            </div>
            <!-- Input -->
            <div class="row clearfix">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="header">
                                <h2>User Level Access</h2>
                            </div>
                            <div class="body">
                                <div class="col-md-6">
                                    <li>
                                        <span>Edit Bill</span>
                                        <div class="switch">
                                            <label>OFF<input type="checkbox" id="edit_bill" checked><span class="lever switch-col-cyan"></span>ON</label>
                                        </div>
                                    </li>
                                    <li>
                                        <span>Reports</span>
                                        <div class="switch">
                                            <label>OFF<input type="checkbox" id="report" checked><span class="lever switch-col-cyan"></span>ON</label>
                                        </div>
                                    </li>
                                    <li>
                                        <span>Print Settings</span>
                                        <div class="switch">
                                            <label>OFF<input type="checkbox" id="print_setting" checked><span class="lever switch-col-cyan"></span>ON</label>
                                        </div>
                                    </li>
                                    <li>
                                        <span>Dashboard</span>
                                        <div class="switch">
                                            <label>OFF<input type="checkbox" id="dashboard" checked><span class="lever switch-col-cyan"></span>ON</label>
                                        </div>
                                    </li>
                                </div>
                                <div class="col-md-6">
                                    <li>
                                        <span>Add Product</span>
                                        <div class="switch">
                                            <label>OFF<input type="checkbox" id="add_product" checked><span class="lever switch-col-cyan"></span>ON</label>
                                        </div>
                                    </li>
                                    <li>
                                        <span>Update Product</span>
                                        <div class="switch">
                                            <label>OFF<input type="checkbox" id="update_product" checked><span class="lever switch-col-cyan"></span>ON</label>
                                        </div>
                                    </li>
                                    <li>
                                        <span>Update Price</span>
                                        <div class="switch">
                                            <label>OFF<input type="checkbox" id="update_price" checked><span class="lever switch-col-cyan"></span>ON</label>
                                        </div>
                                    </li>
                                    <li>
                                        <span>Update Stock</span>
                                        <div class="switch">
                                            <label>OFF<input type="checkbox" id="update_stock" checked><span class="lever switch-col-cyan"></span>ON</label>
                                        </div>
                                    </li>
                                </div>
                            </div>
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

        $(document).ready(function()
        {
            $(".switch").change(function()
            {
                if($("#dashboard").is(":checked"))
                {
                    var dashboard=1;
                }
                else
                {
                    var dashboard=0;
                }

                if($("#report").is(":checked"))
                {
                    var report=1;
                }
                else
                {
                    var report=0;
                }

                if($("#add_product").is(":checked"))
                {
                    var add_product=1;
                }
                else
                {
                    var add_product=0;
                }

                if($("#update_stock").is(":checked"))
                {
                    var update_stock=1;
                }
                else
                {
                    var update_stock=0;
                }

                if($("#update_price").is(":checked"))
                {
                    var update_price=1;
                }
                else
                {
                    var update_price=0;
                }

                if($("#update_product").is(":checked"))
                {
                    var update_product=1;
                }
                else
                {
                    var update_product=0;
                }

                if($("#edit_bill").is(":checked"))
                {
                    var edit_bill=1;
                }
                else
                {
                    var edit_bill=0;
                }

                if($("#print_setting").is(":checked"))
                {
                    var print_setting=1;
                }
                else
                {
                    var print_setting=0;
                }
                $.ajax({
                    type: "POST",
                    url: "../sql/update_user_access.php",
                    data: {dashboard:dashboard,print_setting:print_setting,report:report,add_product:add_product,update_product:update_product,update_price:update_price,update_stock:update_stock,edit_bill:edit_bill,id:<?php echo $_SESSION['login_id']; ?>,d_id:<?php echo $_SESSION['d_id']; ?>},
                    cache: false,
                    success: function(data)
                    {
                        console.log(data);
                        switch(data)
                        {
                            case "1":
                                showNotification("alert-info", "Setting updated successfully", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                            break;
                            case "2":
                                showNotification("alert-danger", "Setting update fail", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                            break;
                        }
                    }
                });
            });
        });

    
        $(document).ready(function()
        {
            var d_id=<?php echo $_SESSION['d_id']; ?>;
            $.ajax({
                type:'POST',
                url:'../sql/user_access_data.php',
                data:{"deviceid":d_id},
                cache:false,
                success: function(data)
                {
                    obj=JSON.parse(data);
                    obj.dashboard==1?$('#dashboard').attr('checked', true):$('#dashboard').attr('checked', false);

                    obj.edit_bill==1?$('#edit_bill').attr('checked', true):$('#edit_bill').attr('checked', false);

                    obj.add_product==1?$('#add_product').attr('checked', true):$('#add_product').attr('checked', false);

                    obj.update_product==1?$('#update_product').attr('checked', true):$('#update_product').attr('checked', false);

                    obj.update_price==1?$('#update_price').attr('checked', true):$('#update_price').attr('checked', false);

                    obj.update_stock==1?$('#update_stock').attr('checked', true):$('#update_stock').attr('checked', false);
                    
                    obj.report==1?$('#report').attr('checked', true):$('#report').attr('checked', false);
                
                    obj.print_setting==1?$('#print_setting').attr('checked', true):$('#print_setting').attr('checked', false);

                    
                }
            });
        });        
    </script>

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
    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

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