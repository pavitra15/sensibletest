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

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
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
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="index.php">SENSIBLE CONNECT</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Call Search -->
                    <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                    <!-- #END# Call Search -->
                    <!-- Notifications -->

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
                    <!-- #END# Notifications -->
                    <!-- Tasks -->
                    <!-- #END# Tasks -->
                    <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
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
                            <li><a href="../account/profile.php"><i class="material-icons">person</i>Profile</a></li>
                            <li><a href="../account/change_password.php"><i class="material-icons">vpn_key</i>Change Password</a></li>
                            <li role="seperator" class="divider"></li>
                            <li><a href="../admin/logout.php"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
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
                <h2>
                    CATEGORY SETTING
                </h2>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <form action="" method="post">
                        <br>
                        <div class="col-md-2">
                            <div class="button-demo">
                                <button type="button" name="save" id="save" class="btn btn-info save">Save</button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">add_circle</i>
                                </span>
                                <div class="form-line">
                                    <input type="number" class="form-control date" id="add" placeholder="Enter No of rows">
                                </div>
                            </div>
                        </div>
                            
                            <div class="body">
                                <table id="mainTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Category Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $status="active";
                                        $query=$db->prepare("select * from category_mst where status='$status'");
                                        $query->execute();
                                        while($data=$query->fetch())
                                        {
                                            echo'<tr id="'.$data['category_id'].'" class="edit_tr">
                                                <td id="category'.$data['category_id'].'">'.$data['category_name'].'</td>
                                                <th><button type="button" class="btn bg-red btn-xs removebutton" title="Remove this row"><i class="material-icons">delete</i></button></th>
                                        </tr>';
                                        }
                                    ?>
                                        <tr id="insert">
                                                <td class="category_name"></td>
                                                <th><button type="button" class="btn bg-red btn-xs removebutton" title="Remove this row"><i class="material-icons">delete</i></button></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
               
                    </div>
                </div>
            </div>
                     </form>
        </div>
    </section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
    
    $(window).keydown(function(){
        if (event.keyCode == 13) {
                $("tbody > tr:last").clone().appendTo("table");       
        event.preventDefault();
            }
});

    $(document).on('click', 'button.removebutton', function () {
    
        var bid = this.id; // button ID 
        var trid = $(this).closest('tr').attr('id');
        var deleteString = 'category_id='+ trid+'&id='+<?php echo $_SESSION['login_id']; ?>;
        $.ajax({
        type: "POST",
        url: "delete_category_mst.php",
        data: deleteString,
        cache: false
        });

    $(this).closest('tr').remove();
     return false;
    
 });
   


$(document).ready(function()
{
$(".edit_tr").click(function()
{
var ID=$(this).attr('id');
$("#category"+ID).hide();
$("#category"+ID).show();
}).change(function()
{
var ID=$(this).attr('id');
var first=$("#category"+ID).text();
var dataString = 'category_id='+ ID +'&category_name='+first+'&id='+<?php echo $_SESSION['login_id']; ?>;
$("#category"+ID).html(''); // Loading image

if(first.length>0)
{

$.ajax({
type: "POST",
url: "update_category_mst.php",
data: dataString,
cache: false,
success: function(html)
{
      setTimeout("location.reload(true);",1000);
 $("#category"+ID).html(first);
}
});
}
else
{
alert('Enter something.');
    }

});

// Edit input box click action
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

$('#add').on('input',function(e){
     var rowsno=$(this).val(); 
     var i;
     for(i = 0; i < rowsno; i++)
     {
        $("tbody > tr:last").clone().appendTo("table");       
        event.preventDefault();
     }
    });


$(document).ready(function()
{
$('#save').click(function(){
  var category_name = [];
  $('.category_name').each(function(){
   category_name.push($(this).text());
  });
  $.ajax({
   url:"category_mst_insert.php",
   method:"POST",
   data:{category_name:category_name,  id:<?php echo $_SESSION['login_id']; ?>},
   success:function(data){
    setTimeout("location.reload(true);",1000);
   }
  });
 });
 });

$(document).ready(function() {
            var nm =  $('.name').text();
        var ret = nm.split(" "); 
     var k=(ret[0].charAt(0))+(ret[1].charAt(0));
   $('#aviator').data("letters",k);
    $('#aviator').attr("data-letters",k);
    
});

</script>
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

    <!-- Editable Table Plugin Js -->
    <script src="../plugins/editable-table/mindmup-editabletable.js"></script>

    <!-- Custom Js -->
    <script src="../js/admin.js"></script>
    <script src="../js/pages/tables/editable-table.js"></script>

    <!-- Demo Js -->
    <script src="../js/demo.js"></script>
</body>

</html>