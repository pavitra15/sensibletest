<?php
    session_start();
    include('../connect.php');
    $flag=0;
    if(isset($_POST['update']))
    {
        $id=$_SESSION['login_id'];
        $pass=$_POST['password'];
        $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
        $clone= clone $date;
        $current_date=$clone->format( 'Y-m-d' ); 
        $key='123acd1245120954';
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $password = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $pass, MCRYPT_MODE_ECB, $iv));
        $update_query=$db->prepare("update login_mst set password='$password',password_updated_date='$current_date' where id='$id'");
        $update_query->execute();
        $count=$update_query->rowCount();
        if($count==1)
        {
            $email_query=$db->prepare("select email, first_name, last_name from user_mst where id='$id'");
            $email_query->execute();
            while ($email_data=$email_query->fetch()) 
            {
               $email=$email_data['email'];
               $name=$email_data['first_name']." ".$email_data['last_name'];
            }
            include ('../mailin-smtp-api-master/Mailin.php');
                $message='<div width="100%" style="background: #eceff4; padding: 50px 20px; color: #514d6a;">
                    <div style="max-width: 700px; margin: 0px auto; font-size: 14px">
                        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                            <tr>
                                <td style="vertical-align: top;">
                                    <a href="https://www.sensibleconnect.com"><img src="https://glass-approach-179716.appspot.com/img/logo-sensible.png" alt="Sensible Connect" style="height: 70px" /></a>
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
                                                <p>You are receiving this email because you change password of account for POSiBILL</p>
                                                <p>If the link does not work, try copying and pasting it into your browser. If you continue to have problems, or if you did n0t request this email, please contact our Customer Support Heroes.</p>
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
                setSubject('Activate your account')->
                setText($message)->
                setHtml($message);
                $mailin->send();
                $_SESSION['flag']=88;
                echo '<script>window.location="../login/signin";</script>'; 
        }
        else 
        {
              $flag=2;
        }
    }  
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Reset | Sensible Connect - Admin</title>
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

<body class="signup-page">
    <div class="signup-box">
        <?php
                switch($flag)
                {
                    case 1:
                        echo'<div class="alert bg-green alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Password Change successfully please sign in to your account
                        </div>';
                    break;
                    case 1:
                        echo'<div class="alert bg-red alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Oops some error occured please try later!
                        </div>';
                    break;
                    default:
                    break;
                }
            ?>
        <div class="logo">
            <a href="javascript:void(0);">SENSIBLE - <b>POS</b></a>
            <small>RELIABLE. STURDY. CONNECTED.</small>
        </div>
        <div class="card">
            <div class="body">
        </div>
            <div class="body">
                <form id="sign_up" method="POST">
                    <div class="msg"> Create new password</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" minlength="6" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="confirm" minlength="6" placeholder="Confirm Password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="terms" id="terms" class="filled-in chk-col-pink">
                        <label for="terms">I read and agree to the <a href="terms/index">terms of usage</a>.</label>
                    </div>

                    <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit" name="update">UPDATE</button>

                    <div class="m-t-25 m-b--5 align-center">
                        <a href="https://app.sensibleconnect.com">You already have a membership?</a>
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
    <script src="../js/pages/examples/sign-up.js"></script>
</body>

</html>