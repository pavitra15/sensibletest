<?php
include("../connect.php");
if($_POST['id'])
{
	$date=date('Y-m-d');
	$user_id=$_POST['user_id'];
	$id=$_POST['id'];
	$english=$_POST['english'];
	$assame=$_POST['assame'];
	$bangla=$_POST['bangla'];
	$gujarati=$_POST['gujarati'];
	$hindi=$_POST['hindi'];
	$kannada=$_POST['kannada'];
	$malayalam=$_POST['malayalam'];
	$marathi=$_POST['marathi'];
	$oriya=$_POST['oriya'];
	$punjabi=$_POST['punjabi'];
	$tamil=$_POST['tamil'];
	$telugu=$_POST['telugu'];
	$query=$db->prepare("update product_mst set english='$english', assame='$assame', bangla='$bangla', gujarati='$gujarati',hindi='$hindi', kannada='$kannada', malayalam='$malayalam', marathi='$marathi',oriya='$oriya', punjabi='$punjabi', tamil='$tamil', telugu='$telugu', updated_by_date='$date', updated_by_id='$user_id' where product_id='$id'");
	echo "update product_mst set english='$english', assame='$assame', bangla='$bangla', gujarati='$gujarati',hindi='$hindi', kannada='$kannada', malayalam='$malayalam', marathi='$marathi',oriya='$oriya', punjabi='$punjabi', tamil='$tamil', telugu='$telugu', updated_by_date='$date', updated_by_id='$user_id' where product_id='$id'";
	$query->execute();
}
?>