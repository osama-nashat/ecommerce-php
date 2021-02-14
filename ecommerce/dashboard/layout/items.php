<?php

session_start();

if(isset($_SESSION['username'])){

    
    $pageTitle = "Items";
    include "init.php"; 

    $page_req = "";

    if(isset($_GET['action'])){

        $page_req = $_GET['action'];

    }else{

        $page_req = "manage";
    }





    if($page_req == 'manage'){
        


        // select all the users from the database except the admins
        
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
                                        users.UserID = items.User_ID");
        $stmt->execute();

        //fetch the data from the databse
        $items = $stmt->fetchAll();
    
        ?>
       
        <h1 class="text-center">Manage Items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Adding Date</td>
                        <td>Category</td>
                        <td>Owner</td>
                        <td>Control</td>
                    </tr>
            <?php 
                    
                foreach($items as $item){ //now $item will contain a row of the items table in every loop until there is no more rows in var $items which contain all the database rows
                    
                    echo '<tr>';
                    echo    '<td>'. $item['Item_ID']   .'</td>';
                    echo    '<td>'. $item['Item_Name'] .'</td>';
                    echo    '<td>'. $item['Item_Description']    .'</td>';
                    echo    '<td>'. $item['Item_Price'] .'</td>';
                    echo    '<td>'. $item['Item_Add_Date']  .'</td>';
                    echo    '<td>'. $item['Cat_Name']  .'</td>';
                    echo    '<td>'. $item['Username']  .'</td>';
                    echo    '<td>
                                <a href="items.php?action=edit&itemid='. $item['Item_ID'] .'" class="btn btn-success" style="margin-right:5px"><i class="fa fa-edit"></i>  Edit</a>
                                <a href="items.php?action=delete&itemid='. $item['Item_ID'] .'" class="btn btn-danger" style="margin-right:5px"><i class="fas fa-times"></i>  Delete</a>';

                                if($item['Item_Approve'] == 0){
                                    echo  '<a href="items.php?action=approve&itemid='. $item['Item_ID'] .'" class="btn btn-primary"><i class="fas fa-check"></i>  Approve</a>';
                                }

                    echo    '</td>';
                    echo '</tr>';
                }        
                    
            ?>

                </table>
            </div>
            <a href="items.php?action=add" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i>  add new item</a>
        </div>


    <?php 


    }elseif($page_req == 'add'){ ?>


        <h1 class="text-center">Add New Item</h1>

        <div class="container">
            <form action="items.php?action=insert" method="post" class="form-horizontal"> 

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
                    <label class="col-sm-2 control-label">Owner</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="item-owner">
                            <option value="0">...</option>
                            <?php
                            
                            $allMembers = ultimateGet("*","users","","UserID","DESC");

                            foreach($allMembers as $owner){
                                echo '<option value="' . $owner['UserID'] . '">' . $owner['Username'] . '</option>';
                            }
                            
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="item-category">
                            <option value="0">...</option>
                            <?php
                            
                            $allCats = ultimateGet("*","categories","WHERE Cat_Parent = 0","Cat_ID","DESC");

                            foreach($allCats as $cat){
                                echo '<option value="' . $cat['Cat_ID'] . '">' . $cat['Cat_Name'] . '</option>';

                                $childCats = ultimateGet("*","categories","WHERE Cat_Parent = {$cat['Cat_ID']}","Cat_ID","DESC");
                                foreach($childCats as $child){
                                    echo '<option value="' . $child['Cat_ID'] . '">---' . $child['Cat_Name'] . '</option>';
                                }
                            }
                            
                            ?>
                        </select>
                    </div>
                </div>
                                
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" name="save" value="Add Item" class="btn btn-primary btn-lg">
                    </div>
                </div>
                                
                            
            </form>
        </div>



    <?php

  
    }elseif($page_req == 'insert'){ //insert page

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            echo "<h1 class='text-center'>Insert Page</h1>";
    
            $name           = $_POST['item-name'];
            $description    = $_POST['item-description'];
            $price          = $_POST['item-price'];
            $country        = $_POST['item-country'];
            $status         = $_POST['item-status'];
            $owner          = $_POST['item-owner'];
            $cat          = $_POST['item-category'];

    
    
           
            
            
    
            //validate the values coming from the form
    
            $formErrors = array();
    
    
            if(empty($name)){
                $formErrors[] = 'item name cant be <strong>empty</strong>';
            }
    
    
            if(empty($description)){
                $formErrors[] = 'item description cant be <strong>empty</strong>';
            }
    
            if(empty($price)){
                $formErrors[] = 'item price cant be <strong>empty</strong>';
            }

            if(empty($country)){
                $formErrors[] = 'item country cant be <strong>empty</strong>';
            }

            if($status == 0){
                $formErrors[] = 'you have to select a <strong>status</strong>';
            }

            if($owner == 0){
                $formErrors[] = 'you have to select the <strong>owner</strong>';
            }

            if($cat == 0){
                $formErrors[] = 'you have to select the <strong>category</strong>';
            }
    
            
    
    
            //update the database with this values if the errors array is empty
            if(empty($formErrors)){

                $stmt = $db_cont->prepare("INSERT INTO items (Item_Name, Item_Description, Item_Price, Item_Country_Made, Item_Status, User_ID, Cat_ID, Item_Add_Date) VALUES (?,?,?,?,?,?,?,now())"); 
                $stmt->execute(array($name,$description,$price,$country,$status,$owner,$cat));

                //echo success message
                $Msg = $stmt->rowCount() . ' Record Inserted';
                RedirectTO($Msg , 6 , "items.php?action=manage" , "success");
    
    
            }else{
    
                //print the content of the errors array
    
                foreach($formErrors as $error){
                    echo '<div class="alert alert-danger">' . $error . "</div>";
                }
            }
    
        }else{
    
            $Msg = "sorry....you cant browse this page directly";
            RedirectTo($Msg, 6, "index.php","danger");
        }
    
       
    }elseif($page_req == 'edit'){ //edit item page

        //check if the get request itemid is numeric then sign it value to the variable $itemid 

        $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0;
            
        //select all the data from the database depend on this ID

        $stmt = $db_cont->prepare("SELECT * FROM items WHERE Item_ID = ?");
        $stmt->execute(array($itemid));

        //fetch the data into item variable to use it inside the form later
        $item = $stmt->fetch();
        $count = $stmt->rowCount();

        //if the record is exist, the edit form will appeare and this form sends the values to items.php?action=update 
        if($count > 0){?>

            <h1 class="text-center">Edit Item</h1>
            <div class="container">
                <form action="items.php?action=update" method="post" class="form-horizontal"> 

                    <input type="hidden" name="item_id" value="<?php echo $item['Item_ID'] ; ?>">

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Item Name</label>
                        <div class="col-sm-10">
                            <input type="text" name="item-name" class="form-control"  required="required" placeholder="item name" value="<?php echo $item['Item_Name']; ?>">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <input type="text" name="item-description" class="form-control"  required="required" placeholder="item description" value="<?php echo $item['Item_Description']; ?>">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10">
                            <input type="text" name="item-price" class="form-control" placeholder="item price" value="<?php echo $item['Item_Price']; ?>">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10">
                            <input type="text" name="item-country" class="form-control" placeholder="country of made" value="<?php echo $item['Item_Country_Made']; ?>">
                        </div>
                    </div>
                
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="item-status">
                                <option value="1" <?php if($item['Item_Status'] == 1){ echo 'selected'; } ?>>New</option>
                                <option value="2" <?php if($item['Item_Status'] == 2){ echo 'selected'; } ?>>Like New</option>
                                <option value="3" <?php if($item['Item_Status'] == 3){ echo 'selected'; } ?>>Used</option>
                                <option value="4" <?php if($item['Item_Status'] == 4){ echo 'selected'; } ?>>Old</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Owner</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="item-owner">
                                <?php
                                
                                $stmt8 = $db_cont->prepare("SELECT * FROM users");
                                $stmt8->execute();
                                $owners = $stmt8->fetchAll();

                                foreach($owners as $owner){
                                    echo "<option value='" . $owner['UserID'] . "'";
                                    if($item['User_ID'] == $owner['UserID']){ echo 'selected' ;}
                                    echo ">" . $owner['Username'] . "</option>";
                                }
                                
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="item-category">
                                <?php
                                
                                $stmt9 = $db_cont->prepare("SELECT * FROM categories");
                                $stmt9->execute();
                                $cats = $stmt9->fetchAll();

                                foreach($cats as $cat){
                                    echo "<option value='" . $cat['Cat_ID'] . "'";
                                    if($item['Cat_ID'] == $cat['Cat_ID']){ echo 'selected' ;}
                                    echo ">" . $cat['Cat_Name'] . "</option>";
                                }
                                
                                ?>
                            </select>
                        </div>
                    </div>
                                    
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" name="save" value="Edit Item" class="btn btn-primary btn-lg">
                        </div>
                    </div>
                                                 
                </form>
            </div>

    <?php 
    
    }else{

         $Msg = "item is not exist in our database";
         RedirectTO($Msg , 6 , "items.php?action=manage" , "danger");
     }

       
    }elseif($page_req == 'update'){ // update item page

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            echo "<h1 class='text-center'>Update Page</h1>";
            //get the variable from the edit page form

            $id             = $_POST['item_id'];
            $name           = $_POST['item-name'];
            $description    = $_POST['item-description'];
            $price          = $_POST['item-price'];
            $country        = $_POST['item-country'];
            $status         = $_POST['item-status'];
            $owner          = $_POST['item-owner'];
            $cat            = $_POST['item-category'];

            

            //validate the values coming from the form

            $formErrors = array();
    
    
            if(empty($name)){
                $formErrors[] = 'item name cant be <strong>empty</strong>';
            }
    
    
            if(empty($description)){
                $formErrors[] = 'item description cant be <strong>empty</strong>';
            }
    
            if(empty($price)){
                $formErrors[] = 'item price cant be <strong>empty</strong>';
            }

            if(empty($country)){
                $formErrors[] = 'item country cant be <strong>empty</strong>';
            }

            if($status == 0){
                $formErrors[] = 'you have to select a <strong>status</strong>';
            }

            if($owner == 0){
                $formErrors[] = 'you have to select the <strong>owner</strong>';
            }

            if($cat == 0){
                $formErrors[] = 'you have to select the <strong>category</strong>';
            }
            


            //update the database with this values if the errors array is empty
            if(empty($formErrors)){


                    $stmt = $db_cont->prepare("UPDATE items SET Item_Name = ?, Item_Description = ?, Item_Price = ?, Item_Country_Made = ?, Item_Status = ?, Cat_ID = ?, User_ID = ? WHERE Item_ID = ?"); 
                    $stmt->execute(array($name,$description,$price,$country,$status,$cat,$owner,$id));
    
                    //echo success message
                    $Msg= $stmt->rowCount() . ' updated records';
                    RedirectTO($Msg , 6 , "items.php?action=manage" , "success");

                

            }else{

                //print the content of the errors array

                foreach($formErrors as $error){
                    echo $error . "<br>";
                }
            }

           

        }else{

            $errorMsg = "sorry....you cant browse this page directly";
            RedirectTo($errorMsg, 6, "index.php");
        }

       
    }elseif($page_req == 'delete'){ // delete item page

        $stmt = $db_cont->prepare("DELETE FROM items WHERE Item_ID = :zitem");
        $stmt->bindParam(":zitem",$_GET['itemid']);
        $stmt->execute();

        $Msg = $stmt->rowCount() . " item deleted";
        RedirectTO($Msg , 6 , "items.php?action=manage" , "success");

       
    }elseif($page_req == 'approve'){ // approve item page

        $stmt3 = $db_cont->prepare("UPDATE items SET Item_Approve = 1 WHERE Item_ID = :zitem");
        $stmt3->bindParam(":zitem",$_GET['itemid']);
        $stmt3->execute();

        $Msg = $stmt3->rowCount() . "item approved";
        RedirectTO($Msg , 6 , "items.php?action=manage" , 'success');
       
    }else{
        header('Location:?action=manage');
    }
    
    
    
    
    
   

    include $tpl . 'footer.php';

}else{

    header('Location:index.php');
    exit();
}
    
    
    
    
?>