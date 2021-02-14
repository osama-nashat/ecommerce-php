<?php 

    ob_start();
    session_start();
    $pageTitle = 'Show Item';
    include "init.php"; 

    //check if the get request itemid is numeric then sign it value to the variable $itemid 
    $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0;
            
    //select all the data from the database depend on this ID
    $stmt = $db_cont->prepare("SELECT
                                    items.*,
                                    categories.Cat_Name,
                                    users.Username
                                FROM
                                    items
                                INNER JOIN
                                    categories
                                ON
                                    categories.Cat_ID = items.Cat_ID
                                INNER JOIN
                                    users
                                ON
                                    users.UserID = items.User_ID
                                WHERE
                                    Item_ID = ?
                                AND
                                    Item_Approve = 1");


    $stmt->execute(array($itemid));
    $count = $stmt->rowCount();

    // check if there is an item with this id
    if($count > 0){

    //fetch the data into item variable to use it inside the page later
    $item = $stmt->fetch();

       
?> 
   
    <h1 class="text-center">Show Item</h1>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img class="image-responsive img-thumbnail center-block" src="image.png" alt="">
            </div>
            <div class="col-md-9 item-info">
                <h2><?php echo $item['Item_Name']; ?></h2>
                <p><?php echo $item['Item_Description']; ?></p>
                <ul class="list-unstyled">
                    <li><i class="fa fa-calendar fa-fw"></i><span>Added Date</span> : <?php echo $item['Item_Add_Date']; ?></li>
                    <li><i class="fa fa-money fa-fw"></i><span>Price</span> : $<?php echo $item['Item_Price']; ?></li>
                    <li><i class="fa fa-building fa-fw"></i><span>Made In</span> : <?php echo $item['Item_Country_Made']; ?></li>
                    <li><i class="fa fa-tags fa-fw"></i><span>Category</span> : <a href="categories.php?catid=<?php echo $item['Cat_ID'] ?>&catname=<?php echo $item['Cat_Name'] ?>"><?php echo $item['Cat_Name'] ?></a></li>
                    <li><i class="fa fa-user fa-fw"></i><span>Added By</span> : <a href="#"><?php echo $item['Username'] ?></a></li>
                </ul>
            </div>
        </div>
        <hr class="custom-hr">
        <?php if(isset($_SESSION['user'])){ ?>
            <!-- start add comment section -->
            <div class="row">
                <div class="col-md-offset-3">
                    <div class="add-comment">
                        <h3>Add Your Comment</h3>
                        <form action="items.php?itemid=<?php echo $itemid ?>" method="post">
                            <textarea name="comment-text" required></textarea>
                            <input class="btn btn-primary" name="add-comment" type="submit" value="add comment"> 
                        </form>
                        <?php
                            if(isset($_POST['add-comment'])){

                                $com_userid = $_SESSION['UserID'];
                                $comment_text = filter_var($_POST['comment-text'],FILTER_SANITIZE_STRING);
                    
                                if(!empty($comment_text)){

                                    $com = $db_cont->prepare("INSERT INTO comments (Comment, Item_ID, User_ID, C_Date) VALUES (?,?,?,now())");
                                    $com->execute(array($comment_text,$itemid,$com_userid));

                                }

                                if($com){
                                    echo '<div class="alert alert-success">Comment Added</div>';
                                }

                               
                            }
                        ?>
                    </div> 
                </div>
            </div>
            <!-- end add comment section -->
        <?php }else{
            echo 'Login/Register To Add Comment';
        } ?>
        <hr class="custom-hr">

        <?php
            $getcom = $db_cont->prepare("SELECT
                                            comments.*,users.Username
                                        FROM 
                                            comments
                                        INNER JOIN
                                            users
                                        ON
                                            users.UserID = comments.User_ID
                                        WHERE
                                            Item_ID = ?
                                        AND
                                            C_Status = 1
                                        ORDER BY
                                            C_ID DESC");

            $getcom->execute(array($itemid));
            $itemComments = $getcom->fetchAll();
        ?>

        <?php
        
        if(!empty($itemComments)){
            foreach($itemComments as $comment){
                echo '<div class="comment-box">';
                    echo '<div class="row">';
                        echo '<div class="col-md-2 text-center">';
                            echo '<img class="image-responsive img-thumbnail img-circle center-block" src="image.png" alt="">';
                            echo $comment['Username'] ;
                        echo '</div>';
                        echo '<div class="col-md-10">';
                            echo '<p class="lead">' . $comment['Comment'] . '</p>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
                echo '<hr class="custom-hr">';
            }
        }else{
            echo 'There Is No Comments To Show';
        }
        
        
        ?>
    </div>

<?php


    }else{
        echo '<div class="container">';
            echo '<div class="alert alert-danger">there is no such id or this item is waiting approval</div>';
        echo '</div>';
    }









    include $tpl . 'footer.php';
    ob_end_flush();
?>