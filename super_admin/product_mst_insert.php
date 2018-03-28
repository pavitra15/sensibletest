<?php
  include('../connect.php');
if(isset($_POST["english"]))
{
  $date=date('Y-m-d');
  $id=$_POST['id'];
   $category_id=$_POST['types'];
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
  $telugu=$_POST['telugu'];$status="active";
   for($count = 0; $count<count($english); $count++)
   {
    $category_id_clean = $category_id[$count]; 
    $english_clean = $english[$count]; 
     $assame_clean = $assame[$count]; 
      $bangla_clean = $bangla[$count]; 
      $gujarati_clean = $gujarati[$count]; 
       $hindi_clean = $hindi[$count]; 
        $kannada_clean = $kannada[$count]; 
         $malayalam_clean = $malayalam[$count]; 
          $marathi_clean = $marathi[$count]; 
           $oriya_clean = $oriya[$count]; 
            $punjabi_clean = $punjabi[$count]; 
             $tamil_clean = $tamil[$count]; 
             $telugu_clean = $telugu[$count]; 
    if($english_clean=="" && $assame_clean=="" && $bangla_clean=="" && $gujarati_clean=="" && $hindi_clean=="" && $kannada_clean=="" && $malayalam_clean=="" && $marathi_clean=="" && $oriya_clean=="" && $punjabi_clean=="" && $tamil_clean=="" && $telugu_clean=="")
    {
    }
    else
    {
      $query=$db->prepare("insert into product_mst (category_id,english, assame, bangla, gujarati, hindi,kannada, malayalam, marathi,oriya, punjabi, tamil,telugu, status, created_by_date, created_by_id) values('$category_id_clean','$english_clean', '$assame_clean', '$bangla_clean','$gujarati_clean', '$hindi_clean','$kannada_clean', '$malayalam_clean', '$marathi_clean', '$oriya_clean','$punjabi_clean', '$tamil_clean', '$telugu_clean', '$status' , '$date', '$id')");
        $query->execute();
        $sk=$query->rowCount();
    }
    }
   }
?>