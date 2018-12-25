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
    include('../../connect.php');
?>
    <table class="table table-striped" id="mainTable">
        <thead>
            <tr>
                <th>Serial Number</th>
                <th>Device Name</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Approve</th>
                <th>Last Login</th>
                <th>City</th>
                <th>State</th>
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
                $limit=25;
                $start_from = ($page-1) * $limit;
                $total_query=$db->prepare("SELECT admin_device.deviceid,admin_device.serial_no, device_name, last_login, mobile, first_name, last_name, status, city,state from (select T1.deviceid, T1.serial_no, device_name, first_name, last_name, mobile, last_login, city, state from(SELECT deviceid,device.serial_no, device_name, first_name, last_name, mobile from device, login_mst, user_mst where device.id=login_mst.id and user_mst.id=login_mst.id) T1 right join last_connect on T1.deviceid=last_connect.deviceid)T2 right JOIN admin_device on T2.deviceid=admin_device.deviceid");
                $total_query->execute();
                $total_records=$total_query->rowCount();    
                $total_pages = ceil($total_records / $limit);
                $query=$db->prepare("SELECT admin_device.deviceid,admin_device.serial_no, device_name, last_login, mobile, first_name, last_name, status, city,state from (select T1.deviceid, T1.serial_no, device_name, first_name, last_name, mobile, last_login, city, state from(SELECT deviceid,device.serial_no, device_name, first_name, last_name, mobile from device, login_mst, user_mst where device.id=login_mst.id and user_mst.id=login_mst.id) T1 right join last_connect on T1.deviceid=last_connect.deviceid)T2 right JOIN admin_device on T2.deviceid=admin_device.deviceid LIMIT $start_from, $limit");
                $query->execute();
                while($data=$query->fetch())
                {
                    echo'<tr>
                        <td>'.$data['serial_no'].'</td>
                        <td>'.$data['device_name'].'</td>
                        <td>'.$data['first_name'].' '.$data['last_name'].'</td>
                        <td>'.$data['mobile'].'</td>
                        <td>'.$data['status'].'</td>
                        <td>'.$data['last_login'].'</td>
                        <td>'.$data['city'].'</td>
                        <td>'.$data['state'].'</td>';
                    echo'</tr>';
                }
            ?>
        </tbody>
    </table>
            <nav>
                <ul class="pagination">
                   <?php include('../../pagination/pagination.php'); ?>    
                </ul>
            </nav>

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
                    url: 'sql/device_report_data.php',
                    data: { "page":page},
                    cache: false,
                    success: function(data)
                    {
                        $('.page-loader-wrapper').hide();
                        $('#data-display').html(data);
                    }
                });    
            });


            $('.prev').click(function()
            {
                var page= <?php echo $page+1;?>;
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: 'sql/device_report_data.php',
                    data: { "page":page},
                    cache: false,
                    success: function(data)
                    {
                        $('.page-loader-wrapper').hide();
                        $('#data-display').html(data);
                    }
                });    
            });
                 
            $('.next').click(function(){
                var page= <?php echo $page+1;?>;
                $('#data-display').show();
                $('.page-loader-wrapper').show();
                $.ajax({
                    type: 'POST',
                    url: 'sql/device_report_data.php',
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

   </script>


   