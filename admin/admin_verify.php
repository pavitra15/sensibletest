<script>
    if (!!window.performance && window.performance.navigation.type === 2) 
    {
        window.location.reload(); 
    }
</script>
<?php
	include('../connect.php');
    if (!isset($_SESSION['login_id']))
    {
         echo '<script>window.location="login";</script>';
    }
    else 
    {
        if ($_SESSION['user_type']=='admin') 
        {
           
        }
        else 
        {
            echo '<script>window.location="login";</script>';
        }         
    }           
?>