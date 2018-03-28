
</script>
<?php
    session_start();
    include('../connect.php');
    if (!isset($_SESSION['login_id']))
    {

       echo '<script >window.location="../super_admin/login";</script>';
    }
    else 
    {
        if ($_SESSION['user_type']=='super_admin') 
        {
            
        }
        else 
        {
            echo '<script>window.location="../super_admin/login";</script>';
        }         
    }           
?>