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
                    PREMISE SETTING
                </h2>
            </div>
            <div class="row card">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="body table-responsive">
                        <table class="table table-striped" id="mainTable">
                            <thead>
                                <tr>
                                    <th>Premise Name</th>
                                    <th>No of Table</th>
                                    <th>Range</th>
                                    <td>Type</td>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                echo'<tr>
                                    <th>Parcel</th>
                                    <th></th>
                                    <th></th>
                                </tr>';
                                $status="active";
                                $d_id=$_SESSION['d_id'];
                                $query=$db->prepare("select * from premise_dtl where deviceid='$d_id' and status='$status'");
                                $query->execute();
                                while($data=$query->fetch())
                                {
                                    echo'<tr id="'.$data['premise_id'].'" class="edit_tr">
                                        <td><input type="text" id="premise'.$data['premise_id'].'" class="test-input cat" value="'.$data['premise_name'].'" maxlength="30"></td>
                                        <td><input type="number"  id="no'.$data['premise_id'].'" class="premise" value="'.$data['no_of_table'].'" ></td>
                                        <td><input type="number"  id="table_range'.$data['premise_id'].'" class="table_range" value="'.$data['table_range'].'" ></td>
                                        <td><select class="form-control" id="premise_type'.$data['premise_id'].'">
                                                <option>'.$data['premise_type'].'</option>
                                                <option>Table</option>
                                                <option>Room</option>
                                            </select>
                                        </td>
                                        <th><button type="button" class="btn bg-red btn-xs removebutton" title="Remove this row"><i class="material-icons">delete</i></button></th>
                                    </tr>';
                                }
                            ?>
                                <tr id="insert">
                                    <td><input type="text"  class="premise_name test-input cat" name="" maxlength="30"></td>
                                    <td><input type="number" class="table_no premise" name="" ></td>
                                    <td><input type="number" class="table_range premise" name="" ></td>
                                    <td><select class="form-control premise_type premise">
                                            <option>Table</option>
                                            <option>Room</option>
                                        </select>
                                    </td>
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
                $("tbody > tr:last").clone().appendTo("table").find("input").val("");
                event.preventDefault();      
            }
            else
            {
                showNotification("alert-danger","Maximum six premises are allowed", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
            }
        });

    	$(window).keydown(function()
    	{
        	if (event.keyCode == 13) 
        	{
            	var rows= $('.table tbody tr').length;
            	if(rows<6)
            	{
                	$("tbody > tr:last").clone().appendTo("table").find("input").val("");
                	event.preventDefault();      
            	}
            	else
            	{
                	showNotification("alert-danger","Maximum six premises are allowed", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
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
                	swal({
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
                var table_name="premise_dtl";
                var column_name="premise_id";
                var deleteString = 'column_name='+ column_name+'&table_name='+ table_name+'&delete_id='+ trid+'&login_id='+<?php echo $_SESSION['login_id']; ?>;
                swal({
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
                                var datastr = 'd_id='+<?php echo $_SESSION['d_id']; ?>+'&id='+<?php echo $_SESSION['login_id']; ?>+'&notification=Premise deleted successfully &priority=3 &device_name='+<?php echo '"'.$_SESSION['device_name'].'"'; ?>;
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
  				var premise_name = [];
                var table_no = [];
                var table_range = [];
                var premise_type = [];
  
                $('.premise_name').each(function(){
                    premise_name.push($(this).val());
                });
                $('.table_no').each(function(){
                    table_no.push($(this).val());
                });

                $('.table_range').each(function(){
                    table_range.push($(this).val());
                });

                $('.premise_type').each(function(){
                    premise_type.push($(this).val());
                });

                $.ajax({
                    url:"../sql/insert_premise.php",
                    method:"POST",
                    data:{premise_name:premise_name, table_no:table_no, table_range:table_range,premise_type:premise_type, id:<?php echo $_SESSION['login_id']; ?>, d_id:<?php echo $_SESSION['d_id']; ?>},
                    success:function(data){
                         $('.page-loader-wrapper').hide();
                        values=data.split('_');
                        ch=values[0];
                        count=values[1];
                        switch(ch)
                        {
                            case "1":
                                showNotification("alert-info", count+" records saved successfully", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                                var table_name="premise_dtl";
                                $.ajax({
                                    url:"../sql/row_count.php",
                                    method:"POST",
                                    data:{table_name:table_name, d_id:<?php echo $_SESSION['d_id']; ?>},
                                    success:function(data){
                                        if(data>0)
                                        {
                                            window.location.replace('../cumulative/kitchen');                                                           
                                        }
                                        else
                                        {
                                            $('#save').prop('disabled', false);
                                            showNotification("alert-danger", "Fill details here", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                                        }
                                    }
                                });
                            break;
                            case "2":
                                showNotification("alert-danger","Maximum six premises are allowed", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                                setTimeout("window.location.replace('../cumulative/kitchen');",2000);
                            break;                    
                        }
                    }
                });
			});
		});

        $('.premise').on('keyup', function() {
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


 		$(document).ready(function() {
            $('.premise').keypress(function (event) {
                return isNumber(event, this)
            });
        });
    
        function isNumber(evt, element) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if ((charCode < 48 || charCode > 57))
                return false;

                return true;
        }

        $(document).ready(function() {
            $("input[type=number]").on("focus", function() {
                $(this).on("keydown", function(event) {
                    if (event.keyCode === 38 || event.keyCode === 40) {
                        event.preventDefault();
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
            if ((event.which != 32) &&(((event.which < 65 || event.which > 90) && (event.which < 48 || event.which > 57) && (event.which < 97 || event.which > 122)) &&(event.which != 0 && event.which != 8))) 
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
    
</body>

</html>