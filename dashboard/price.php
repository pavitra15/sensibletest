<?php
    session_start();
    include('../validate.php');        
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome | Sensible Connect - Admin</title>
    
    <link rel="icon" href="../favicon.png" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />
    
    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <link href="../plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/aviator.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
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
                     include('../connect.php');
                        $id=$_SESSION['login_id'];
                        $d_id=$_SESSION['d_id'];
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
                            <i class="material-icons">expand_more</i>
                        </a>          
                        <ul class="dropdown-menu">
                            <li class="header">SELECT DEVICE</li>
                            <li class="body">
                                <ul class="menu">
                                <?php
                                    $device_query=$db->prepare("select * from device where id='$id' and status='$status'");
                                    $device_query->execute();
                                    while ($device_data=$device_query->fetch())
                                    {
                                        echo'<li>
                                            <a href="javascript:void(0);" onClick="change_device(this.id)" id="'.$device_data['d_id'].'">
                                                <div class="icon-circle bg-light-green">
                                                    <i class="material-icons"> devices_other</i>
                                                </div>
                                                <div class="menu-info">
                                                    <h4>'.$device_data['device_name'].'</h4>
                                                    <p>
                                                        <i class="material-icons">confirmation_number</i> '.$device_data['serial_no'].'
                                                    </p>
                                                </div>
                                            </a>
                                        </li>';
                                    } 
                                ?>
                                </ul>
                            </li>
                        </ul>             
                    </li>
                    <?php 
                        include('../notification/device_notification.php');
                    ?>
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
            <?php 
                include('../left_menu.php');
            ?>
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
            <div class="tab-content">
                <?php 
                    if(isset($_SESSION['d_id']))
                    {   
                        include('../right_menu.html');
                    } 
                ?>
                <div role="tabpanel" class="tab-pane fade" id="settings">
                    <?php 
                        if(isset($_SESSION['d_id']))
                        {   
                            include('../setting.php');
                        } 
                    ?>
                </div>
            </div>
        </aside>
        <!-- #END# Right Sidebar -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    PRICE
                </h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <form action="" method="post">
                            <div class="header">
                                <div class="row clearfix">
                                    <div class="col-xs-12 col-sm-6">
                                         <div class="switch panel-switch-btn" style="margin-left: 30px">
                                            <div id="switch" style="display: none"><?php echo $tax_type; ?></div>
                                            <span class="m-r-10 font-12">REVERSE TAXATION</span>
                                            <label>OFF<input type="checkbox" id="realtime" checked><span class="lever switch-col-cyan"></span>ON</label>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-xs-offset-3 align-right">
                                        <input type="text" name="search" id="search" placeholder="Search">
                                    </div>  
                                    </div>
                                    </div>  
                            <div class="body table-responsive">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="data-display" style="display: none">
                                    
                                </div>
                            </div>
                        </form>
                     </div>
            </div>
            </div>
        </div>
    </section>
   
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <script type="text/javascript">

        $(document).ready(function() 
        {
            $("#search").keyup(function()
            {
                var search= $("#search").val();
                if(search.length==0)
                {
                    var page="1";
                    $('#data-display').show();
                    $.ajax({
                        type: 'POST',
                        url: 'price_data.php',
                        data: { "page":page},
                        cache: false,
                        success: function(data)
                        {
                            $('#data-display').html(data);
                        }
                    });
                }
                else
                {
                    $('#data-display').show();
                    $.ajax({
                        type: 'POST',
                        url: 'price_search.php',
                        data: { "search":search},
                        cache: false,
                        success: function(data)
                        {
                            $('#data-display').html(data);
                        }
                    });    
                }    
            });
        });

        $(document).ready(function() 
        {  
            var page="1";
            $('#data-display').show();
            $.ajax({
                type: 'POST',
                url: 'price_data.php',
                data: { "page":page},
                cache: false,
                success: function(data)
                {
                    $('#data-display').html(data);
                }
            });
        });

