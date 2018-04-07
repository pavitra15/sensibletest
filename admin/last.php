
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
    <link href="../plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <!-- Waves Effect Css -->
    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

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
            <?php include('../footer.html'); ?>
            <!-- #Footer -->
        </aside>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>LAST CONNECT</h2>
            </div>
            <!-- Widgets -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 card">
                	<div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-6"></div>
                           	<div class="col-xs-3 col-xs-offset-3 align-right">
                                <input type="text" name="search" id="search" placeholder="Search">
                            </div>
                       	</div>
                    </div>
                    <div class="body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="data-display" style="display: none">
                        </div>
                    </div>        
                </div>

        <div class="row clearfix">
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function()
        {
            $('#left_data').addClass('active');
        });   

        $(document).ready(function() 
        {  
            var page="1";
            var search=$('#search').val();
            $('#data-display').show();
            $('.page-loader-wrapper').show();
            $.ajax({
                type: 'POST',
                url: 'sql/last_data.php',
                data: { "page":page,"search":search},
                cache: false,
                success: function(data)
                {
                    $('.page-loader-wrapper').hide();
                    $('#data-display').html(data);
                }
            });
        });

        $(document).ready(function() 
        {
            $("#search").keyup(function()
            {
                var page="1";
	            var search=$('#search').val();
	            if(search.length>3)
	            {
		            $('#data-display').show();
		            $('.page-loader-wrapper').show();
		            $.ajax({
		                type: 'POST',
		                url: 'sql/last_data.php',
		                data: { "page":page,"search":search},
		                cache: false,
		                success: function(data)
		                {
		                    $('.page-loader-wrapper').hide();
		                    $('#data-display').html(data);
		                }
		            });
		        }
		        else if(search.length==0)
		        {
		        	$('#data-display').show();
		            $('.page-loader-wrapper').show();
		            $.ajax({
		                type: 'POST',
		                url: 'sql/last_data.php',
		                data: { "page":page,"search":search},
		                cache: false,
		                success: function(data)
		                {
		                    $('.page-loader-wrapper').hide();
		                    $('#data-display').html(data);
		                }
		            });
		        }  
            });
        });

    </script>
    <script src="../js/avatar.js"></script>

    <script src="../plugins/jquery/jquery.min.js"></script>

    <script src="../plugins/sweetalert/sweetalert.min.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>
    <script src="../plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <script src="../plugins/node-waves/waves.js"></script>

    <script src="../plugins/jquery-countto/jquery.countTo.js"></script>

    <script src="../plugins/raphael/raphael.min.js"></script>
    <script src="../plugins/morrisjs/morris.js"></script>

    <script src="../plugins/chartjs/Chart.bundle.js"></script>

    <script src="../plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <script src="../js/admin.js"></script>

    <script src="../js/demo.js"></script>
</body>

</html>