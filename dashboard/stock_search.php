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
        
    <link href="../plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

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
                <th>Current Stock</th>
                <th>Unit</th>
                <th>Stockable</th>
                <th>New Stock</th>
            </tr>
        </thead>
        <tbody >
        <?php
            $d_id=$_SESSION['d_id'];
            $search=$_POST['search'];
            $status="active";
            $query=$db->prepare("select * from product,stock_mst,price_mst where stock_mst.product_id=product.product_id and product.status='$status'  and product.deviceid='$d_id' and product.product_id= price_mst.product_id and product.product_id=stock_mst.product_id and product.english_name LIKE '$search%'");
            $query->execute();
            while($data=$query->fetch())
            {
                $unit="";
                $abbrevation="";
                $unit_id=$data['unit_id'];
                $unit_query=$db->prepare("select * from unit_mst where unit_mst.unit_id='$unit_id'");
                $unit_query->execute();
                if($unit_data=$unit_query->fetch())
                {
                    do
                    {
                        $unit=$unit_data['unit_id'];
                        $abbrevation=$unit_data['abbrevation'];
                    }
                    while($unit_data=$unit_query->fetch());
                }
                echo'<tr id="'.$data['stock_id'].'" class="edit_tr">
                    <td>'.$data['english_name'].'</td>
                    <td id="current_stock'.$data['stock_id'].'">'.$data['current_stock'].'</td>
                    <td><select class="form-control category" id="unit'.$data['stock_id'].'">
                        <option value="'.$unit.'">'.$abbrevation.'</option>';
                        $status="active";
                        $unit_query=$db->prepare("select * from unit_mst where status='$status'");
                        $unit_query->execute();
                        if($unit=$unit_query->fetch())
                        {
                            do
                            {
                                echo'<option value="'.$unit['unit_id'].'">'.$unit['abbrevation'].'</option>';
                            }
                            while($unit=$unit_query->fetch());
                        }
                echo'</select></td>
                <td><select class="form-control check_empty" id="stockable'.$data['stock_id'].'">
                    <option>'.$data['stockable'].'</option>
                    <option>Yes</option>
                    <option>No</option>
                </select></td>
                <td><input type="text" class="grpprice" id="new_stock'.$data['stock_id'].'" value="0"></td>';
            ?>
                <script type="text/javascript">
                    var id=<?php echo $data['stock_id'];?>;
                    var stockable='<?php echo $data['stockable'];?>';
                    if(stockable==="Yes")
                    {
                        $("#new_stock"+id).prop('disabled',false);
                    }
                    else
                    {
                        $("#new_stock"+id).prop('disabled',true);
                    }                                            
                </script>
            <?php
                echo'</tr>';
            }
        ?>
        </tbody>
    </table>
    
<script type="text/javascript">

   $(document).ready(function()
{
    $(".edit_tr").click(function()
    {
    var ID=$(this).attr('id');

    $("#current_stock"+ID).hide();
    $("#stockable"+ID).show();
    $("#current_stock"+ID).show();
    }).change(function()
    {

var ID=$(this).attr('id');
var stockable=$("#stockable"+ID).val();
var current_stock=$("#current_stock"+ID).text();
var new_stock=$("#new_stock"+ID).val();
var unit=$("#unit"+ID).val();
if(stockable=="Yes")
{
    $("#new_stock"+ID).prop('disabled', false);
}
else
{
     $("#new_stock"+ID).prop('disabled', true);
}
if(new_stock=='')
{
    new_stock=0;
}
if(stockable.length>0 && unit.length>0)
{
    var addition=parseFloat(current_stock)+parseFloat(new_stock);
    $("#current_stock"+ID).html(addition);
}

var dataString = 'stock_id='+ ID +'&stockable='+stockable+'&current_stock='+addition+'&unit='+unit+'&id='+<?php echo $_SESSION['login_id']; ?>;
if(unit.length>0 && stockable.length>0)
{

$.ajax({
type: "POST",
url: "../sql/update_stock.php",
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
            $("#new_stock"+ID).val(0); 
        break;
        case "2":
            showNotification("alert-danger", "Unable to update", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
            $("#new_stock"+ID).val(0); 
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


$(document).ready(function() {
        $('.grpprice').keypress(function (event) {
            return isNumber(event, this)
        });
    });
    function isNumber(evt, element) {

        var charCode = (evt.which) ? evt.which : event.keyCode
        if ((charCode != 46 || $(element).val().indexOf('.') != -1) && (charCode < 48 || charCode > 57))
        {
            if(charCode==45)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return true;    
        }
        
    }
   </script>


<script src="../plugins/sweetalert/sweetalert.min.js"></script>
<script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
<script src="../js/pages/ui/notifications.js"></script>
   