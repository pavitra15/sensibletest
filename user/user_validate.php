<script>
    if (!!window.performance && window.performance.navigation.type === 2) 
    {
        window.location.reload(); 
    }
</script>
<?php
    if (!isset($_SESSION['login_id']))
    {
        echo '<script >window.location="../logout.php";</script>';
    }
    else 
    {
        if ($_SESSION['user_type']=='user') 
        {
        }
        else 
        {
            echo '<script >window.location="../logout.php";</script>';
        }         
    }
?>