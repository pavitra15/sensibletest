<?php
    session_start();
    include('admin_verify.php');       
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

    <link href="../plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <!-- Waves Effect Css -->
    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <link href="../css/custom.css" rel="stylesheet">
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
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="../dashboard/dashboard">SENSIBLE CONNECT</a>
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
        <aside id="rightsidebar" class="right-sidebar">
            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                <li role="presentation" class="active"><a href="#skins" data-toggle="tab">DEVICE</a></li>
                <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>
            </ul>
        </aside>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    LANGUAGE MASTER
                </h2>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <br>
                        <div class="col-md-2">
                            <div class="button-demo">
                                <button type="button" name="save" id="save" class="btn btn-info save">Save</button>
                            </div>
                        </div>
                        <div class="body">
                            <table  class="table table-striped" id="mainTable">
                                <thead>
                                    <tr>
                                        <th>State Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $status="active";
                                    $query=$db->prepare("select * from language_mst where status='$status'");
                                    $query->execute();
                                    while($data=$query->fetch())
                                    {
                                        echo'<tr id="'.$data['language_id'].'" class="edit_tr">
                                            <td><input type="text" id="language'.$data['language_id'].'" value="'.$data['language_name'].'"></td>
                                            <th><button type="button" class="btn bg-red btn-xs removebutton" title="Remove this row"><i class="material-icons">delete</i></button></th>
                                        </tr>';
                                    }
                                ?>
                                    <tr class="insert">
                                        <td><input type="text" class="language_name" name="" value=""></td>
                                        <th><button type="button" class="btn bg-red btn-xs removebutton" title="Remove this row"><i class="material-icons">delete</i></button></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>           
                    </div>
                </div>
            </div>
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
                var table_name="language_mst";
                var column_name="language_id";
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
                            url: "sql/delete.php",
                            data: deleteString,
                            cache: false,
                            success: function(data)
                            {

                            }
                        });
                        $(kid).closest('tr').remove();
                    } 
                }); 
            }
        });

        $(document).ready(function()
        {
            $(".edit_tr").click(function()
            {
                var ID=$(this).attr('id');
                $("#language"+ID).hide();
                $("#language"+ID).show();
            }).change(function()
            {
                var ID=$(this).attr('id');
                var language_name=$("#language"+ID).val();
                var dataString = 'language_id='+ ID +'&language_name='+language_name+'&id='+<?php echo $_SESSION['login_id']; ?>;
                if(language_name.length>0)
                {
                    $.ajax({
                    type: "POST",
                    url: "sql/update_language_mst.php",
                    data: dataString,
                    cache: false,
                    success: function(data)
                    {
                        if(data==1)
                            showNotification("alert-info", "Record updated successfully", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                        else
                            showNotification("alert-Danger", "Fail to update record", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                    }
                    });
                }
                else
                {
                    showNotification("alert-Danger", "All fields are mandatory", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
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

        $(document).ready(function()
        {
            $('#save').click(function(){
                this.disabled = true;
                var language_name = [];
              
                $('.language_name').each(function(){
                    language_name.push($(this).val());
                });
                $.ajax({
                    url:"sql/insert_language_mst.php",
                    method:"POST",
                    data:{language_name:language_name, id:<?php echo $_SESSION['login_id']; ?>},
                    success:function(data){
                        setTimeout("location.reload(true);",500);
                    }
                });
            });
        });
        
        $(document).ready(function()
        {
            $('#left_master').addClass('active');
        });
</script>
    <script src="../js/change_device.js"></script>
    <script src="../js/avatar.js"></script>
    <!-- Jquery Core Js -->
    <script src="../plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <script src="../plugins/sweetalert/sweetalert.min.js"></script>
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