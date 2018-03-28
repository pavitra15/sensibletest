<?php
    session_start();
    include('../validate.php');        
?>

<?php
    $deviceid;
    $flag=$_SESSION['flag'];  
    if(isset($_POST['register']))
    {
        $id=$_SESSION['login_id'];
        $date=date('Y-m-d');
        $serial_no=$_POST['serial_no'];
        $device_name=$_POST['device_name'];
        $device_language=$_POST['device_language'];
        $device_type=$_POST['device_type'];
        $sth=$db->prepare("select * from admin_device where serial_no='$serial_no'");
        $sth->execute();
        $cnt=$sth->rowCount();
        if($cnt==0)
        {
            $flag=6;
        }
        else
        {
            $status='active';
            $st=$db->prepare("select deviceid,model from admin_device where serial_no='$serial_no' and used='no' and status='$status'");
            $st->execute();
            $cn=$st->rowCount();
            if($cn>0)
            {
                while ($data=$st->fetch())
                {
                    $deviceid=$data['deviceid'];
                    $model=$data['model'];
                } 
                $status='active';
                $type='on';
                $config_type='user';
                $query=$db->prepare("insert into device(deviceid,serial_no,device_name,id,language_id,device_type,model,tax_type,prnt_billno,prnt_billtime,access_control, config_type,created_by_id,created_by_date,status)values('$deviceid', '$serial_no','$device_name','$id','$device_language','$device_type','$model','$type','$type','$type','$status','$config_type','$id','$date','$status')");
                $query->execute();
                $count=$query->rowCount();
                if($count>0)
                {
                    $_SESSION['d_id']=$deviceid;
                    $update=$db->prepare("update admin_device set used='use',use_by='$id',used_by_date='$date' where serial_no='$serial_no'");
                    if($update->execute())
                    {
                        $status="active";
                        $sth=$db->prepare("select * from user_mst,login_mst where user_mst.id=login_mst.id and login_mst.id='$id' and login_mst.status='$status'");
                        $sth->execute();
                        while ($data=$sth->fetch())
                        {
                            $email=$data['email'];
                            $name=$data['first_name']." ".$data['last_name'];
                        }
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
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Hi '.$name.',</p>
                                                    <p>You are receiving this email because you register your device successfully on POSiBILL</p>
                                                    <p>You will be taken to POSiBILL where you will manage your devices.</p>
                                                    <p>If you continue to have problems, or if you did not request this email, please contact our Customer Support Heroes.</p>
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
                            setSubject('Device Registration inforamation')->
                            setText($message)->
                            setHtml($message);
                            if(!$mailin->send())
                            {   
                                $flag=4;
                            }
                            else
                            {
                                $_SESSION['flag']=5; 
                            }     
                    }
                    $_SESSION['flag']=5;
                    $query=$db->prepare("select * from device where serial_no='$serial_no'");
                    $query->execute();
                    $count=$query->rowCount();
                    if($count>0)
                    {
                        while($data=$query->fetch())
                        {
                            $deviceid=$data['deviceid'];
                            $d_id=$data['d_id'];
                            $device_type=$data['device_type'];
                            $device_name=$data['device_name'];
                        }
                        $_SESSION['deviceid']=$deviceid;
                        $_SESSION['d_id']=$d_id;
                        $_SESSION['device_type']=$device_type;
                        $_SESSION['device_name']=$device_name;
                        $query=$db->prepare("insert into user_dtl (user_name, user_type, user_mobile,deviceid, status, created_by_date, created_by_id) values('$name', '$user_type', '$mobile', '$d_id', '$status' , '$current_date', '$login_id')");
                        $query->execute();
                        $print_query=$db->prepare("insert into print_setup(d_id,title,sub_title,address,contact,gstn,tax_invoice,bill_name,sr_no,prnt_bill_no,prnt_sr_col, prnt_base_col,prnt_bill_time,prnt_disc_col,footer,consolidated_tax,payment_mode,decimal_point,status,created_by_id,created_by_date)values('$d_id','','','','','',1,'TAX INVOICE','SR. NO.',1,1,0,1,0,'',0,0,3,'$status','$login_id',$created_by_date)");
                        $print_query->execute();
                        if ($device_type=="Table") 
                        {
                            $person_config="Waiter";
                            $configuration_query=$db->prepare("insert into configuration(d_id, person_config, status, created_by_id, created_by_date)values('$d_id','$person_config','$status','$login_id','$created_by_date')");
                            $configuration_query->execute();
                        }
                        else
                        {
                            $person_config="Sales Man";
                            $configuration_query=$db->prepare("insert into configuration(d_id, person_config, status, created_by_id, created_by_date)values('$d_id','$person_config','$status','$login_id','$created_by_date')");
                            $configuration_query->execute();
                        }
                        echo '<script>window.location="index";</script>';  
                    }
                }
                else
                {
                    $flag=2;
                }           
            }
            else
            {
                $flag=6;
            }                   
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
                        if(isset($_SESSION['d_id']))
                        {
                            $d_id=$_SESSION['d_id'];
                        }
                        else
                        {
                            $d_id=0;
                        }
                        $status="active";
                        $device_name="";
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

                <h2>REGISTER DEVICE</h2>
            </div>
            <!-- Input -->
            <div class="row clearfix">
                <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <?php
                                switch($flag)
                                {
                                    case 6:
                                        echo'<div class="alert bg-red alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                Invalid Serial number!
                                        </div>';
                                    break;
                                    case 2:
                                        echo'<div class="alert bg-red alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                Please contact to customer support
                                        </div>';
                                    break;
                                    case 11:
                                        echo'<div class="alert bg-green alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                Login Success, please register device here
                                        </div>';
                                    break;
                                    default:
                                    break;
                                }
                            ?>  
                            <form id="form_validation" method="POST">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="serial_no" id="serial_no" required>
                                        <label class="form-label">Serial Number</label>
                                    </div>
                                    <label id="error-one" class="error" style="display: none">Invalid Serial number!</label>
                                    <label id="error-two" class="error" style="display: none">Serial number is already use, please contact customer support</label>
                                    <label id="name-success" class="error" style="color: green; display: none">Serial number is valid.</label>
                                    <label id="error-three" class="error" style="display: none">Contact to support for activation.</label>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="device_name" required>
                                        <label class="form-label">Device Name</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <label class="form-label">Device Type</label><br><br>
                                        <input type="radio" name="device_type" class="with-gap" value="Table" id="radio_1" />
                                        <label for="radio_1">Table</label><br>
                                        <input  type="radio" name="device_type" class="with-gap" value="Non-Table" id="radio_2" />
                                        <label for="radio_2">General</label><br>
                                        <input  type="radio" name="device_type" class="with-gap" value="Weighing" id="radio_3" />
                                        <label for="radio_3">Weighing</label> 
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <label class="form-label">Device Language</label><br>
                                        <select class="form-control show-tick" name="device_language" data-live-search="true">
                                        <?php
                                            $language_query=$db->prepare("select language_id, language_name from language_mst where status='active'");
                                            $language_query->execute();
                                            if($dat=$language_query->fetch())
                                            {
                                                do
                                                {
                                                    echo'<option value="'.$dat['language_id'].'">'.$dat['language_name'].' </option>';
                                                }
                                                while($dat=$language_query->fetch());
                                            }
                                        ?>                    }
                                        </select>
                                    </div>
                                </div>
                                
                                <button class="btn btn-primary waves-effect" name="register" type="submit">REGISTER</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!--#END# DateTime Picker -->
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $("#serial_no").change(function()
            {
                var serial_no=$("#serial_no").val();
                $('.preloader').show();
                $.ajax({
                type: "POST",
                url: "../sql/validate_serial.php",
                data: {serial_no:serial_no},
                cache: false,
                success: function(data)
                {
                    if(data==1)
                    {
                        $('#error-one').show();
                        $('#error-two').hide();
                        $('#error-three').hide();
                        $('#name-success').hide();
                    }
                    else if (data==2) 
                    {
                        $('#error-one').hide();
                        $('#error-two').show();
                        $('#error-three').hide();
                        $('#name-success').hide();
                    }
                    else if (data==3) 
                    {
                        $('#error-one').hide();
                        $('#error-two').hide();
                        $('#error-three').hide();
                        $('#name-success').show();
                    }
                    else if (data==4) 
                    {
                        $('#error-one').hide();
                        $('#error-two').hide();
                        $('#error-three').show();
                        $('#name-success').hide();
                    }
                },
                complete: function(){
                    $('.preloader').hide();
                }
                });
            });
        });

    $(document).ready(function()
    {
        $('#left_register').addClass('active');
    });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
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

    <!-- Morris Plugin Js -->
    <script src="../plugins/raphael/raphael.min.js"></script>
    <script src="../plugins/morrisjs/morris.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <script src="../plugins/chartjs/Chart.bundle.js"></script>
    <script src="../plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <script src="../js/admin.js"></script>
    <script src="../js/demo.js"></script>
</body>

</html>