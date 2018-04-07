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
            <div class="legal">
                <div class="copyright">
                    &copy; 2016 - 2017 <a href="https://app.sensibleconnect.com/redirect">Sensible Connect Solutions</a>.
                </div>
                <div class="version">
                    <b>Pune</b>
                </div>
            </div>
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
                <h2>Dealership Approval</h2>
            </div>
            <!-- Widgets -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 card">
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Name</th>
                                        <th>Company Name</th>
                                        <th>Address</th>
                                        <th>GST</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        include('../connect.php');
                                        $status    = "Approved";
                                        $appstatus = array(
                                            'Pending' => 1,
                                            'Hold' => 2,
                                            'Approved' => 3,
                                            'Rejected' => 4
                                        );
                                        $query     = $db->prepare("select dealer_id,first_name,last_name,company_name,address,gst,pincode,appstatus,remarks from dealer_mst where appstatus!='$status'");
                                        $query->execute();
                                        $count = $query->rowCount();
                                        if ($data = $query->fetch()) {
                                            $i = 1;
                                            do {
                                                echo '<tr>
                                                        <form action="" method="post">
                                                            <input type="hidden" id="id" name="id" value=' . $data['dealer_id'] . ' >
                                                            
                                                            <td>' . $i . '</td>
                                                            <td>' . $data['first_name'] . ' ' . $data['last_name'] . '</td>
                                                            <td>' . $data['company_name'] . '</td>
                                                            <td>' . $data['address'] . '</td>
                                                            <td>' . $data['gst'] . '</td>   
                                                            <td id="appstatus' . $data['dealer_id'] . '">' . $data['appstatus'] . '</td>
                                                           
                                                            <td id="remarksText' . $data['dealer_id'] . '">
                                                                <input type="text" id="remarksTextbox' . $data['dealer_id'] . '" value=' . $data['remarks'] . '> 
                                                            </td>
                                                             <td id="appstatusSel' . $data['dealer_id'] . '">
                                                                <select class="selectAction" id="status_' . $data['dealer_id'] . '">
                                                                    <option value="">Select</option>                      <option value="Pending">Pending</option>
                                                                    <option value="Hold">Hold</option>
                                                                    <option value="Approved">Approved</option>
                                                                    <option value="Rejected">Rejected</option>
                                                                </select>
                                                            </td>                                                          
                                                        </form>
                                                    </tr>';
                                                $i++;
                                                
                                            } while ($data = $query->fetch());
                                        }
                                    ?>
                                </tbody>
                            </table>
                    
                        </div>
                    </div>        
                </div>
        <div class="row clearfix">
        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="../js/avatar.js"></script>

    <!-- Jquery Core Js -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.selectAction').change(function(){
                
                var d_Status =this.selectedOptions[0].value;
                var d_id=this.id.split('_')[1];
                var d_remarks=$('#remarksTextbox'+d_id).val();
                var data= 'dealer_id='+ d_id+'&appstatus='+d_Status+'&remarks='+d_remarks;
                 $.ajax({
                    type: "POST",
                    url: "update_dealer_approval.php",
                    dataType: 'json',
                    data: data,
                    success: function(data) {                            
                        console.log(data);
                       window.location="dealer_approval.php";                            
                    },
                    error:function(res){
                          console.log(res);
                    }
                });
            })
        })
       
        
    </script> 
    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    

    <!-- Slimscroll Plugin Js -->
    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../plugins/node-waves/waves.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="../plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- Morris Plugin Js -->
    <script src="../plugins/raphael/raphael.min.js"></script>
    <script src="../plugins/morrisjs/morris.js"></script>

    <!-- ChartJs -->
    <script src="../plugins/chartjs/Chart.bundle.js"></script>

    <!-- Flot Charts Plugin Js -->
    <script src="../plugins/flot-charts/jquery.flot.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.resize.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.pie.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.categories.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.time.js"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="../plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <!-- Custom Js -->
    <script src="../js/admin.js"></script>
    <script src="../js/pages/index.js"></script>

    <!-- Demo Js -->
    <script src="../js/demo.js"></script>

    <script src="../plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="../plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="../plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="../plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="../plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="../plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="../plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="../plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="../plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

    <script src="../js/admin.js"></script>
    <script src="../js/pages/tables/jquery-datatable.js"></script>
    
</body>

</html>