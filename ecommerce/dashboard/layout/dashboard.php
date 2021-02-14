<?php

session_start();

if(isset($_SESSION['username'])){

    
    $pageTitle = "dashboard";
    include "init.php"; 



    //start dashboard page
    ?>

    <div class="container home-stats text-center">
        <h1>Dashboard</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="stat st-members">
                    <i class="fa fa-users"></i>
                    <div class="info">
                        Total Members
                        <span><a href="members.php"><?php echo calcItems("UserID", "users" , "GroupID", 0, "true"); ?></a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-pending">
                    <i class="fa fa-user-plus"></i>
                    <div class="info">
                        Pending Members
                        <span><a href="members.php?action=manage&page=pending"><?php echo calcItems("UserID", "users" , "RegStatus", 0, "true"); ?></a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-items">
                    <i class="fa fa-tag"></i>
                    <div class="info">
                        Total Items
                        <span><a href="items.php"><?php echo calcItems("Item_ID", "items" , "", 0, "false"); ?></a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-comments">
                    <i class="fa fa-comments"></i>
                    <div class="info">
                        Total Comments
                        <span><a href="comments.php"><?php echo calcItems("C_ID", "comments" , "", 0, "false"); ?></a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container latest">
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <?php $numUsers = 5; //the number of latest users to show?>
                    <div class="panel-heading">
                        <i class="fa fa-users"></i> Latest <?php echo $numUsers; ?> Registered Users
                    </div>
                    <div class="panel-body">

                        <ul class="list-unstyled latest-users">
                            <?php 

                                $latestUser = getLatest("*" , "users" , "UserID" , $numUsers); //latest users array

                                foreach($latestUser as $user){
                                    echo '<li>'. $user['Username'] . '<a href="members.php?action=edit&userid=' . $user['UserID'] . '"><span class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit</span></a></li>';
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <?php $numItems = 5; //the number of latest users to show?>
                    <div class="panel-heading">
                        <i class="fa fa-tag"></i> Latest <?php echo $numItems; ?> Items
                    </div>
                    <div class="panel-body">
                    
                        <ul class="list-unstyled latest-users">
                            <?php 

                                $latesItems = getLatest("*" , "items" , "Item_ID" , $numItems); //latest items array

                                foreach($latesItems as $item){
                                    echo '<li>'. $item['Item_Name'] . '<a href="items.php?action=edit&itemid=' . $item['Item_ID'] . '"><span class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit</span></a></li>';
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <?php
    //end dashboard page
    

    include $tpl . 'footer.php';

}else{

    header('Location:index.php');
    exit();
}



?>