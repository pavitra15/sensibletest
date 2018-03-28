
<?php 
include('../connect.php');

  
  $state_id=$_POST['state_id'];
  
    $city_query=$db->prepare("select city_id, city_name,city_code from city_mst where state_id='$state_id' and status='active'");
   
    $city_query->execute();
   
    if($dat=$city_query->fetch())
    {
      do
        {
               
                                       
            echo'<option value="'.$dat['city_code'].'_'.$dat['city_id'].'">'.$dat['city_name'].' </option>';
        }
          while($dat=$city_query->fetch());
        }
    
?> 
search_Cities.php
Displaying search_Cities.php.