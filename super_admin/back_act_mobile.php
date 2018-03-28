<?php
    include('super_admin_verify.php');
?> 

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome To | Sensible Connect - Admin</title>
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
    <link href="../css/aviator.css" rel="stylesheet">

    <link href="../css/themes/all-themes.css" rel="stylesheet" />
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
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="../dashboard/dashboard">SENSIBLE CONNECT</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">notifications</i>
                            <span class="label-count">7</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">NOTIFICATIONS</li>
                            <li class="body">
                                <ul class="menu">
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">person_add</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>12 new members joined</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 14 mins ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                     <p id="aviator" data-letters="">
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['name']; ?></div>
                    <div class="email"><?php echo $_SESSION['email_id']; ?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="../account/profile"><i class="material-icons">person</i>Profile</a></li>
                            <li><a href="../account/change_password"><i class="material-icons">vpn_key</i>Change Password</a></li>
                            <li role="seperator" class="divider"></li>
                            <li><a href="../admin/logout"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
           <div class="menu">
                <ul class="list">
                    <li class="active">
                        <a href="../super_admin/index.php">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">settings</i>
                            <span>Master</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="../super_admin/category_master">Category</a>
                            </li>
                            <li>
                                <a href="../super_admin/product_master">Product</a>
                            </li>
                            <li>
                                <a href="../super_admin/tax_master">Tax</a>
                            </li>
                            <li>
                                <a href="../super_admin/unit_master">Unit</a>
                            </li>
                            <li>
                                <a href="../super_admin/user_type_master">User Type</a>
                            </li>
                            <li>
                                <a href="../super_admin/state_master">State</a>
                            </li>
                            <li>
                                <a href="../super_admin/language_master">Language</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">accessibility</i>
                            <span>Access Control</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="../super_admin/allow_access">Allow Access</a>
                            </li>
                            <li>
                                <a href="../super_admin/deny_access">Deny Access</a>
                            </li>
                            <li>
                                <a href="../super_admin/make_demo">Make Demo</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">settings</i>
                            <span>Data</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="../super_admin/login_id">Login Info</a>
                            </li>
                            <li>
                                <a href="../super_admin/token">Device Token</a>
                            </li>
                             <li>
                                <a href="../super_admin/user_device">User Device</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="../super_admin/upload">
                            <i class="material-icons">cloud_upload</i>
                            <span>Upload</span>
                        </a>
                    </li>
                    <li>
                        <a href="../super_admin/approve">
                            <i class="material-icons">home</i>
                            <span>Approval Pending</span>
                        </a>
                    </li>
                    <li>
                        <a href="../super_admin/back_act_mobile">
                            <i class="material-icons">system_update_alt</i>
                            <span>Update Back Activity Number</span>
                        </a>
                    </li>  
                </ul>
            </div>
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
    </section>
<style type="text/css">
                            input, textarea,select {
    border: 0 !important;
    border-style: none !important;
    border-color: white !important;
    background-color:transparent !important;
    outline: 0 !important;
    outline-style: none !important;
    box-shadow: none !important;
    text-shadow: none !important;
    border-radius: 0 !important;
    outline-offset: 0 !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    -webkit-box-shadow: none !important;
    -moz-box-shadow: none !important;
}
}
                        </style>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    BACK ACTIVITY MOBILE
                </h2>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <form action="" method="post">
                            <div class="body">
                                <table  class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Mobile Number</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $status="active";
                                        $query=$db->prepare("select * from back_act_otp where status='$status' and id=1");
                                        $query->execute();
                                        while($data=$query->fetch())
                                        {
                                            echo'<tr id="'.$data['id'].'" class="edit_tr">
                                            <td><input type="text" id="mobile'.$data['id'].'" value="'.$data['mobile_no'].'"></td>
                                            <td></td>
                                        </tr>';
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
               
                    </div>
                </div>
            </div>
            </form>
    </section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">

$(document).ready(function()
{
$(".edit_tr").click(function()
{
var ID=$(this).attr('id');
$("#mobile"+ID).hide();
$("#mobile"+ID).show();
}).change(function()
{
var ID=$(this).attr('id');
var mobile_no=$("#mobile"+ID).val();
var dataString = 'id='+ ID +'&mobile_no='+mobile_no+'&login_id='+<?php echo $_SESSION['login_id']; ?>;
if(mobile_no.length>0)
{

$.ajax({
type: "POST",
url: "update_back_act_mobile.php",
data: dataString,
cache: false,
success: function(data)
{

}
});
}
else
{
    alert('Enter something.');
}

});

$(".editbox").mouseup(function() 
{
return false
});

// Outside click action
$(document).mouseup(function()
{
$(".editbox").hide();
$(".text").show();
});
});

</script>
    <script src="../js/change_device.js"></script>
    <script src="../js/avatar.js"></script>
    <!-- Jquery Core Js -->
    <script src="../plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <!-- <script src="../plugins/bootstrap-select/js/bootstrap-select.js"></script> -->

    <!-- Slimscroll Plugin Js -->
    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../plugins/node-waves/waves.js"></script>

    <!-- Editable Table Plugin Js -->
    <script src="../plugins/editable-table/mindmup-editabletable.js"></script>

    <!-- Custom Js -->
    <script src="../js/admin.js"></script>
    <script src="../js/pages/tables/editable-table.js"></script>

    <!-- Demo Js -->
    <script src="../js/demo.js"></script>
</body>

</html>