<?php
    session_start();
    if(isset($_SESSION['login_id']))
    {
        include('../connect.php');
        $flag=$_SESSION['flag'];
        $id=$_SESSION['login_id'];
        $status='active';
        $query=$db->prepare("select * from device where id='$id'");
        $query->execute();
        $count=$query->rowCount();
        if($count==0)
        {
            $_SESSION['flag']=11;
            echo '<script>window.location="../cumulative/register";</script>';
        }
        elseif($count==1)
        {
            if($data=$query->fetch())
            {
                do
                {
                    $d_id=$data['d_id'];
                    $deviceid=$data['deviceid'];
                    $device_type=$data['device_type'];
                }
                while($data=$query->fetch());
                $_SESSION['d_id']=$d_id;
                $_SESSION['device_type']=$device_type;
                echo '<script>window.location="../dashboard/index";</script>';
            }
        }
        else
        {
            echo '<script>window.location="../cumulative/index";</script>';
        }
    }
    else
    {
        echo '<script>window.location="../login/signin";</script>';
    }
?>

