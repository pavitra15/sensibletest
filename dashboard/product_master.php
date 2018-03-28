<?php
    session_start();
    include("../connect.php");
    if(!empty($_POST["keyword"])) 
    {
        $keyword=$_POST["keyword"];
        $device_language=$_SESSION['device_language'];
        $query=$db->prepare("select english, ". $device_language.", bucket, product_id from product_mst where status='active' and english LIKE'" . $keyword . "%' order by english");
        $query->execute();
        if($data=$query->fetch()) 
        {
?>
            <ul id="country-list">
<?php
            do 
            {
?>
                <li onClick="selectCountry('<?php echo $data["english"]; ?>','<?php echo $data[$device_language]; ?>','<?php echo $data["bucket"]; ?>','<?php echo $data["product_id"]; ?>');"><?php echo $data['english'];?></li>
<?php 
            }
            while($data=$query->fetch()); 
?>
            </ul>
<?php 
        } 
    } 
?>