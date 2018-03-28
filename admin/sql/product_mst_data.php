<?php
    session_start();
    include('../../connect.php');
?>
    <table class="table" id="mainTable">
        <thead>
            <tr>
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>English</th>
                        <th>Bengali</th>
                        <th>Gujarati</th>
                        <th>Hindi</th>
                        <th>Kannada</th>
                        <th>Malayalam</th>
                        <th>Marathi</th>
                        <th>Punjabi</th>
                        <th>Tamil</th>
                        <th>Telugu</th>
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
                    $total_query=$db->prepare("select * from product_mst where status='$status'");
                    $total_query->execute();
                    $total_records=$total_query->rowCount();    
        			$total_pages = ceil($total_records / $limit);
                    $query=$db->prepare("select * from product_mst where status='$status' LIMIT $start_from, $limit");
                    $query->execute();
                    while($data=$query->fetch())
                    {
                        echo'<tr id="'.$data['product_id'].'" class="edit_tr">
                            <th style="max-width:15px"><button type="button" class="btn bg-red btn-xs removebutton" title="Remove this row"><i class="material-icons">delete</i></button></th>
                            <td><input type="text" id="english'.$data['product_id'].'" class="test-input cat search-box" value="'.$data['English'].'" maxlength="50"> </td>
                            <td><input type="text" id="bangla'.$data['product_id'].'" class="test-input cat search-box" value="'.$data['Bengali'].'" maxlength="50"> </td>
                            <td><input type="text" id="gujarati'.$data['product_id'].'" class="test-input cat search-box" value="'.$data['Gujarati'].'" maxlength="50"> </td>
                            <td><input type="text" id="hindi'.$data['product_id'].'" class="test-input cat search-box" value="'.$data['Hindi'].'" maxlength="50"> </td>
                            <td><input type="text" id="kannada'.$data['product_id'].'" class="test-input cat search-box" value="'.$data['Kannada'].'" maxlength="50"> </td>
                            <td><input type="text" id="malayalam'.$data['product_id'].'" class="test-input cat search-box" value="'.$data['Malayalam'].'" maxlength="50"> </td>
                            <td><input type="text" id="marathi'.$data['product_id'].'" class="test-input cat search-box" value="'.$data['Marathi'].'" maxlength="50"> </td>
                            <td><input type="text" id="punjabi'.$data['product_id'].'" class="test-input cat search-box" value="'.$data['Punjabi'].'" maxlength="50"> </td>
                            <td><input type="text" id="tamil'.$data['product_id'].'" class="test-input cat search-box" value="'.$data['Tamil'].'" maxlength="50"> </td>
                            <td><input type="text" id="telugu'.$data['product_id'].'" class="test-input cat search-box" value="'.$data['Telugu'].'" maxlength="50"> </td>
                        </tr>';
                }
            ?>
             <tr class="insert" id="1">
                <th style="max-width:15px"><button type="button" class="btn bg-red btn-xs removebutton" title="Remove this row"><i class="material-icons">delete</i></button></th>
                <td><input type="text" class="english_nm test-input cat search-box" id="english_1" value="" maxlength="50"> </td>
                <td><input type="text" class="bangla_nm test-input cat search-box" id="english_1" value="" maxlength="50"> </td>
                <td><input type="text" class="gujarati_nm test-input cat search-box" id="english_1" value="" maxlength="50"> </td>
                <td><input type="text" class="hindi_nm test-input cat search-box" id="english_1" value="" maxlength="50"> </td>
                <td><input type="text" class="kannada_nm test-input cat search-box" id="english_1" value="" maxlength="50"> </td>
                <td><input type="text" class="malayalam_nm test-input cat search-box" id="english_1" value="" maxlength="50"> </td>
                <td><input type="text" class="marathi_nm test-input cat search-box" id="english_1" value="" maxlength="50"> </td>
                <td><input type="text" class="punjabi_nm test-input cat search-box" id="english_1" value="" maxlength="50"> </td>
                <td><input type="text" class="tamil_nm test-input cat search-box" id="english_1" value="" maxlength="50"> </td>
                <td><input type="text" class="telugu_nm test-input cat search-box" id="english_1" value="" maxlength="50"> </td>
            </tr>
        </tbody>
    </table>
    <nav><ul class="pagination">
    <?php include('../../pagination/pagination.php'); ?>          
    </ul></nav>

    <script type="text/javascript">
        $(document).ready(function() 
        {         
            $('.gen').click(function()
            {
                var page=$(this).text();
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: 'sql/product_mst_data.php',
                    data: { "page":page },
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
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: 'sql/product_mst_data.php',
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
            $('.next').click(function(){
                var page= <?php echo $page+1;?>;
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax(
                {
                    type: 'POST',
                    url: 'sql/product_mst_data.php',
                    data: { "page":page },
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
                $("#english"+ID).hide();
                $("#bangla"+ID).hide();
                $("#gujarati"+ID).hide();
                $("#hindi"+ID).hide();
                $("#kannada"+ID).hide();
                $("#malayalam"+ID).hide();
                $("#marathi"+ID).hide();
                $("#punjabi"+ID).hide();
                $("#tamil"+ID).hide();
                $("#telugu"+ID).hide();
                $("#cat_types"+ID).show();
                $("#english"+ID).show();
                $("#bangla"+ID).show();
                $("#gujarati"+ID).show();
                $("#hindi"+ID).show();
                $("#kannada"+ID).show();
                $("#malayalam"+ID).show();
                $("#marathi"+ID).show();
                $("#punjabi"+ID).show();
                $("#tamil"+ID).show();
                $("#telugu"+ID).show();
            }).change(function()
            {
                var ID=$(this).attr('id');
                var english=$("#english"+ID).val();
                var bangla=$("#bangla"+ID).val();
                var gujarati=$("#gujarati"+ID).val();
                var hindi=$("#hindi"+ID).val();
                var kannada=$("#kannada"+ID).val();
                var malayalam=$("#malayalam"+ID).val();
                var marathi=$("#marathi"+ID).val();
                var punjabi=$("#punjabi"+ID).val();
                var tamil=$("#tamil"+ID).val();
                var telugu=$("#telugu"+ID).val();
                var dataString = 'id='+ ID+'&english='+english+'&bangla='+bangla+'&gujarati='+gujarati+'&hindi='+hindi+'&kannada='+kannada+'&malayalam='+malayalam+'&marathi='+marathi+'&punjabi='+punjabi+'&tamil='+tamil+'&telugu='+telugu+'&user_id=<?php echo $_SESSION['login_id']; ?>';
                if(1)
                {
                    $.ajax({
                        type: "POST",
                        url: "sql/update_product_mst.php",
                        data: dataString,
                        cache: false,
                        success: function(data)
                        {
                            // alert(data);
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

<script src="../plugins/sweetalert/sweetalert.min.js"></script>

<script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

   