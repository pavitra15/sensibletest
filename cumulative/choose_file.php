<?php
	session_start();
    if($_SESSION['device_type']=="Table")
    {
    	echo '<script>window.location="premise";</script>';
    }
    else
    {
    	echo '<script>window.location="customer";</script>';
    } 
?>