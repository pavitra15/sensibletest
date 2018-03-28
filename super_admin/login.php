<?php
    session_start();
    $flag="";
    include('../connect.php');
    if(isset($_POST['signin']))
    {
        $username=$_POST['username'];
        $password=$_POST['password'];
        $status="active";
        $type="super_admin";
        $query=$db->prepare("select * from login_mst where username='$username' and password=md5('$password') and status='$status' and type='$type'");
        $query->execute();
        $count=$query->rowCount();
        if ($count==1)
        {
            $select_query=$db->prepare("select * from login_mst where username='$username' and password=md5('$password') and status='$status' and access_control='$status' and type='$type'");
            $select_query->execute();
            $count=$select_query->rowCount();
            if ($count==1)
            {
                if($select_data=$select_query->fetch())
                {
                    do
                    {
                       $id=$select_data['id'];
                        $user_type=$select_data['type'];
                    }
                     while ($select_data=$select_query->fetch());
                     $_SESSION['login_id']=$id;
                     $_SESSION['user_type']=$user_type;
                 }

                $my_query=$db->prepare("select first_name, last_name, email from user_mst where id='$id'");
                $my_query->execute();
                if ($user_data=$my_query->fetch()) 
                {
                    do
                    {
                        $name=$user_data['first_name']." ".$user_data['last_name'];
                        $email=$user_data['email'];
                    }
                    while ($user_data=$my_query->fetch());

                $_SESSION['flag']=9;
                $_SESSION['name']=$name;
                $_SESSION['email_id']=$email;
                }
                 echo '<script>window.location="index";</script>';
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
            default:
            break;
        }
    ?>
    </div>
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">POSiBILL - <b>Admin</b></a>
            <small>POSiBILL - Manage all devices easily.</small>
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
                            <input type="text" class="form-control" name="username" placeholder="Mobile Or Email" required autofocus>
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
                            <a href="../account/sign-up">Register Now!</a>
                        </div>
                        <div class="col-xs-6 align-right">
                            <a href="../account/forgot-password">Forgot Password?</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
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