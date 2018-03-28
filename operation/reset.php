<?php
    session_start();
    include('../connect.php');
    if($_SESSION['reset']!=1)
    {
         echo '<script>window.location="../operation/device_reset";</script>';
    }
    else
    {
        $_SESSION['terms']=0;
        if (!isset($_SESSION['login_id']))
        {
            echo '<script >window.location="../login/index";</script>';
        }
        else 
        {
            if ($_SESSION['user_type']=='user') 
            {
            }
            else 
            {
                echo '<script >window.location="../login/index";</script>';
            }         
        }
    }           
?>

<?php
    if(isset($_POST['reset']))
    {
        $configuration=0;
        $d_id=$_SESSION['d_id'];
        $check=$_POST['check'];
        $length=sizeof($check);
        for($i=0;$i<$length;$i++)
        {
            $status='delete';
            $name=$check[$i];
            if($name=="configuration")
            {
                $query=$db->prepare("update device set language_id=0, device_type='' where d_id='$d_id'");
                $query->execute();
                $_SESSION['device_type']="";
                $configuration=1;
            }
            else
            {
                $reset_query=$db->prepare("update $name set status='$status' where deviceid='$d_id'");
                $reset_query->execute();
            }
        }

        $_SESSION['flag']=1;
        $_SESSION['reset']=0;
        if($configuration==1)
        {
            $_SESSION['configure']=1;
            echo "<script>window.location='../operation/configure';</script>";
        }
        else
        {
            echo "<script>window.location='../dashboard/index';</script>";
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome | Sensible Connect - Reset</title>
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
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/aviator.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
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

                <h2>REGISTER DEVICE</h2>
            </div>
            <!-- Input -->
            <div class="row clearfix">
                <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <form id="form_validation" method="POST">
                                <div class="demo-checkbox">
                                    <input type="checkbox" id="select_all" class="filled-in select" value="" />
                                    <label for="select_all">Select All</label>
                                </div>
                                <div class="demo-checkbox">
                                    <input type="checkbox" id="device_configuration" class="filled-in reset" value="configuration" name="check[]" />
                                    <label for="device_configuration">Device Configuration</label>
                                </div>
                                <?php
                                    if($_SESSION['device_type']=="Table")
                                    {
                                        echo'<div class="demo-checkbox">
                                            <input type="checkbox" id="premise" class="filled-in reset" value="premise_dtl" name="check[]"/>
                                            <label for="premise">Premise</label>
                                        </div>
                                        <div class="demo-checkbox">
                                            <input type="checkbox" id="kitchen" class="filled-in reset" value="kitchen_dtl" name="check[]"/>
                                            <label for="kitchen">Kitchen</label>
                                        </div>
                                        <div class="demo-checkbox">
                                            <input type="checkbox" id="waiter" class="filled-in reset" value="waiter_dtl" name="check[]"/>
                                            <label for="waiter">Waiter</label>
                                        </div>';   
                                    }
                                    else
                                    {
                                        echo'<div class="demo-checkbox">
                                            <input type="checkbox" id="customer" class="filled-in reset" value="customer_dtl" name="check[]"/>
                                            <label for="customer">Customer Type</label>
                                        </div>';
                                    }
                                ?>
                                <div class="demo-checkbox">
                                    <input type="checkbox" id="category" class="filled-in reset" value="category_dtl" name="check[]" />
                                    <label for="category">Category</label>
                                </div>
                                <div class="demo-checkbox">
                                    <input type="checkbox" id="product" class="filled-in reset" value="product" name="check[]" />
                                    <label for="product">Product</label>
                                </div>
                                <div class="demo-checkbox">
                                    <input type="checkbox" id="user" class="filled-in reset" value="user_dtl" name="check[]" />
                                    <label for="user">User</label>
                                </div>
                                <button class="btn btn-primary waves-effect" id="reset" name="reset" type="submit" disabled='disabled'>RESET</button>
                            </form>
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
    $("#select_all").change(function(){  //"select all" change 
    $(".reset").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
});


         $('.reset').change(function(){ 
    //uncheck "select all", if one of the listed checkbox item is unchecked
    if(false == $(this).prop("checked")){ //if this item is unchecked
        $("#select_all").prop('checked', false); //change "select all" checked status to false
    }
    //check "select all" if all checkbox items are checked
    if ($('.reset:checked').length == $('.reset').length ){
        $("#select_all").prop('checked', true);
    }
});


        $('.select').click(function() {
       
        if ($('.select:checked').length) {

            $('#reset').removeAttr('disabled');
        } else {

            $('#reset').attr('disabled', 'disabled');
        }
    });

        $('.reset').click(function() {
       
        if ($('.reset:checked').length) {

            $('#reset').removeAttr('disabled');
        } else {

            $('#reset').attr('disabled', 'disabled');
        }
    });
        $(document).ready(function()
        {
            $('#left_home').addClass('active');
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