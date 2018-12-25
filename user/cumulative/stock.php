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
    <link href="../css/custom.css" rel="stylesheet">
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
    <?php
        $id=$_SESSION['login_id'];
        $d_id=$_SESSION['d_id'];
        $status="active";
        $name_query=$db->prepare("select device_name,tax_type,prnt_billno, prnt_billtime, model from device where d_id='$d_id'");
        $name_query->execute();
        while ($name_data=$name_query->fetch()) 
        {
            $device_name=$name_data['device_name'];
            $device_model=$name_data['model'];
            $tax_type=$name_data['tax_type'];
            $prnt_billno=$name_data['prnt_billno'];
            $prnt_billtime=$name_data['prnt_billtime'];
        }
    ?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    PRODUCT SETTING
                </h2>
            </div>
            <div class="row card">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="body table-responsive">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="data-display" style="display: none">
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
        </section>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script type="text/javascript">

        
        
        $(document).ready(function() 
        {  
            var page="1";
            $('#data-display').show();
            $('.page-loader-wrapper').show();
            $.ajax({
                type: 'POST',
                url: '../dashboard/stock_data.php',
                data: { "page":page},
                cache: false,
                success: function(data)
                {
                    $('.page-loader-wrapper').hide();
                    $('#data-display').html(data);
                }
            });
        });



		
        $(document).ready(function() {
            $('.grpprice').keypress(function (event) {
                return isNumber(event, this)
            });
        });

        function isNumber(evt, element) {
            var charCode = (evt.which) ? evt.which : event.keyCode
        if ((charCode != 46 || $(element).val().indexOf('.') != -1) &&(charCode < 48 || charCode > 57))
            return false;

            return true;
        }

        $(document).ready(function() {
            $("input[type=number]").on("focus", function() {
                $(this).on("keydown", function(event) {
                    if (event.keyCode === 38 || event.keyCode === 40) {
                        event.preventDefault();
                    }
                });
            });
        });


        $('.grpprice').keypress(function(event) {
            var $this = $(this);
            if ((event.which != 46 || $this.val().indexOf('.') != -1) &&((event.which < 48 || event.which > 57) &&(event.which != 0 && event.which != 8))) {
               event.preventDefault();
            }

            var text = $(this).val();
            if ((event.which == 46) && (text.indexOf('.') == -1)) {
                setTimeout(function() {
                    if ($this.val().substring($this.val().indexOf('.')).length > 3) {
                        $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
                    }
                }, 1);
            }

            if((text.indexOf('.') != -1) &&(text.substring(text.indexOf('.')).length > 2) &&(event.which != 0 && event.which != 8) &&($(this)[0].selectionStart >= text.length - 2)) {
                    event.preventDefault();
            }  
        });


        $('.grpprice').bind("paste", function(e) {

            var text = e.originalEvent.clipboardData.getData('Text');
            if ($.isNumeric(text)) {
                if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
                    e.preventDefault();
                    $(this).val(text.substring(0, text.indexOf('.') + 3));
                }
            }
            else 
            {
                e.preventDefault();
            }
        });


        $('.grpprice').keypress(function(event) {
            var value = event.target.value + String.fromCharCode(event.keyCode);
            if (!(/^\d{1,5}(\.$|\.\d{1,2}$|$)/).test(value)) {
                event.preventDefault();
                event.stopPropagation();
            }
        });

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
                window.location.href = "../cumulative/tax";     
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

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <script src="../js/demo.js"></script>
    
</body>

</html>