<?php
    include('../connect.php');
    $u_id=$_GET['u_id']; 

    $select_query=$db->prepare("select d_id from delete_device where u_id='$u_id'");
    $select_query->execute();
    if($select_query->rowCount())
    {
        while($data=$select_query->fetch())
        {
            $d_id=$data['d_id'];
        }
    }   
    else
    {
        echo '<script >window.location="invalid";</script>';
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Report</title>
    <link rel="icon" href="../favicon.png" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <link href="../plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
    
    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <link href="../plugins/morrisjs/morris.css" rel="stylesheet" />

    <link href="../css/style.css" rel="stylesheet">
    
    <link href="../css/aviator.css" rel="stylesheet">

    <link href="../css/themes/all-themes.css" rel="stylesheet" />

    <link href="../plugins/waitme/waitMe.css" rel="stylesheet" />
    <link href="../plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <link href="../plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
</head>
<body class="theme-teal">
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
                <a class="navbar-brand">SENSIBLE CONNECT</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <?php
                     include('../connect.php');
                        $status="active";
                        $name_query=$db->prepare("select device_name,tax_type,prnt_billno, prnt_billtime from device where d_id='$d_id'");
                        $name_query->execute();
                        while ($name_data=$name_query->fetch()) 
                        {
                            $device_name=$name_data['device_name'];
                            $tax_type=$name_data['tax_type'];
                            $prnt_billno=$name_data['prnt_billno'];
                            $prnt_billtime=$name_data['prnt_billtime'];
                        }
                    ?>
                    <li>
                        <a href="javascript:void(0);" class="device_nm" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <span class="device_nm"><?php echo $device_name; ?></span>
                        </a>             
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <section>
        <aside id="leftsidebar" class="sidebar">
            <div class="menu">
                <?php 
                    include('left_menu.php');
                ?>
            </div>
            <?php include('../footer.html'); ?>
            <!-- #Footer -->
        </aside>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>BILL REPORT</h2>
            </div>
            <div class="row clearfix">
                <div class="card">
                    <div class="body table-responsive">
                        <div class="row clearfix">                
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="summary-display" style="display: none">
                    
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="data-display" style="display: none">
                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {         
            $(document).ready(function(){
                var d_id=<?php echo $d_id; ?>;
                $('.page-loader-wrapper').show();
                $('#summary-display').show();
                $('#data-display').hide();
                $.ajax({
                    type: 'POST',
                    url: 'bill_summary.php',
                    data: { "d_id":d_id},
                    cache: false,
                    success: function(data)
                    {
                        $('.page-loader-wrapper').hide();
                        $('#summary-display').html(data);
                    }
                });
            });
        });
                </script>
            </div>
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script type="text/javascript">
        $(document).ready(function()
        {
            $('#left_report').addClass('active');
        });

    </script>
    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>
    <script src="../plugins/momentjs/moment.js"></script>
    <script src="../js/change_device.js"></script>
    <script src="../js/admin.js"></script>
    <script src="../js/demo.js"></script>
    <script src="../plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <script src="../plugins/node-waves/waves.js"></script>
    <script src="../plugins/autosize/autosize.js"></script>
    <script src="../plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>
   
</html>