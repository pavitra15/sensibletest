<style>
#country-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
#country-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
#country-list li:hover{background:#ece3d2;cursor: pointer;}
#search-box{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
</style>

<?php
    session_start();
    include('admin_verify.php');       
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome | Sensible Connect - Admin</title>
    <!-- Favicon-->
    <link rel="icon" href="../favicon.png" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />
    <link href="../plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <link href="../plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/aviator.css" rel="stylesheet">

    <link href="../css/themes/all-themes.css" rel="stylesheet" />
</head>
<style type="text/css">
    
    [type="checkbox"]:not(:checked), [type="checkbox"]:checked {
     position: initial; 
     left: -9999px; 
    opacity: 1;
        }
    .table > tbody > tr > th.selected, .table > tfoot > tr > th.selected, .table > thead > tr.selected > td, .table > tbody > tr.selected > td, .table > tfoot > tr.selected > td, .table > thead > tr.selected > th, .table > tbody > tr.selected > th, .table > tfoot > tr.selected > th {
            background-color:#9E9E9E;
        }
</style>

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
    <section>
        <aside id="leftsidebar" class="sidebar">
            <?php 
                include('../user_menu.php');
            ?>
            <div class="menu">
                <ul class="list">
                    <li>
                        <a href="../admin/dashboard">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">settings</i>
                            <span>Master</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="../admin/category_master">Category</a>
                            </li>
                            <li>
                                <a href="../admin/product_master">Product</a>
                            </li>
                            <li>
                                <a href="../admin/tax_master">Tax</a>
                            </li>
                            <li>
                                <a href="../admin/unit_master">Unit</a>
                            </li>
                            <li>
                                <a href="../admin/user_type_master">User Type</a>
                            </li>
                             <li>
                                <a href="../admin/state_master">State</a>
                            </li>
                            <li>
                                <a href="../admin/language_master">Language</a>
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
                                <a href="../admin/allow_access">Allow Access</a>
                            </li>
                            <li>
                                <a href="../admin/deny_access">Deny Access</a>
                            </li>
                             <li>
                                <a href="../admin/make_demo">Make Demo</a>
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
                                <a href="../admin/user_device">User Device</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="../admin/upload">
                            <i class="material-icons">cloud_upload</i>
                            <span>Upload</span>
                        </a>
                    </li>
                    <li>
                        <a href="../admin/approve">
                            <i class="material-icons">update</i>
                            <span>Approval Pending</span>
                        </a>
                    </li>
                    <li>
                        <a href="../admin/back_act_mobile">
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
        <!-- #END# Right Sidebar -->
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
    -webkit-appearance:  !important;
    -moz-appearance: none !important;
    -webkit-box-shadow: none !important;
    -moz-box-shadow: none !important;
}
}
input[type='file'] {
  color: transparent;
}
</style>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    PRODUCT SETTING
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
               <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 card">
                    <form action="" method="post">
                        <br>
                                <table id="mainTable" class="table table-bordered  table-hover js-basic-example ">
                                    <thead>
                                        <tr>
                                            <th>English</th>
                                            <th>Image</th>
                                            <th>Browse</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab_id">
                                    <?php
                                        $status="active";
                                        $query=$db->prepare("select product_id, english,paths, bucket from product_mst where status='$status'");
                                        $query->execute();
                                        while($data=$query->fetch())
                                        {
                                            echo'<tr id="'.$data['product_id'].'" class="edit_tr">
                                                <td>'.$data['english'].'</td>
                                                <td><input type="file" id="blah'.$data['product_id'].'" class="change" onchange="readURL(this);"/></td><td><img class="blah'.$data['product_id'].'" src="'.$data['bucket'].'" height="30px"/></td>
                                                </tr>';
                                        }
                                    ?>
                                    </tbody>
                                </table>
                           </div>
                    </div>
            </div>
        </div>
    </section>
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <script type="text/javascript">

        function readURL(input) 
        {
            var idname=input.id;
            if (input.files && input.files[0]) 
            {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.'+idname)
                        .attr('src', e.target.result)
                        .width(30)
                        .height(30);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

  


$(document).ready(function()
{
    $(".edit_tr").change(function()
    {
        var ID=$(this).attr('id');
        var file_data = $('#blah'+ID).prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('id',ID);
        $.ajax({
            url:'upload_img.php',
            dataType: 'text', // what to expect back from the PHP script
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data){
                switch(data)
                {
                    case "0":
                        showNotification("alert-danger", "File is too large", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                    break;
                     case "1":
                        showNotification("alert-info", "Records saved successfully", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                    break;
                     case "2":
                        showNotification("alert-danger", "Invalid file format", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                    break;
                     case "3":
                        showNotification("alert-danger", "Invalid request", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                    break;
                }
            }
        });
    });
});





</script>

    <script src="../js/change_device.js"></script>
    <script src="../js/avatar.js"></script>
    <!-- Jquery Core Js -->
    <script src="../plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../plugins/node-waves/waves.js"></script>

    <script src="../plugins/sweetalert/sweetalert.min.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <script src="../plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="../plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="../plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="../plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="../plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="../plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="../plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="../plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="../plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

    <!-- Custom Js -->
    <script src="../js/admin.js"></script>
    <script src="../js/pages/tables/jquery-datatable.js"></script>
    <!-- Demo Js -->
    <script src="../js/demo.js"></script>
</body>

</html>