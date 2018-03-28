<?php

include('../connect.php');
if(isset($_POST["category_name"]))
{
  $status="active";
  $date=date('Y-m-d');
  $id=$_POST['id'];
  $category_name = $_POST["category_name"];
  $push_query=$db->prepare("select * from category_mst where status='active'");
    $push_query->execute();
    if($data=$push_query->fetch())
    {
      do
      {
        $exists_arr[]=$data['category_name'];
      }
      while($data=$push_query->fetch());
    }

   for($count = 0; $count<count($category_name); $count++)
   {
    $category_name_clean = $category_name[$count];
    if($category_name_clean=="")
    {
    }
    else
    {
      if(in_array($category_name_clean, $exists_arr))
      {

      }
      else
      {
      $query=$db->prepare("insert into category_mst(category_name, status, created_by_date, created_by_id) values('$category_name_clean', '$status' , '$date', '$id')");
      echo "insert into category_dtl(category_name, status, created_by_date, created_by_id) values('$category_name_clean', '$status' , '$date', '$id')";
        $query->execute();
      }
    }
    }
   }
?>