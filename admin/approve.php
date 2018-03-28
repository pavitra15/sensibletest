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
    
    <link rel="icon" href="../favicon.png" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />
    
    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <link href="../plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

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
                <a class="navbar-brand" href="../admin/dashboard">SENSIBLE CONNECT</a>
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
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    APPROVAL PENDING
                </h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <form action="" method="post">
                            <div class="body">
                                <table id="mainTable" class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <tr>
                                            <th>Device Id</th>
                                            <th>Serial Number</th>
                                            <th>Date</th>
                                            <th>Model</th>
                                            <th>Dealer Name</th>
                                            <th>Approve</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $status="inactive";
                                        $query=$db->prepare("select * from admin_device where status='$status'");
                                        $query->execute();
                                        if($data=$query->fetch())
                                        {
                                            $i=1;
                                            do 
                                            {
                                                $model_val="";
                                                $dealer="";
                                                $dealer_name="";
                                                $dealer_id=$data['dealer_id'];
                                                $dealer_query=$db->prepare("select dealer_id, dealer_code from dealer_mst where dealer_id='$dealer_id'");
                                                $dealer_query->execute();
                                                if($dealer_data=$dealer_query->fetch())
                                                {
                                                    do
                                                    {
                                                        $dealer=$dealer_data['dealer_id'];
                                                        $dealer_name=$dealer_data['dealer_code'];
                                                    }
                                                    while($dealer_data=$dealer_query->fetch());
                                                }
                                                echo'<tr id="'.$data['id'].'" class="edit_tr">
                                                    <td>'.$data['deviceid'].'</td>
                                                    <td>'.$data['serial_no'].'</td>
                                                    <td>'.$data['test_date'].'</td>
                                                    <td><select class="form-cotrol tax" id="model'.$data['id'].'">
                                                        <option value="'.$data['model'].'">'.$data['model'].'</option>
                                                        <option value="200">200</option>
                                                        <option value="300">300</option>
                                                        <option value="500">500</option>
                                                    </select></td>
                                                    <td><select class="form-cotrol tax" id="dealer'.$data['id'].'">
                                                        <option value="'.$dealer.'">'.$dealer_name.'</option>';
                                                        $status="active";
                                                        $dealer_query=$db->prepare("select * from dealer_mst");
                                                        $dealer_query->execute();
                                                        if($dealer_dat=$dealer_query->fetch())
                                                        {
                                                            do
                                                            {
                                                                echo'<option value="'.$dealer_dat['dealer_id'].'">'.$dealer_dat['dealer_code'].'</option>';
                                                            }
                                                            while($dealer_dat=$dealer_query->fetch());
                                                        }
                                                    echo'</select></td>';
                                                echo'<td><button type="button" class="btn bg-green btn-xs removebutton" title="Remove this row"><i class="material-icons">update</i></button></td></tr>';
                                            }
                                            while($data=$query->fetch());
                                        }
                                    ?>
                                    </tbody>
                                </table>
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
        $(document).on('click', 'button.removebutton', function () 
        {
            var trid = $(this).closest('tr').attr('id');
            var dealer_id=$('#dealer'+trid).val();
            var model=$('#model'+trid).val();
            var data= 'id='+ trid+'&model='+model+'&dealer_id='+dealer_id+'&login_id='+<?php echo $_SESSION['login_id']; ?>;
            if(model.length >0 && dealer_id > 0)
            {
                $.ajax({
                    type: "POST",
                    url: "sql/update_approval.php",
                    data: data,
                    success: function(data)
                    {
                        switch(data)
                        {
                            case "1":
                                showNotification("alert-info", "Records updated successfully", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                                setTimeout("location.reload(true);",3000);
                            break;
                            case "2":
                                showNotification("alert-danger", "Error occured", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                            break;
                        }
                    }
                });
            }
            else
            {
                showNotification("alert-danger", "Please select all fields", "top", "center",'animated fadeInDown', 'animated fadeOutUp');   
            }
        });

        $(document).ready(function()
        {
            $('#left_approve').addClass('active');
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

    <script src="../js/admin.js"></script>

    <script src="../js/pages/tables/jquery-datatable.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <script src="../js/demo.js"></script>
</body>

</html>