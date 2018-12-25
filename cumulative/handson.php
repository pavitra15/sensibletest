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
	<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

	<link rel="stylesheet" media="screen" href="https://docs.handsontable.com/bower_components/handsontable/dist/handsontable.css">
	<link rel="stylesheet" media="screen" href="https://docs.handsontable.com/bower_components/handsontable/dist/pikaday/pikaday.css">
	<script src="https://docs.handsontable.com/bower_components/handsontable/dist/pikaday/pikaday.js"></script>
	<script src="https://docs.handsontable.com/bower_components/handsontable/dist/moment/moment.js"></script>
	<script src="https://docs.handsontable.com/bower_components/handsontable/dist/numbro/numbro.js"></script>
	<script src="https://docs.handsontable.com/bower_components/handsontable/dist/numbro/languages.js"></script>
	<script src="https://docs.handsontable.com/bower_components/handsontable/dist/handsontable.js"></script>
</head>

<body class="theme-teal">    
    <!-- <div class="page-loader-wrapper">
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
    </div> -->
    
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
    	<?php
			$d_id=$_SESSION['d_id'];
			$status='active';
			$yes_source='["Yes","No"]';
			$category_query=$db->prepare("select category_name from category_dtl where deviceid='$d_id' and status='$status'");
		   	$category_query->execute();
		    $category_data=$category_query->fetchAll(PDO::FETCH_COLUMN, 0);
		    $category=json_encode($category_data);

	        $tax_query=$db->prepare("select tax_name from tax_mst where status='$status'");
	        $tax_query->execute();
	        $tax_data=$tax_query->fetchAll(PDO::FETCH_COLUMN, 0);
	        $tax=json_encode($tax_data);

	        $unit_query=$db->prepare("select unit_name from unit_mst where status='$status'");
	        $unit_query->execute();
	        $unit_data=$unit_query->fetchAll(PDO::FETCH_COLUMN, 0);
	        $unit=json_encode($unit_data);

	        if($_SESSION['device_type']=="Table")
	        {
	            $kitchen_query=$db->prepare("select kitchen_name from kitchen_dtl where deviceid='$d_id' and status='$status'");
	            $kitchen_query->execute();
	            $kitchen_data=$kitchen_query->fetchAll(PDO::FETCH_COLUMN, 0);
	            $kitchen=json_encode($kitchen_data);
	        }

	        $json='[{"data":"english_name","title":"English Name","type":"text"},{"data":"regional_name","title":"Regional Name","type":"text"},{"data":"barcode","title":"barcode","type":"text"},';
	        // $headers=array('English Name','Regional Name','Barcode');
	        if($_SESSION['device_type']=="Weighing")
	        {
	        	$json.='{"data":"Weightable","title":"Weightable","type":"dropdown","source":'.$yes_source.'},{"data":"category_name","title":"Category","type":"dropdown","source":'.$category.'},{"data":"Discount","title":"Discount","type":"numeric"},{"data":"Unit","title":"Unit","type":"dropdown","source":'.$unit.'},{"data":"Stockable","title":"Stockable","type":"dropdown","source":'.$yes_source.'},{"data":"Reorder Level","title":"Reorder Level","type":"numeric"},{"data":"Stock","title":"Stock","type":"numeric"},{"data":"Tax Type","title":"Tax Type","type":"dropdown","source":'.$tax.'}';
	            $sk=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
	            $sk->execute();
	            if($data=$sk->fetch())
	            {
	                do
	                {
	                    $name=$data['customer_name'].' Price';
	                    $json.=',{"data":"'.$name.'","title":"'.$name.'","type":"numeric"}';
	                }
	                while($data=$sk->fetch());
	            } 
	        }
	        elseif ($_SESSION['device_type']=="Table") 
	        {
	            $json.='{"data":"category_name","title":"Category","type":"dropdown","source":'.$category.'},{"data":"kitchen_name","title":"Kitchen","type":"dropdown","source":'.$kitchen.'},{"data":"discount","title":"Discount","type":"numeric"},{"data":"unit_name","title":"Unit","type":"dropdown","source":'.$unit.'},{"data":"stockable","title":"Stockable","type":"dropdown","source":'.$yes_source.'},{"data":"reorder_level","title":"Reorder Level","type":"numeric"},{"data":"current_stock","title":"Stock","type":"numeric"},{"data":"tax_name","title":"Tax Type","type":"dropdown","source":'.$tax.'},{"data":"parcel price","title":"Parcel Price","type":"numeric"}';
	            $sk=$db->prepare("select * from premise_dtl where deviceid='$d_id' and status='$status'");
	            $sk->execute();
	            if($data=$sk->fetch())
	            {
	                do
	                {
	                    $name=$data['premise_name'].' price';
	                    $json.=',{"data":"'.$name.'","title":"'.$name.'","type":"numeric"}';
	                }
	                while($data=$sk->fetch());
	            }
	        }
	        else
	        {
	            $json.='{"data":"Category","title":"Category","type":"dropdown","source":'.$category.'},{"data":"Discount","title":"Discount","type":"numeric"},{"data":"Unit","title":"Unit","type":"dropdown","source":'.$unit.'},{"data":"Stockable","title":"Stockable","type":"dropdown","source":'.$yes_source.'},{"data":"Reorder Level","title":"Reorder Level","type":"numeric"},{"data":"Stock","title":"Stock","type":"numeric"},{"data":"Tax Type","title":"Tax Type","type":"dropdown","source":'.$tax.'}';
	            $sk=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
	            $sk->execute();
	            if($data=$sk->fetch())
	            {
	                do
	                {
	                    $name=$data['customer_name'].' Price';
	                    $json.=',{"data":"'.$name.'","title":"'.$name.'","type":"numeric"}';
	                }
	                while($data=$sk->fetch());
	            } 
	        }
	        $json.=']';
		?>
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    PRODUCT SETTING
                </h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <button name="load" class="btn btn-primary waves-effect" id="load">Load</button>
                                </div>
                                <div class="col-xs-6 ">
                                </div>  
                            </div>
                        </div>  
                        <div class="body table-responsive">	  		
							<div id="example1" class="handsontable htRowHeaders htColumnHeaders"></div>
							<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
                            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
							<script type="text/javascript">
                                
                                $(document).ready(function(){
                                    $.ajax({
                                        url: 'data.php',
                                        type: 'POST',
                                        success: function (res) {
                                            $ = function(id) {
                                                return document.getElementById(id);
                                            },
                                            container = $('example1');
                                            exampleConsole = $('example1console'),
                                            hot = new Handsontable(container, {
                                                // data: res,
                                                rowHeaders: true,
                                                colHeaders: true,
                                                stretchH: 'all',
                                                minSpareCols: 0,
                                                minSpareRows: 1,
                                                height: 700,
                                                columns: <?php echo $json; ?>,
                                                contextMenu: false,
                                                afterInit: function(){
                                                    console.log("Done");
                                                }
                                            });
                                            data=JSON.parse(res);
                                            hot.loadData(data);
                                        }
                                    });
                                });
</script>
    <script src="../js/change_device.js"></script>
    <script src="../js/avatar.js"></script>

    
    <script src="../js/pages/ui/dialogs.js"></script>
    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>
    <script src="../plugins/node-waves/waves.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="../plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- Morris Plugin Js -->
    <script src="../plugins/raphael/raphael.min.js"></script>
    <script src="../plugins/morrisjs/morris.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <!-- <script src="../js/admin.js"></script> -->
    <script src="../plugins/jquery/jquery.min.js"></script>

   
   </body>

</html>