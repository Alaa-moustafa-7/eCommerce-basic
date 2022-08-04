<?php include 'init.php'; ?>
       
<div class="container">
    

    <?php
        if(isset($_GET['name'])){
        $tag = $_GET['name'];
        echo "<h1 class='text-center'> $tag  </h1>";
        $tagitem = getAllFrom("*", "items", "where tags like '%$tag%'", "AND Approve = 1", "item_ID");
        foreach($tagitem as $item){
            echo '<div class="col-sm-6 col-sm-3">';
                echo '<div class="thumbnail item-box">';
                    echo '<span class="price-tag">' . $item['Price'] . '</span>';
                    echo '<img class="img-responsive" src="images.png" alt="" />'; 
                    echo '<div class="caption">';
                        echo '<h3><a href="items.php?itemid='. $item['item_ID'] . '">' . $item['Name'] . '</a></h3>';                    
                        echo '<p>' . $item['Description'] . '</p>';
                        echo '<div class="date">' . $item['Add_Date'] . '</div>';   
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }
    } else {
        echo "You Msut Add Page ID";
    }
    
    ?>
</div>
        
<?php include  $tpl . 'footer.php'; ?>
