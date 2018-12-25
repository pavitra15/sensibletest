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
    include('../connect.php');
?>
    <table class="table table-striped" id="mainTable">
        <thead>
            <tr>
                <th>English Name</th>
                <th>Category</th>    
                <th>Current Stock</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody id="tab_id">
            <?php
                $d_id=$_POST['d_id'];
                $status="active";
                if(isset($_POST['page']))
                {
                    $page=$_POST['page'];
                }
                else
                {
                    $page=1;
                }
                $limit=30;
                $start_from = ($page-1) * $limit;
                $total_query=$db->prepare("select product.product_id from product,price_mst, stock_mst where status='$status' and deviceid='$d_id' and product.product_id= price_mst.product_id and product.product_id=stock_mst.product_id");
                $total_query->execute();
                $total_records=$total_query->rowCount();    
    			$total_pages = ceil($total_records / $limit);
                $query=$db->prepare("select product.product_id, english_name,regional_name, weighing,category_id,current_stock, price1 from product,price_mst, stock_mst where status='$status' and deviceid='$d_id' and product.product_id = price_mst.product_id and product.product_id=stock_mst.product_id LIMIT $start_from, $limit");
                $query->execute();
                while($data=$query->fetch())
                {
                    $product_idc=$data['product_id'];
                    $category_query=$db->prepare("select * from product, category_dtl where category_dtl.category_id=product.category_id and product_id='$product_idc'");
                    $category_query->execute();
                    if($cate=$category_query->fetch())
                    {
                        do
                        {
                            $category=$cate['category_name'];
                        }
                        while($cate=$category_query->fetch());
                    }

                    echo'<tr>
                        <td>'.$data['english_name'].'</td>
                        <td>'.$category.'</td>
                        <td>'.$data['current_stock'].'</td>
                        <td>'.$data['price1'].'</td>
                    </tr>';
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
        $(document).ready(function() 
        {         
            $('.gen').click(function()
            {
                var page=$(this).text();
                var d_id= <?php echo $d_id; ?>;
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: 'product_data.php',
                    data: { "page":page,"d_id":d_id },
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
                var d_id= <?php echo $d_id; ?>;
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: 'product_data.php',
                    data: { "page":page,"d_id":d_id },
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
                var d_id= <?php echo $d_id; ?>;
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax(
                {
                    type: 'POST',
                    url: 'product_data.php',
                    data: { "page":page,"d_id":d_id },
                    cache: false,
                    success: function(data)
                    {
                        $('.page-loader-wrapper').hide();
                        $('#data-display').html(data);
                    }
                });
            });
        });
   </script>

<script src="../plugins/sweetalert/sweetalert.min.js"></script>

<script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
<script src="../js/pages/ui/notifications.js"></script>

   