<?php
    session_start();
    include('../connect.php');
    if(is_ajax())
    {
        $dtm="";
        $mobile=$_SESSION['mobile'];
        $otp=$_POST['otp'];
        $query=$db->prepare("select * from temp_verification where otp='$otp' and mobile='$mobile'");
        $query->execute();
        $count=$query->rowCount();
        if($count==1)
        {
            while ($data=$query->fetch())
            {
                $dtm=$data['dtm'];
            }  
            $current=date('Y-m-d h:i:s');
            $first=strtotime($current);
            $second=strtotime($dtm);
            if(($first-$second)<300)
            {       
                try
                {
                    $db->beginTransaction();
                    $status="active";
                    $verify="yes";
                    $date=date('Y-m-d');
                    $query=$db->prepare("update login_mst set username_verified='$verify', access_control='$status', status='$status',status_change_date='$date' where username='$mobile'");
                    if($query->execute())
                    {
            
                        $sth=$db->prepare("delete from temp_verification where mobile='$mobile'");
                        $sth->execute();
                        $sh=$db->prepare("select * from login_mst where username='$mobile'");
                        $sh->execute();
                        if($data=$sh->fetch())
                        {
                            do
                            {
                                $id=$_SESSION['login_id']=$data['id'];
                                $_SESSION['user_type']=$data['type'];
                            }
                            while($data=$sh->fetch());   
                        }
                        $my_query=$db->prepare("select first_name, last_name, email from user_mst where id='$id'");
                        $my_query->execute();
                        while ($user_data=$my_query->fetch()) 
                        {
                            $_SESSION['name']=$user_data['first_name']." ".$user_data['last_name'];
                            $_SESSION['email']=$user_data['email'];
                        }
                    }
                    if($db->commit())
                    {
                        echo 1;
                    }
                    
                }
                catch(Exception $e)
                {
                    // echo $e;
                    echo 2;
                    $db->rollBack();
                }
            }
            else
            {
                echo 3;
            }
        }
        else
        {
            echo 4;
        }
    }
    else
    {
        echo "Something goes wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
?>