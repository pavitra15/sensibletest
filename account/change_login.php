<?php
    session_start();
    include('../validate.php');   
    $flag=0;         
?>
<?php
    if(isset($_POST['update']))
    {
        $old_password=$_POST['old_password'];
        $new_password=$_POST['new_password'];
        $confirm_password=$_POST['confirm_password'];
        $id=$_SESSION['login_id'];
        $status="active";
        $date=date('Y-m-d');
        if($new_password==$confirm_password)
        {
            $password_query=$db->prepare("select * from login_mst where id='$id' and password='$old_password' and status='$status'");
            $password_query->execute();
            $count=$password_query->rowCount();
            if($count==1)
            {
                $update_query=$db->prepare("update login_mst set Password='$new_password', password_updated_date='$date', updated_by_id='$id', updated_by_date='$date' where id='$id'");
                $update_query->execute();
                $update_count=$update_query->rowCount();
                if($update_count>0)
                {
                    $flag=3;
                    $mail_query=$db->prepare("select first_name, email from user_mst where id='$id'");
                    $mail_query->execute();
                    while($data=$mail_query->fetch())
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
                                        <img src="https://www.sensibleconnect.com/img/logo-sensible.png" alt="Sensible Connect" style="height: 70px" />
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
                                                <p>Your Password has been updated</p>
                                                <p>If you continue to have problems,or if you did n0t request this email, please contact our Customer Support Heroes.</p>
                                                <p>Thank you,<br/>The Sensible Connect team</p>
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
                        setSubject('Password updated successfully')->
                        setText($message)->
                        setHtml($message);
                        $res = $mailin->send();
                        $flag=3;

                         $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
                        $log_date=$date->format('Y-m-d H:i:s');
                        $query=$db->prepare("insert into notification_mst (d_id, user_id, notification, device_name, priority,see,notification_time) values('0', '$id', 'Login number updated successfully', '', '0', '0', '$log_date')");
                        $query->execute();
                }
            }
            else
            {
                $flag=2;
            }
        }
        else
        {
            $flag=1;
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
    <style>
          input[type=number]::-webkit-inner-spin-button {
  -webkit-appearance: none;
}
</style>
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
                    <?php 
                        include('../notification/notification.php');
                    ?>
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
                        <a href="../cumulative/index">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                    
                </ul>
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

                <h2>CHANGE LOGIN</h2>
            </div>
            <!-- Input -->
            <div class="row clearfix">
                <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                        	<div class="alert alert-info alert-dismissible" role="alert" style="display: none;" id="case-one">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                	OTP send on your mobile!
                            </div>
                            <div class="alert bg-red alert-dismissible" role="alert" style="display: none" id="case-two">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                	Mobile number already use!
                            </div>
                            <div class="alert bg-red alert-dismissible" role="alert" style="display: none" id="case-three">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                	Some technical error occured, please try again
                            </div>
                            <div class="alert bg-red alert-dismissible" role="alert" style="display: none" id="case-four">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                	New Password and confirm password not match!
                            </div>
                            <div class="alert bg-red alert-dismissible" role="alert" style="display: none" id="case-five">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                	Please enter mobile and OTP properly!
                            </div>
                            <div class="alert bg-red alert-dismissible" role="alert" style="display: none" id="case-six">
	                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                                Invalid OTP!
	                        </div>
	                        <div class="alert bg-red alert-dismissible" role="alert" style="display: none" id="case-seven">
	                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                                OTP timeout!
	                        </div>
	                        <div class="alert bg-green alert-dismissible" role="alert" style="display: none" id="case-nine">
	                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                                Login info updated successfully!
	                        </div>
                            <form id="sign_up" method="POST">
                                <div class="msg">Change Login</div>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">email</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="number" class="form-control" name="mobile" id="mobile" minlength="10" placeholder="New Mobile" required>
                                    </div>
                                    <label id="error-one" class="error" style="display: none">Enter Valid mobile number!</label>
                                </div>
                                <button class="btn btn-block btn-lg waves-effect generate" type="button" name="generate">Generate OTP</button>
                                <div class="input-group">
                                </div>
                                <div style="display: none;" id="opt_verify">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">lock</i>
                                        </span>
                                        <div class="form-line">
                                            <input type="number" class="form-control" id="otp" name="otp" minlength="6" placeholder="OTP">
                                        </div>
                                    </div>
                                    <button class="btn btn-block btn-lg bg-pink waves-effect verify" type="button" name="verify">VERIFY</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).on('click', 'button.generate', function () {
            var mobile=$("#mobile").val();
            if(mobile.length==10)
                {
                	$('#error-one').hide();	
                   $.ajax({
                    type: "POST",
                    url: "generate_otp.php",
                    data: {mobile:mobile},
                    cache: false,
                    success: function(data)
                    {
                    	switch(data)
                    	{
                    		case "1":
                    			$('#case-one').show();
                    			$('#case-two').hide();
                    			$('#case-three').hide();
                    			$('#case-four').hide();
                    			$('#opt_verify').show();
                    		break;
                    		case "2":
                    			$('#case-two').show();
                    			$('#case-one').hide();
                    			$('#case-three').hide();
                    			$('#case-four').hide();
                    		break;
                    		case "3":
                    			$('#case-three').show();
                    			$('#case-one').hide();
                    			$('#case-two').hide();
                    			$('#case-four').hide();
                    		break;
                    		case "4":
                    			$('#case-four').show();
                    			$('#case-one').hide();
                    			$('#case-three').hide();
                    			$('#case-two').hide();
                    		break;
                    	}
                    }
                    }); 
                }
                else
                {
                	$('#error-one').show();	
                	$('#case-one').hide();
                    $('#case-two').hide();
                    $('#case-three').hide();
                    $('#case-four').hide();
                }
        });

        $(document).on('click', 'button.verify', function () {
        	$('#case-one').hide();
            var mobile=$("#mobile").val();
            var otp=$("#otp").val();
            var login_id=<?php echo $_SESSION['login_id']; ?>;
            if((mobile.length==10)&&(otp.length!=0))
            {
            	$.ajax({
                    type: "POST",
                    url: "verify_otp.php",
                    data: {mobile:mobile,otp:otp,login_id:login_id},
                    cache: false,
                    success: function(data)
                    {
                    	switch(data)
                    	{
                    		case "6":
                    			$('#case-six').show();
                    			$('#case-seven').hide();
                    			$('#case-three').hide();
                    			$('#case-nine').hide();
                    		break;
                    		case "7":
                    			$('#case-seven').show();
                    			$('#case-six').hide();
                    			$('#case-three').hide();
                    			$('#case-nine').hide();
                    		break;
                    		case "8":
                    			$('#case-three').show();
                    			$('#case-six').hide();
                    			$('#case-seven').hide();
                    			$('#case-nine').hide();
                    		break;
                    		case "9":
                    			$('#case-nine').show();
                    			$('#case-three').hide();
                    			$('#case-six').hide();
                    			$('#case-seven').hide();
                    		break;
                    	}
                    }
                    }); 
                }
                else
                {
                	$('#case-five').show();	
                }
        });

        $(document).ready(function() {
$("input[type=number]").on("focus", function() {
    $(this).on("keydown", function(event) {
        if (event.keyCode === 38 || event.keyCode === 40) {
            event.preventDefault();
        }
     });
   });
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