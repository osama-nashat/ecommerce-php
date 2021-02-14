<?php 

    ob_start();
    session_start();
    $pageTitle = 'Profile';
    include "init.php"; 

    if(isset($_SESSION['user'])){

        $getUser = $db_cont->prepare("SELECT * FROM users WHERE Username = ?");
        $getUser->execute(array($_SESSION['user']));
        $info = $getUser->fetch();
?> 
   
<h1 class="text-center">My Profile</h1>

<div class="information block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">
                <ul class="list-unstyled">
                    <?php 
                    echo '<li><i class="fa fa-unlock-alt fa-fw"></i><span>Login Name</span>'. ': ' . $info['Username'] . '</li>';
                    echo '<li><i class="fa fa-envelope-o fa-fw"></i><span>Email</span>'. ': ' .  $info['Email'] . '</li>';
                    echo '<li><i class="fa fa-user fa-fw"></i><span>Full Name</span>'. ': ' . $info['FullName'] . '</li>';
                    echo '<li><i class="fa fa-calendar fa-fw"></i><span>Register Date</span>'. ': ' . $info['RegDate'] . '</li>';
                    echo '<li><i class="fa fa-tag fa-fw"></i><span>Fav Category</span>'.": ".'</li>';
                    ?>
                </ul>
                <a href="#" class="btn btn-default">Edit Information</a>
            </div>
        </div>
    </div>
</div>

<div id="my-ads" class="my-ads block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Latest Ads</div>
            <div class="panel-body">
                <?php
                if(!empty(getItems('User_ID',$info['UserID']))){
                    echo '<div class="row">';
                        foreach(getItems('User_ID',$info['UserID'],1) as $item){
                            echo '<div class="col-sm-6 col-md-4 col-lg-3">';
                                echo '<div class="thumbnail item-box">';
                                    if($item['Item_Approve'] == 0){ echo '<span class="approve-status">Waiting Approval</span>'; }
                                    echo '<span class="price-tag">$' . $item['Item_Price'] . '</span>';
                                    echo '<img class="img-responsive" src="image.png" alt="">';
                                    echo '<div class="caption">';
                                        echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] . '">' . $item['Item_Name'] . '</a></h3>';
                                        echo '<p>' . $item['Item_Description'] . '</p>';
                                        echo '<div class="date">' . $item['Item_Add_Date'] . '</div>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        }
                    echo '</div>';
                }else{
                    echo 'There Is No Ads To Show, Create <a href="newad.php">New Ad</a>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="my-comments block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Latest Comments</div>
            <div class="panel-body">
                <?php
                $stmtz = $db_cont->prepare("SELECT * FROM comments WHERE User_ID = ?");
                $stmtz->execute(array($info['UserID']));
                $comments = $stmtz->fetchAll();

                if(! empty($comments)){

                    foreach($comments as $comment){
                        echo '<p>' . $comment['Comment'] . '</p>';
                    }

                }else{
                    echo "There is No Comments To Show ";
                }
                ?>
            </div>
        </div>
    </div>
</div>


<?php

    }else{
        header('Location:login.php');
        exit();
    }
    include $tpl . 'footer.php';
    ob_end_flush();
?>