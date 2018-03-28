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
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    CUSTOMER TYPE SETTING
                </h2>
            </div>
            <div class="row card">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="body table-responsive">
                    	<table class="table table-striped" id="mainTable">
                        	<thead>
                            	<tr>
                                	<th>Customer Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            	echo'<tr>
                                	<th>Default</th>
                                   	<th></th></tr>';
                                $status="active";
                                $d_id=$_SESSION['d_id'];
                                $query=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
                                $query->execute();
                                while($data=$query->fetch())
                                {
                                	echo'<tr id="'.$data['customer_id'].'" class="edit_tr">
                                    	<td><input type="text" id="customer'.$data['customer_id'].'" class="cat test-input" value="'.$data['customer_name'].'" maxlength="30"></td>
                                        <th><button type="button" class="btn bg-red btn-xs removebutton" title="Remove this row"><i class="material-icons">delete</i></button></th>
                                    </tr>';
                                }
                            ?>
                            	<tr id="insert" class="insert">
                                	<td> <input type="text" class="customer_name test-input cat" maxlength="30"></td>
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
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 " style="margin-bottom: 15px">
                    <button class="btn waves-effect" id="skip">SKIP</button>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 15px; text-align: right;">
                    <button class="btn btn-primary waves-effect" id="save">CONTINUE</button>
                </div>
            </div>
        </div>
    </section>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script type="text/javascript">

         $('#add').click(function()
         {
            var rows= $('.table tbody tr').length;
            if(rows<6)
            {
                $("tbody > tr:last").clone().appendTo("table").find("input[type='text']").val("");
                event.preventDefault();      
            }
            else
            {
                showNotification("alert-danger","Maximum six customer type are allowed", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
            }
        });

    	$(window).keydown(function()
    	{
        	if (event.keyCode == 13) 
        	{
            	var rows= $('.table tbody tr').length;
            	if(rows<6)
            	{
                	$("tbody > tr:last").clone().appendTo("table").find("input[type='text']").val("");
                	event.preventDefault();      
            	}
            	else
            	{
                	showNotification("alert-danger","Maximum six customer type are allowed", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
            	}
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
                var table_name="customer_dtl";
                var column_name="customer_id";
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
                                var datastr = 'd_id='+<?php echo $_SESSION['d_id']; ?>+'&id='+<?php echo $_SESSION['login_id']; ?>+'&notification=Customer deleted successfully &priority=3 &device_name='+<?php echo '"'.$_SESSION['device_name'].'"'; ?>;
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
    			$('.page-loader-wrapper').show();
  				var customer_name = [];
  
  				$('.customer_name').each(function(){
   					customer_name.push($(this).val());
  				});
  				$.ajax({
   					url:"../sql/insert_customer.php",
   					method:"POST",
   					data:{customer_name:customer_name, id:<?php echo $_SESSION['login_id']; ?>, d_id:<?php echo $_SESSION['d_id']; ?>},
   					success:function(data){
   						$('.page-loader-wrapper').hide();
	    				values=data.split('_');
	    				ch=values[0];
	    				count=values[1];
	    				switch(ch)
	    				{
					        case "1":
					            showNotification("alert-info", count+" records saved successfully", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
					            setTimeout("window.location.replace('../cumulative/category');",2000);
					        break;
					        case "2":
					            showNotification("alert-danger","Maximum six premises are allowed", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
					            setTimeout("window.location.replace('../cumulative/category');",3000);
					        break;                    
					    }
				   	}
				});
			});
		});


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

    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <script src="../plugins/node-waves/waves.js"></script>

    <script src="../plugins/jquery-countto/jquery.countTo.js"></script>

    <script src="../plugins/raphael/raphael.min.js"></script>
    <script src="../plugins/morrisjs/morris.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <script src="../js/admin.js"></script>

    <script src="../js/demo.js"></script>
    
</body>

</html>