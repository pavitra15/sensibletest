<style type="text/css">
    input[type=number]::-webkit-inner-spin-button 
    {
        -webkit-appearance: none;
    }
</style>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Sign Up | Sensible Connect - Admin</title>
    <link rel="icon" href="../favicon.png" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <link href="../css/style.css" rel="stylesheet">
</head>

<body class="signup-page">
    <div class="signup-box">
        <div class="logo">
            <a href="javascript:void(0);">SENSIBLE - <b>POS</b></a>
            <small>RELIABLE. STURDY. CONNECTED.</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_up" method="POST" onsubmit="return false">
                    <div class="msg">Register a new membership</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">call</i>
                        </span>
                        <div class="form-line">
                            <input type="number" class="form-control" name="email" id="email" placeholder="Mobile" required>
                        </div>
                        <label id="error-one" class="error" style="display: none">Mobile number already registered!</label>
                        <label id="error-two" class="error" style="display: none">Enter 10 digit mobile number!</label>
                        <label id="name-success" class="error" style="color: green; display: none">Mobile number is valid.</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" id="password" minlength="6" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="confirm" id="confirm" minlength="6" placeholder="Confirm Password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="terms" id="terms" id="terms" class="filled-in chk-col-pink">
                        <label for="terms">I read and agree to the <a href="terms/index">terms of usage</a>.</label>
                    </div>

                    <button class="btn btn-block btn-lg bg-pink waves-effect" id="signup">SIGN UP</button>

                    <div class="m-t-25 m-b--5 align-center">
                        <a href="../login/signin">You already have a membership?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $("#email").change(function()
            {
                var mobile=$("#email").val();
                if(mobile.length==10)
                {
                   $.ajax({
                    type: "POST",
                    url: "validate_mobile.php",
                    data: {mobile:mobile},
                    cache: false,
                    success: function(data)
                    {
                        if(data==1)
                        {
                            $('#error-one').show();
                             $('#error-two').hide();
                            $('#name-success').hide();
                        }
                        else if (data==2) 
                        {
                            $('#error-one').hide();
                            $('#error-two').hide();
                            $('#name-success').show();
                        }
                    }
                    }); 
                }
                else
                {
                    $('#error-one').hide();
                    $('#error-two').show();
                    $('#name-success').hide();
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

        $('#signup').click(function()
        {
            $('#signup').prop('disabled', true);
            var first_name=$('#first_name').val();
            var last_name=$('#last_name').val();
            var email=$('#email').val();
            var password=$('#password').val();
            var confirm=$('#confirm').val();
            var terms=$('#terms').val();
            if((first_name.length>0) && (last_name.length>0) && (email.length==10) && (password.length>5) && (confirm.length>5) && (password==confirm) && (terms.length>0))
            {

            $.ajax({
                type: 'POST',
                data:{first_name:first_name,last_name:last_name,email:email,password:password,terms:terms},
                url: 'insert_sign_up.php',
                cache: false,
                success: function(data)
                {
                    switch(data)
                    {
                        case "1":
                            showNotification("alert-danger", "Mobile number already exist!", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                        break;  
                        case "2":
                            window.location="../account/verify";
                        break; 
                        case "3":
                            showNotification("alert-danger", "Error occured, Something you do wrong", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                        break; 
                        case "4":
                            showNotification("alert-danger", "Error occured, Something you do wrong", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                        break;              
                    }
                    $('#signup').prop('disabled', false);
                }
            });
        }
        else
        {
            showNotification("alert-danger", "Please add correct data", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
            $('#signup').prop('disabled', false);
        }
        });
    </script>
    <script src="../plugins/jquery/jquery.min.js"></script>

    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <script src="../plugins/node-waves/waves.js"></script>
    <script src="../plugins/sweetalert/sweetalert.min.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <script src="../plugins/jquery-validation/jquery.validate.js"></script>

    <script src="../js/admin.js"></script>
    <script src="../js/pages/examples/sign-up.js"></script>
</body>

</html>