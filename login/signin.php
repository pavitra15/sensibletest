<?php
    session_start();
    if(isset($_SESSION['flag']))
    {
        $flag=$_SESSION['flag'];
    }
    else
    {
        $flag=0;
    }
    include('../connect.php');
    if(isset($_POST['signin']))
    {
        $executionStartTime = microtime(true);
        $username=$_POST['username'];
        $password=$_POST['password'];
        $status="active";
        $type="user";
        $query=$db->prepare("select * from login_mst where username='$username' and password=md5('$password') and status='$status'");
        $query->execute();
        $count=$query->rowCount();
        if ($count==1)
        {
            $select_query=$db->prepare("select * from login_mst where username='$username' and password=md5('$password') and status='$status' and access_control='$status' and type='$type'");
            $select_query->execute();
            $count=$select_query->rowCount();
            if($count==1)
            {
                if($select_data=$select_query->fetch())
                {
                    do
                    {
                        $id=$select_data['id'];
                        $user_type=$select_data['type'];
                    }
                    while ($select_data=$select_query->fetch());
                }
                $my_query=$db->prepare("select first_name, last_name, email from user_mst where id='$id'");
                $my_query->execute();
                while ($user_data=$my_query->fetch()) 
                {
                    $name=$user_data['first_name']." ".$user_data['last_name'];
                    $email=$user_data['email'];
                }
                $_SESSION['login_id']=$id;
                $_SESSION['user_type']=$user_type;
                $_SESSION['name']=$name;
                $_SESSION['email_id']=$email;
                $_SESSION['flag']=9;
                if(!empty($_POST["rememberme"])) 
                {
                    setcookie ("username",$_POST["username"],time()+ (10 * 365 * 24 * 60 * 60));
                    setcookie ("password",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60));
                } 
                else 
                {
                    if(isset($_COOKIE["username"])) 
                    {
                        setcookie ("username","");
                    }
                    if(isset($_COOKIE["password"])) 
                    {
                        setcookie ("password","");
                    }
                }
                if(isset($_SESSION['login_id']))
                {
                    $flag=$_SESSION['flag'];
                    $id=$_SESSION['login_id'];
                    $status='active';
                    $query=$db->prepare("select * from device where id='$id'");
                    $query->execute();
                    $count=$query->rowCount();
                    $executionEndTime = microtime(true);
                    $seconds = $executionEndTime - $executionStartTime;

                    // echo '<script>alert('.$seconds.');</script>';
                    if($count==0)
                    {
                        $_SESSION['flag']=11;
                        echo '<script>window.location="../cumulative/register";</script>';
                    }
                    elseif($count==1)
                    {
                        if($data=$query->fetch())
                        {
                            do
                            {
                                $d_id=$data['d_id'];
                                $deviceid=$data['deviceid'];
                                $device_type=$data['device_type'];
                            }
                            while($data=$query->fetch());
                            $_SESSION['d_id']=$d_id;
                            $_SESSION['device_type']=$device_type;
                            echo '<script>window.location="../cumulative/index";</script>';
                        }
                    }
                    else
                    {
                        echo '<script>window.location="../cumulative/index";</script>';
                    }
                    // echo '<script>window.location="../login/choose_device";</script>';
                    // exit();
                }
                else
                {
                    echo '<script>window.location="../login/signin";</script>';
                }
            }
            else
            {
                $flag=2;
            }
        }
        else
        {
            $inactive_status='inactive';
            $query=$db->prepare("select * from login_mst where username='$username' and password=md5('$password') and status='$inactive_status'");
            $query->execute();
            $count=$query->rowCount();
            if ($count==1)
            {
                $_SESSION['mobile']=$username;
                echo '<script>window.location="../account/verify";</script>';
            }
            else
            {
                $flag=1;
            }
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Sign In | Sensible Connect - Admin</title>
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
    <style>
          input[type=number]::-webkit-inner-spin-button {
  -webkit-appearance: none;
}
</style>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-114450018-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-114450018-1');
</script>

</head>

<body class="login-page">
    <div class="body">
    <?php
        switch($flag)
        {
            case 2:
                echo'<div class="alert bg-red alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Sorry ! your website access is denied, please contact to our customer service.
                </div>';
            break;
            case 1:
                echo'<div class="alert bg-red alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Username or password is invalid!
                </div>';
            break;
            case 11:
                echo'<div class="alert alert-info alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Device transfer successfully.
                </div>';
            break;
            case 88:
                echo'<div class="alert bg-green alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Password Change successfully please sign in to your account
                </div>';
            break;
            default:
            break;
        }
    ?>
    </div>
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">SENSIBLE - <b>POS</b></a>
            <small>RELIABLE. STURDY. CONNECTED.</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_in" method="POST">
                    <div class="msg">Sign in to start your session</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="number" class="form-control username" name="username"  placeholder="Mobile" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <label for="rememberme">Remember Me</label>
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-pink waves-effect" type="submit" name="signin">SIGN IN</button>
                        </div>
                    </div>
                    <div class="row m-t-15 m-b--20">
                        <div class="col-xs-6">
                            <a href="../account/sign-up.php">Register Now!</a>
                        </div>
                        <div class="col-xs-6 align-right">
                            <a href="../account/forgot-password">Forgot Password?</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">
        
        $(document).ready(function() {
        $('.username').keypress(function (event) 
        {
            return isNumber(event, this)
        });
    });
    
    function isNumber(evt, element) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if ((charCode < 48 || charCode > 57))
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

    </script>

    <script src="../plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="../plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="../js/admin.js"></script>
    <script src="../js/pages/examples/sign-in.js"></script>
</body>

</html>