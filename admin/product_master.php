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
    <link href="../css/custom.css" rel="stylesheet">
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
            <div class="legal">
                <div class="copyright">
                    &copy; 2016 - 2017 <a href="https://app.sensibleconnect.com/redirect">Sensible Connect Solutions</a>.
                </div>
                <div class="version">
                    <b>Pune</b>
                </div>
            </div>
        
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    PRODUCT MASTER
                </h2>
            </div>
            <div class="row card">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="header">
                        <div class="col-md-2">
                            <div class="button-demo">
                                <button type="button" name="save" id="save" class="btn btn-info save">Save</button>
                            </div>
                        </div>
                        <div class="body">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="data-display" style="display: none">
                            </div>
                        </div>               
                    </div>
                </div>
            </div>
        </div>
    </section>
   
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <script type="text/javascript">
        $(window).keydown(function()
        {
            if (event.keyCode == 13) 
            {   
                var rows= $('.table tbody tr').length;  
                $("tbody > tr:last").clone().appendTo("table").find("input[type='text']").val(""); 
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
                var table_name="product_mst";
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
            var page="1";
            $('#data-display').show();
            $('.page-loader-wrapper').show();
            $.ajax({
                type: 'POST',
                url: 'sql/product_mst_data.php',
                data: { "page":page ,},
                cache: false,
                success: function(data)
                {
                    $('.page-loader-wrapper').hide();
                    $('#data-display').html(data);
                }
            });
        });

        $(document).ready(function()
        {
            $('#save').click(function(){
                var types = [];
                var english = [];
                var bangla = [];
                var gujarati = [];
                var hindi = [];
                var kannada = [];
                var malayalam = [];
                var marathi = [];
                var punjabi = [];
                var tamil = [];
                var telugu = [];

                $('.cat_types').each(function(){
                    types.push($(this).val());
                });

                $('.english_nm').each(function(){
                    english.push($(this).text());
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
                    url:"sql/insert_product_mst.php",
                    method:"POST",
                    data:{types:types, english:english, bangla:bangla, gujarati:gujarati, hindi:hindi, kannada:kannada, malayalam:malayalam, marathi:marathi, punjabi:punjabi, tamil:tamil, telugu:telugu, id:<?php echo $_SESSION['login_id']; ?>},
                    success:function(data){

                    }
                });
            });
        });

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
            $('#left_master').addClass('active');
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

    <!-- Custom Js -->
    <script src="../js/admin.js"></script>
    <script src="../js/pages/tables/jquery-datatable.js"></script>
    <!-- Demo Js -->
    <script src="../js/demo.js"></script>
</body>

</html>