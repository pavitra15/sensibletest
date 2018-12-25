<style type="text/css">
   li {
  list-style-type: none;
}
</style>
<?php
    include('../../connect.php');
    $id=$_SESSION['login_id'];
    $count_query=$db->prepare("select notification_id, notification, device_name, notification_time from notification_mst where user_id='$id' and see='0'");
    $count_query->execute();
    $notification_count=$count_query->rowCount();
?>

    <li class="dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" id="notice_alert">
            <i class="material-icons">notifications</i>
            <?php
                if($notification_count>0)
                {
            ?>
                 <span class="label-count" id="id_notification"><?php echo $notification_count; ?></span>
            <?php
                }
            ?>                                                                                                                                                                            
        </a>
        <ul class="dropdown-menu">
                <li class="header">NOTIFICATIONS</li>
                <li class="body">
                    <ul class="menu">
                        <?php
                            if($notification_data=$count_query->fetch())
                            {
                                
                                do
                                {
                                    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
                                    $log_date=$date->format('Y-m-d H:i:s');
                                    $date1=date_create($log_date);
                                    $date2=date_create($notification_data['notification_time']);
                                    $hourdiff = round((strtotime($log_date) - strtotime($notification_data['notification_time']))/3600, 1);

                                    if($hourdiff>24)
                                    {
                                        $diff=date_diff($date2,$date1);
                                        $show_time = $diff->format("%R%a days");
                                    }
                                    else
                                    {
                                         $diff=date_diff($date2,$date1);
                                         $show_time = $diff->format("%R%h hours");   
                                    }
                                    echo'<li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-cyan">
                                                <i class="material-icons">build</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>'.$notification_data['notification'].'</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i>'.$show_time.'
                                                </p>
                                                <p>
                                                    <i class="material-icons">important_devices</i> '.$notification_data['device_name'].'
                                                </p>
                                            </div>
                                        </a>
                                    </li>';
                                }
                                while ($notification_data=$count_query->fetch());
                            }
                            $query=$db->prepare("select notification_id, notification, device_name, notification_time from notification_mst where user_id='$id' and see='1' order by notification_id DESC LIMIT 5");
                            $query->execute();
                            if($data=$query->fetch())
                            {
                                
                                do
                                {
                                    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
                                    $log_date=$date->format('Y-m-d H:i:s');
                                    $date1=date_create($log_date);
                                    $date2=date_create($data['notification_time']);
                                    $hourdiff = round((strtotime($log_date) - strtotime($data['notification_time']))/3600, 1);

                                    if($hourdiff>24)
                                    {
                                        $diff=date_diff($date2,$date1);
                                        $show_time = $diff->format("%R%a days");
                                    }
                                    else
                                    {
                                         $diff=date_diff($date2,$date1);
                                         $show_time = $diff->format("%R%h hours");   
                                    }
                                    echo'<li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-cyan">
                                                <i class="material-icons">build</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>'.$data['notification'].'</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i>'.$show_time.'
                                                </p>
                                                <p>
                                                    <i class="material-icons">important_devices</i> '.$data['device_name'].'
                                                </p>
                                            </div>
                                        </a>
                                    </li>';
                                }
                                while ($data=$query->fetch());
                            }

                       ?>
                        
                    </ul>
                </li>
                <li class="footer">
                                <a href="javascript:void(0);">View All Notifications</a>
                            </li>
            </ul>
        </li>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).on('click', '#notice_alert', function () 
            {
                $.ajax
                ({
                    type: "POST",
                    url: "../notification/notification_hide.php",
                    data:'user_id='+<?php echo $_SESSION['login_id']; ?>,
                    success: function(data)
                    {
                        $("#id_notification").hide();
                    }
                });
            });
        </script>

        <script src="../../plugins/jquery/jquery.min.js"></script>


    
    <script src="../../js/pages/ui/notifications.js"></script>

    <!-- Demo Js -->
    <script src="../../js/demo.js"></script>

