<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php getTitle(); ?></title>
    <link rel="stylesheet" href="<?php echo $css ?>bootstrap.css">
    <link rel="stylesheet" href="<?php echo $css ?>design.css">
    <script src="https://kit.fontawesome.com/9f6375ab16.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="upper-bar">
    <div class="container">
        <?php 
        if(isset($_SESSION['user'])){ ?>

            <img class="my-image img-circle img-thumbnail" src="image.png" alt="">
            <div class="btn-group my-info">
                <span class="btn dropdown-toggle" data-toggle="dropdown">
                    <?php echo $_SESSION['user'] ?>
                    <span class="caret"></span>
                    </span>
                <ul class="dropdown-menu">
                    <li><a href="profile.php">My Profile</a></li>
                    <li><a href="newad.php">New Item</a></li>
                    <li><a href="profile.php#my-ads">My Items</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>

            <?php

            $userStatus = checkUserStatus($_SESSION['user']);
            if($userStatus == 1){
                echo ' Your Membership Needs To Be Activated By The Admin';
            }
        }else{ ?>
            <a href="login.php">
                <span class="pull-right">Login</span>
            </a>
        <?php
        }?>
        
        
    </div>
</div>