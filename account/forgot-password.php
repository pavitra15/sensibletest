<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Forgot Password | Sensible Connect - Admin</title>
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

    <!-- Custom Css -->
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
                    <div class="msg">
                        Enter your mobile number that you used to register. We'll send you an OTP on your registered mobile to reset your password.
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">call</i>
                        </span>
                        <div class="form-line">
                            <input type="number" class="form-control" id="email" placeholder="Mobile" required autofocus>
                        </div>
                    </div>

                    <button class="btn btn-block btn-lg bg-pink waves-effect" id="forgot" type="submit">RESET MY PASSWORD</button>

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

        $('#forgot').click(function()
        {
            $('#forgot').prop('disabled', true);
            var email=$('#email').val();
            if((email.length==10))
            {

            $.ajax(
            {
                type: 'POST',
                data:{email:email},
                url: 'username_verify.php',
                cache: false,
                success: function(data)
                {
                    switch(data)
                    {
                        case "1":
                            window.location="../account/verify_otp";
                        break;  
                        case "2":
                            showNotification("alert-danger", "Please enter valid moible number", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                        break;             
                    }
                    $('#forgot').prop('disabled', false);
                }
            });
        }
        else
        {
            showNotification("alert-danger", "Please enter valid moible number", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
            $('#forgot').prop('disabled', false);
        }
        });
</script>
    <!-- Jquery Core Js -->
    <script src="../plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="../plugins/jquery-validation/jquery.validate.js"></script>
    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <!-- Custom Js -->
    <script src="../js/admin.js"></script>
    <script src="../js/pages/examples/forgot-password.js"></script>
</body>

</html>