$(document).ready(function() {
   var table =  $('#example').DataTable();
    
     $('#dropdown').on('change', function () {
                    table.columns(1).search( this.value ).draw();
                } );     
});

var realtime;
 $('#realtime').on('change', function () {
    realtime = this.checked ? 'on' : 'off';
    var dataString = 'realtime='+ realtime+'&id='+<?php echo $_SESSION['login_id']; ?> +'&d_id='+<?php echo $_SESSION['d_id']; ?>;  
    $.ajax({
type: "POST",
url: "../sql/update_incl.php",
data: dataString,
cache: false,
success: function(data)
{
    var total_page=parseInt(data)/30;
    recursiveAjaxCall(total_page,1);
}
});
        
});


function recursiveAjaxCall(aNum,currentNum)
{
    $('.page-loader-wrapper').show();
  $.ajax({
    type: 'POST',
    url: '../sql/calculation.php',
    data: {page_no: currentNum, inclusive:realtime, d_id:<?php echo $_SESSION['d_id']; ?>, id:<?php echo $_SESSION['login_id']; ?>},
    success: function(data)
    {
        if(data<aNum)
        {
            recursiveAjaxCall(aNum,data);
        }
        else
        {
            $('.page-loader-wrapper').hide();
            showNotification("alert-info", " records saved successfully", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
            setTimeout("location.reload(true);",2000);
        }
    },
    async:   true
  });
}

    
 $(document).ready(function() {
    var realt;
    $('#switch').each(function(){
   realt=$(this).text();
  });
    if(realt==="on")
    {
         $('#realtime').attr('checked', true);
    }
    else
    {
         $('#realtime').attr('checked', false);
     }
});


    $(document).ready(function() 
    {
        $('.grpprice').keypress(function (event) 
        {
            return isNumber(event, this)
        });
    });
    
    function isNumber(evt, element) {

        var charCode = (evt.which) ? evt.which : event.keyCode

        if (
            (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
            (charCode < 48 || charCode > 57))
            return false;

        return true;
    }  


    $('.grpprice').keypress(function(event) {
    var $this = $(this);
    if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
       ((event.which < 48 || event.which > 57) &&
       (event.which != 0 && event.which != 8))) {
           event.preventDefault();
    }

    var text = $(this).val();
    if ((event.which == 46) && (text.indexOf('.') == -1)) {
        setTimeout(function() {
            if ($this.val().substring($this.val().indexOf('.')).length > 3) {
                $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
            }
        }, 1);
    }

    if ((text.indexOf('.') != -1) &&
        (text.substring(text.indexOf('.')).length > 2) &&
        (event.which != 0 && event.which != 8) &&
        ($(this)[0].selectionStart >= text.length - 2)) {
            event.preventDefault();
    }      
});

$('.grpprice').bind("paste", function(e) {
var text = e.originalEvent.clipboardData.getData('Text');
if ($.isNumeric(text)) {
    if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
        e.preventDefault();
        $(this).val(text.substring(0, text.indexOf('.') + 3));
   }
}
else {
        e.preventDefault();
     }
});

    $(document).ready(function() {
$("input[type=number]").on("focus", function() {
    $(this).on("keydown", function(event) {
        if (event.keyCode === 38 || event.keyCode === 40) {
            event.preventDefault();
        }
     });
   });
});


$('.grpprice').keypress(function(event) {
    var value = event.target.value + String.fromCharCode(event.keyCode);
    if (!(/^\d{1,7}(\.$|\.\d{1,2}$|$)/).test(value)) {
      event.preventDefault();
      event.stopPropagation();
    }

    });

$(document).ready(function()
    {
        $('#left_product').addClass('active');
    });

</script>

    <script src="../js/change_device.js"></script>

    <script src="../js/avatar.js"></script>

    <script src="../plugins/jquery/jquery.min.js"></script>

    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <script src="../plugins/node-waves/waves.js"></script>

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

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <script src="../js/demo.js"></script>
</body>

</html>