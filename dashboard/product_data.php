<style type="text/css">
    
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {

    /* Force table to not be like tables anymore */
    table, thead, tbody, th, td, tr { 
        display: block; 
    }
    
    /* Hide table headers (but not display: none;, for accessibility) */
    thead tr { 
        position: absolute;
        top: -9999px;
        left: -9999px;
    }
    
    tr { border: 1px solid #ccc; }
    
    td { 
        /* Behave  like a "row" */
        border: none;
        border-bottom: 1px solid #eee; 
        position: relative;
        padding-left: 50%; 
    }
    
    td:before { 
        /* Now like a table header */
        position: absolute;
        /* Top/left values mimic padding */
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
    $device_model=$_POST['device_model'];
?>
    <table class="table table-striped" id="mainTable">
        <thead>
            <tr>
                <th>English Name</th>
                <th>Regional Name</th>
                <?php
                    if($_SESSION['device_type']=="Weighing")
                    {
                        echo "<th>Weightable</th>";
                    }
                ?>
                <th>Category</th>
                <th>Disc(%)</th>
                <?php
                    if($_SESSION['device_type']=="Table")
                    {
                        echo "<th>Kitchen</th>";
                        echo "<th>Comisson(%)</th>";
                    }
                ?>
                <?php
                    if($device_model==500)
                    {
                        echo'<th>Image</th>';
                    }
                ?>
                <th style="max-width:15px">Action</th>
            </tr>
        </thead>
        <tbody id="tab_id">
            <?php
                $d_id=$_SESSION['d_id'];
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
                $total_query=$db->prepare("select product.product_id from product,price_mst, stock_mst where status='$status' and deviceid='$d_id' and product.product_id= price_mst.product_id and product.product_id=stock_mst.product_id");
                $total_query->execute();
                $total_records=$total_query->rowCount();    
    			$total_pages = ceil($total_records / $limit);
                $query=$db->prepare("select product.product_id, english_name,regional_name, weighing,category_id,discount,bucket_id,kitchen_id, comission from product,price_mst, stock_mst where status='$status' and deviceid='$d_id' and product.product_id = price_mst.product_id and product.product_id=stock_mst.product_id LIMIT $start_from, $limit");
                $query->execute();
                while($data=$query->fetch())
                {
                    $category="";
                    $category_val="";
                    $kitchen="";
                    $kitchen_val="";
                    $product_idc=$data['product_id'];
                    $bucket=$data['bucket_id'];
                    $category_query=$db->prepare("select * from product, category_dtl where category_dtl.category_id=product.category_id and product_id='$product_idc'");
                    $category_query->execute();
                    if($cate=$category_query->fetch())
                    {
                        do
                        {
                            $category=$cate['category_name'];
                            $category_val=$cate['category_id'];
                        }
                        while($cate=$category_query->fetch());
                    }

                    $kitchen_query=$db->prepare("select * from product, kitchen_dtl where kitchen_dtl.kitchen_id=product.kitchen_id and product_id='$product_idc'");
                    $kitchen_query->execute();
                    if($kitchen_data=$kitchen_query->fetch())
                    {
                        do
                        {
                            $kitchen=$kitchen_data['kitchen_name'];
                            $kitchen_val=$kitchen_data['kitchen_id'];
                        }
                        while($kitchen_data=$kitchen_query->fetch());
                    }
                    $img_url="";
                    $img_query=$db->prepare("select product_id, bucket from product_mst where product_id='$bucket'");
                    $img_query->execute();
                    while($img_data=$img_query->fetch())
                    {
                        $img_url=$img_data['bucket'];
                    }
                    
                    echo'<tr id="'.$data['product_id'].'" class="edit_tr">
                        <td><input type="text" id="english_'.$data['product_id'].'" class="test-input cat search-box" value="'.$data['english_name'].'" maxlength="50"> </td>
                        <td><input type="text" id="regional_'.$data['product_id'].'" class="test-input cat" value="'.$data['regional_name'].'" maxlength="50"> </td>';
                        if($_SESSION['device_type']=="Weighing")
                        {
                            echo '<td><select class="form-control" id="weightable'.$data['product_id'].'">
                                <option>'.$data['weighing'].'</option>
                                <option>Yes</option>
                                <option>No</option>
                            </select></td>';
                        }
                        echo'<td><select class="form-control type" id="category'.$data['product_id'].'">
                            <option value="'.$category_val.'">'.$category.'</option>';
                            $status="active";
                            $cat_query=$db->prepare("select * from category_dtl where deviceid='$d_id' and status='$status'");
                            $cat_query->execute();
                            if($cat=$cat_query->fetch())
                            {
                                do
                                {
                                    echo'<option value="'.$cat['category_id'].'">'.$cat['category_name'].'</option>';
                                }
                                while($cat=$cat_query->fetch());
                            }
                        echo'</select></td>
                         <td><input type="number" id="discount_'.$data['product_id'].'" class="test-input cat search-box" value="'.$data['discount'].'"> </td>';
                        if($_SESSION['device_type']=="Table")
                        {
                            echo'<td><select class="form-control" id="kitchen'.$data['product_id'].'">
                                <option value="'.$kitchen_val.'">'.$kitchen.'</option>';
                                $status="active";
                                $kit_query=$db->prepare("select * from kitchen_dtl where deviceid='$d_id' and status='$status'");
                                $kit_query->execute();
                                if($kit=$kit_query->fetch())
                                {
                                    do
                                    {
                                        echo'<option value="'.$kit['kitchen_id'].'">'.$kit['kitchen_name'].'</option>';
                                    }
                                    while($kit=$kit_query->fetch());
                                }
                            echo'</select></td>
                            <td><input type="number" id="comission_'.$data['product_id'].'" class="test-input cat search-box" value="'.$data['comission'].'"> </td>';
                        }
                        if($device_model==500)
                        {
                            echo'<td><img id="blah_'.$data['product_id'].'" src="'.$img_url.'" height="30px"/> <input type="text" id="img_'.$data['product_id'].'" value="'.$data['bucket_id'].'" style="display:none;"></td>';
                        }
                        echo'<th style="max-width:15px"><button type="button" class="btn bg-red btn-xs removebutton" title="Remove this row"><i class="material-icons">delete</i></button></th>
                    </tr>';
                }
            ?>
            <tr class="insert" id="1">
                <td><input type="text" class="english_name test-input cat search-box" id="english_1" value="" maxlength="50"> </td>
                <td><input type="text" class="regional_name test-input cat" id="regional_1"  value="" maxlength="50"> </td>
                <?php
                    if($_SESSION['device_type']=="Weighing")
                    {
                        echo '<td><select class="form-control weighing">
                            <option>Yes</option>
                            <option>No</option>
                        </select></td>';
                    }
                ?>
                <td><select class="form-control category">
                    <option>Select</option>
                    <?php
                        $status="active";
                        $cat_query=$db->prepare("select * from category_dtl where deviceid='$d_id' and status='$status'");
                        $cat_query->execute();
                        if($cat=$cat_query->fetch())
                        {
                            do
                            {
                                echo'<option value="'.$cat['category_id'].'">'.$cat['category_name'].'</option>';
                            }
                            while($cat=$cat_query->fetch());
                        }
                    ?>
                </select></td>
                <td><input type="number" class="test-input cat search-box discount"> </td>

                <?php
                    if($_SESSION['device_type']=="Table")
                    {
                        echo'<td><select class="form-control kitchen">
                            <option value="">Select</option>';
                            $status="active";
                            $kit_query=$db->prepare("select * from kitchen_dtl where deviceid='$d_id' and status='$status'");
                            $kit_query->execute();
                            if($kit=$kit_query->fetch())
                            {
                                do
                                {
                                    echo'<option value="'.$kit['kitchen_id'].'">'.$kit['kitchen_name'].'</option>';
                                }
                                while($kit=$kit_query->fetch());
                            }
                  
                echo '</select></td>
                 <td><input type="number" class="test-input cat search-box comission"> </td>';
                   }
                ?>
                <?php
                    if($device_model==500)
                    {
                        echo'<td><img id="blah_1" src="#" height="30px"/><input type="text" class="img" id="img_1" value="" style="display: none;"></td>';
                    }
                ?>
                <th style="max-width:15px"><button type="button" class="btn bg-red btn-xs removebutton" title="Remove this row"><i class="material-icons">delete</i></button></th>
            </tr>
        </tbody>
    </table>
    <div class="row" style="text-align: right;">
        <button type="button" id="add" class="btn bg-cyan btn-circle waves-effect waves-circle waves-float"> 
            <i class="material-icons">add</i> 
        </button>
    </div>
    <nav>
                <ul class="pagination">
                   <?php include('../pagination/pagination.php'); ?>    
                </ul>
            </nav>

    <script type="text/javascript">

        $('#add').click(function()
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
        });

        
        $(document).ready(function() 
        {         
            $('.gen').click(function()
            {
                var page=$(this).text();
                var device_model= <?php echo $device_model; ?>;
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: '../dashboard/product_data.php',
                    data: { "page":page,"device_model":device_model },
                    cache: false,
                    success: function(data)
                    {
                        $('.page-loader-wrapper').hide();
                        $('#data-display').html(data);
                    }
                });    
            });
        });

        $(document).ready(function()
        {         
            $('.prev').click(function()
            {
                var page= <?php echo $page-1;?>;
                var device_model= <?php echo $device_model; ?>;
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: '../dashboard/product_data.php',
                    data: { "page":page,"device_model":device_model },
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
            $('.next').click(function(){
                var page= <?php echo $page+1;?>;
                var device_model= <?php echo $device_model; ?>;
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax(
                {
                    type: 'POST',
                    url: '../dashboard/product_data.php',
                    data: { "page":page,"device_model":device_model },
                    cache: false,
                    success: function(data)
                    {
                        $('.page-loader-wrapper').hide();
                        $('#data-display').html(data);
                    }
                });
            });
        });


 $(document).ready(function()
{
    $(".edit_tr").click(function()
    {
        var ID=$(this).attr('id');
        $("#english_"+ID).hide();
        $("#regional_"+ID).hide();
        $("#english_"+ID).show();
        $("#regional_"+ID).show();
        $("#weightable"+ID).show();
        $("#category"+ID).show();
        $("#comission_"+ID).show();
        }).change(function()
        {
            var ID=$(this).attr('id');
            setTimeout(function() 
            {
                var english=$("#english_"+ID).val();
                var regional=$("#regional_"+ID).val();
                var weightable=$("#weightable"+ID).val();
                var comission=$("#comission_"+ID).val();
                var discount=$("#discount_"+ID).val();
                if(weightable=== undefined)
                {
                    weightable="No";
                }
                var kitchen=$("#kitchen"+ID).val();

                if(kitchen=== undefined)
                {
                    kitchen=0;
                }

                if(comission=== undefined)
                {
                    comission=0;
                }

                if(discount=== undefined)
                {
                    discount=0;
                }

                if(comission>100)
                {
                    comission=0;
                }

                var category=$("#category"+ID).val();
                var dataString = 'product_id='+ ID +'&english='+english+'&regional='+regional+'&weightable='+weightable+'&category='+category+'&discount='+discount+'&kitchen='+kitchen+'&comission='+comission+'&id='+<?php echo $_SESSION['login_id']; ?>+'&d_id='+<?php echo $_SESSION['d_id']; ?>;
                if(english.length>0)
                {

                    $.ajax({
                        type: "POST",
                        url: "../sql/update_product.php",
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
                                    var datastr = 'd_id='+<?php echo $_SESSION['d_id']; ?>+'&id='+<?php echo $_SESSION['login_id']; ?>+'&notification=Product updated successfully &priority=3 &device_name='+<?php echo '"'.$_SESSION['device_name'].'"'; ?>;
                                    $.ajax({
                                        type: "POST",
                                        url: "../dashboard/notification_insert.php",
                                        data: datastr,
                                        cache: false,
                                        success: function(data)
                                        {
                                            // alert(data);
                                        }
                                        });
                                break;
                                case "2":
                                    showNotification("alert-danger","Duplicate record not allowed", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                                    $("#english_"+ID).val(name);
                                    $("#regional_"+ID).val(mobile);
                                break;                    
                            }
                        }
                    });
                }
                else
                {
                    showNotification("alert-danger","All fields are mandatory and add proper data", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                }
            },2000);
        });

        $(".editbox").mouseup(function() 
        {
            return false
        });

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

        if (
            (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    $('input#add').on('keyup', function() {
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
   </script>

<script src="../plugins/sweetalert/sweetalert.min.js"></script>

<script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

   