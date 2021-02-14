<?php 

    session_start();
    $pageTitle = 'New Ad';
    include "init.php"; 

    if(isset($_SESSION['user'])){

        if(isset($_POST['publish'])){

            $formErrors = array();

            // but the form values into variables after sanitize them

            $name       = filter_var($_POST['item-name'],FILTER_SANITIZE_STRING);
            $desc       = filter_var($_POST['item-description'],FILTER_SANITIZE_STRING);
            $price      = filter_var($_POST['item-price'],FILTER_SANITIZE_NUMBER_INT);
            $country    = filter_var($_POST['item-country'],FILTER_SANITIZE_STRING);
            $status     = filter_var($_POST['item-status'],FILTER_SANITIZE_NUMBER_INT);
            $category   = filter_var($_POST['item-category'],FILTER_SANITIZE_NUMBER_INT);

            // validate all these variables

            if(strlen($name) < 4){
                $formErrors[] = 'item name must be at least 4 chars';
            }

            if(strlen($desc) < 10){
                $formErrors[] = 'item description must be at least 10 chars';
            }

            if(strlen($country) < 2){
                $formErrors[] = 'item country must be at least 2 chars';
            }

            if(empty($price)){
                $formErrors[] = 'item price cant be empty';
            }

            if(empty($status)){
                $formErrors[] = 'item status cant be empty';
            }

            if(empty($category)){
                $formErrors[] = 'iitem category cant be empty';
            }



            if(empty($formErrors)){

                $stmt = $db_cont->prepare("INSERT INTO items (Item_Name, Item_Description, Item_Price, Item_Country_Made, Item_Status, User_ID, Cat_ID, Item_Add_Date) VALUES (?,?,?,?,?,?,?,now())"); 
                $stmt->execute(array($name,$desc,$price,$country,$status,$_SESSION['UserID'],$category));

                //echo success message
                if($stmt){
                    $successMsg = 'Item Has Been Added';
                }
    
    
            }
            
        }

?> 
   
<h1 class="text-center">Create New Ad</h1>

<div class="create-ad block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Create New Ad</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <form action="newad.php" method="post" class="form-horizontal"> 

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Item Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="item-name" class="form-control"  required="required" placeholder="item name">
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10">
                                    <input type="text" name="item-description" class="form-control"  required="required" placeholder="item description">
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Price</label>
                                <div class="col-sm-10">
                                    <input type="text" name="item-price" class="form-control" placeholder="item price">
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Country</label>
                                <div class="col-sm-10">
                                    <input type="text" name="item-country" class="form-control" placeholder="country of made">
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="item-status">
                                        <option value="0">...</option>
                                        <option value="1">New</option>
                                        <option value="2">Like New</option>
                                        <option value="3">Used</option>
                                        <option value="4">Old</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Category</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="item-category">
                                        <option value="0">...</option>
                                        <?php
                                        
                                        $cats = ultimateGet('*','categories','','Cat_ID','ASC');

                                        foreach($cats as $cat){
                                            echo '<option value="' . $cat['Cat_ID'] . '">' . $cat['Cat_Name'] . '</option>';
                                        }
                                        
                                        ?>
                                    </select>
                                </div>
                            </div>
                                            
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" name="publish" value="Add Item" class="btn btn-primary btn-lg">
                                </div>
                            </div>
                                        
                                    
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail item-box">
                            <span class="price-tag">0</span>
                            <img class="img-responsive" src="image.png" alt="">
                            <div class="caption">
                                <h3>Title</h3>
                                <p>Description</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- start looping through errors array -->
                <?php
                    if(!empty($formErrors)){
                        foreach($formErrors as $error){
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }
                    }

                    if(isset($successMsg)){
                        echo '<div class="alert alert-success">' . $successMsg . '</div>';
                    }
                ?>
                <!-- end looping through errors array -->
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
?>