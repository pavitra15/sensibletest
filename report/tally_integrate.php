<?php
	session_start();
	include('../validate.php');
	include('../connect.php');
	$status='active';
	$unitQuery=$db->prepare("select * from unit_mst where status='$status'");
	$unitQuery->execute();
	$unitXML="";
	if($unitData=$unitQuery->fetch())
	{
		do
		{
			$unitXML.='<TALLYMESSAGE xmlns:UDF="TallyUDF"><UNIT NAME="' . $unitData['abbrevation'].'" ACTION="CREATE"><NAME>' . $unitData['abbrevation'].'</NAME><ISSIMPLEUNIT>Yes</ISSIMPLEUNIT><FORPAYROLL>No</FORPAYROLL></UNIT></TALLYMESSAGE>';
		}
		while ($unitData=$unitQuery->fetch());
	}
?>
<!DOCTYPE html>
<html>
<head>
     <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Report</title>
    <link rel="icon" href="../favicon.png" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <link href="../plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
    
    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <link href="../plugins/morrisjs/morris.css" rel="stylesheet" />

    <link href="../css/style.css" rel="stylesheet">
    
    <link href="../css/aviator.css" rel="stylesheet">

    <link href="../css/themes/all-themes.css" rel="stylesheet" />

    <link href="../plugins/waitme/waitMe.css" rel="stylesheet" />
    <link href="../plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <link href="../plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
