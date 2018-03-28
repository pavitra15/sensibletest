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
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
                    <ul class="demo-choose-skin">
                        <li>
                            <a href="../operation/information.php">
                                <i class="material-icons">text_fields</i>
                                <span>Device Information</span>
                            </a>
                        </li> 
                       
                       
                    </ul>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="settings">
                    <div class="demo-settings">
                        <p>GENERAL SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Report Panel Usage</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Email Redirect</span>
                                <div class="switch">
                                    <label><input type="checkbox"><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                        <p>SYSTEM SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Notifications</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Auto Updates</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                        <p>ACCOUNT SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Offline</span>
                                <div class="switch">
                                    <label><input type="checkbox"><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Location Permission</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </aside>
        <!-- #END# Right Sidebar -->
    </section>
<style type="text/css">
                        select {
                        	font-weight: 100;
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
                    PRODUCT MASTER
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
                                <table id="mainTable" class="table table-bordered table-striped table-hover dataTable">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>English</th>
                                            <th>Assamese</th>
                                            <th>Bangla</th>
                                            <th>Gujarati</th>
                                            <th>Hindi</th>
                                            <th>Kannada</th>
                                            <th>Malayalam</th>
                                            <th>Marathi</th>
                                            <th>Oriya</th>
                                            <th>Punjabi</th>
                                            <th>Tamil</th>
                                            <th>Telugu</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $status="active";
                                        $query=$db->prepare("select * from product_mst, category_mst where category_mst.category_id=product_mst.category_id and product_mst.status='$status'");
                                        $query->execute();
                                        while($data=$query->fetch())
                                        {
                                            echo'<tr id="'.$data['product_id'].'" class="edit_tr">
                                            <th><select id="cat_types'.$data['product_id'].'">';
                                                echo'<option value="'.$data['category_id'].'">'.$data['category_name'].'</option>';
                                                $status="active";
                                                    $cat_query=$db->prepare("select * from category_mst where status='$status'");
                                                    $cat_query->execute();
                                                    if($cat=$cat_query->fetch())
                                                    {
                                                        do
                                                        {
                                                            echo'<option value="'.$cat['category_id'].'">'.$cat['category_name'].'</option>';
                                                        }
                                                        while($cat=$cat_query->fetch());
                                                    }
                                                echo'</select> </th>
                                                <td id="english'.$data['product_id'].'">'.$data['english'].'</td>
                                                <td id="assame'.$data['product_id'].'">'.$data['assame'].'</td>
                                                <td id="bangla'.$data['product_id'].'">'.$data['bangla'].'</td>
                                                <td id="gujarati'.$data['product_id'].'">'.$data['gujarati'].'</td>
                                                <td id="hindi'.$data['product_id'].'">'.$data['hindi'].'</td>
                                                <td id="kannada'.$data['product_id'].'">'.$data['kannada'].'</td>
                                                <td id="malayalam'.$data['product_id'].'">'.$data['malayalam'].'</td>
                                                <td id="marathi'.$data['product_id'].'">'.$data['marathi'].'</td>
                                                <td id="oriya'.$data['product_id'].'">'.$data['oriya'].'</td>
                                                <td id="punjabi'.$data['product_id'].'">'.$data['punjabi'].'</td>
                                                <td id="tamil'.$data['product_id'].'">'.$data['tamil'].'</td>
                                                <td id="telugu'.$data['product_id'].'">'.$data['telugu'].'</td>
                                                <th><button type="button" class="btn bg-red btn-xs removebutton" title="Remove this row"><i class="material-icons">delete</i></button></th>
                                        </tr>';
                                        }
                                    ?>
                                        <tr id="insert">
                                            <th><select class="cat_types">
                                            <?php
                                                $status="active";
                                                    $cat_query=$db->prepare("select * from category_mst where status='$status'");
                                                    $cat_query->execute();
                                                    if($cat=$cat_query->fetch())
                                                    {
                                                        do
                                                        {
                                                            echo'<option value="'.$cat['category_id'].'">'.$cat['category_name'].'</option>';
                                                        }
                                                        while($cat=$cat_query->fetch());
                                                    }
                                                    ?>
                                                </select> </th>
                                                <td class="english_nm"></td>
                                                <td class="assame_nm"></td>
                                                <td class="bangla_nm"></td>
                                                <td class="gujarati_nm"></td>
                                                <td class="hindi_nm"></td>
                                                <td class="kannada_nm"></td>
                                                <td class="malayalam_nm"></td>
                                                <td class="marathi_nm"></td>
                                                <td class="oriya_nm"></td>
                                                <td class="punjabi_nm"></td>
                                                <td class="tamil_nm"></td>
                                                <td class="telugu_nm"></td>
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
        var deleteString = 'product_id='+ trid+'&id='+<?php echo $_SESSION['login_id']; ?>;
        $.ajax({
        type: "POST",
        url: "delete_product_mst.php",
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
$("#cat_types"+ID).hide();
$("#english"+ID).hide();
$("#assame"+ID).hide();
$("#bangla"+ID).hide();
$("#gujarati"+ID).hide();
$("#hindi"+ID).hide();
$("#kannada"+ID).hide();
$("#malayalam"+ID).hide();
$("#marathi"+ID).hide();
$("#oriya"+ID).hide();
$("#punjabi"+ID).hide();
$("#tamil"+ID).hide();
$("#telugu"+ID).hide();
$("#cat_types"+ID).show();
$("#english"+ID).show();
$("#assame"+ID).show();
$("#bangla"+ID).show();
$("#gujarati"+ID).show();
$("#hindi"+ID).show();
$("#kannada"+ID).show();
$("#malayalam"+ID).show();
$("#marathi"+ID).show();
$("#oriya"+ID).show();
$("#punjabi"+ID).show();
$("#tamil"+ID).show();
$("#telugu"+ID).show();
}).change(function()
{
var ID=$(this).attr('id');
var types=$("#cat_types"+ID).val();
var english=$("#english"+ID).text();
var assame=$("#assame"+ID).text();
var bangla=$("#bangla"+ID).text();
var gujarati=$("#gujarati"+ID).text();
var hindi=$("#hindi"+ID).text();
var kannada=$("#kannada"+ID).text();
var malayalam=$("#malayalam"+ID).text();
var marathi=$("#marathi"+ID).text();
var oriya=$("#oriya"+ID).text();
var punjabi=$("#punjabi"+ID).text();
var tamil=$("#tamil"+ID).text();
var telugu=$("#telugu"+ID).text();
var dataString = 'id='+ ID+'&types='+types +'&english='+english+'&assame='+assame+'&bangla='+bangla+'&gujarati='+gujarati+'&hindi='+hindi+'&kannada='+kannada+'&malayalam='+malayalam+'&marathi='+marathi+'&oriya='+oriya+'&punjabi='+punjabi+'&tamil='+tamil+'&telugu='+telugu+'&user_id=<?php echo $_SESSION['login_id']; ?>';
if(english.length>0&& assame.length>0 && bangla.length>0 && gujarati.length>0 && hindi.length>0  && kannada.length>0&& malayalam.length>0 && marathi.length>0&& oriya.length>0 && punjabi.length>0&& tamil.length>0 && telugu.length>0)
{

$.ajax({
type: "POST",
url: "update_product_mst.php",
data: dataString,
cache: false,
success: function(data)
{
// $("#premise"+ID).html(first);
// $("#no"+ID).html(last);
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
    var types = [];
    var english = [];
    var assame = [];
    var bangla = [];
    var gujarati = [];
    var hindi = [];
     var kannada = [];
  var malayalam = [];
   var marathi = [];
  var oriya = [];
   var punjabi = [];
  var tamil = [];
   var telugu = [];

   $('.cat_types').each(function(){
   types.push($(this).val());
  });

    $('.english_nm').each(function(){
   english.push($(this).text());
  });

    
    $('.assame_nm').each(function(){
   assame.push($(this).text());
  });


   $('.bangla_nm').each(function(){
   bangla.push($(this).text());
  });

$('.gujarati_nm').each(function(){
   gujarati.push($(this).text());
  });


    $('.hindi_nm').each(function(){
   hindi.push($(this).text());
  });



    $('.kannada_nm').each(function(){
   kannada.push($(this).text());
  });



    $('.malayalam_nm').each(function(){
   malayalam.push($(this).text());
  });



    $('.marathi_nm').each(function(){
   marathi.push($(this).text());
  });

    $('.oriya_nm').each(function(){
   oriya.push($(this).text());
  });


    $('.punjabi_nm').each(function(){
   punjabi.push($(this).text());
  });


    $('.tamil_nm').each(function(){
   tamil.push($(this).text());
  });


    $('.telugu_nm').each(function(){
   telugu.push($(this).text());
  });


  $.ajax({
   url:"../admin/product_mst_insert.php",
   method:"POST",
   data:{types:types, english:english, assame:assame, bangla:bangla, gujarati:gujarati, hindi:hindi, kannada:kannada, malayalam:malayalam, marathi:marathi, oriya:oriya, punjabi:punjabi, tamil:tamil, telugu:telugu, id:<?php echo $_SESSION['login_id']; ?>},
   success:function(data){

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