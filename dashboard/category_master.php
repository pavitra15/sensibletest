<?php
    session_start();
    include("../connect.php");
    if(!empty($_POST["keyword"])) 
    {
        $keyword=$_POST["keyword"];
        $query=$db->prepare("select category_name from category_mst where status='active' and category_name LIKE'" . $keyword . "%' order by category_name");
        $query->execute();
        if($data=$query->fetch()) 
        {
?>
            <ul id="country-list">
<?php
            do 
            {
?>
                <li onClick="selectCountry('<?php echo $data["category_name"]; ?>');"><?php echo $data['category_name'];?></li>
<?php 
            }
            while($data=$query->fetch()); 
?>
            </ul>
<?php 
        } 
    } 
?>