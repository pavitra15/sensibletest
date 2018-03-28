<?php
    session_start();
    include('../connect.php');
    if(is_ajax())
    {
        $dtm="";
        $username=$_POST['email'];
        $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
        $clone= clone $date;
        $current_date=$clone->format( 'Y-m-d' ); 
        $select_query=$db->prepare("select * from login_mst where username='$username'");
        $select_query->execute();
        $count=$select_query->rowCount();
        if ($count==1)
        {
             if(is_numeric($username))
             {
                $_SESSION['mobile']=$username;
                echo 1;
             }
        }
        else
        {
           echo 2;
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