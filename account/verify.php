
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Verify | Sensible Connect - Admin</title>
    <link rel="icon" href="../favicon.png" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <link href="../css/style.css" rel="stylesheet">
</head>
<style type="text/css">
    input[type=number]::-webkit-inner-spin-button 
    {
        -webkit-appearance: none;
    }
</style>
<body class="fp-page">
    <div class="fp-box">
        <div class="logo">
            <a href="javascript:void(0);">SENSIBLE - <b>POS</b></a>
            <small>RELIABLE. STURDY. CONNECTED.</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="forgot_password" method="POST" onsubmit="return false">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">security</i>
                        </span>
                        <div class="form-line">
                            <input type="number" class="form-control" id="otp" placeholder="OTP" required autofocus>
                        </div>
                    </div>
                    <div class="col-xs-6">
                            <div class="btn bg-cyan btn-block btn-xs waves-effect" id='resend'>Resend OTP ?</div>
                    </div>
                    <button class="btn btn-block btn-lg bg-pink waves-effect" id="verify">VERIFY MY ACCOUNT</button>
                    <div class="row m-t-20 m-b--5 align-center">
                        <a href="../login/signin">Sign In!</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function()
    { 
        $('#resend').hide();
        $.ajax({
            type: 'POST',
            url: 'otp_send.php',
            cache: false,
            success: function(data)
            {
                switch(data)
                {
                    case "1":
                        showNotification("alert-info", "OTP send to your mobile", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                        setTimeout("$('#resend').show()",30000);
                    break;  
                    case "2":
                        showNotification("alert-danger", "Error occured, please try again", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                        $('#resend').show();
                    break;
                    case "3":
                        showNotification("alert-danger", "Error occured, Something you do wrong", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                        $('#resend').show();
                    break;               
                    }
                }
            });

        $('#resend').click(function()
        {
            $('#resend').hide();
            $.ajax({
                type: 'POST',
                url: 'otp_send.php',
                cache: false,
                success: function(data)
                {
                       
                    
                    switch(data)
                    {
                        case "1":
                            showNotification("alert-info", "OTP send to your mobile", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                            setTimeout("$('#resend').show()",30000);
                        break;  
                        case "2":
                            showNotification("alert-danger", "Error occured, please try again", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                            $('#resend').show();
                        break; 
                        case "3":
                            showNotification("alert-danger", "Error occured, Something you do wrong", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                            $('#resend').show();
                        break;              
                    }
                }
            });
        });

        $(document).ready(function() 
        {
            $("input[type=number]").on("focus", function() 
            {
                $(this).on("keydown", function(event) 
                {
                    if (event.keyCode === 38 || event.keyCode === 40) 
                    {
                        event.preventDefault();
                    }
                });
            });
        });

        $('#verify').click(function()
        {
            $('#verify').prop('disabled', true);
            var otp=$('#otp').val();
            $.ajax({
                type: 'POST',
                data:{otp:otp},
                url: 'verify_otp_data.php',
                cache: false,
                success: function(data)
                {
                    console.log(data);
                    switch(data)
                    {
                        case "1":
                            showNotification("alert-info", "OTP verification success", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                            window.location='../account/profile';
                        break;  
                        case "2":
                            showNotification("alert-danger", "Error occured, please try again", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                            $('#verify').prop('disabled', false);
                        break; 
                        case "3":
                            showNotification("alert-danger", "Time out! please resend OTP", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                            $('#verify').prop('disabled', false);
                        break; 
                        case "4":
                            showNotification("alert-danger", "Invalid OPT!", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                            $('#verify').prop('disabled', false);
                        break;              
                    }
                }
            });
        });

    });
    </script>
    <script src="../plugins/jquery/jquery.min.js"></script>

    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <script src="../plugins/node-waves/waves.js"></script>

    <script src="../plugins/jquery-validation/jquery.validate.js"></script>
    <script src="../plugins/sweetalert/sweetalert.min.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <script src="../js/admin.js"></script>
    <script src="../js/pages/examples/forgot-password.js"></script>
</body>

</html>