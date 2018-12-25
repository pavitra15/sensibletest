<?php
    session_start();
    require_once('../user_validate.php');
    include_once('../../connect.php');        
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>Welcome | Sensible Connect - Admin</title>
        <link rel="icon" href="../../favicon.png" type="image/x-icon">
        
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
        <link href="../../plugins/bootstrap/css/bootstrap-one.css" rel="stylesheet">
        <link href="../../plugins/node-waves/waves.css" rel="stylesheet" />

        <link href="../../plugins/animate-css/animate.css" rel="stylesheet" />
        <link href="../../plugins/sweetalert/sweetalert.css" rel="stylesheet" />
        
        <link href="../../css/style-one.css" rel="stylesheet">
        <link href="../../css/aviator.css" rel="stylesheet">
        <link href="../../css/custom.css" rel="stylesheet">
        <link href="../../css/themes/all-themes.css" rel="stylesheet" />
        <body class="theme-teal ls-closed" id="body">
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
                            <?php 
                                include('../notification/device_notification.php');
                            ?>
                    </ul>
                </div>
            </div>
        </nav>
        <section>
            <aside id="leftsidebar" class="sidebar">
                <?php 
                    include('../menu/user_menu.php');
                ?>
                <div class="menu" style="display: none">
                    <?php 
                        include('../menu/left_menu.php');
                    ?>
                </div>
               <?php include('../../footer.html'); ?>
            </aside>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="block-header">
                    <h2>
                        PRODUCT SETTING
                    </h2>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 card">
                            <div class="header">
                                <div class="row clearfix">
                                    <div class="col-xs-4 col-sm-4">
                                        <button type="button" name="save" id="save" class="btn btn-info save">Save</button>
                                    </div>
                                    <div class="col-xs-4 col-sm-4">
                                        <div class="switch panel-switch-btn" style="margin-left: 30px">
                                            <div id="switch" style="display: none"><?php echo $tax_type; ?></div>
                                            <span class="m-r-10 font-12">REVERSE TAXATION <?php echo $tax_type; ?></span>
                                            <label>OFF<input type="checkbox" id="realtime" checked><span class="lever switch-col-cyan"></span>ON</label>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-xs-offset-0 align-right">
                                        <input type="text" name="search" id="search" placeholder="Search">
                                    </div>
                                </div>
                            </div>
                           
                        <div class="body">
                            <div class="frmSearch">
                                <div id="suggesstion-box"></div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " >
                                <table class="table table-striped">
                                    <thead>
                                        <?php
                                            echo'<tr>
                                                <th>English Name </th>
                                                <th>Regional Name</th>';
                                            if($_SESSION['device_type']=="Table")
                                            {
                                                echo'<th>Category</th>
                                                    <th>Kitchen</th>
                                                    <th>Discount</th>
                                                    <th>Reorder Level</th>
                                                    <th>Stock</th>
                                                    <th>Stockable</th>
                                                    <th>Unit</th>
                                                    <th>Tax</th>
                                                    <th>Parcel Price</th>';
                                                $sk=$db->prepare("select * from premise_dtl where deviceid='$d_id' and status='$status'");
                                                $sk->execute();
                                                while($data=$sk->fetch())
                                                {
                                                    echo '<th>'.$data['premise_name'].' price</th>';
                                                }
                                            }
                                            elseif($_SESSION['device_type']=="Weighing")
                                            {
                                                 echo'<th>Category</th>
                                                    <th>weightable</th>
                                                    <th>Discount</th>
                                                    <th>Reorder Level</th>
                                                    <th>Stock</th>
                                                    <th>Stockable</th>
                                                    <th>Unit</th>
                                                    <th>Tax</th>
                                                    <th>Default Price</th>';
                                                $sk=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
                                                $sk->execute();
                                                while($data=$sk->fetch())
                                                {
                                                    echo '<th>'.$data['customer_name'].' price</th>';
                                                }
                                            }
                                            elseif($_SESSION['device_type']=="Non-Table")
                                            {
                                                 echo'<th>Category</th>
                                                    <th>Discount</th>
                                                    <th>Reorder Level</th>
                                                    <th>Stock</th>
                                                    <th>Stockable</th>
                                                    <th>Unit</th>
                                                    <th>Tax</th>
                                                    <th>Default Price</th>';
                                                $sk=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
                                                $sk->execute();
                                                while($data=$sk->fetch())
                                                {
                                                    echo '<th>'.$data['customer_name'].' price</th>';
                                                }
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                            
                                    <tbody id="data-display" style="display: none">
                                    
                                        </tbody>    
                                    </table>
                            </div>
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
            </div>
        </section>
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">       
        $(document).ready(function() 
        {  
            var limit=20;
            var start=0;
            var action="inactive";
            function load_data(limit, start)
            {
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: '../sql/test_data.php',
                    data: { "limit":limit ,"start": start},
                    cache: false,
                    success: function(data)
                    {
                        $('#data-display').append(data);
                        $('.page-loader-wrapper').hide();
                        if(data == '')
                        {
                            $('#load_data_message').html("<button type='button' class='btn btn-info'>No Data Found</button>");
                            action = 'active';
                        }
                        else
                        {
                            $('#load_data_message').html("<button type='button' class='btn btn-warning'>Please Wait....</button>");
                            action = "inactive";
                        }
                    }
                });
            }

            if(action == 'inactive')
            {
                action = 'active';
                load_data(limit, start);
            }
            $(window).scroll(function(){
                if($(window).scrollTop() + $(window).height() > $("#data-display").height() && action == 'inactive')
                {
                    action = 'active';
                    start = start + limit;
                    setTimeout(function(){
                        load_data(limit, start);
                    }, 1000);
                }
            });     
        });

        $(document).ready(function()
        {
            $('#left_product').addClass('active');
            setTimeout("$('#body').addClass('ls-closed');",50);
        });

        var realtime;
        $('#realtime').on('change', function () 
        {
            realtime = this.checked ? 'on' : 'off';
            var dataString = 'realtime='+ realtime+'&id='+<?php echo $_SESSION['login_id']; ?> +'&d_id='+<?php echo $_SESSION['d_id']; ?>;  
            $.ajax({
                type: "POST",
                url: "../sql/update_incl.php",
                data: dataString,
                cache: false,
                success: function(data)
                {
                    var total_page=Math.ceil(parseInt(data)/30);
                    recursiveAjaxCall(total_page,1);
                }
            });
        });


        function recursiveAjaxCall(aNum,currentNum)
        {
            $('.page-loader-wrapper').show();
            $.ajax({
                type: 'POST',
                url: '../sql/calculation.php',
                data: {page_no: currentNum, inclusive:realtime, d_id:<?php echo $_SESSION['d_id']; ?>, id:<?php echo $_SESSION['login_id']; ?>},
                success: function(data)
                {
                    if(data<aNum)
                    {
                        recursiveAjaxCall(aNum,data);
                    }
                    else
                    {
                        $('.page-loader-wrapper').hide();
                        showNotification("alert-info", " records saved successfully", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                        setTimeout("location.reload(true);",50);
                    }
                },
                async:   true
            });
        }

        $(document).ready(function() 
        {
            var kr;
            var realt;
            $('#switch').each(function(){
                realt=$(this).text();
            });
            if(realt==="on")
            {
                $('#realtime').attr('checked', true);
            }
            else
            {
                $('#realtime').attr('checked', false);
            }
        });

        $(document).on('click', ".edit_tr", function () 
        {
            var ID=$(this).attr('id');
            kr=ID;
            var count=$("#count").text();
            var i=0;
            for(i=1;i<=count;i++)
            {
                $("#price"+i+ID).hide();
            }

            $("#realtime").hide();
            $("#tax"+ID).show();
                
            for(i=1;i<=count;i++)
            {
                $("#price"+i+ID).show();
            }

            $("#realtime").show();
            }).change(function()
            {
                var ID=kr;
                var price1=$("#price1"+ID).val();
                var price2=$("#price2"+ID).val();
                var price3=$("#price3"+ID).val();
                var price4=$("#price4"+ID).val();
                var price5=$("#price5"+ID).val();
                var price6=$("#price6"+ID).val();
                var price7=$("#price7"+ID).val();
                var price8=$("#price8"+ID).val();
                var price9=$("#price9"+ID).val();

                var tax_id=$("#tax"+ID).val();
                var rev_tax=$('#switch').text();
                var  tax =$("#tax"+ID+" option:selected").text();
                values=tax.split(' ');
                var ch=values[1];
                var tax_calc=parseFloat(1+(ch/100)); 
                if(rev_tax==="on")
                {
                    $("#prices1"+ID).val((parseFloat(price1)/tax_calc).toFixed(2));
                    $("#prices2"+ID).val((parseFloat(price2)/tax_calc).toFixed(2));
                    $("#prices3"+ID).val((parseFloat(price3)/tax_calc).toFixed(2));
                    $("#prices4"+ID).val((parseFloat(price4)/tax_calc).toFixed(2));
                    $("#prices5"+ID).val((parseFloat(price5)/tax_calc).toFixed(2));
                    $("#prices6"+ID).val((parseFloat(price6)/tax_calc).toFixed(2));
                    $("#prices7"+ID).val((parseFloat(price7)/tax_calc).toFixed(2));
                    $("#prices8"+ID).val((parseFloat(price8)/tax_calc).toFixed(2));
                    $("#prices9"+ID).val((parseFloat(price9)/tax_calc).toFixed(2));
                }
                else
                {
                    $("#prices1"+ID).val((parseFloat(price1)*tax_calc).toFixed(2));
                    $("#prices2"+ID).val((parseFloat(price2)*tax_calc).toFixed(2));
                    $("#prices3"+ID).val((parseFloat(price3)*tax_calc).toFixed(2));
                    $("#prices4"+ID).val((parseFloat(price4)*tax_calc).toFixed(2));
                    $("#prices5"+ID).val((parseFloat(price5)*tax_calc).toFixed(2));
                    $("#prices6"+ID).val((parseFloat(price6)*tax_calc).toFixed(2));
                    $("#prices7"+ID).val((parseFloat(price7)*tax_calc).toFixed(2));
                    $("#prices8"+ID).val((parseFloat(price8)*tax_calc).toFixed(2));
                    $("#prices9"+ID).val((parseFloat(price9)*tax_calc).toFixed(2));
                }

                var tax=$("#tax"+ID).val();
                var price1=parseFloat($("#price1"+ID).val());
                var price2=parseFloat($("#price2"+ID).val());
                var price3=parseFloat($("#price3"+ID).val());
                var price4=parseFloat($("#price4"+ID).val());
                var price5=parseFloat($("#price5"+ID).val());
                var price6=parseFloat($("#price6"+ID).val());
                var price7=parseFloat($("#price7"+ID).val());
                var price8=parseFloat($("#price8"+ID).val());
                var price9=parseFloat($("#price9"+ID).val());

                var prices1=parseFloat($("#prices1"+ID).val());
                var prices2=parseFloat($("#prices2"+ID).val());
                var prices3=parseFloat($("#prices3"+ID).val());
                var prices4=parseFloat($("#prices4"+ID).val());
                var prices5=parseFloat($("#prices5"+ID).val());
                var prices6=parseFloat($("#prices6"+ID).val());
                var prices7=parseFloat($("#prices7"+ID).val());
                var prices8=parseFloat($("#prices8"+ID).val());
                var prices9=parseFloat($("#prices9"+ID).val());

                    if(isNaN(price1))
                    {
                        price1=0;
                        prices1=0;
                    }
                    if(isNaN(price2))
                    {
                        price2=0;
                        prices2=0;
                    }
                    if(isNaN(price3))
                    {
                        price3=0;
                        prices3=0;
                    }

                    if(isNaN(price4))
                    {
                        price4=0;
                        prices4=0;
                    }
                    if(isNaN(price5))
                    {
                        price5=0;
                        prices5=0;
                    }
                    if(isNaN(price6))
                    {
                        price6=0;
                        prices6=0;
                    }
                    if(isNaN(price7))
                    {
                        price7=0;
                        prices7=0;
                    }
                    if(isNaN(price8))
                    {
                        price8=0;
                        prices8=0;
                    }
                    if(isNaN(price9))
                    {
                        price9=0;
                        prices9=0;
                    }

                    var dataString = 'price_id='+ ID +'&tax='+tax+'&price1='+price1+'&prices1='+prices1+'&price2='+price2+'&prices2='+prices2+'&price3='+price3+'&prices3='+prices3+'&price4='+price4+'&prices4='+prices4+'&price5='+price5+'&prices5='+prices5+'&price6='+price6+'&prices6='+prices6+'&price7='+price7+'&prices7='+prices7+'&price8='+price8+'&prices8='+prices8+'&price9='+price9+'&prices9='+prices9+'&id='+<?php echo $_SESSION['login_id']; ?>;
                    if(tax.length>0)
                    {
                        $.ajax({
                                type: "POST",
                                url: "../sql/update_price.php",
                                data: dataString,
                                cache: false,
                                success: function(data)
                                {
                                    console.log(data);
                                    values=data.split('_');
                                    ch=values[0];
                                    name=values[1];
                                    mobile=values[2];
                                    switch(ch)
                                    {
                                        case "1":
                                            showNotification("alert-info", "Record updated successfully", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                                        break;
                                    }
                                }
                            });
                    }
                    else
                    {
                        showNotification("alert-danger","All fields are mandatory and add proper data", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                    }

                });
               
        



$(document).ready(function()
{
$(".edit_tr").click(function()
{
var ID=$(this).attr('id');
var count=$("#count").text();
var i=0;
for(i=1;i<=count;i++)
{
    $("#price"+i+ID).hide();
}

$("#realtime").hide();
$("#tax"+ID).show();
for(i=1;i<=count;i++)
{
    $("#price"+i+ID).show();
}
$("#realtime").show();
}).change(function()
{
var ID=$(this).attr('id');
var tax=$("#tax"+ID).val();

var price1=parseFloat($("#price1"+ID).val());
var price2=parseFloat($("#price2"+ID).val());
var price3=parseFloat($("#price3"+ID).val());
var price4=parseFloat($("#price4"+ID).val());
var price5=parseFloat($("#price5"+ID).val());
var price6=parseFloat($("#price6"+ID).val());
var price7=parseFloat($("#price7"+ID).val());
var price8=parseFloat($("#price8"+ID).val());
var price9=parseFloat($("#price9"+ID).val());

var prices1=parseFloat($("#prices1"+ID).val());
var prices2=parseFloat($("#prices2"+ID).val());
var prices3=parseFloat($("#prices3"+ID).val());
var prices4=parseFloat($("#prices4"+ID).val());
var prices5=parseFloat($("#prices5"+ID).val());
var prices6=parseFloat($("#prices6"+ID).val());
var prices7=parseFloat($("#prices7"+ID).val());
var prices8=parseFloat($("#prices8"+ID).val());
var prices9=parseFloat($("#prices9"+ID).val());



if(isNaN(price1))
{
    price1=0;
    prices1=0;
}
if(isNaN(price2))
{
    price2=0;
    prices2=0;
}
if(isNaN(price3))
{
    price3=0;
    prices3=0;
}

if(isNaN(price4))
{
    price4=0;
    prices4=0;
}
if(isNaN(price5))
{
    price5=0;
    prices5=0;
}
if(isNaN(price6))
{
    price6=0;
    prices6=0;
}
if(isNaN(price7))
{
    price7=0;
    prices7=0;
}
if(isNaN(price8))
{
    price8=0;
    prices8=0;
}
if(isNaN(price9))
{
    price9=0;
    prices9=0;
}

var dataString = 'price_id='+ ID +'&tax='+tax+'&price1='+price1+'&prices1='+prices1+'&price2='+price2+'&prices2='+prices2+'&price3='+price3+'&prices3='+prices3+'&price4='+price4+'&prices4='+prices4+'&price5='+price5+'&prices5='+prices5+'&price6='+price6+'&prices6='+prices6+'&price7='+price7+'&prices7='+prices7+'&price8='+price8+'&prices8='+prices8+'&price9='+price9+'&prices9='+prices9+'&id='+<?php echo $_SESSION['login_id']; ?>;
if(tax.length>0)
    {
    setTimeout(function() 
            
            {
            $.ajax({
            type: "POST",
            url: "../sql/update_price.php",
            data: dataString,
            cache: false,
            success: function(data)
            {
                values=data.split('_');
                ch=values[0];
                name=values[1];
                mobile=values[2];
                switch(ch)
                {
                    case "1":
                        showNotification("alert-info", "Record updated successfully", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                    break;
                }
            }
        });
            },2000);
}
else
{
    showNotification("alert-danger","All fields are mandatory and add proper data", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
}

});

// Edit input box click action
$(".editbox").mouseup(function() 
{
return false
});

// Outside click action
$(document).mouseup(function()
{
$(".editbox").hide();
$(".text").show();
});
});



 
</script>
    <script src="../../js/change_device.js"></script>
    <script src="../../js/avatar.js"></script>
    <script src="../../plugins/jquery/jquery.min.js"></script>

    <script src="../../plugins/bootstrap/js/bootstrap.js"></script>

    <script src="../../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    
    <script src="../../plugins/node-waves/waves.js"></script>

    <script src="../../js/admin.js"></script>
    <script src="../../js/demo.js"></script>

    <script src="../../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../../js/pages/ui/notifications.js"></script>
</body>

</html>