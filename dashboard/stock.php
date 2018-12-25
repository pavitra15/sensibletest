<?php
    session_start();
    include('../validate.php');        
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
        <link href="../css/aviator.css" rel="stylesheet">
        <link href="../css/custom.css" rel="stylesheet">
        <link href="../css/themes/all-themes.css" rel="stylesheet" />
</head>
<style>
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    .sack{
        display: flex;
    }
</style>

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
                        $d_id=$_SESSION['d_id'];
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
                    STOCK
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-6">
                                    <span class="m-r-10 font-12">Yoa are not able to add stock here</span>
                                </div>
                                <div class="col-xs-6">
                                    <button class="btn waves-effect" style="background: #EF5350"></button><span class="m-r-10 font-12">OUT OF STOCK</span>
                                    <button class="btn waves-effect" style="background: #90CAF9"></button><span class="m-r-10 font-12">LOW STOCK</span>
                                </div>
                                <div class="col-xs-3 col-xs-offset-9 align-right sack">
                                    <input type="text" name="search" id="search" placeholder="Search">
                                    <button id="search-btn" class="btn btn-info">Search</button>
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
            $("#search-btn").click(function()
            {
                var search= $("#search").val();
                if(search.length==0)
                {
                    var page="1";
                    $('#data-display').show();
                    $.ajax({
                        type: 'POST',
                        url: 'stock_data.php',
                        data: { "page":page},
                        cache: false,
                        success: function(data)
                        {
                            $('#data-display').html(data);
                        }
                    });
                }
                else
                {
                    $('#data-display').show();
                    $.ajax({
                        type: 'POST',
                        url: 'stock_search.php',
                        data: { "search":search},
                        cache: false,
                        success: function(data)
                        {
                            $('#data-display').html(data);
                        }
                    });    
                }    
            });
        });
        $(document).ready(function() 
        {  
            var page="1";
            $('#data-display').show();
            $.ajax({
                type: 'POST',
                url: 'stock_data.php',
                data: { "page":page},
                cache: false,
                success: function(data)
                {
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
    <script src="../plugins/jquery/jquery.min.js"></script>

    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    
    <script src="../plugins/node-waves/waves.js"></script>

    <script src="../js/admin.js"></script>
    <script src="../js/demo.js"></script>
</body>

</html>