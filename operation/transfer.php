<?php
    session_start();
    include('../validate.php');          
?>

<?php
    $deviceid;
    $flag=$_SESSION['flag'];
    $_SESSION['flag']=0;
    if(isset($_POST['resend']))
    {
        $_SESSION['transfer']=1;
    }

    if($_SESSION['transfer']==1)
    {
        $current_otp= rand(100000, 999999);
        $new_otp= rand(100000, 999999);
        $authKey = "153437AHNd3Hcat5923dae5";
        $mobileNumber = $_SESSION['current_mobi'];
        $mobileNumber1 = $_SESSION['new_mobi'];
        $senderId = "SENSBL";
        $message   =  $current_otp." is your POSiBILL device ownership transfer verification code";
        $message1   =  $new_otp." is your POSiBILL device ownership transfer verification code"; 
        $route = "default";
        $postData = array(
               'authkey' => $authKey,
               'mobiles' => $mobileNumber,
                'message' => $message,
               'sender' => $senderId,
               'route' => $route,
               'otp'=>$current_otp
                );
        $url="https://control.msg91.com/api/sendotp.php";
        $ch = curl_init();
        curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $postData1 = array(
               'authkey' => $authKey,
               'mobiles' => $mobileNumber1,
                'message' => $message1,
               'sender' => $senderId,
               'route' => $route,
                'otp'=>$new_otp
                );
        $url="https://control.msg91.com/api/sendotp.php";
        $ch1 = curl_init();
        curl_setopt_array($ch1, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData1
        ));
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
        if(curl_exec($ch) && curl_exec($ch1))
        {
            $dtm=date('Y-m-d h:i:s');
            $delete_query=$db->prepare("delete from owner_verification where current_mobile='$mobileNumber' and new_mobile='$mobileNumber1'");
            $delete_query->execute();
            $query=$db->prepare("insert into owner_verification(current_mobile,current_otp,new_mobile,new_otp,dtm) values('$mobileNumber','$current_otp','$mobileNumber1','$new_otp','$dtm')");
            $query->execute();   
            $_SESSION['flag']=6;

        }
        else
        {
            $flag=3;
        }
        $_SESSION['transfer']=0;
    }
    elseif(isset($_POST['verify_otp']))
    {
        $dtm="";
        $current_otp=$_POST['current_otp'];
        $new_otp=$_POST['new_otp'];
        $current_mobi=$_SESSION['current_mobi'];
        $new_mobi=$_SESSION['new_mobi'];
        $otp=$_POST['otp'];
        $with_prod=$_POST['with_prod'];
        $with_trans=$_POST['with_trans'];
        $query=$db->prepare("select * from owner_verification where current_mobile='$current_mobi' and current_otp='$current_otp' and new_mobile='$new_mobi'and new_otp='$new_otp'");
        $query->execute();
        $count=$query->rowCount();
        if($count==1)
        {
            do
            {
                $dtm=$data['dtm'];
            }
            while ($data=$query->fetch()); 
            $current=date('Y-m-d h:i:s');
            $first=strtotime($current);
            $second=strtotime($dtm);
            if(($first-$second)<600)
            {       
                $status="active";
                $verify="yes";
                $date=date('Y-m-d');
                $new_id=$_SESSION['new_id'];
                $d_id=$_SESSION['d_id'];
                $id=$_SESSION['id'];
                $query=$db->prepare("update device set id='$new_id' where d_id='$d_id'");
                if($query->execute())
                {
                    $owner_query=$db->prepare("insert into owner_transfer (old_id, new_id, deviceid, transfer_date)values('$id','$new_id','$deviceid','$date')");
                    $owner_query->execute();
                    $sth=$db->prepare("delete from owner_verification where current_mobile='$current_mobi'");
                    $sth->execute();

                    if($with_prod=="on")
                    {
                    	$status='delete';
                        $date=date('Y-m-d');
                        $id=$_SESSION['login_id'];
                        $query=$db->prepare("update category_dtl set status='$status', status_change_date='$date', deleted_by_id='$id' where deviceid='$d_id'");
                        $query->execute();

                        $query=$db->prepare("update customer_dtl set status='$status', status_change_date='$date', deleted_by_id='$id' where deviceid='$d_id'");
                        $query->execute();

                        $query=$db->prepare("update kitchen_dtl set status='$status', status_change_date='$date', deleted_by_id='$id' where deviceid='$d_id'");
                        $query->execute();

                        $query=$db->prepare("update premise_dtl set status='$status', status_change_date='$date', deleted_by_id='$id' where deviceid='$d_id'");
                        $query->execute();

                        $query=$db->prepare("update product set status='$status', status_change_date='$date', deleted_by_id='$id' where deviceid='$d_id'");
                        $query->execute();

                        $query=$db->prepare("update user_dtl set status='$status', status_change_date='$date', deleted_by_id='$id' where deviceid='$d_id'");
                        $query->execute();

                        $query=$db->prepare("update waiter_dtl set status='$status', status_change_date='$date', deleted_by_id='$id' where deviceid='$d_id'");
                        $query->execute();
                    }
                   
                    if($with_trans=="on")
                    {
                    	$status='delete';
                        $date=date('Y-m-d');
                        $id=$_SESSION['login_id'];
                        $query=$db->prepare("update transaction_mst set status='$status', status_change_date='$date', deleted_by_id='$id' where device_id='$d_id'");
                        echo "update transaction_mst set status='$status', status_change_date='$date', deleted_by_id='$id' where device_id='$d_id'";
                        $query->execute();
                    }
                    
                    $serial_query=$db->prepare("select serial_no from device where d_id='$d_id'");
                    $serial_query->execute();
                    while($serial_data=$serial_query->fetch())
                    {
                        $serial_no=$serial_data['serial_no'];
                    }
                    $email_query=$db->prepare("select email from user_mst where id='$id'");
                    $email_query->execute();
                    while($email_data=$email_query->fetch())
                    {
                        $email=$email_data['email'];
                        $name=$email_data['first_name'];
                    }
                    session_destroy();
                    include ('../mailin-smtp-api-master/Mailin.php');
                    $message='<div width="100%" style="background: #eceff4; padding: 50px 20px; color: #514d6a;">
                        <div style="max-width: 700px; margin: 0px auto; font-size: 14px">
                            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                                <tr>
                                    <td style="vertical-align: top;">
                                        <a href="https://www.sensibleconnect.com"><img src="https://www.sensibleconnect.com/img/logo-sensible.png" alt="Sensible Connect" style="height: 70px" /></a>
                                    </td>
                                    <td style="text-align: right; vertical-align: middle;">
                                        <span style="color: #a09bb9;">
                                            POSiBILL
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            <div style="padding: 40px 40px 20px 40px; background: #fff;">
                                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                    <tbody><tr>
                                             <td>
                                                <p>Hi '.$name.',</p>
                                                <p>You are receiving this email because you requestedto transfer ownership</p>
                                                <p>Your device serial number : '.$serial_no.' ownership transfered successfully.
                                                <p>If you are not processed for ownership transfer, please contact our Customer Support Heroes.</p>
                                                <p>Thank you,<br>The Sensible Connect Solutions Team</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div style="text-align: center; font-size: 12px; color: #a09bb9; margin-top: 20px">
                                <p>
                                    Sensible Connect solutions Pvt Ltd, Pune, 411009
                                    <br />
                                    Dont like these emails? <a href="javascript: void(0);" style="color: #a09bb9; text-decoration: underline;">Unsubscribe</a>
                                    <br />
                                    © 2017 Sensible Connect solutions pvt Ltd. All Rights Reserved.
                                </p>
                            </div>
                        </div>
                    </div>';
                    $mailin = new Mailin('info@sensibleconnect.com', 'QUT6g8qdZ7XmVn49');
                            $mailin->
                            addTo($email, 'Sensible Connect')->
                            setFrom('info@sensibleconnect.com', 'Sensible Connect')->
                            setReplyTo('info@sensibleconnect.com','Sensible Connect')->
                            setSubject('Ownership Transfer')->
                            setText($message)->
                            setHtml($message);
                    $res=$mailin->send();
                    $_SESSION['flag']=11;
                    echo "<script>window.location='../dashboard/logout.php';</script>";
                }
            }
            else
            {
                $flag=4;
            }  
        }
        else
        {
            $flag=5;
        }
    }
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
    <link href="../css/style.css" rel="stylesheet">

    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

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

                <h2>VERIFY USER</h2>
            </div>
            <!-- Input -->
            <div class="row clearfix">
                <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <?php
                                switch($flag)
                                {
                                    case 1:
                                        echo'<div class="alert bg-green alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                OTP Snd on both mobile!
                                        </div>';
                                    break;
                                    case 2:
                                        echo'<div class="alert bg-red alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                Old password mismatch
                                        </div>';
                                    break;
                                     case 3:
                                        echo'<div class="alert bg-green alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                Password changed successfully
                                        </div>';
                                    break;
                                    case 4:
                                        echo'<div class="alert bg-red alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                               OTP Timeout
                                        </div>';
                                    break;
                                    case 5:
                                        echo'<div class="alert bg-red alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                               OTP Mismatch
                                        </div>';
                                    break;
                                    case 9:
                                        echo'<div class="alert alert-info alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                OTP is send on both mobile numbers.
                                        </div>';
                                    break;
                                    default:
                                    break;
                                }
                            ?>  
                            <form id="sign_up" method="POST">
                                <div class="msg">VERIFY USER</div>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">lock</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="current_otp" minlength="6" placeholder="Current User OTP <?php echo $_SESSION['current_mobi']; ?>" >
                                    </div>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">lock</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="new_otp" minlength="6" placeholder="New User OTP <?php echo $_SESSION['new_mobi'];?>" >
                                    </div>
                                </div>
                                    <div class="demo-checkbox">
                                        <input type="checkbox" id="with_trans" class="filled-in reset" name="with_trans"/>
                                        <label for="with_trans">Without Transaction Data</label>
                                    </div>
                                    <div class="demo-checkbox">
                                        <input type="checkbox" id="with_prod" class="filled-in reset" name="with_prod"/>
                                        <label for="with_prod">Without all Product Data</label>
                                    </div>
                                <div class="col-md-6"><button class="btn btn-block " type="submit" name="resend">RESEND</button></div>
                                
                                <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit" name="verify_otp" id="verify_otp">VERIFY</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $('#left_home').addClass('active');
        });
    </script>
    <script src="../js/change_device.js"></script>
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

     <script src="../plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="../js/admin.js"></script>
    <script src="../js/pages/examples/sign-up.js"></script>

    <!-- Custom Js -->
    <script src="../js/admin.js"></script>
    <script src="../js/pages/index.js"></script>

    <!-- Demo Js -->
    <script src="../js/demo.js"></script>
</body>

</html>