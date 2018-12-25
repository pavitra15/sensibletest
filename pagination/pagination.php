<?php 
    $adjacents = "3"; 
    $lpm1=$total_pages-1;
    if(!empty($total_pages))
    {
        if($page==1)
        {
            echo"<li class='disabled'>
                <a href='javascript:void(0);'>
                    <i class='material-icons'>chevron_left</i>
                </a>
            </li>";
        }
        else
        {
            echo"<li class='prev'>
                <a href=''>
                    <i class='material-icons'>chevron_left</i>
                </a>
            </li>";
        }
        $print=1;
        if($total_pages<7 + ($adjacents * 2))
        {
            for($i=1; $i<=$total_pages; $i++)
            {
                if($i == $page)
                {
?>
                    <li class='active gen'><a><?php echo $i;?></a></li>  
<?php
                }
                else
                {
?>
                    <li class="gen waves-effect"><a><?php echo $i;?></a></li>
<?php
                }
            }
        }
        elseif($total_pages > 5 + ($adjacents * 2))
        {
            if($page < 1 + ($adjacents * 2))        
            {
                for ($i = 1; $i < 4 + ($adjacents * 2); $i++)
                {
                    if($i == $page)
                    {
?>
                        <li class='active gen'><a><?php echo $i;?></a></li>  
<?php
                    }
                    else
                    {
?>
                        <li class="gen waves-effect"><a><?php echo $i;?></a></li>
<?php    
                    }
                }
                echo"<li><a class='waves-effect'>...</a></li>";
                echo"<li><a class='gen waves-effect'>$lpm1</a></li>";
                echo"<li><a class='gen waves-effect'>$total_pages</a></li>";      
            }
            elseif($total_pages - ($adjacents * 2) > $page && $page > ($adjacents * 2))
            {
                echo"<li><a class='gen waves-effect'>1</a></li>";
                echo"<li><a class='gen waves-effect'>2</a></li>";
                echo"<li><a class='dot'>...</a></li>";
                for ($i = $page - $adjacents; $i <= $page + $adjacents; $i++)
                {
                    if ($i == $page)
                    {
                    ?>
                        <li class='active gen'><a><?php echo $i;?></a></li>  
<?php
                    }
                    else
                    {
?>
                        <li class="gen waves-effect"><a><?php echo $i;?></a></li>
<?php    
                    }                 
                }
                echo"<li><a class='waves-effect'>...</a></li>";
                echo"<li><a class='gen waves-effect'>$lpm1</a></li>";
                echo"<li><a class='gen waves-effect'>$total_pages</a></li>";       
            }
            else
            {
                echo"<li><a class='gen waves-effect'>1</a></li>";
                echo"<li><a class='gen waves-effect'>2</a></li>";
                echo"<li><a class='dot'>...</a></li>";
                for ($i = $total_pages - (2 + ($adjacents * 2)); $i <= $total_pages; $i++)
                {
                    if ($i == $page)
                    {
                    ?>
                        <li class='active gen'><a><?php echo $i;?></a></li>  
<?php
                    }
                    else
                    {
?>
                        <li class="gen waves-effect"><a><?php echo $i;?></a></li>
<?php    
                    }
                }
            }
        }                         
    }
            if($page==$total_pages)
            {
                echo"<li class='disabled'>
                    <a href='javascript:void(0);'>
                        <i class='material-icons'>chevron_right</i>
                    </a>
                </li>";
            }
            else
            {
                echo"<li class='next'>
                    <a href='javascript:void(0);'>
                        <i class='material-icons'>chevron_right</i>
                    </a>
                </li>";
            }   
    ?>  