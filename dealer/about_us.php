<?php
    session_start();
    include('dealer_verify.php');   
    include('../connect.php');    
?>

<?php
    $deviceid;
    $flag=$_SESSION['flag'];  
    if(isset($_POST['update']))
    {
        $id=$_SESSION['dealer_id'];
        $company_name=ucwords($_POST['company_name']);
        $website=ucwords($_POST['website']);
        $mail=$_POST['mail'];
        $contact=$_POST['contact'];
        if(!filter_var($mail, FILTER_VALIDATE_EMAIL))
        {
        	$flag=4;
        }
        else
        {
            $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
            $created_by_date=$date->format('Y-m-d');
	        $update_query=$db->prepare("update about set company_name='$company_name', website='$website', mail='$mail', contact='$contact', updated_by_id='$id', updated_by_date='$created_by_date' where dealer_id='$id'");
            $update_query->execute();
	        $count=$update_query->rowCount();
	        if($count>0)
	        {
	            $flag=1;

	            $log_date=$date->format('Y-m-d H:i:s');

	            $query=$db->prepare("insert into dealer_notification_mst (user_id, notification priority,see,notification_time) values('0', '$id', 'About updated successfully', '0', '0', '$log_date')");
    			$query->execute();
	        }
	        else
	        {
	            $flag=6;
	        }
	    }
    }
    if(isset($_POST['insert']))
    {
        $id=$_SESSION['dealer_id'];
        $company_name=ucwords($_POST['company_name']);
        $website=ucwords($_POST['website']);
        $mail=$_POST['mail'];
        $contact=$_POST['contact'];
        if(!filter_var($mail, FILTER_VALIDATE_EMAIL))
        {
            $flag=4;
        }
        else
        {
             $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
             $created_by_date=$date->format('Y-m-d');
            $status="active";
            $update_query=$db->prepare("insert into about (company_name, website, mail, contact, dealer_id, created_by_date,created_by_id, status) values('$company_name','$website','$mail', '$contact','$id', '$created_by_date','$id','$status' )");
            $update_query->execute();
            $count=$update_query->rowCount();
            if($count>0)
            {
                $flag=1;

               
                $log_date=$date->format('Y-m-d H:i:s');

                $query=$db->prepare("insert into dealer_notification_mst (user_id, notification priority,see,notification_time) values('0', '$id', 'About added successfully', '0', '0', '$log_date')");
                $query->execute();
            }
            else
            {
                $flag=6;
            }
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
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <link href="../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" />

    <link href="../plugins/dropzone/dropzone.css" rel="stylesheet">

    <link href="../plugins/multi-select/css/multi-select.css" rel="stylesheet">

    <link href="../plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

    <link href="../plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

    <link href="../plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <link href="../plugins/nouislider/nouislider.min.css" rel="stylesheet" />

    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/aviator.css" rel="stylesheet">

    <link href="../css/themes/all-themes.css" rel="stylesheet" />
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
                        include('notification.php');
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <section>
        <aside id="leftsidebar" class="sidebar">
            <?php 
                include('user_menu.php');
            ?>
            <div class="menu">
                <ul class="list">
                    <li class="active">
                        <a href="../dealer/index">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
            	</ul>
            </div>
	        <?php include('../footer.html'); ?>
        </aside>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>About Us</h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <?php
                                switch($flag)
                                {
                                    case 1:
                                        echo'<div class="alert bg-green alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                Profile updated successfully.
                                        </div>';
                                    break;
                                    case 2:
                                        echo'<div class="alert bg-red alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                Invalid mobile number!
                                        </div>';
                                    break;
                                    case 3:
                                        echo'<div class="alert bg-green alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                Login Success, please update profile
                                        </div>';
                                    break;
                                    case 4:
                                        echo'<div class="alert bg-red alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                Invalid Email address!
                                        </div>';
                                    break;
                                    default:
                                    break;
                                }
                            ?>
                            <form id="form_validation" method="POST">
                            <?php
                                $id=$_SESSION['dealer_id'];
                                $query=$db->prepare("select * from about where dealer_id='$id'");
                                $query->execute();
                                if($query->rowCount())
                                {
                                    if($data=$query->fetch())
                                    {
                                        do
                                        {    
                                            echo'<div class="body">
                                                <div class="row clearfix">
                                                    <div class="col-md-12">
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input type="text" class="form-control" value="'.$data['company_name'].'" name="company_name" required>
                                                                <label class="form-label">Company Name</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input type="text" value="'.$data['website'].'" class="form-control" id="website" name="website" required>
                                                                <label class="form-label">Website</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input type="text" value="'.$data['mail'].'" class="form-control" name="mail" required>
                                                                <label class="form-label">Email Address</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-float">
                                                                <div class="form-line">
                                                                    <input type="text" value="'.$data['contact'].'" class="form-control grpprice" name="contact" required>
                                                                    <label class="form-label">Contact Details</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <button class="btn btn-primary waves-effect" name="update" type="submit">UPDATE</button>';
                                        }
                                        while($data=$query->fetch());
                                    }
                                }
                                else
                                {
                                    echo'<div class="body">
                                            <div class="row clearfix">
                                                <div class="col-md-12">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="text" class="form-control" name="company_name" required>
                                                            <label class="form-label">Company Name</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="text"  class="form-control" id="website" name="website" required>
                                                            <label class="form-label">Website</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="text"  class="form-control" name="mail" required>
                                                            <label class="form-label">Email Address</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="text"  class="form-control grpprice" name="contact" required>
                                                            <label class="form-label">Contact Details</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <button class="btn btn-primary waves-effect" name="insert" type="submit">SAVE</button>';
                                }
                            ?>    
                            </form>
                            <p><strong>Note : </strong>This about us information is display into our Sensible POS machine about us page</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $(".grpprice").change(function()
            {
                var mobile=$(".grpprice").val();
                if(mobile.length==10)
                {
                    
                }
                else
                {
                    showNotification("alert-danger", "Enter 10 digit mobile number", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                }
            });
        });
         $(document).ready(function()
        {
            $("#email").change(function()
            {
                var value=$("#email").val();
                if( /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test( value ))
                {
                 
                }
                else
                {
                    showNotification("alert-danger", "Enter Valid email address", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
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
    <script src="../js/change_device.js"></script>
    <script src="../js/avatar.js"></script>

    <script src="../plugins/jquery/jquery.min.js"></script>

    <script src="../plugins/bootstrap/js/bootstrap.js"></script>
    <script src="../plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <script src="../plugins/node-waves/waves.js"></script>

    <script src="../plugins/jquery-countto/jquery.countTo.js"></script>

    <script src="../plugins/jquery-validation/jquery.validate.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <!-- Morris Plugin Js -->
    <script src="../plugins/raphael/raphael.min.js"></script>
    <script src="../plugins/morrisjs/morris.js"></script>

    <script src="../js/admin.js"></script>
    <script src="../js/pages/forms/basic-form-elements.js"></script>
    <!-- ChartJs -->
    <script src="../plugins/chartjs/Chart.bundle.js"></script>

    <script src="../plugins/flot-charts/jquery.flot.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.resize.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.pie.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.categories.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.time.js"></script>

    <script src="../plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <script src="../js/admin.js"></script>
    <script src="../js/pages/index.js"></script>
    <script src="../js/demo.js"></script>
</body>

</html>