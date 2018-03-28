<?php
    session_start();
    include('../validate.php');   
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome To | Sensible Connect - Admin</title>
    <!-- Favicon-->
    <link rel="icon" href="../favicon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />
    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/aviator.css" rel="stylesheet">
    <link href="../css/themes/all-themes.css" rel="stylesheet" />
    <link href="../css/custom.css" rel="stylesheet" />
    
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
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <?php 
                include('../user_menu.php');
            ?>
            <div class="menu">
            <?php 
                include('../left_menu.php');
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
                    CATEGORY SETTING
                </h2>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 card">
                    <br> 
                    <div class="row">
                        <div class="col-md-2">
                            <div class="button-demo">
                                <button type="button" name="save" id="save" class="btn btn-info save">Save</button>
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
                            <div class="body table-responsive">
                                <div class="frmSearch">
                                    <div id="suggesstion-box"></div>
                                </div>
                                <table id="maintable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Category Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab_id">
                                    <?php
                                        $status="active";
                                        $query=$db->prepare("select * from category_dtl where deviceid='$d_id' and status='$status'");
                                        $query->execute();
                                        while($data=$query->fetch())
                                        {
                                            echo'<tr id="'.$data['category_id'].'" class="edit_tr">
                                                <td><input type="text" id="category_'.$data['category_id'].'" class="cat test-input search-box" value="'.$data['category_name'].'" maxlength="30"></td>
                                                <th><button type="button" class="btn bg-red btn-xs removebutton" title="Remove this row"><i class="material-icons">delete</i></button></th>
                                        </tr>';
                                        }
                                    ?>
                                        <tr class="insert" id='1'>
                                                <td ><input type="text" class="category_name cat test-input search-box" id="category_1" maxlength="30"></td>
                                                <th><button type="button" class="btn bg-red btn-xs removebutton" title="Remove this row"><i class="material-icons">delete</i></button></th>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="row" style="text-align: right;">
                                    <button type="button" id="add" class="btn bg-cyan btn-circle waves-effect waves-circle waves-float"> 
                                        <i class="material-icons">add</i> 
                                    </button>
                                </div>
                            </div>
               
                    </div>
                </div>
            </div>
        </div>
    </section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">

        var id_category;
        $(document).on('keyup', '.search-box', function() 
        {
            id_category = $(this).attr('id');
            var values=id_category.split('_');
            var keyword=$('#'+id_category).val();
            $.ajax
            ({
                type: "POST",
                url: "category_master.php",
                data:'keyword='+keyword,
                success: function(data)
                {
                    $("#suggesstion-box").show();
                    $("#suggesstion-box").html(data);
                }
            });
        });
    
        function selectCountry(category_name) 
        {
            $('#'+id_category).val(category_name);
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

                row.find("input[id='category_"+idold+"']").attr('id','category_'+idnew);
                row.find("input[type='text']").val("");
                $("#tab_id").append(row);   

                event.preventDefault();
            }
        }
    });

    $('#add').click(function()
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

            row.find("input[id='category_"+idold+"']").attr('id','category_'+idnew);
            row.find("input[type='text']").val("");
            $("#tab_id").append(row);   

            event.preventDefault();
        }
    });
   
   $(document).on('click', 'button.removebutton', function () 
    {
        var kid=$(this);
        var bid = this.id;
        var class_name =$(this).closest('tr').attr('class');
        var trid = $(this).closest('tr').attr('id');
        if(class_name=="insert")
        {
            var length= $('#mainTable tbody tr.insert').length;
            if(length>1)
            {
                var trid = $(this).closest('tr').attr('id');
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
            var table_name="category_dtl";
            var column_name="category_id";
            var deleteString = 'column_name='+ column_name+'&table_name='+ table_name+'&delete_id='+ trid+'&login_id='+<?php echo $_SESSION['login_id']; ?>; swal(
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
                        url: "../sql/delete_update.php",
                        data: deleteString,
                        cache: false,
                        success: function(data)
                        {
                            console.log(data);
                            var datastr = 'd_id='+<?php echo $_SESSION['d_id']; ?>+'&id='+<?php echo $_SESSION['login_id']; ?>+'&notification=Category deleted successfully &priority=3 &device_name='+<?php echo '"'.$_SESSION['device_name'].'"'; ?>;
                            $.ajax({
                                type: "POST",
                                url: "../dashboard/notification_insert.php",
                                data: datastr,
                                cache: false,
                                success: function(data)
                                {
                                }
                            });
                        }
                    });
                    $(kid).closest('tr').remove();
                } 
                else 
                {
                }
            }); 
        }
    });
  
    $(document).ready(function()
    {
        $(".edit_tr").click(function()
        {
            var ID=$(this).attr('id');
            $("#category_"+ID).hide();
            $("#category_"+ID).show();
        }).change(function()
        {
            var ID=$(this).attr('id');
            var first=$("#category_"+ID).val();
            var dataString = 'category_id='+ ID +'&category_name='+first+'&id='+<?php echo $_SESSION['login_id']; ?>+'&d_id='+<?php echo $_SESSION['d_id']; ?>;
            if(first.length>0)
            {
                $.ajax({
                    type: "POST",
                    url: "../sql/update_category.php",
                    data: dataString,
                    cache: false,
                    success: function(data)
                    {
                        values=data.split('_');
                        ch=values[0];
                        name=values[1];
                        switch(ch)
                        {
                            case "1":
                                showNotification("alert-info", "Record updated successfully", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                                $("#category"+ID).val(first);
                                var datastr = 'd_id='+<?php echo $_SESSION['d_id']; ?>+'&id='+<?php echo $_SESSION['login_id']; ?>+'&notification=Category added successfully &priority=3 &device_name='+<?php echo '"'.$_SESSION['device_name'].'"'; ?>;
                                $.ajax({
                                    type: "POST",
                                    url: "notification_insert.php",
                                    data: datastr,
                                    cache: false,
                                    success: function(data)
                                    {
                                    }
                                });
                            break;
                            case "2":
                                showNotification("alert-danger","Duplicate record not allowed", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                                $("#category_"+ID).val(name);
                            break;
                            case "3":
                                showNotification("alert-danger","Empty value not allowed", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                                $("#category_"+ID).val(name);
                            break;                    
                        }
                    }
                });
            }
            else
            {
                showNotification("alert-danger","All fields are mandatory", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
            }
        });
        $(".editbox").mouseup(function() 
        {
            return false
        });

        $(document).mouseup(function()
        {
            $(".editbox").hide();
            $(".text").show();
        });
    });

    $('#add').change(function()
    {
        var rowsno=$('#add').val(); 
        var i;
        if(rowsno<=99)
        {
            for(i = 0; i < rowsno; i++)
            {
                $("tbody > tr:last").clone().appendTo("table");       
                event.preventDefault();
            }
            $('#add').val(0);
        }
    });


    $(document).ready(function()
    {
        $('#save').click(function(){
            this.disabled = true;
            var category_name = [];
            $('.category_name').each(function(){
               category_name.push($(this).val());
            });
            $.ajax({
                url:"../sql/insert_category.php",
                method:"POST",
                data:{category_name:category_name,  id:<?php echo $_SESSION['login_id']; ?>, d_id:<?php echo $_SESSION['d_id']; ?>},
                success:function(data){
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


    $('input#add').on('keyup', function() {
        limitText(this, 2)
    });

    function limitText(field, maxChar){
        var ref = $(field),
            val = ref.val();
        if ( val.length >= maxChar ){
            ref.val(function() {
                console.log(val.substr(0, maxChar))
                return val.substr(0, maxChar);       
            });
        }
    }

    $('.test-input').unbind('keyup change input paste').bind('keyup change input paste',function(e){
        var $this = $(this);
        var val = $this.val();
        var valLength = val.length;
        var maxCount = $this.attr('maxlength');
        if(valLength>maxCount){
            $this.val($this.val().substring(0,maxCount));
        }
    });


    $('.cat').keypress(function(event) 
    {
        var $this = $(this);
        if ((event.which != 32) &&
           (((event.which < 65 || event.which > 90) && (event.which < 48 || event.which > 57) && (event.which < 97 || event.which > 122) && (event.which < 40 || event.which > 41)) &&
           (event.which != 0 && event.which != 8))) 
            {
               event.preventDefault();
            }      
    });

    $(document).ready(function()
    {
        $('#left_application').addClass('active');
    });

</script>
    
    <script src="../js/change_device.js"></script>
    <script src="../js/avatar.js"></script>
    <script src="../plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="../plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../plugins/node-waves/waves.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <!-- Editable Table Plugin Js -->
    <script src="../plugins/editable-table/mindmup-editabletable.js"></script>
    <script src="../plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Custom Js -->
    <script src="../js/admin.js"></script>
    <script src="../js/pages/tables/editable-table.js"></script>

    <script src="../js/demo.js"></script>
</body>

</html>