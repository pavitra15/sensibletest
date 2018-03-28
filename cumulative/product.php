<?php
    session_start();
    include('../validate.php');     
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome | Sensible Connect - Admin</title>
    <link rel="icon" href="../favicon.png" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    
    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <link href="../plugins/morrisjs/morris.css" rel="stylesheet" />

    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />
        <link href="../plugins/sweetalert/sweetalert.css" rel="stylesheet" />

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
                        include('../notification/notification.php');
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
                <ul class="list">
                    <li class="active">
                        
                    </li>
                </ul>
            </div>
            <?php include('../footer.html'); ?>
        </aside>
    </section>
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
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    PRODUCT SETTING
                </h2>
            </div>
            <div class="row card">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="data-display" style="display: none">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 " style="margin-bottom: 15px">
                        <button class="btn waves-effect" id="skip">SKIP</button>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 15px; text-align: right;">
                        <button class="btn btn-primary waves-effect" id="save">CONTINUE</button>
                    </div>
                </div>
                <div class="frmSearch">
                    <div id="suggesstion-box"></div>
                </div>
            </div>
        </section>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script type="text/javascript">

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
                url: "../dashboard/product_master.php",
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
                url: '../dashboard/product_data.php',
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
                            cache: false,
                            success: function(data)
                            {
                                var datastr = 'd_id='+<?php echo $_SESSION['d_id']; ?>+'&id='+<?php echo $_SESSION['login_id']; ?>+'&notification=Product deleted successfully &priority=3 &device_name='+<?php echo '"'.$_SESSION['device_name'].'"'; ?>;
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
                            var table_name="product";
                            $.ajax({
                                url:"../sql/row_count.php",
                                method:"POST",
                                data:{table_name:table_name, d_id:<?php echo $_SESSION['d_id']; ?>},
                                success:function(data){
                                    if(data>0)
                                    {
                                        window.location.replace('../cumulative/stock');                                                           
                                    }
                                    else
                                    {
                                        $('#save').prop('disabled', false);
                                        showNotification("alert-danger", "Fill details here", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                                    }
                                }
                            });
                        break;                
                    }
                }
            });
        });
    });

		// $(document).ready(function()
  //       {
  //           $('#save').click(function(){
  //               this.disabled = true;
  //                 var english = [];
  //                 var regional = [];
  //                 var weightable = [];
  //                 var kitchen = [];
  //                 var category = [];
  //                 var bucket=[];

  //                 $('.english_name').each(function(){
  //                      english.push($(this).val());
  //                 });
  //                 $('.regional_name').each(function(){
  //                      regional.push($(this).val());
  //                 });
  //                 $('.weighing').each(function(){
  //                      weightable.push($(this).val());
  //                 });

  //                  $('.kitchen').each(function(){
  //                      kitchen.push($(this).val());
  //                 });

  //                 $('.category').each(function(){
  //                      category.push($(this).val());
  //                 });

  //                 $('.img').each(function(){
  //                   bucket.push($(this).val());
  //                 });

  //                 $.ajax({
  //                   url:"../sql/insert_product.php",
  //                   method:"POST",
  //                   data:{english:english, regional:regional,  weightable:weightable,  category:category, kitchen:kitchen, bucket:bucket,id:<?php //echo $_SESSION['login_id']; ?>, d_id:<?php //echo $_SESSION['d_id']; ?>},
  //                   success:function(data){
  //                       values=data.split('_');
  //                       ch=values[0];
  //                       count=values[1];
  //                       switch(ch)
  //                       {
  //                           case "1":
  //                               showNotification("alert-info", count+" records saved successfully", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
  //                               var table_name="product";
  //                               $.ajax({
  //                                   url:"../sql/row_count.php",
  //                                   method:"POST",
  //                                   data:{table_name:table_name, d_id:<?php //echo $_SESSION['d_id']; ?>},
  //                                   success:function(data){
  //                                       if(data>0)
  //                                       {
  //                                           window.location.replace('../cumulative/stock');                                                           
  //                                       }
  //                                       else
  //                                       {
  //                                           $('#save').prop('disabled', false);
  //                                           showNotification("alert-danger", "Fill details here", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
  //                                       }
  //                                   }
  //                               });
  //                           break;               
  //                       }
  //                   }
  //               });
  //            });
  //       });

 		$('.test-input').unbind('keyup change input paste').bind('keyup change input paste',function(e)
 		{
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
		    if((event.which != 32) &&
		    (((event.which < 65 || event.which > 90) && (event.which < 48 || event.which > 57) && (event.which < 97 || event.which > 122) && (event.which < 40 || event.which > 41)) &&
		    (event.which != 0 && event.which != 8))) 
		    {
		       event.preventDefault();
		    }      
		});

  		$(document).ready(function() 
        {         
            $('#skip').click(function()
            {
             	window.location.href = "../cumulative/index";     
            });
        });

        
         </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="../js/change_device.js"></script>
    <script src="../js/avatar.js"></script>

    
    <script src="../js/pages/ui/dialogs.js"></script>
    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../plugins/node-waves/waves.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="../plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- Morris Plugin Js -->
    <script src="../plugins/raphael/raphael.min.js"></script>
    <script src="../plugins/morrisjs/morris.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <script src="../js/admin.js"></script>

    <script src="../js/demo.js"></script>

    <script src="../js/avatar.js"></script>
    <script src="../plugins/jquery/jquery.min.js"></script>

    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    
    <script src="../plugins/node-waves/waves.js"></script>
    <script src="../js/admin.js"></script>
    
</body>

</html>