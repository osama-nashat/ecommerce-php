<?php 

    ob_start();
    session_start();
    $pageTitle = 'Home';
    include "init.php"; 
    
?>


<div class="container">
        <h1 class="text-center">All Items</h1>
        <div class="row">
            <?php 
            $allItem = ultimateGet('*','items','WHERE Item_Approve = 1','Item_ID','DESC');
            foreach($allItem as $item){
                echo '<div class="col-sm-6 col-md-4 col-lg-3">';
                    echo '<div class="thumbnail item-box">';
                        echo '<span class="price-tag">' . $item['Item_Price'] . '</span>';
                        echo '<img class="image-responsive" src="image.png" alt="">';
                        echo '<div class="caption">';
                            echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] . '">' . $item['Item_Name'] . '</a></h3>';
                            echo '<p>' . $item['Item_Description'] . '</p>';
                            echo '<div class="date">' . $item['Item_Add_Date'] . '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>



<?php
    include $tpl . 'footer.php';
    ob_end_flush();
?>