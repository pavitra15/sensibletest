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
    <link href="../plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/aviator.css" rel="stylesheet">

    <link href="../css/themes/all-themes.css" rel="stylesheet" />
</head>
<style type="text/css"> 
    [type="checkbox"]:not(:checked), [type="checkbox"]:checked {
     position: initial; 
     left: -9999px; 
    opacity: 1;
        }
    .table > tbody > tr > th.selected, .table > tfoot > tr > th.selected, .table > thead > tr.selected > td, .table > tbody > tr.selected > td, .table > tfoot > tr.selected > td, .table > thead > tr.selected > th, .table > tbody > tr.selected > th, .table > tfoot > tr.selected > th {
            background-color:#9E9E9E;
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
                <?php 
                    include('../left_menu.php');
                ?>
            </div>
            <?php include('../footer.html'); ?>
        </aside>
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
    </section>
<style type="text/css">
        input, textarea,select {
    border: 0 !important;
    border-style: none !important;
    border-color: white !important;
    background-color:transparent !important;
    outline: 0 !important;
    outline-style: none !important;
    box-shadow: none !important;
    text-shadow: none !important;
    border-radius: 0 !important;
    outline-offset: 0 !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    -webkit-box-shadow: none !important;
    -moz-box-shadow: none !important;
}
}
    </style>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    COPY DATA
                </h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <input type="button" class="btn bg-teal waves-effect" id="copy" name="copy" value="COPY">
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <label class="form-label">COPY TO : <?php echo $device_name; ?></label><br>                                        
                                    </div>
                                </div>
                                <div class="col-sm-6 from">
                                    <?php
                                        $id=$_SESSION['login_id'];
                                        $d_id=$_SESSION['d_id'];
                                        $type_query=$db->prepare("select device_type from device where d_id='$d_id'");
                                        $type_query->execute();
                                        while($type_data=$type_query->fetch())
                                        {
                                            $device_type=$type_data['device_type'];
                                        }
                                        echo'<div class="form-group form-float"><div class="form-line"><label class="form-label">COPY FROM</label><br>';
                                            echo'<select class="form-control from_name show-tick" data-live-search="true">';
                                                $query=$db->prepare("select * from device where id='$id' and device_type='$device_type' and d_id !='$d_id' and status='$status'");
                                                $query->execute();
                                                if($data=$query->fetch())
                                                {
                                                    echo'<option value="">-- Please select --</option>';
                                                    do
                                                    {
                                                        echo'<option value='.$data['d_id'].'>'.$data['device_name'].'</option>';
                                                    }
                                                    while($data=$query->fetch());
                                                }
                                                else
                                                {
                                                    echo'<option value="">No record found</option>';
                                                }
                                        echo'</select></div></div>';
                                    ?>
                            </div>
                        </div>
                        <div class="row clearfix copy_data">

                        </div>
                        <div class="row clearfix copy_table">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <script type="text/javascript">

         $(document).ready(function(){
            $('.from_name').change(function(){
                var d_id=$('.from_name').val();
                var dataString = 'd_id='+ d_id;
                if(d_id.length>0)
                {
                    $.ajax({
                       url:"copy_data.php",
                       method:"POST",
                       data:dataString,
                       success:function(data){
                            $(".copy_data").html(data);
                       }
                    });
                }
                else
                {
                    alert("error");
                }
            });
        });
         
     $('#copy').click( function () {
        var from=$('.from_name').val();
        var to="<?php echo $_SESSION['d_id']; ?>";

        if($('#pre_all').is(':checked')){
            var pre_all="on";
        }
        else
        {
            var pre_all="off";
        }

        if($('#cat_all').is(':checked')){
            var cat_all="on";
        }
        else
        {
            var cat_all="off";
        }

        if($('#pro_all').is(':checked')){
            var pro_all="on";
        }
        else
        {
            var pro_all="off";
        }

        if($('#premise').is(':checked')){
            var pre_type="on";
            var premise = [];
            $.each($(".premise:checked"), function(){            
                premise.push($(this).val());
            });
        }
        else
        {
            var pre_type="off";
             var premise = [];
        }

        if($('#category').is(':checked')){
            var cat_type="on";
            var category = [];
            $.each($(".category:checked"), function(){            
                category.push($(this).val());
            });
        }
        else
        {
            var cat_type="off";
             var category = [];
        }

        if($('#product').is(':checked')){
            var product="on";
        }
        else
        {
            var product="off";
        }
        $('.page-loader-wrapper').show();
        var dataString = {from:from,to:to,pre_all:pre_all,cat_all:cat_all,pro_all:pro_all,pre_type:pre_type,premise:premise,cat_type:cat_type,category:category,product:product,products:products};
        $.ajax({
            url:"copy.php",
            method:"POST",
            data:dataString,
            success:function(sk){
                $('.page-loader-wrapper').hide();
                window.location.replace('../dashboard/index');
            }
        });
    });

    $(document).ready(function()
    {
        $('#left_home').addClass('active');
    });
</script>
<script type="text/javascript">
    var products = [];
</script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.2.3/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>

    <script src="../js/change_device.js"></script>
    <script src="../js/avatar.js"></script>
    
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>
    <script src="../plugins/node-waves/waves.js"></script>

    <script src="../js/admin.js"></script>
    <script src="../js/demo.js"></script>

    <script src="../plugins/jquery/jquery.min.js"></script>

    <script src="../plugins/bootstrap/js/bootstrap.js"></script>


    <script src="../plugins/node-waves/waves.js"></script>
    <script src="../js/admin.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <script src="../js/demo.js"></script>
    <script src="../js/change_device.js"></script>
    <script src="../js/avatar.js"></script>

    <script src="../js/pages/ui/notifications.js"></script>
    
    <script src="../plugins/jquery/jquery.min.js"></script>

     <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
     <script src="../plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>
    <!-- Morris Plugin Js -->
    <script src="../plugins/raphael/raphael.min.js"></script>
    <script src="../plugins/morrisjs/morris.js"></script>
    <!-- ChartJs -->
    <script src="../plugins/chartjs/Chart.bundle.js"></script>
    <script src="../plugins/jquery-sparkline/jquery.sparkline.js"></script>
    <script src="../js/admin.js"></script>
 
</body>
</html>