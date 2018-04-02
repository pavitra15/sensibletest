<?php
    use Silex\Application;
	use Silex\Provider\TwigServiceProvider;
	use Symfony\Component\HttpFoundation\Request;
	$dsn = getenv('MYSQL_DSN');
	$user = getenv('MYSQL_USER');
	$password = getenv('MYSQL_PASSWORD');
	if (!isset($dsn, $user) || false === $password) 
	{
    	throw new Exception('Set MYSQL_DSN, MYSQL_USER, and MYSQL_PASSWORD environment variables');
	}
  	$db = new PDO($dsn, $user, $password) or die("error");

 //  	$servername = "localhost";
	// $username = "root";
	// $password = "";

	// try {
	//     $db = new PDO("mysql:host=$servername;dbname=posibill", $username, $password);
	//     // set the PDO error mode to exception
	//     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//     }
	// catch(PDOException $e)
	//     {
	//     echo "Connection failed: " . $e->getMessage();
	//    }	
 ?>
