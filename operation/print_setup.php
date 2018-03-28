<?php
    session_start();
    include('../validate.php'); 
    $flag=0;         
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome | Sensible Connect - Admin</title>
    <!-- Favicon-->
   <link rel="icon" href="../favicon.png" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Colorpicker Css -->
    <link href="../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" />

    <!-- Dropzone Css -->
    <link href="../plugins/dropzone/dropzone.css" rel="stylesheet">

    <!-- Multi Select Css -->
    <link href="../plugins/multi-select/css/multi-select.css" rel="stylesheet">

    <!-- Bootstrap Spinner Css -->
    <link href="../plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

    <!-- Bootstrap Tagsinput Css -->
    <link href="../plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <link href="../plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- noUISlider Css -->
    <link href="../plugins/nouislider/nouislider.min.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="../css/aviator.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">

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
                <a class="navbar-brand" href="../cumulative/index">SENSIBLE CONNECT</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    
                    <?php
                     include('../connect.php');
                        $id=$_SESSION['login_id'];
                        if(isset($_SESSION['d_id']))
                        {
                            $d_id=$_SESSION['d_id'];
                        }
                        else
                        {
                            $d_id=0;
                        }
                        $device_name="";
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
                ?>s
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

                <h2>PRINT SETUP</h2>
            </div>
            <!-- Input -->
            <div class="row clearfix">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-md-6">
                                <div class="header">
                                    <h2>PRINT SETTING</h2>
                                </div>
                                <div class="body">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="title" name="title" required>
                                            <label class="form-label">Title</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="subtitle" required>
                                            <label class="form-label">Subtitle/Address</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="address" name="address" required>
                                            <label class="form-label">Address</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="number" class="form-control" id="contact" name="contact" required>
                                            <label class="form-label">Contact Number</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="gstn" name="gstn" required>
                                            <label class="form-label">GST Number</label>
                                        </div>
                                    </div>
                                    <div>
                                        <li>
                                            <span>Print TAX INVOICE</span>
                                            <div class="switch">
                                                <label>OFF<input type="checkbox" id="tax_invoice" checked><span class="lever switch-col-cyan"></span>ON</label>
                                            </div>
                                        </li>
                                    </div>
                                    <div class="form-group form-float" id="ip_bill_name">
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="bill_name" name="bill_name" value="TAX INVOICE" required>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="sr_no" name="sr_no" value="SR. NO." required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <li>
                                            <span>Print Bill No</span>
                                            <div class="switch">
                                                <label>OFF<input type="checkbox" id="bill_no" checked><span class="lever switch-col-cyan"></span>ON</label>
                                            </div>
                                        </li>
                                        <li>
                                            <span>Show SR. NO. Column</span>
                                            <div class="switch">
                                                <label>OFF<input type="checkbox" id="sr_col" checked><span class="lever switch-col-cyan"></span>ON</label>
                                            </div>
                                        </li>
                                        <li>
                                            <span>Show Base price in rate</span>
                                            <div class="switch">
                                                <label>OFF<input type="checkbox" id="base_col" checked><span class="lever switch-col-cyan"></span>ON</label>
                                            </div>
                                        </li>
                                        <li>
                                            <span>Show Payment Mode</span>
                                            <div class="switch">
                                                <label>OFF<input type="checkbox" id="payment_mode" checked><span class="lever switch-col-cyan"></span>ON</label>
                                            </div>
                                        </li>
                                    </div>
                                    <div class="col-md-6">
                                        <li>
                                            <span>Print Time</span>
                                            <div class="switch">
                                                <label>OFF<input type="checkbox" id="bill_time" checked><span class="lever switch-col-cyan"></span>ON</label>
                                            </div>
                                        </li>
                                        <li>
                                            <span>Show Discount Column</span>
                                            <div class="switch">
                                                <label>OFF<input type="checkbox" id="disc_col" checked><span class="lever switch-col-cyan"></span>ON</label>
                                            </div>
                                        </li>
                                        <li>
                                            <span>Consolidated Tax</span>
                                            <div class="switch">
                                                <label>OFF<input type="checkbox" id="consolidated_tax" checked><span class="lever switch-col-cyan"></span>ON</label>
                                            </div>
                                        </li>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="footer" name="footer" required>
                                            <label class="form-label">Extra Footer</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="number" class="form-control" id="decimal_point" name="decimal_point" value="3" required>
                                            <label class="form-label">Decimal point in quantity</label>
                                        </div>
                                        <label>Decimal point value should be in between 1 and 3</label>
                                    </div>
                                    <button class="btn btn-primary waves-effect" id="update">update</button>
                                </div>
                            </div>
                            <style type="text/css">
                                .div_head
                                {
                                    text-align: center;

                                }
                                 .tax
                                {
                                    text-align: right;

                                }
                                .head
                                {
                                    font-size: 20px;
                                    line-height:18px;
                                    font-weight: 500;
                                }
                                 .subtitle
                                {
                                    font-size: 16px;
                                    line-height:14px;
                                    font-weight: 450;
                                }
                                .bill
                                {
                                    font-size: 16px;
                                    line-height:12px;
                                    font-weight: 450;
                                }
                                input[type=number]::-webkit-inner-spin-button 
                                {
                                    -webkit-appearance: none;
                                }
                            </style>
                            <div class="col-md-6">
                                <div class="header">
                                    <h2>PRINT PREVIEW</h2>
                                </div>
                                <div class="body">
                                    <div class="div_head">
                                        <div>
                                            <label class="head" id="preview_title"></label>
                                        </div>
                                        <div>
                                            <label class="subtitle" id="preview_subtitle"></label>
                                        </div>
                                        <div>
                                            <label class="subtitle" id="preview_address"></label>
                                        </div>
                                        <div>
                                            <label class="bill" id="preview_contact_word" style="display: none;">Contact :&nbsp</label><label class="bill" id="preview_contact"></label>
                                        </div>
                                        <div>
                                            <label class="bill" id="preview_gstn_word" style="display: none;">GST No. :&nbsp</label><label class="bill" id="preview_gstn"></label>
                                        </div>
                                        <div>
                                            <label class="bill" id="preview_bill_name">TAX INVOICE</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="div_head">
                                            <div>
                                                <label class="bill" id="preview_sr_no">SR. NO. </label><label>&nbsp:&nbsp</label><label class="bill">9</label>
                                            </div>
                                            <div>
                                                <label class="bill" id="preview_date"></label>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                         <div class="div_head">
                                            <div>
                                                <label class="bill preview_bill_no" id="preview_bill_no">Bill No. :&nbsp</label><label class="bill preview_bill_no">A-18020426</label>
                                            </div>
                                            <div>
                                                <label class="bill" id="preview_time"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <table class="table" id="mytable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ITEM_NAME</th>
                                                <th>QTY</th>
                                                <th>RATE</th>
                                                <th>Disc.(%)</th>
                                                <th>TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Vada Pav</td>
                                                <td>1No</td>
                                                <td class="base">15.00</td>
                                                <td>2</td>
                                                <td>15.00</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Misal</td>
                                                <td>3No</td>
                                                <td class="base">45.00</td>
                                                <td>3</td>
                                                <td>135.00</td>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>TOTAL</th>
                                                <th>4</th>
                                                <th></th>
                                                <th></th>
                                                <th>150.00</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>NET</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>157.50</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                         <div>
                                            <div>
                                                <label class="bill">Total Tax Amount </label>
                                            </div>
                                            <div>
                                                <label class="bill">SGST @ 2.5 </label>
                                            </div>
                                            <div>
                                                <label class="bill">CGST @ 2.5 </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                         <div class="tax">
                                            <div>
                                                <label class="bill">7.50</label>
                                            </div>
                                            <div>
                                                <label class="bill">3.75</label>
                                            </div>
                                            <div>
                                                <label class="bill">3.75</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <table class="table">
                                        <thead style="text-align: center;">
                                            <tr id="preview_payment_mode">
                                                <td> PAYMENT MODE : CASH</td>
                                            </tr>
                                            <tr>
                                                <td> POWERED BY SENSIBLE CONNECT</td>
                                            </tr>
                                            <tr>
                                                <td id="preview_footer"></td>
                                            </tr>
                                            <tr>
                                                <td> **THANK YOU ! VISIT AGAIN !!**</td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="../js/change_device.js"></script>
    <script src="../js/avatar.js"></script>
    <script type="text/javascript">

         $(document).ready(function()
        {
            $('#update').click(function()
            {
                var d_id=<?php echo $_SESSION['d_id']; ?>;
                var title=$("#preview_title").text();
                var sub_title=$("#preview_subtitle").text();
                var address=$("#preview_address").text();
                var contact=$("#preview_contact").text();
                var gstn=$("#preview_gstn").text();
                var bill_name=$("#preview_bill_name").text();
                var decimal_point=$("#decimal_point").val();
                if($("#tax_invoice").is(":checked"))
                {
                    var tax_invoice=1;
                }
                else
                {
                    var tax_invoice=0;
                }

                if($("#preview_sr_no").is(":checked"))
                {
                    var sr_no=1;
                }
                else
                {
                    var sr_no=0;
                }

                if($("#bill_no").is(":checked"))
                {
                    var bill_no=1;
                }
                else
                {
                    var bill_no=0;
                }

                if($("#sr_col").is(":checked"))
                {
                    var sr_col=1;
                }
                else
                {
                    var sr_col=0;
                }

                if($("#base_col").is(":checked"))
                {
                    var base_col=1;
                }
                else
                {
                    var base_col=0;
                }

                if($("#disc_col").is(":checked"))
                {
                    var disc_col=1;
                }
                else
                {
                    var disc_col=0;
                }

                if($("#bill_time").is(":checked"))
                {
                    var bill_time=1;
                }
                else
                {
                    var bill_time=0;
                }

                
                if($("#consolidated_tax").is(":checked"))
                {
                    var consolidated_tax=1;
                }
                else
                {
                    var consolidated_tax=0;
                }

                if($("#payment_mode").is(":checked"))
                {
                    var payment_mode=1;
                }
                else
                {
                    var payment_mode=0;
                }

                var footer=$("#preview_footer").text();
                 $.ajax({
                    type:'POST',
                    url:'update_print_setup.php',
                    data:{"deviceid":d_id,"title":title,"sub_title":sub_title,"address":address,"contact":contact,"gstn":gstn,"bill_name":bill_name,"tax_invoice":tax_invoice,"sr_no":sr_no,"bill_no":bill_no,"sr_col":sr_col,"base_col":base_col,"disc_col":disc_col,"bill_time":bill_time,"consolidated_tax":consolidated_tax,"footer":footer,"payment_mode":payment_mode,"decimal_point":decimal_point},
                    cache:false,
                    success: function(data)
                    {
                        if(data==1)
                        {
                         showNotification("alert-info", "Successfully updated print setup", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                        }
                    }
                });
            });
        });

        $(document).ready(function()
        {
            var d_id=<?php echo $_SESSION['d_id']; ?>;
            $.ajax({
                type:'POST',
                url:'print_setup_data.php',
                data:{"deviceid":d_id},
                cache:false,
                success: function(data)
                {
                    obj=JSON.parse(data);
                    $('#preview_title').text(obj.title);
                    $('#decimal_point').val(obj.decimal_point);
                    $('#preview_subtitle').text(obj.sub_title);
                    $('#preview_address').text(obj.address);
                    $('#preview_sr_no').text(obj.sr_no);
                    if(obj.contact==="")
                    {

                    }
                    else
                    {
                        $("#preview_contact_word").show();
                        $('#preview_contact').text(obj.contact);
                    }
                    if(obj.gstn==="")
                    {

                    }
                    else
                    {
                        $("#preview_gstn_word").show();
                        $('#preview_gstn').text(obj.gstn);
                    }
                    if(obj.tax_invoice==1)
                    {

                        $("#preview_bill_name").text((obj.bill_name).toUpperCase());
                    }
                    else
                    {
                        $('#preview_bill_name, #ip_bill_name').hide();
                        obj.tax_invoice==1?$('#tax_invoice').attr('checked', true):$('#tax_invoice').attr('checked', false);   
                        
                    }
                    obj.prnt_bill_no==1?$('.preview_bill_no').show():$('.preview_bill_no').hide();
                    obj.prnt_bill_no==1?$('#bill_no').attr('checked', true):$('#bill_no').attr('checked', false);

                    obj.payment_mode==1?$('#preview_payment_mode').show():$('#preview_payment_mode').hide();
                    obj.payment_mode==1?$('#payment_mode').attr('checked', true):$('#payment_mode').attr('checked', false);

                    obj.prnt_bill_time==1?$('#preview_time').show():$('#preview_time').hide();
                    obj.prnt_bill_time==1?$('#bill_time').attr('checked', true):$('#bill_time').attr('checked', false);

                    col = $("#mytable tr th:nth-child(1), #mytable tr td:nth-child(1)");
                    obj.prnt_sr_col==1?col.show():col.hide();
                    obj.prnt_sr_col==1?$('#sr_col').attr('checked', true):$('#sr_col').attr('checked', false);

                    cols = $("#mytable tr th:nth-child(5), #mytable tr td:nth-child(5)");
                    obj.prnt_disc_col==1?cols.show():cols.hide();
                    obj.prnt_disc_col==1?$('#disc_col').attr('checked', true):$('#disc_col').attr('checked', false);

                    obj.prnt_base_col==1?$('#base_col').attr('checked', true):$('#base_col').attr('checked', false);
                    if(obj.prnt_base_col==1)
                    {
                        $('table td:nth-child(4)').each(function() 
                        {
                            let $this = $(this);
                            var val=$this.text();
                            $this.html((val/1.05).toFixed(2));
                        });

                    }
                    else
                    {
                        $('table td:nth-child(4)').each(function() 
                        {
                            let $this = $(this);
                            var val=$this.text();
                            $this.html((val*1.05).toFixed(2));
                        });
                    }

                    obj.consolidated_tax==1?$('#consolidated_tax').attr('checked', true):$('#consolidated_tax').attr('checked', false);

                    $("#preview_footer").text((obj.footer).toUpperCase());   
                }
            });
        });

        $(document).ready(function()
        {
            n =  new Date();
            y = n.getFullYear();
            m = n.getMonth() + 1;
            if(m<10)
            {
                m='0'+m;
            }
            d = n.getDate();
            if(d<10)
            {
                d='0'+d;
            }
            var t = n.toLocaleString([], { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true});
            $("#preview_date").text(d + " - " + m + " - " + y);
            $("#preview_time").text(t);
            $("#title").change(function()
            {
                $("#preview_title").text($("#title").val().toUpperCase());
            });
            $("#subtitle").change(function()
            {
                $("#preview_subtitle").text($("#subtitle").val());
            });
            $("#address").change(function()
            {
                $("#preview_address").text($("#address").val());
            });
            $("#gstn").change(function()
            {
                if($("#gstn").val().length>0)
                {
                    $("#preview_gstn_word").show();
                    $("#preview_gstn").text($("#gstn").val());
                }
                else
                {
                    $("#preview_gstn_word").hide();   
                    $("#preview_gstn").text("");
                }
            });
            $("#contact").change(function()
            {
                if($("#contact").val().length>0)
                {
                    $("#preview_contact_word").show();
                    $("#preview_contact").text($("#contact").val());
                }
                else
                {
                    $("#preview_contact_word").hide();   
                    $("#preview_contact").text("");
                }
            });
            $("#bill_name").change(function()
            {
                if($("#bill_name").val().length>0)
                {
                    $("#preview_bill_name").text($("#bill_name").val().toUpperCase());
                }
                else
                {
                    $("#preview_bill_name").text("");
                }
            });
            $("#sr_no").change(function()
            {
                if($("#sr_no").val().length>0)
                {
                    $("#preview_sr_no").text($("#sr_no").val().toUpperCase());
                }
                else
                {
                    $("#preview_sr_no").text("SR. NO.");
                }
            });
            $("#footer").change(function()
            {
                if($("#footer").val().length>0)
                {
                    $("#preview_footer").text($("#footer").val().toUpperCase());
                }
                else
                {
                    $("#preview_footer").text("");
                }
            });
        });
            
        $(document).ready(function() 
        {
            $("input[type=number]").on("focus", function() 
            {
                $(this).on("keydown", function(event) 
                {
                    if (event.keyCode === 38 || event.keyCode === 40) 
                    {
                        event.preventDefault();
                    }
                });
            });
        });

        $('#tax_invoice').on('change', function () {
            this.checked ? $('#preview_bill_name, #ip_bill_name').show() : $('#preview_bill_name, #ip_bill_name').hide();
         });

         $('#bill_no').on('change', function () {
            this.checked ? $('.preview_bill_no').show() : $('.preview_bill_no').hide();
         });

         $('#bill_time').on('change', function () {
            
         });

        $('#sr_col').on('change', function () {
            col = $("#mytable tr th:nth-child(1), #mytable tr td:nth-child(1)");
             this.checked ? col.show() : col.hide();
        });

         $('#payment_mode').on('change', function () {
            this.checked ? $('#preview_payment_mode').show() : $('#preview_payment_mode').hide();
        });

        

        $('#disc_col').on('change', function () {
            col = $("#mytable tr th:nth-child(5), #mytable tr td:nth-child(5)");
             this.checked ? col.show() : col.hide();
        });

        $('#base_col').on('change', function () {
            var base_col=this.checked ? "on":"off";
            if(base_col=="on")
            {
                $('table td:nth-child(4)').each(function() 
                {
                    let $this = $(this);
                    var val=$this.text();
                    $this.html((val/1.05).toFixed(2));
                });
            }
            else
            {
                $('table td:nth-child(4)').each(function() 
                {
                    let $this = $(this);
                    var val=$this.text();
                    $this.html((val*1.05).toFixed(2));
                });
            }
        });     

        $('#decimal_point').on('click', function () 
        {
            var decimal_point=$('#decimal_point').val();
            if(0< decimal_point <=3)
            {
                return true;
            }
            else
            {
                return false;
            }
        });
        $(document).ready(function()
        {
            $('#left_home').addClass('active');
        });        
    </script>

    <!-- Jquery Core Js -->
    <script src="../plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="../plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../plugins/node-waves/waves.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="../plugins/jquery-countto/jquery.countTo.js"></script>
    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <!-- Morris Plugin Js -->
    <script src="../plugins/raphael/raphael.min.js"></script>
    <script src="../plugins/morrisjs/morris.js"></script>

    <script src="../js/admin.js"></script>
    <script src="../js/pages/forms/basic-form-elements.js"></script>
    <!-- ChartJs -->
    <script src="../plugins/chartjs/Chart.bundle.js"></script>

    <!-- Flot Charts Plugin Js -->
    <script src="../plugins/flot-charts/jquery.flot.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.resize.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.pie.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.categories.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.time.js"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="../plugins/jquery-sparkline/jquery.sparkline.js"></script>



    <!-- Custom Js -->
    <script src="../js/admin.js"></script>
    <script src="../js/pages/index.js"></script>

    <!-- Demo Js -->
    <script src="../js/demo.js"></script>
</body>

</html>    