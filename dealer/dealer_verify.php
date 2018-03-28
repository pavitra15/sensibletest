<script>
    if (!!window.performance && window.performance.navigation.type === 2) 
    {
        window.location.reload(); 
    }
</script>
<?php
    session_start();
	include('../connect.php');
    if (!isset($_SESSION['dealer_id']))
    {
         echo '<script>window.location="logout.php";</script>';
    }
    else 
    {
        if ($_SESSION['user_type']=='dealer') 
        {
           
        }
        else 
        {
            echo '<script>window.location="logout.php";</script>';
        }         
    }           
?>