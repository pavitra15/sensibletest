<?php
    session_start();
    include('../validate.php');
    include('../connect.php');    
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome To | Sensible Connect - Admin</title>
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

    <link href="../plugins/waitme/waitMe.css" rel="stylesheet" />
    
    <link href="../plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <link href="../plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    
    <script src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
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
    <section>
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
                <h2>
                    TAX INFORMATION
                </h2>
            </div>
            <div class="row card">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="body">
                        <table  class="table table-bordered table-striped table-hover dataTable">
                            <thead>
                                <tr>
                                    <th>Tax Name</th>
                                    <th>Tax Type</th>
                                    <th>Tax Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $Product_query=$db->prepare("select tax_name, tax_type, tax_perc from tax_mst where status='active'");
                                $Product_query->execute();
                                if($product_data=$Product_query->fetch())
                                { 
                                    do
                                    {
                                        echo'<tr>
                                            <td>'.$product_data['tax_name'].'</td>
                                            <td>'.$product_data['tax_type'].'</td>
                                            <td>'.$product_data['tax_perc'].'</td>
                                        </tr>';
                                    }
                                    while($product_data=$Product_query->fetch());
                                }
                            ?>   
                            </tbody>
                        </table>
                        <p><strong>Note : </strong>The above tax slabs are in conformance with the goods and service tax for INDIA. You may request for the addition of VAT, CESS through request portal. The same will be processed within 24 business hours.</p>
                    </div>
                </div>
            </div>

    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
    {
        $('#left_application').addClass('active');
    });

        $(document).ready(function()
        {
            $.ajax({
                type: 'POST',
                url: '../sql/check_last_sync.php',
                data: { "d_id":<?php echo $_SESSION['d_id']; ?>},
                cache: false,
                success: function(data)
                {
                    showNotification("alert-info", "Last sync : "+data, "top", "right",'', '');
                }
            });
        });
    </script>
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

    <script src="../plugins/raphael/raphael.min.js"></script>
    <script src="../plugins/morrisjs/morris.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>


    <script src="../js/admin.js"></script>
    
    <script src="../js/demo.js"></script>
</body>

</html>