<?php
	include('../connect.php');
	$deviceid=$_POST['deviceid'];
	$status="active";
    $responce.='{"data":{';
    $responce.='"device_type":[{"name":"Weighing"},{"name":"Table"},{"name":"Non-Table"}],';        	
            
    $user_type_query=$db->prepare("select id, name from user_type_mst where status='$status'");
    $user_type_query->execute();
    $responce.='"user_type":[';
    if($user_type_data=$user_type_query->fetch())
    {
        do
        {
            $responce.='{"id":'.$user_type_data['id'].',"name":"'.$user_type_data['name'].'"},';
        }
        while($user_type_data=$user_type_query->fetch());
        $responce=substr($responce, 0,-1);
    }
    $responce.='],';

    $tax_type_query=$db->prepare("select tax_id, tax_type, tax_name, tax_perc from tax_mst where status='$status'");
    $tax_type_query->execute();
    $responce.='"tax_type":[';
    if($tax_type_data=$tax_type_query->fetch())
    {
        do
        {
            $responce.='{"tax_id":'.$tax_type_data['tax_id'].',"tax_type":"'.$tax_type_data['tax_type'].'","tax_name":"'.$tax_type_data['tax_name'].'","tax_perc":"'.$tax_type_data['tax_perc'].'"},';
        }
        while($tax_type_data=$tax_type_query->fetch());
        $responce=substr($responce, 0,-1);
    }
    $responce.='],';

    $unit_type_query=$db->prepare("select unit_id, unit_name, abbrevation from unit_mst where status='$status'");
    $unit_type_query->execute();
    $responce.='"unit_type":[';
    if($unit_type_data=$unit_type_query->fetch())
    {
        do
        {
            $responce.='{"unit_id":'.$unit_type_data['unit_id'].',"unit_name":"'.$unit_type_data['unit_name'].'","abbrevation":"'.$unit_type_data['abbrevation'].'"},';
        }
        while($unit_type_data=$unit_type_query->fetch());
        $responce=substr($responce, 0,-1);
    }
    $responce.='],';

    $state_query=$db->prepare("select state_id, state_name from state_mst where status='$status'");
    $state_query->execute();
    $responce.='"state":[';
    if($state_data=$state_query->fetch())
    {
        do
        {
            $responce.='{"state_id":'.$state_data['state_id'].',"state_name":"'.$state_data['state_name'].'"},';
        }
        while($state_data=$state_query->fetch());
        $responce=substr($responce, 0,-1);
    }
    $responce.='],';

    $language_query=$db->prepare("select language_id, language_name from language_mst where status='$status'");
    $language_query->execute();
    $responce.='"language":[';
    if($language_data=$language_query->fetch())
    {
        do
        {
            $responce.='{"language_id":'.$language_data['language_id'].',"language_name":"'.$language_data['language_name'].'"},';
        }
        while($language_data=$language_query->fetch());
        $responce=substr($responce, 0,-1);
    }
    $responce.=']}}';
    echo $responce;
?>