</head>
<body class="theme-teal">
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
                <h2>Tally Integration</h2>
            </div>
            <div class="row clearfix">
                <div class="card">
                    <div class="body table-responsive">
                        <div class="row clearfix">                
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            	<section>
                                    <p>
                                       First time integrate our POS device to Tally. please export ledgers and stock and import into tally
                                    </p>
                                </section>
                                <div class="col-sm-4">
                                    <div class="button-demo">
                                        <input type="button" name="" class="btn btn-info waves-effect m-r-20"  value="Export Ledger" id="exportLedger">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="button-demo">
                                        <input type="button" name="" class="btn btn-info waves-effect m-r-20"  value="Export Stock" id="exportStock">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            	<section>
                                    <p>
                                       Already export ledger and stock only export vouchers
                                    </p>
                                </section>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="start_date" class="datepicker form-control" placeholder="Please choose a date..." required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text"  id="end_date" class="datepicker form-control" placeholder="Please choose a date..." required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="button-demo">
                                        <input type="submit" id="exportVoucher" class="btn btn-info waves-effect m-r-20" data-toggle="modal" >
                                    </div>
                                </div>
                            </div>
                       
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script type="text/javascript">
	masterStart = "<ENVELOPE>" +
			"<HEADER>" +
			"<TALLYREQUEST>Import Data</TALLYREQUEST>" +
			"</HEADER>" +
			"<BODY>" +
			"<IMPORTDATA>" +
			"<REQUESTDESC>" +
			"<REPORTNAME>All Masters</REPORTNAME>" +
			"</REQUESTDESC>" +
			"<REQUESTDATA>";
	
	group = '<TALLYMESSAGE xmlns:UDF="TallyUDF">' +
			'<GROUP NAME="GST Sales" ACTION="Create">' +
			'<NAME.LIST>' +
			'<NAME>GST Sales</NAME>' +
			'</NAME.LIST>' +
			'<PARENT>Sales Accounts</PARENT>' +
			'<ISSUBLEDGER>No</ISSUBLEDGER>' +
			'<ISBILLWISEON>No</ISBILLWISEON>' +
			'<ISCOSTCENTRESON>No</ISCOSTCENTRESON>' +
			'</GROUP>' +
			'</TALLYMESSAGE>' +
			'<TALLYMESSAGE xmlns:UDF="TallyUDF">' +
			'<GROUP NAME="GST @ 0" ACTION="Create">' +
			'<NAME.LIST>' +
			'<NAME>GST @ 0</NAME>' +
			'</NAME.LIST>' +
			'<PARENT>GST Sales</PARENT>' +
			'<ISSUBLEDGER>No</ISSUBLEDGER>' +
			'<ISBILLWISEON>No</ISBILLWISEON>' +
			'<ISCOSTCENTRESON>No</ISCOSTCENTRESON>' +
			'</GROUP>' +
			'</TALLYMESSAGE>' +
			'<TALLYMESSAGE xmlns:UDF="TallyUDF">' +
			'<GROUP NAME="GST @ 5" ACTION="Create">' +
			'<NAME.LIST>' +
			'<NAME>GST @ 5</NAME>' +
			'</NAME.LIST>' +
			'<PARENT>GST Sales</PARENT>' +
			'<ISSUBLEDGER>No</ISSUBLEDGER>' +
			'<ISBILLWISEON>No</ISBILLWISEON>' +
			'<ISCOSTCENTRESON>No</ISCOSTCENTRESON>' +
			'</GROUP>' +
			'</TALLYMESSAGE>' +
			'<TALLYMESSAGE xmlns:UDF="TallyUDF">' +
			'<GROUP NAME="GST @ 12" ACTION="Create">' +
			'<NAME.LIST>' +
			'<NAME>GST @ 12</NAME>' +
			'</NAME.LIST>' +
			'<PARENT>GST Sales</PARENT>' +
			'<ISSUBLEDGER>No</ISSUBLEDGER>' +
			'<ISBILLWISEON>No</ISBILLWISEON>' +
			'<ISCOSTCENTRESON>No</ISCOSTCENTRESON>' +
			'</GROUP>' +
			'</TALLYMESSAGE>' +
			'<TALLYMESSAGE xmlns:UDF="TallyUDF">' +
			'<GROUP NAME="GST @ 18" ACTION="Create">' +
			'<NAME.LIST>' +
			'<NAME>GST @ 18</NAME>' +
			'</NAME.LIST>' +
			'<PARENT>GST Sales</PARENT>' +
			'<ISSUBLEDGER>No</ISSUBLEDGER>' +
			'<ISBILLWISEON>No</ISBILLWISEON>' +
			'<ISCOSTCENTRESON>No</ISCOSTCENTRESON>' +
			'</GROUP>' +
			'</TALLYMESSAGE>' +
			'<TALLYMESSAGE xmlns:UDF="TallyUDF">' +
			'<GROUP NAME="GST @ 28" ACTION="Create">' +
			'<NAME.LIST>' +
			'<NAME>GST @ 28</NAME>' +
			'</NAME.LIST>' +
			'<PARENT>GST Sales</PARENT>' +
			'<ISSUBLEDGER>No</ISSUBLEDGER>' +
			'<ISBILLWISEON>No</ISBILLWISEON>' +
			'<ISCOSTCENTRESON>No</ISCOSTCENTRESON>' +
			'</GROUP>' +
			'</TALLYMESSAGE>';
			
		ledger='<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
				'<LEDGER NAME="Cash Sale" ACTION="Create">'+
					'<NAME.LIST>'+
						'<NAME>Cash Sale</NAME>'+
					'</NAME.LIST>'+
					'<PARENT>Sales Accounts</PARENT>'+
					'<ISBILLWISEON>No</ISBILLWISEON>'+
					'<AFFECTSSTOCK>No</AFFECTSSTOCK>'+
					'<OPENINGBALANCE>0</OPENINGBALANCE>'+
					'<TAXCLASSIFICATIONNAME/>'+
					'<TAXTYPE>Others</TAXTYPE>'+
					'<GSTTYPE/>'+
					'<ROUNDINGMETHOD>Normal Rounding</ROUNDINGMETHOD>'+
					'<RATEOFTAXCALCULATION/>'+
					'<UDF:VATDEALERNATURE.LIST DESC="`VATDealerNature`" ISLIST="YES" TYPE="String" INDEX="10031">'+
       					'<UDF:VATDEALERNATURE DESC="`VATDealerNature`">Invoice Rounding</UDF:VATDEALERNATURE>'+
					  '</UDF:VATDEALERNATURE.LIST>'+
					  '<AFFECTSSTOCK>Yes</AFFECTSSTOCK>'+
				'</LEDGER>'+
			'</TALLYMESSAGE>'+
			'<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
				'<LEDGER NAME="Digital Sale" ACTION="Create">'+
					'<NAME.LIST>'+
						'<NAME>Digital Sale</NAME>'+
					'</NAME.LIST>'+
					'<PARENT>Sales Accounts</PARENT>'+
					'<ISBILLWISEON>No</ISBILLWISEON>'+
					'<AFFECTSSTOCK>No</AFFECTSSTOCK>'+
					'<OPENINGBALANCE>0</OPENINGBALANCE>'+
					'<TAXCLASSIFICATIONNAME/>'+
					'<TAXTYPE>Others</TAXTYPE>'+
					'<GSTTYPE/>'+
					'<AFFECTSSTOCK>Yes</AFFECTSSTOCK>'+
					'<ROUNDINGMETHOD>Normal Rounding</ROUNDINGMETHOD>'+
					'<RATEOFTAXCALCULATION/>'+
				'</LEDGER>'+
			'</TALLYMESSAGE>'+
			'<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
				'<LEDGER NAME="Credit Sale" ACTION="Create">'+
					'<NAME.LIST>'+
						'<NAME>Credit Sale</NAME>'+
					'</NAME.LIST>'+
					'<PARENT>Sundry Debtors</PARENT>'+
					'<ISBILLWISEON>No</ISBILLWISEON>'+
					'<AFFECTSSTOCK>No</AFFECTSSTOCK>'+
					'<OPENINGBALANCE>0</OPENINGBALANCE>'+
					'<TAXCLASSIFICATIONNAME/>'+
					'<TAXTYPE>Others</TAXTYPE>'+
					'<GSTTYPE/>'+
					'<ROUNDINGMETHOD>Normal Rounding</ROUNDINGMETHOD>'+
					'<RATEOFTAXCALCULATION/>'+
					'<AFFECTSSTOCK>Yes</AFFECTSSTOCK>'+
				'</LEDGER>'+
			'</TALLYMESSAGE>'+
			'<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
				'<LEDGER NAME="Cash" ACTION="Create">'+
					'<NAME.LIST>'+
						'<NAME>Cash</NAME>'+
					'</NAME.LIST>'+
					'<PARENT>Cash-in-Hand</PARENT>'+
					'<ISBILLWISEON>No</ISBILLWISEON>'+
					'<AFFECTSSTOCK>No</AFFECTSSTOCK>'+
					'<OPENINGBALANCE>0</OPENINGBALANCE>'+
					'<USEFORVAT>No </USEFORVAT>'+
					'<TAXCLASSIFICATIONNAME/>'+
					'<TAXTYPE/>'+
					'<RATEOFTAXCALCULATION/>'+
				'</LEDGER>'+
			'</TALLYMESSAGE>'+
			'<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
				'<LEDGER NAME="Digital" ACTION="Create">'+
					'<NAME.LIST>'+
						'<NAME>Digital</NAME>'+
					'</NAME.LIST>'+
					'<PARENT>Cash-in-Hand</PARENT>'+
					'<ISBILLWISEON>No</ISBILLWISEON>'+
					'<AFFECTSSTOCK>No</AFFECTSSTOCK>'+
					'<OPENINGBALANCE>0</OPENINGBALANCE>'+
					'<USEFORVAT>No </USEFORVAT>'+
					'<TAXCLASSIFICATIONNAME/>'+
					'<TAXTYPE/>'+
					'<RATEOFTAXCALCULATION/>'+
				'</LEDGER>'+
			'</TALLYMESSAGE>'+
			'<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
				'<LEDGER NAME="Discount" ACTION="Create">'+
					'<NAME.LIST>'+
						'<NAME>Discount</NAME>'+
					'</NAME.LIST>'+
					'<PARENT>Direct Expenses</PARENT>'+
					'<ISBILLWISEON>No</ISBILLWISEON>'+
					'<AFFECTSSTOCK>No</AFFECTSSTOCK>'+
					'<USEFORVAT>No </USEFORVAT>'+
					'<TAXCLASSIFICATIONNAME/>'+
				    '<TAXTYPE>Others</TAXTYPE>'+
				    '<LEDADDLALLOCTYPE/>'+
				    '<GSTTYPE/>'+
					'<RATEOFTAXCALCULATION/>'+
				'</LEDGER>'+
			'</TALLYMESSAGE>'+
			'<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
				'<LEDGER NAME="Parcel" ACTION="Create">'+
					'<NAME.LIST>'+
						'<NAME>Parcel</NAME>'+
					'</NAME.LIST>'+
					'<PARENT>Direct Expenses</PARENT>'+
					'<ISBILLWISEON>No</ISBILLWISEON>'+
					'<AFFECTSSTOCK>No</AFFECTSSTOCK>'+
					'<USEFORVAT>No </USEFORVAT>'+
					'<TAXCLASSIFICATIONNAME/>'+
				    '<TAXTYPE>Others</TAXTYPE>'+
				    '<LEDADDLALLOCTYPE/>'+
				    '<GSTTYPE/>'+
					'<RATEOFTAXCALCULATION/>'+
				'</LEDGER>'+
			'</TALLYMESSAGE>';

			taxLedger='<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
					'<LEDGER NAME="OUTPUT CGST  @ 6%" ACTION="Create">'+
				  		'<NAME.LIST>'+
							'<NAME>OUTPUT CGST  @ 6%</NAME>'+
				  		'</NAME.LIST>'+
				  		'<PARENT>Duties &amp; Taxes</PARENT>'+
				  		'<TAXCLASSIFICATIONNAME/>'+
				  		'<TAXTYPE>GST</TAXTYPE>'+
				  		'<GSTTYPE/>'+
				  		'<APPROPRIATEFOR/>'+
				  		'<GSTDUTYHEAD>Central Tax</GSTDUTYHEAD>'+
				  		'<GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>'+
				  		'<ROUNDINGMETHOD>Downward Rounding</ROUNDINGMETHOD>'+
				  		'<ISBILLWISEON>No</ISBILLWISEON>'+
				  		'<ISCOSTCENTRESON>No</ISCOSTCENTRESON>'+
				  		'<ISINTERESTON>No</ISINTERESTON>'+
				  		'<ALLOWINMOBILE>No</ALLOWINMOBILE>'+
				  		'<ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>'+
				  		'<ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>'+
				  		'<ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>'+
				  		'<ASORIGINAL>Yes</ASORIGINAL>'+
				  		'<USEFORVAT>No</USEFORVAT>'+
				  		'<RATEOFTAXCALCULATION> 6.00</RATEOFTAXCALCULATION>'+
					'</LEDGER>'+
				  '</TALLYMESSAGE>'+
				  '<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
					'<LEDGER NAME="OUTPUT SGST  @ 6%" ACTION="Create">'+
				  		'<NAME.LIST>'+
							'<NAME>OUTPUT SGST  @ 6%</NAME>'+
				  		'</NAME.LIST>'+
				  		'<PARENT>Duties &amp; Taxes</PARENT>'+
				  		'<TAXCLASSIFICATIONNAME/>'+
				  		'<TAXTYPE>GST</TAXTYPE>'+
				  		'<GSTTYPE/>'+
				  		'<APPROPRIATEFOR/>'+
				  		'<GSTDUTYHEAD>State Tax</GSTDUTYHEAD>'+
				  		'<GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>'+
				  		'<ROUNDINGMETHOD>Downward Rounding</ROUNDINGMETHOD>'+
				  		'<ISBILLWISEON>No</ISBILLWISEON>'+
				  		'<ISCOSTCENTRESON>No</ISCOSTCENTRESON>'+
				  		'<ISINTERESTON>No</ISINTERESTON>'+
				  		'<ALLOWINMOBILE>No</ALLOWINMOBILE>'+
				  		'<ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>'+
				  		'<ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>'+
				  		'<ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>'+
				  		'<ASORIGINAL>Yes</ASORIGINAL>'+
				  		'<USEFORVAT>No</USEFORVAT>'+
				  		'<RATEOFTAXCALCULATION> 6.00</RATEOFTAXCALCULATION>'+
					'</LEDGER>'+
				  '</TALLYMESSAGE>'+
				  '<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
					'<LEDGER NAME="OUTPUT CGST  @ 0%" ACTION="Create">'+
				  		'<NAME.LIST>'+
							'<NAME>OUTPUT CGST  @ 0%</NAME>'+
				  		'</NAME.LIST>'+
				  		'<PARENT>Duties &amp; Taxes</PARENT>'+
				  		'<TAXCLASSIFICATIONNAME/>'+
				  		'<TAXTYPE>GST</TAXTYPE>'+
				  		'<GSTTYPE/>'+
				  		'<APPROPRIATEFOR/>'+
				  		'<GSTDUTYHEAD>Central Tax</GSTDUTYHEAD>'+
				  		'<GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>'+
				  		'<ROUNDINGMETHOD>Downward Rounding</ROUNDINGMETHOD>'+
				  		'<ISBILLWISEON>No</ISBILLWISEON>'+
				  		'<ISCOSTCENTRESON>No</ISCOSTCENTRESON>'+
				  		'<ISINTERESTON>No</ISINTERESTON>'+
				  		'<ALLOWINMOBILE>No</ALLOWINMOBILE>'+
				  		'<ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>'+
				  		'<ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>'+
				  		'<ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>'+
				  		'<ASORIGINAL>Yes</ASORIGINAL>'+
				  		'<USEFORVAT>No</USEFORVAT>'+
				  		'<RATEOFTAXCALCULATION> 0.00</RATEOFTAXCALCULATION>'+
					'</LEDGER>'+
				  '</TALLYMESSAGE>'+
				  '<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
					'<LEDGER NAME="OUTPUT SGST  @ 0%" ACTION="Create">'+
				  		'<NAME.LIST>'+
							'<NAME>OUTPUT SGST  @ 0%</NAME>'+
				  		'</NAME.LIST>'+
				  		'<PARENT>Duties &amp; Taxes</PARENT>'+
				  		'<TAXCLASSIFICATIONNAME/>'+
				  		'<TAXTYPE>GST</TAXTYPE>'+
				  		'<GSTTYPE/>'+
				  		'<APPROPRIATEFOR/>'+
				  		'<GSTDUTYHEAD>State Tax</GSTDUTYHEAD>'+
				  		'<GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>'+
				  		'<ROUNDINGMETHOD>Downward Rounding</ROUNDINGMETHOD>'+
				  		'<ISBILLWISEON>No</ISBILLWISEON>'+
				  		'<ISCOSTCENTRESON>No</ISCOSTCENTRESON>'+
				  		'<ISINTERESTON>No</ISINTERESTON>'+
				  		'<ALLOWINMOBILE>No</ALLOWINMOBILE>'+
				  		'<ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>'+
				  		'<ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>'+
				  		'<ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>'+
				  		'<ASORIGINAL>Yes</ASORIGINAL>'+
				  		'<USEFORVAT>No</USEFORVAT>'+
				  		'<RATEOFTAXCALCULATION> 0.00</RATEOFTAXCALCULATION>'+
					'</LEDGER>'+
				  '</TALLYMESSAGE>'+
				  '<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
					'<LEDGER NAME="OUTPUT CGST  @ 2.50%" ACTION="Create">'+
				  		'<NAME.LIST>'+
							'<NAME>OUTPUT CGST  @ 2.50%</NAME>'+
				  		'</NAME.LIST>'+
				  		'<PARENT>Duties &amp; Taxes</PARENT>'+
				  		'<TAXCLASSIFICATIONNAME/>'+
				  		'<TAXTYPE>GST</TAXTYPE>'+
				  		'<GSTTYPE/>'+
				  		'<APPROPRIATEFOR/>'+
				  		'<GSTDUTYHEAD>Central Tax</GSTDUTYHEAD>'+
				  		'<GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>'+
				  		'<ROUNDINGMETHOD>Downward Rounding</ROUNDINGMETHOD>'+
				  		'<ISBILLWISEON>No</ISBILLWISEON>'+
				  		'<ISCOSTCENTRESON>No</ISCOSTCENTRESON>'+
				  		'<ISINTERESTON>No</ISINTERESTON>'+
				  		'<ALLOWINMOBILE>No</ALLOWINMOBILE>'+
				  		'<ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>'+
				  		'<ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>'+
				  		'<ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>'+
				  		'<ASORIGINAL>Yes</ASORIGINAL>'+
				  		'<USEFORVAT>No</USEFORVAT>'+
				  		'<RATEOFTAXCALCULATION> 2.50</RATEOFTAXCALCULATION>'+
					'</LEDGER>'+
				  '</TALLYMESSAGE>'+
				  '<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
					'<LEDGER NAME="OUTPUT SGST  @ 2.50%" ACTION="Create">'+
				  		'<NAME.LIST>'+
							'<NAME>OUTPUT SGST  @ 2.50%</NAME>'+
				  		'</NAME.LIST>'+
				  		'<PARENT>Duties &amp; Taxes</PARENT>'+
				  		'<TAXCLASSIFICATIONNAME/>'+
				  		'<TAXTYPE>GST</TAXTYPE>'+
				  		'<GSTTYPE/>'+
				  		'<APPROPRIATEFOR/>'+
				  		'<GSTDUTYHEAD>State Tax</GSTDUTYHEAD>'+
				  		'<GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>'+
				  		'<ROUNDINGMETHOD>Downward Rounding</ROUNDINGMETHOD>'+
				  		'<ISBILLWISEON>No</ISBILLWISEON>'+
				  		'<ISCOSTCENTRESON>No</ISCOSTCENTRESON>'+
				  		'<ISINTERESTON>No</ISINTERESTON>'+
				  		'<ALLOWINMOBILE>No</ALLOWINMOBILE>'+
				  		'<ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>'+
				  		'<ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>'+
				  		'<ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>'+
				  		'<ASORIGINAL>Yes</ASORIGINAL>'+
				  		'<USEFORVAT>No</USEFORVAT>'+
				  		'<RATEOFTAXCALCULATION> 2.0</RATEOFTAXCALCULATION>'+
					'</LEDGER>'+
				  '</TALLYMESSAGE>'+
				  '<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
					'<LEDGER NAME="OUTPUT CGST  @ 9%" ACTION="Create">'+
				  		'<NAME.LIST>'+
							'<NAME>OUTPUT CGST  @ 9%</NAME>'+
				  		'</NAME.LIST>'+
				  		'<PARENT>Duties &amp; Taxes</PARENT>'+
				  		'<TAXCLASSIFICATIONNAME/>'+
				  		'<TAXTYPE>GST</TAXTYPE>'+
				  		'<GSTTYPE/>'+
				  		'<APPROPRIATEFOR/>'+
				  		'<GSTDUTYHEAD>Central Tax</GSTDUTYHEAD>'+
				  		'<GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>'+
				  		'<ROUNDINGMETHOD>Downward Rounding</ROUNDINGMETHOD>'+
				  		'<ISBILLWISEON>No</ISBILLWISEON>'+
				  		'<ISCOSTCENTRESON>No</ISCOSTCENTRESON>'+
				  		'<ISINTERESTON>No</ISINTERESTON>'+
				  		'<ALLOWINMOBILE>No</ALLOWINMOBILE>'+
				  		'<ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>'+
				  		'<ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>'+
				  		'<ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>'+
				  		'<ASORIGINAL>Yes</ASORIGINAL>'+
				  		'<USEFORVAT>No</USEFORVAT>'+
				  		'<RATEOFTAXCALCULATION> 9.00</RATEOFTAXCALCULATION>'+
					'</LEDGER>'+
				  '</TALLYMESSAGE>'+
				  '<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
					'<LEDGER NAME="OUTPUT SGST  @ 9%" ACTION="Create">'+
				  		'<NAME.LIST>'+
							'<NAME>OUTPUT SGST  @ 9%</NAME>'+
				  		'</NAME.LIST>'+
				  		'<PARENT>Duties &amp; Taxes</PARENT>'+
				  		'<TAXCLASSIFICATIONNAME/>'+
				  		'<TAXTYPE>GST</TAXTYPE>'+
				  		'<GSTTYPE/>'+
				  		'<APPROPRIATEFOR/>'+
				  		'<GSTDUTYHEAD>State Tax</GSTDUTYHEAD>'+
				  		'<GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>'+
				  		'<ROUNDINGMETHOD>Downward Rounding</ROUNDINGMETHOD>'+
				  		'<ISBILLWISEON>No</ISBILLWISEON>'+
				  		'<ISCOSTCENTRESON>No</ISCOSTCENTRESON>'+
				  		'<ISINTERESTON>No</ISINTERESTON>'+
				  		'<ALLOWINMOBILE>No</ALLOWINMOBILE>'+
				  		'<ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>'+
				  		'<ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>'+
				  		'<ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>'+
				  		'<ASORIGINAL>Yes</ASORIGINAL>'+
				  		'<USEFORVAT>No</USEFORVAT>'+
				  		'<RATEOFTAXCALCULATION> 9.00</RATEOFTAXCALCULATION>'+
					'</LEDGER>'+
				  '</TALLYMESSAGE>'+
				  '<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
					'<LEDGER NAME="OUTPUT CGST  @ 14%" ACTION="Create">'+
				  		'<NAME.LIST>'+
							'<NAME>OUTPUT CGST  @ 14%</NAME>'+
				  		'</NAME.LIST>'+
				  		'<PARENT>Duties &amp; Taxes</PARENT>'+
				  		'<TAXCLASSIFICATIONNAME/>'+
				  		'<TAXTYPE>GST</TAXTYPE>'+
				  		'<GSTTYPE/>'+
				  		'<APPROPRIATEFOR/>'+
				  		'<GSTDUTYHEAD>Central Tax</GSTDUTYHEAD>'+
				  		'<GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>'+
				  		'<ROUNDINGMETHOD>Downward Rounding</ROUNDINGMETHOD>'+
				  		'<ISBILLWISEON>No</ISBILLWISEON>'+
				  		'<ISCOSTCENTRESON>No</ISCOSTCENTRESON>'+
				  		'<ISINTERESTON>No</ISINTERESTON>'+
				  		'<ALLOWINMOBILE>No</ALLOWINMOBILE>'+
				  		'<ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>'+
				  		'<ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>'+
				  		'<ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>'+
				  		'<ASORIGINAL>Yes</ASORIGINAL>'+
				  		'<USEFORVAT>No</USEFORVAT>'+
				  		'<RATEOFTAXCALCULATION> 14.00</RATEOFTAXCALCULATION>'+
					'</LEDGER>'+
				  '</TALLYMESSAGE>'+
				  '<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
					'<LEDGER NAME="OUTPUT SGST  @ 14%" ACTION="Create">'+
				  		'<NAME.LIST>'+
							'<NAME>OUTPUT SGST  @ 14%</NAME>'+
				  		'</NAME.LIST>'+
				  		'<PARENT>Duties &amp; Taxes</PARENT>'+
				  		'<TAXCLASSIFICATIONNAME/>'+
				  		'<TAXTYPE>GST</TAXTYPE>'+
				  		'<GSTTYPE/>'+
				  		'<APPROPRIATEFOR/>'+
				  		'<GSTDUTYHEAD>State Tax</GSTDUTYHEAD>'+
				  		'<GSTTYPEOFSUPPLY>Goods</GSTTYPEOFSUPPLY>'+
				  		'<ROUNDINGMETHOD>Downward Rounding</ROUNDINGMETHOD>'+
				  		'<ISBILLWISEON>No</ISBILLWISEON>'+
				  		'<ISCOSTCENTRESON>No</ISCOSTCENTRESON>'+
				  		'<ISINTERESTON>No</ISINTERESTON>'+
				  		'<ALLOWINMOBILE>No</ALLOWINMOBILE>'+
				  		'<ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>'+
				  		'<ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>'+
				  		'<ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>'+
				  		'<ASORIGINAL>Yes</ASORIGINAL>'+
				  		'<USEFORVAT>No</USEFORVAT>'+
				  		'<RATEOFTAXCALCULATION> 14.00</RATEOFTAXCALCULATION>'+
					'</LEDGER>'+
	  			'</TALLYMESSAGE>';

		end = "</REQUESTDATA></IMPORTDATA></BODY></ENVELOPE>";

		stock = '<TALLYMESSAGE xmlns:UDF="TallyUDF">'+
					'<STOCKITEM NAME="BHEL" ACTION="Create">'+
						'<NAME.LIST>'+
							'<NAME>BHEL</NAME>'+
						'</NAME.LIST>'+
						'<ADDITIONALNAME.LIST>'+
							'<ADDITIONALNAME>0010817824</ADDITIONALNAME>'+
						'</ADDITIONALNAME.LIST>'+
						'<BASEUNITS>Kilogram</BASEUNITS>'+
						'<OPENINGBALANCE>0.000 Kilogram</OPENINGBALANCE>'+
						'<OPENINGVALUE>0.000</OPENINGVALUE>'+
						'<OPENINGRATE>100.000/Kilogram</OPENINGRATE>'+
						'<BATCHALLOCATIONS.LIST>'+
							'<NAME>Primary Batch</NAME>'+
							'<BATCHNAME>Primary Batch</BATCHNAME>'+
							'<GODOWNNAME>Main Location</GODOWNNAME>'+
							'<MFDON>20181001</MFDON>'+
							'<OPENINGBALANCE>0.000 Kilogram</OPENINGBALANCE>'+
							'<OPENINGVALUE>0.000</OPENINGVALUE>'+
							'<OPENINGRATE>0.000/Kilogram</OPENINGRATE>'+
						'</BATCHALLOCATIONS.LIST>'+
						'<STANDARDPRICELIST.LIST>'+
							'<RATE>100.000</RATE>'+
							'<DATE>20181001</DATE>'+
						'</STANDARDPRICELIST.LIST>'+
						'<STANDARDCOSTLIST.LIST>'+
							'<RATE>80.000</RATE>'+
							'<DATE>20181001</DATE>'+
						'</STANDARDCOSTLIST.LIST>'+
						'<REORDERBASE>0.000</REORDERBASE>'+
						'<MINIMUMORDERBASE>0.000</MINIMUMORDERBASE>'+
					'</STOCKITEM>'+
				'</TALLYMESSAGE>';

		start = "<ENVELOPE>" +
			"<HEADER>" +
			"<TALLYREQUEST>Import Data</TALLYREQUEST>" +
			"</HEADER>" +
			"<BODY>" +
			"<IMPORTDATA>" +
			"<REQUESTDESC>" +
			"<REPORTNAME>Vouchers</REPORTNAME>" +
			"</REQUESTDESC>" +
			"<REQUESTDATA>";
			   		var unitXML='<?php echo $unitXML; ?>';

	$(function(){
            $('.datepicker').bootstrapMaterialDatePicker({
                format: 'dddd DD MMMM YYYY',
                clearButton: true,
                time: false,
                maxDate: new Date()
            });

        })

	$('#exportStock').click(function(){
		var stock="";
		recursiveAjaxCall(1);
        function recursiveAjaxCall(currentNum)
        {
        	$('.page-loader-wrapper').show();
            $.ajax({
            	type: 'POST',
                url: 'stockLedger.php',
                data: { "d_id":<?php echo $_SESSION['d_id']; ?>,"page_no" : currentNum},
                success: function(data)
                {
                	console.log(data)
                	var sk = JSON.parse(data);
                    var next=sk.page;
                    var total=sk.total;
                    stock+=sk.stock;
                    if(next<=total)
                    {
                    	recursiveAjaxCall(next);
                    }
                    else
                    {
                    	$('.page-loader-wrapper').hide();
                        go();
                    }
               	},
                async:   true
            });
        }

		function go()
		{
			var blob=masterStart+stock+end;
			let fileName = 'stock.xml';
  			let a = document.createElement('a');
			a.download = fileName;
			a.href = URL.createObjectURL(new File([blob], fileName, {type: 'text/xml'}));
			a.click();


		}
	});


	$('#exportVoucher').click(function(){
		var voucher="";
		recursiveAjaxCall(1);
        function recursiveAjaxCall(currentNum)
        {
        	var start_date=$('#start_date').val();
            var end_date=$('#end_date').val();
            if(start_date.length>0 && end_date.length>0)
            {
	            $.ajax({
	            	type: 'POST',
	                url: 'tallyVoucher.php',
	                data: { "d_id":<?php echo $_SESSION['d_id']; ?>,"page_no" : currentNum,"start_date":start_date,"end_date":end_date},
	                success: function(data)
	                {
	                	var sk = JSON.parse(data);
	                    var next=sk.page;
	                    var total=sk.total;
	                    voucher+=sk.voucher;
	                    if(next<=total)
	                    {
	                    	recursiveAjaxCall(next);
	                    }
	                    else
	                    {
	                    	$('.page-loader-wrapper').hide();
	                        go();
	                    }
	               	},
	                async:   true
	            });
	        }
        }

		function go()
		{
			var blob=masterStart+voucher+end;
			
			let fileName = 'voucher.xml';
  			let a = document.createElement('a');
			a.download = fileName;
			a.href = URL.createObjectURL(new File([blob], fileName, {type: 'text/xml'}));
			a.click();
			
		}
	});

	$('#exportLedger').click(function(){
			var blob=masterStart+group+unitXML+ledger+taxLedger+end;
			let fileName = 'ledger.xml';
  			let a = document.createElement('a');
			a.download = fileName;
			a.href = URL.createObjectURL(new File([blob], fileName, {type: 'text/xml'}));
			a.click();
	});

</script>
 <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>
    <script src="../plugins/momentjs/moment.js"></script>
    <script src="../js/change_device.js"></script>
    <script src="../js/avatar.js"></script>
    <script src="../js/admin.js"></script>
    <script src="../js/demo.js"></script>
    <script src="../plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <script src="../plugins/node-waves/waves.js"></script>
    <script src="../plugins/autosize/autosize.js"></script>
    <script src="../plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>
   
</html>