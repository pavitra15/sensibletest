<?php
	session_start();
	include('../connect.php');
	$username=$_GET['username'];
	$status="active";
	$date=date('Y-m-d');
	$query=$db->prepare("update login_mst set access_control='$status', status='$status',status_change_date='$date' where username='$username'");
	if($query->execute())
	{
        $sh=$db->prepare("select * from login_mst where username='$username'");
        $sh->execute();
        if($data=$sh->fetch())
        {
            do
            {
                $_SESSION['login_id']=$id=$data['id'];
            }
            while($data=$sh->fetch());   
        }
        $profile="update";
        $sk=$db->prepare("select * from user_mst where id='$id' and profile='$profile'");
        $sk->execute();
        $count=$sk->rowCount();
        if($count>0)
        {
            $_SESSION['flag']=3;
            header("location:../login/index");
        }
        else
        {
            header("location:../account/profile");
        }
	}
	else
	{
    	$flag=0;
	}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Verify | Sensible Connect - Admin</title>
    <link rel="icon" href="../favicon.png" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="../css/style.css" rel="stylesheet">
</head>

<body class="fp-page">
    <div class="fp-box">
        <div class="logo">
            <a href="javascript:void(0);">SENSIBLE - <b>POS</b></a>
            <small>RELIABLE. STURDY. CONNECTED.</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="forgot_password" method="POST">
                    <div class="msg">
                        Due to some technical error, we are unable to verify your account please contact customer support
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../plugins/jquery/jquery.min.js"></script>

    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="../plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="../js/admin.js"></script>
    <script src="../js/pages/examples/forgot-password.js"></script>
</body>

</html>