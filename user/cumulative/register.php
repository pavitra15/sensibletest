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

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    
    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <link href="../plugins/morrisjs/morris.css" rel="stylesheet" />

    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />
        <link href="../plugins/sweetalert/sweetalert.css" rel="stylesheet" />

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
                        include('../notification/notification.php');
                    ?>
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
                <ul class="list">
                    <li class="active">
                        
                    </li>
                </ul>
            </div>
            <?php include('../footer.html'); ?>
        </aside>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">

                <h2>REGISTER DEVICE</h2>
            </div>
            <!-- Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 card">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-xs-offset-3">
                        <div class="body">
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
                                        <input type="text" class="form-control" name="device_name"  id="device_name" required>
                                        <label class="form-label">Device Name</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <label class="form-label">Device Type</label><br><br>
                                        <input type="radio" name="device_type"  class="with-gap" value="Table" id="radio_1" />
                                        <label for="radio_1">Table</label><br>
                                        <input  type="radio" name="device_type"  class="with-gap" value="Non-Table" id="radio_2" />
                                        <label for="radio_2">General</label><br>
                                        <input  type="radio" name="device_type"  class="with-gap" value="Weighing" id="radio_3" />
                                        <label for="radio_3">Weighing</label> 
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <label class="form-label">Device Language</label><br>
                                        <select class="form-control show-tick" name="device_language"  id="device_language" data-live-search="true">
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
                                        ?> 
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 " style="margin-bottom: 15px">
                        <button class="btn waves-effect" id="skip">SKIP</button>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 15px; text-align: right;">
                        <button class="btn btn-primary waves-effect" id="save">CONTINUE</button>
                    </div>
                </div>
            </div>

        </div>
    </section>
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script type="text/javascript">
            
       
        $(document).ready(function() 
        {         
            $('#skip').click(function()
            {
             	window.location.href = "../cumulative/index";     
            });
        });

        $(document).ready(function() 
        {         
            $('#save').click(function()
            {
                var serial_no=$('#serial_no').val();
                var device_language = $('#device_language').val();
                var device_name = $('#device_name').val();
                var device_type =  $("input[name='device_type']:checked").val();
                var dataString={"serial_no" :serial_no, "device_language" : device_language, "device_name" : device_name, "device_type" :device_type, "id" : <?php echo $_SESSION['login_id']; ?>};
                if(serial_no.length>0 && device_language.length>0 && device_name.length>0 && device_type.length>0)
                {
                    $.ajax({
                        url:"register_device.php",
                        method:"POST",
                        data:dataString,
                        success:function(data){
                            switch(data)
                            {
                                case "0":
                                    showNotification("alert-danger", "Invalid serial number", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                                break;
                                case "1":
                                    showNotification("alert-danger", "Please contact to customer support", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                                break;
                                case "2":
                                    showNotification("bg-cyan", "Device registerd successfully", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                                    setTimeout("window.location.replace('../cumulative/configuration');",2000);  
                                break;
                                case "3":
                                    showNotification("alert-danger", "Some technical error occured, please try later", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                                break;
                                case "4":
                                    showNotification("alert-danger", "Device already registerd", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                                break;                
                            }
                        }
                    });
                }
                else
                {
                    showNotification("alert-danger", "All fields are mandatory", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                }     
                    
            });
        });

        $(document).ready(function()
        {
            $("#serial_no").change(function()
            {
                var serial_no=$("#serial_no").val();
                $('.page-loader-wrapper').show();
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
                    $('.page-loader-wrapper').hide();
                }
                });
            });
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