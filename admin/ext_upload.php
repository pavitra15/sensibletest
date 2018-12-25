<?php
    session_start();
    include('admin_verify.php');       
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

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />
     <link href="../plugins/dropzone/dropzone.css" rel="stylesheet">

    <!-- Animation Css -->
    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Morris Chart Css-->
    <link href="../plugins/morrisjs/morris.css" rel="stylesheet" />

    <!-- Custom Css -->
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
                <a class="navbar-brand" href="dashboard">SENSIBLE CONNECT</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <?php 
                        include('notification/notification.php');
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <?php 
                include('../user_menu.php');
            ?>
            <div class="menu">
                <?php 
                    include('menu/left_menu.php');
                ?>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
           <?php include('../footer.html'); ?>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        <!-- Right Sidebar -->
        <aside id="rightsidebar" class="right-sidebar">
            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                <li role="presentation" class="active"><a href="#skins" data-toggle="tab">DEVICE</a></li>
                <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>
            </ul>
        </aside>
        <!-- #END# Right Sidebar -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>UPDATE APP</h2>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <form action="../admin/ext_upload_apk.php" id="frmFileUpload" class="dropzone" method="post" enctype="multipart/form-data">
                    <div class="card">
                        <?php
                        if(isset($_SESSION['apk']))
                        {
                            $apk=$_SESSION['apk'];
                        }
                        else
                        {
                            $apk=0;
                        }
                                switch($apk)
                                {
                                    case 1:
                                        echo'<div class="alert bg-red alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                Some error occured please retry!
                                        </div>';
                                    break;
                                    case 2:
                                        echo'<div class="alert bg-green alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                               APK uploaded successfully!
                                        </div>';
                                    break;
                                    case 3:
                                        echo'<div class="alert bg-red alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                               APK uploaded vfdf!
                                        </div>';
                                    break;
                                    default:
                                    break;
                                }
                                $_SESSION['apk']=0;
                            ?> 
                    <div class="header">
                    <div class="col-md-4">
                        <div class="input-group input-group-lg">
                            <?php
                                        $version_query=$db->prepare("select version from apk where apk_id=3");
                                        $version_query->execute();
                                        if($version_data=$version_query->fetch())
                                        {
                                            do
                                            {
                                                $version=$version_data['version'];
                                            }
                                            while ($version_data=$version_query->fetch());
                                        }
                                        $version=$version+1;
                                        echo'<span class="input-group-addon">
                                <i class="material-icons">android</i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" name="version" value="'.$version.'" placeholder="Version">
                            </div>'; 
                                    ?>
                            
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="button-demo">
                            <input type="submit" name="upload" id="upload" value="upload" class="btn btn-info">    
                        </div>
                    </div>
                    </div>
                        <div class="body">
                            <div class="dz-message">
                                <div class="drag-icon-cph">
                                    <i class="material-icons">touch_app</i>
                                </div>
                                <h3>Drop files here & click to upload.</h3>
                            </div>
                            <div class="fallback">
                                <input name="file" type="file" multiple />
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $('#left_upload').addClass('active');
        });
    </script>
    <script src="../js/avatar.js"></script>

    <!-- Jquery Core Js -->
    <script src="../plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="../plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../plugins/node-waves/waves.js"></script>
    <script src="../plugins/dropzone/dropzone.js"></script>

    <script src="../plugins/jquery-countto/jquery.countTo.js"></script>

    <script src="../plugins/raphael/raphael.min.js"></script>
    <script src="../plugins/morrisjs/morris.js"></script>

    <script src="../plugins/chartjs/Chart.bundle.js"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="../plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <!-- Custom Js -->
    <script src="../js/admin.js"></script>

    <!-- Demo Js -->
    <script src="../js/demo.js"></script>
</body>

</html>