<style type="text/css">   
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
    table, thead, tbody, th, td, tr { 
        display: block; 
    }
    
    thead tr { 
        position: absolute;
        top: -9999px;
        left: -9999px;
    }
    
    tr { border: 1px solid #ccc; }
    
    td { 
        border: none;
        border-bottom: 1px solid #eee; 
        position: relative;
        padding-left: 50%; 
    }
    
    td:before { 
        position: absolute;
        top: 6px;
        left: 6px;
        width: 45%; 
        padding-right: 10px; 
        white-space: nowrap;
    }
    </style>
        <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

        <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

        <link href="../plugins/animate-css/animate.css" rel="stylesheet" />
        <link href="../plugins/sweetalert/sweetalert.css" rel="stylesheet" />

        
        <link href="../css/style.css" rel="stylesheet">
        <link href="../css/aviator.css" rel="stylesheet">

        <link href="../css/themes/all-themes.css" rel="stylesheet" />
        <?php
            session_start();
            include('../connect.php');
        ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>English Name</th>
                    <th>Tax Type</th>
                                            <?php
                                            $d_id=$_SESSION['d_id'];
                                            $status="active";
                                            if($_SESSION['device_type']=="Table")
                                            {
                                                echo'<th>Parcel Price</th>';
                                                $k=1;
                                                $sk=$db->prepare("select * from premise_dtl where deviceid='$d_id' and status='$status'");
                                                $sk->execute();
                                                $price_count=$sk->rowCount();
                                                if($data=$sk->fetch())
                                                {
                                                    do
                                                    {
                                                        echo'<th>'.$data['premise_name'].' Price</th>';
                                                        $k++;
                                                    }
                                                    while($data=$sk->fetch());
                                                }
                                            }
                                            else
                                            {
                                                echo'<th>Default Price</th>';
                                                $k=1;
                                                $status="active";
                                                $sk=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
                                                $sk->execute();
                                                $price_count=$sk->rowCount();
                                                if($data=$sk->fetch())
                                                {
                                                    do
                                                    {
                                                        $k++;
                                                        echo'<th>'.$data['customer_name'].' Price</th>';
                                                    }
                                                    while($data=$sk->fetch());
                                                }
                                            }
                                        ?>
                                        <th style="display: none;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        
                                        $status="active";
                                        if(isset($_POST['page']))
                                        {
                                            $page=$_POST['page'];
                                        }
                                        else
                                        {
                                            $page=1;
                                        }


                                        $limit=10;
                                        $start_from = ($page-1) * $limit;

                                        $total_query=$db->prepare("select product.product_id from product,price_mst, stock_mst where price_mst.product_id=product.product_id and product.status='$status' and product.deviceid='$d_id' and product.product_id=stock_mst.product_id ");

                                        $total_query->execute();
                                        $total_records=$total_query->rowCount();    
                                        $total_pages = ceil($total_records / $limit);


                                        $query=$db->prepare("select * from product,price_mst, stock_mst where price_mst.product_id=product.product_id and product.status='$status' and product.deviceid='$d_id' and product.product_id=stock_mst.product_id LIMIT $start_from, $limit ");
                                        $query->execute();
                                        while($data=$query->fetch())
                                        {
                                            $tax="";
                                            $tax_name="";
                                            $tax_id=$data['tax_id'];
                                            $tax_query=$db->prepare("select * from tax_mst where tax_id='$tax_id'");
                                            $tax_query->execute();
                                            if($tax_data=$tax_query->fetch())
                                            {
                                                do
                                                {
                                                    $tax=$tax_data['tax_id'];
                                                    $tax_name=$tax_data['tax_name'];
                                                }
                                                while($tax_data=$tax_query->fetch());
                                            }
                                            echo'<tr id="'.$data['price_id'].'" class="edit_tr">
                                                <td>'.$data['english_name'].'</td>
                                                 <td><select class="form-cotrol tax" id="tax'.$data['price_id'].'">
                                                    <option value="'.$tax.'">'.$tax_name.'</option>';
                                                    $status="active";
                                                    $tax_query=$db->prepare("select * from tax_mst where status='$status'");
                                                    $tax_query->execute();
                                                    if($tax=$tax_query->fetch())
                                                    {
                                                        do
                                                        {
                                                            echo'<option value="'.$tax['tax_id'].'">'.$tax['tax_name'].'</option>';
                                                        }
                                                        while($tax=$tax_query->fetch());
                                                    }
                                                
                                                    echo'</select></td>';

                                                    for($j=1; $j<=$k; $j++)
                                                    {
                                                        $price_name='price'.$j;
                                                        $price='prices'.$j;
                                                        $id=$price_name.$data['price_id'];
                                                        $idc=$price.$data['price_id'];
                                                        echo'<td style="max-width:100px"><input type="text" min="0" value="'.$data[$price_name].'"  id="'.$id.'" class="grpprice" style="max-width:100%" /><br><input type="text" min="0" value="'.$data[$price].'"  id="'.$idc.'" class="grpprice1" style="max-width:100%" readonly/></td>';

                                                    }

                                            echo'<td id="count" style="display:none;">'.$k.'</td></tr>';
                                        }
                                    ?>
                                    </tbody>
                                </table>
                                 <nav>
                <ul class="pagination">
                   <?php include('../pagination/pagination.php'); ?>    
                </ul>
            </nav>

    <script type="text/javascript">
        $(document).ready(function() {         
            $('.gen').click(function(){
                var page=$(this).text();
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                   $.ajax({
                        type: 'POST',
                        url: '../dashboard/price_data.php',
                        data: { "page":page },
                        cache: false,
                        success: function(data)
                        {
                            $('.page-loader-wrapper').hide();
                            $('#data-display').html(data);
                        }
                    });
                
            } );
        });

        $(document).ready(function() {         
            $('.prev').click(function(){
                var page= <?php echo $page-1;?>;
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                   $.ajax({
                        type: 'POST',
                        url: '../dashboard/price_data.php',
                        data: { "page":page },
                        cache: false,
                        success: function(data)
                        {
                            $('.page-loader-wrapper').hide();
                            $('#data-display').html(data);
                        }
                    });
                
            } );
        });

        $(document).ready(function() {         
            $('.next').click(function(){
                var page= <?php echo $page+1;?>;
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                    $.ajax({
                        type: 'POST',
                        url: '../dashboard/price_data.php',
                        data: { "page":page },
                        cache: false,
                        success: function(data)
                        {
                            $('.page-loader-wrapper').hide();
                            $('#data-display').html(data);
                        }
                    });
            } );
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
                });
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

$(document).ready(function() 
    {
        $('.grpprice').keypress(function (event) 
        {
            return isNumber(event, this)
        });
    });
    
    function isNumber(evt, element) {

        var charCode = (evt.which) ? evt.which : event.keyCode

        if (
            (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
            (charCode < 48 || charCode > 57))
            return false;

        return true;
    }  


    $('.grpprice').keypress(function(event) {
    var $this = $(this);
    if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
       ((event.which < 48 || event.which > 57) &&
       (event.which != 0 && event.which != 8))) {
           event.preventDefault();
    }

    var text = $(this).val();
    if ((event.which == 46) && (text.indexOf('.') == -1)) {
        setTimeout(function() {
            if ($this.val().substring($this.val().indexOf('.')).length > 3) {
                $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
            }
        }, 1);
    }

    if ((text.indexOf('.') != -1) &&
        (text.substring(text.indexOf('.')).length > 2) &&
        (event.which != 0 && event.which != 8) &&
        ($(this)[0].selectionStart >= text.length - 2)) {
            event.preventDefault();
    }      
});

$('.grpprice').bind("paste", function(e) {
var text = e.originalEvent.clipboardData.getData('Text');
if ($.isNumeric(text)) {
    if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
        e.preventDefault();
        $(this).val(text.substring(0, text.indexOf('.') + 3));
   }
}
else {
        e.preventDefault();
     }
});

    $(document).ready(function() {
$("input[type=number]").on("focus", function() {
    $(this).on("keydown", function(event) {
        if (event.keyCode === 38 || event.keyCode === 40) {
            event.preventDefault();
        }
     });
   });
});


$('.grpprice').keypress(function(event) {
    var value = event.target.value + String.fromCharCode(event.keyCode);
    if (!(/^\d{1,7}(\.$|\.\d{1,2}$|$)/).test(value)) {
      event.preventDefault();
      event.stopPropagation();
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
   </script>
 <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
<script src="../plugins/sweetalert/sweetalert.min.js"></script>
<script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
<script src="../js/pages/ui/notifications.js"></script>

   