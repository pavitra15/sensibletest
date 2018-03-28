
<?php
    session_start();
    include('../validate.php');
    include('../connect.php');        
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
        <link href="../plugins/sweetalert/sweetalert.css" rel="stylesheet" />

        <link href="../plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

        <link href="../css/style.css" rel="stylesheet">
        <link href="../css/aviator.css" rel="stylesheet">
        <link href="../css/custom.css" rel="stylesheet">
        <link href="../css/themes/all-themes.css" rel="stylesheet" />
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
                                $id=$_SESSION['login_id'];
                                $d_id=$_SESSION['d_id'];
                                $status="active";
                                $name_query=$db->prepare("select device_name,tax_type,prnt_billno, prnt_billtime, model from device where d_id='$d_id'");
                                $name_query->execute();
                                while ($name_data=$name_query->fetch()) 
                                {
                                    $device_name=$name_data['device_name'];
                                    $device_model=$name_data['model'];
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
            </aside>
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
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="block-header">
                    <h2>
                        PRODUCT SETTING
                    </h2>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 card">
                            <div class="header">
                                <div class="row clearfix">
                                    <div class="col-xs-12 col-sm-6">
                                        <button type="button" name="save" id="save" class="btn btn-info save">Save</button>
                                    </div>
                                    <div class="col-xs-3 col-xs-offset-3 align-right">
                                        <input type="text" name="search" id="search" placeholder="Search">
                                    </div>
                                </div>
                            </div>
                           
                        <div class="body table-responsive">
                            <div class="frmSearch">
                                <div id="suggesstion-box"></div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " id="data-display" style="display: none">
                            </div>
                        </div>
                    </div>
                </div>
                <style type="text/css">
                    .frmSearch
                    {
                        margin-left: 15%;
                        z-index: 1;
                        overflow: auto;
                        position: fixed;
                        background: #eee;
                        font-weight: 400;
                        font-size: 16px;
                    }
                </style>
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
                var device_model= <?php echo $device_model; ?>;
                if(search.length==0)
                {
                    var page="1";
                    $('#data-display').show();
                    $.ajax({
                        type: 'POST',
                        url: 'product_data.php',
                        data: { "page":page,"device_model": device_model},
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
                        url: 'product_search.php',
                        data: { "search":search,"device_model": device_model},
                        cache: false,
                        success: function(data)
                        {
                            $('#data-display').html(data);
                        }
                    });    
                }    
            });
        });

        var id_english;
        var id_regional;
        var id_blah;
        var id_img;
        $(document).on('keyup', '.search-box', function() 
        {
            id_english = $(this).attr('id');
            var values=id_english.split('_');
            id_regional='regional_'+values[1];
            id_blah='blah_'+values[1];
            id_img='img_'+values[1];
            var keyword=$('#'+id_english).val();
            $.ajax
            ({
                type: "POST",
                url: "product_master.php",
                data:'keyword='+keyword,
                success: function(data)
                {
                    $("#suggesstion-box").show();
                    $("#suggesstion-box").html(data);
                }
            });
        });
        
        function selectCountry(english,regional,image,img_id) 
        {
            $('#'+id_english).val(english);
            $('#'+id_regional).val(regional);
            $('#'+id_blah).attr('src', image);
            $('#'+id_img).val(img_id);
            $("#suggesstion-box").hide();
        }


        $(window).keydown(function()
        {
            if (event.keyCode == 13) 
            {
                var classname= $("#tab_id > tr:last").attr('class');
                values=classname.split(' ');
                class_name=values[0];
                if(class_name=="insert")
                {
                    var idold= $("#tab_id > tr:last").attr('id');
                    var idnew;
                    idnew=parseInt(idold)+1;
                    var row =$("#tab_id > tr:last").clone();

                    row.attr('id',idnew);

                    row.find("input[id='english_"+idold+"']").attr('id','english_'+idnew);
                    row.find("input[id='regional_"+idold+"']").attr('id','regional_'+idnew);
                    row.find("input[type='text']").val("");
                    $("#tab_id").append(row);   

                    event.preventDefault();
                } 
            }
        });

        $(document).ready(function() 
        {  
            var page="1";
            var device_model= <?php echo $device_model; ?>;
            $('#data-display').show();
            $('.page-loader-wrapper').show();
            $.ajax({
                type: 'POST',
                url: 'product_data.php',
                data: { "page":page ,"device_model": device_model},
                cache: false,
                success: function(data)
                {
                    $('.page-loader-wrapper').hide();
                    $('#data-display').html(data);
                }
            });
        });


    $(document).on('click', 'button.removebutton', function () 
    {
        var kid=$(this);
        var bid = this.id;
        var class_name =$(this).closest('tr').attr('class');
        var values=class_name.split(' ');
        var classname=values[0];
        if(classname=="insert")
        {
            var length= $('#mainTable tbody tr.insert').length;
            if(length>1)
            {
                var trid = $(this).closest('tr').attr('id');
                var deleteString = 'product_id='+ trid+'&id='+<?php echo $_SESSION['login_id']; ?>;
                swal(
                {
                    title: "Are you sure?",
                    text: "You will not be able to recover this record!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function (isConfirm) 
                {
                    if (isConfirm) 
                    {
                        $(kid).closest('tr').remove();
                    } 
                }); 
            }
        }
        else
        {
            var trid = $(this).closest('tr').attr('id');
            var table_name="product";
            var column_name="product_id";
            var deleteString = 'column_name='+ column_name+'&table_name='+ table_name+'&delete_id='+ trid+'&login_id='+<?php echo $_SESSION['login_id']; ?>;
            swal(
            {
                title: "Are you sure?",
                text: "You will not be able to recover this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) 
            {
                if (isConfirm) 
                {
                   $.ajax({
                        type: "POST",
                        url: "../sql/delete.php",
                        data: deleteString,
                        cache: false
                    });
                    $(kid).closest('tr').remove();
                } 
            }); 
        }
    });

$(document).ready(function()
{
$('#save').click(function(){
    this.disabled = true;
  var english = [];
  var regional = [];
  var weightable = [];
  var kitchen = [];
  var category = [];
  var bucket=[];
  var comission=[];
  var discount=[];

  $('.english_name').each(function(){
   english.push($(this).val());
  });
  $('.regional_name').each(function(){
   regional.push($(this).val());
  });
  $('.weighing').each(function(){
   weightable.push($(this).val());
  });

   $('.kitchen').each(function(){
   kitchen.push($(this).val());
  });

  $('.category').each(function(){
   category.push($(this).val());
  });

  $('.img').each(function(){
   bucket.push($(this).val());
  });

  $('.comission').each(function(){
   comission.push($(this).val());
  });

  $('.discount').each(function(){
   discount.push($(this).val());
  });

  $.ajax({
   url:"../sql/insert_product.php",
   method:"POST",
   data:{english:english, regional:regional,  weightable:weightable,  category:category, discount:discount, kitchen:kitchen, comission:comission, bucket:bucket,id:<?php echo $_SESSION['login_id']; ?>, d_id:<?php echo $_SESSION['d_id']; ?>},
   success:function(data){
    console.log(data);
    values=data.split('_');
    ch=values[0];
    count=values[1];
    switch(ch)
    {
        case "1":
            showNotification("alert-info", count+" records saved successfully", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
            setTimeout("location.reload(true);",3000);
        break;               
    }
   }
  });
 });
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

    <script src="../js/admin.js"></script>
    <script src="../js/demo.js"></script>
</body>

</html>