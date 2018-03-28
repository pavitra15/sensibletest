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
    <link href="../../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../../plugins/node-waves/waves.css" rel="stylesheet" />

    <link href="../../plugins/animate-css/animate.css" rel="stylesheet" />
    <link href="../../plugins/sweetalert/sweetalert.css" rel="stylesheet" />
        
    <link href="../../plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

    <link href="../../css/style.css" rel="stylesheet">
    <link href="../../css/aviator.css" rel="stylesheet">
    <link href="../../css/themes/all-themes.css" rel="stylesheet" />
<?php
    session_start();
    include('../../connect.php');
?>
    <table class="table table-striped" id="mainTable">
        <thead>
            <tr>
                <th>Category Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="tab_id">
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
                $status='active';
                $total_query=$db->prepare("select category_id from category_mst where status='$status'");
                $total_query->execute();
                $total_records=$total_query->rowCount();    
                $total_pages = ceil($total_records / $limit);
                $query=$db->prepare("select category_id, category_name from category_mst where status='$status' LIMIT $start_from, $limit");
                $query->execute();
                while($data=$query->fetch())
                {
                    echo'<tr id="'.$data['category_id'].'" class="edit_tr">
                        <td><input type="text" id="category'.$data['category_id'].'" class="cat test-input" value="'.$data['category_name'].'" maxlength="30"></td>
                        <th><button type="button" class="btn bg-red btn-xs removebutton" title="Remove this row"><i class="material-icons">delete</i></button></th>
                    </tr>';
                }
            ?>
            <tr class="insert">
                <td><input type="text" class="category_name" maxlength="30"></td>
                <th><button type="button" class="btn bg-red btn-xs removebutton" title="Remove this row"><i class="material-icons">delete</i></button></th>
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
                   <?php include('../../pagination/pagination.php'); ?>    
                </ul>
            </nav>

    <script type="text/javascript">

        $('#add').click(function()
         {
            $("tbody > tr:last").clone().appendTo("table").find("input[type='text']").val("");
            event.preventDefault();
        });

        
        $(document).ready(function() 
        {         
            $('.gen').click(function()
            {
                var page=$(this).text();
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: 'sql/category_mst_data.php',
                    data: { "page":page},
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
                var page= <?php echo $page+1;?>;
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: 'sql/category_mst_data.php',
                    data: { "page":page},
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
            $('.next').click(function(){
                var page= <?php echo $page+1;?>;
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: 'sql/category_mst_data.php',
                    data: { "page":page},
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
                $("#category"+ID).hide();
                $("#category"+ID).show();
            }).change(function()
            {
                var ID=$(this).attr('id');
                var first=$("#category"+ID).val();
                var dataString = 'category_id='+ ID +'&category_name='+first+'&id='+<?php echo $_SESSION['login_id']; ?>;
                $("#category"+ID).html(''); 
                if(first.length>0)
                {
                    $.ajax({
                        type: "POST",
                        url: "sql/update_category_mst.php",
                        data: dataString,
                        cache: false,
                        success: function(html)
                        {
                            if(html==1)
                                 showNotification("alert-info", "Record updated successfully", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                             else
                                 showNotification("alert-Danger", "Fail to update record", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                        }
                    });
                }
                else
                {
                alert('Enter something.');
                }
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


   