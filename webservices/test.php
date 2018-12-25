<?php
	session_start();
	echo "data".$_SESSION['execution'];
	$_SESSION['execution']=1;
	echo "sachin".$_SESSION['execution'];
?>