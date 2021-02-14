<?php

ob_start();

session_start();

if(isset($_SESSION['username'])){

    $pageTitle = "Categories";
    include 'init.php';

    $action = "";

    if(isset($_GET['action'])){
        $action = $_GET['action'];
    }else{
        $action = 'manage';
    }



    if($action == 'manage'){//manage page

        // give a default value for the variable $sort
        $sort = 'ASC';
        // make an array of all possible values for the ordering variable ($sort)
        $array_Sort = array('ASC','DESC');
        // check if there is a get request named sort and if its value found in the previous array
        if(isset($_GET['sort']) && in_array($_GET['sort'],$array_Sort)){
            // if its true put the value of the get request in the sort variable
            $sort = $_GET['sort'];
        }

        $stmt5 = $db_cont->prepare("SELECT * FROM categories WHERE Cat_Parent = 0 ORDER BY Cat_Ordering $sort");
        $stmt5->execute();
        $cats = $stmt5->fetchAll(); ?>

        <h1 class="text-center">Manage Categories</h1>
        <div class="container categories">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Manage Categories
                    <div class="ordering pull-right">
                        Ordering : 
                        <a class="<?php if($sort == 'ASC'){ echo 'active';} ?>" href="categories.php?action=manage&sort=ASC">ASC</a> | 
                        <a class="<?php if($sort == 'DESC'){ echo 'active';} ?>" href="categories.php?action=manage&sort=DESC">DESC</a>
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    
                    foreach($cats as $cat){
                        echo '<div class="cat">';
                            echo '<div class="hidden-buttons">';
                                echo '<a href="categories.php?action=edit&catid='. $cat['Cat_ID'] .'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                                echo '<a href="categories.php?action=delete&catid='. $cat['Cat_ID'] .'" class="btn btn-xs btn-danger"><i class="fa fa-close"></i> Delete</a>';
                            echo '</div>';
                            echo '<h3>' . $cat['Cat_Name'] .'</h3>';
                            echo '<p>'; if($cat['Cat_Description'] == ""){echo "this category has no description"; }else{ echo $cat['Cat_Description']; } echo '</p>';
                            if($cat['Cat_Visibility'] == 1){echo '<span class="visibility">Hidden</span>';}
                            if($cat['Cat_Allow_Comments'] == 1){echo '<span class="commenting">Comment Disabled</span>';}
                            if($cat['Cat_Allow_Ads'] == 1){echo '<span class="advertises">Ads Disabled</span>';}
                        echo '</div>';

                        //get child categories

                        $childCats = ultimateGet("*","categories","WHERE Cat_Parent = {$cat['Cat_ID']}","Cat_ID","ASC");
                        if(!empty($childCats)){
                            
                            echo '<h4 class="child-head">Child Categories</h4>';

                            echo '<ul class="list-unstyled child-cats">';
                            foreach($childCats as $cc){
                                echo '<li><a href="categories.php?action=edit&catid='. $cc['Cat_ID'] .'">' . $cc['Cat_Name'] . '</a></li>';
                            } 
                            echo '</ul>';
                            echo '<hr>';

                        }  
                    }
                    
                    ?>
                </div>
            </div>
            <a href="categories.php?action=add" class="btn btn-primary add-category"><i class="fa fa-plus"></i> Add New Category</a>
        </div>


    
    <?php
    }elseif($action == 'add'){ // add category page ?>


        <h1 class="text-center">Add New Category</h1>

        <div class="container">
            <form action="categories.php?action=insert" method="post" class="form-horizontal"> 

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Category Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="cat-name" class="form-control" autocomplete="off" required="required" placeholder="category name">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10">
                        <input type="text" name="description" class="form-control"  placeholder="descripe the category">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10">
                        <input type="text" name="ordering" class="form-control" autocomplete="off" placeholder="number to arrange the categories">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Parent ?</label>
                    <div class="col-sm-10">
                        <select name="parent" class="form-control">
                            <option value="0">None</option>
                            <?php
                            
                            $allCat = ultimateGet('*','categories','WHERE Cat_Parent = 0','Cat_ID','ASC');
                            foreach($allCat as $oneCat){
                                echo '<option value="'. $oneCat['Cat_ID'] .'">'. $oneCat['Cat_Name'] .'</option>';
                            }

                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Visibility</label>
                    <div class="col-sm-10">
                        <div>
                            <input id="vis-yes" type="radio" name="visibility" value="0" checked>
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input id="vis-no" type="radio" name="visibility" value="1" >
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Commenting</label>
                    <div class="col-sm-10">
                        <div>
                            <input id="com-yes" type="radio" name="commenting" value="0" checked>
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input id="com-no" type="radio" name="commenting" value="1" >
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Ads</label>
                    <div class="col-sm-10">
                        <div>
                            <input id="ads-yes" type="radio" name="ads" value="0" checked>
                            <label for="ads-yes">Yes</label>
                        </div>
                        <div>
                            <input id="ads-no" type="radio" name="ads" value="1" >
                            <label for="ads-no">No</label>
                        </div>
                    </div>
                </div>
                                
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" name="save" value="Add Category" class="btn btn-primary btn-lg">
                    </div>
                </div>
                                
                            
            </form>
        </div>



    <?php

    }elseif($action == 'insert'){ //start insert category page

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            echo "<h1 class='text-center'>Insert Category</h1>";
    
            $name       =   $_POST['cat-name'];
            $desc       =   $_POST['description'];
            $parent     =   $_POST['parent'];
            $order      =   $_POST['ordering'];
            $visibile   =   $_POST['visibility'];
            $comment    =   $_POST['commenting'];
            $ads        =   $_POST['ads'];
    
            
            // check if there is a username equal to the new username
            
            if(checkItem("Cat_Name","categories",$name) == 0){

                $stmt = $db_cont->prepare("INSERT INTO categories (Cat_Name, Cat_Description,Cat_Parent, Cat_Ordering, Cat_Visibility, Cat_Allow_Comments, Cat_Allow_Ads) VALUES (?,?,?,?,?,?,?)"); 
                $stmt->execute(array($name,$desc,$parent,$order,$visibile,$comment,$ads));

                //echo success message
                $Msg = $stmt->rowCount() . ' Record Inserted';
                RedirectTO($Msg , 6 , "categories.php?action=manage" , "success");

            }else{
                // show a message and redirect to manage page

                $Msg = "This category name Is Already Used .... Try Another One";

                RedirectTO($Msg,6,"categories.php?action=manage","danger");
                
            }
    
    
        }else{
    
            $Msg = "sorry....you cant browse this page directly";
            RedirectTo($Msg, 6, "index.php","danger");
        }


    }elseif($action == 'edit'){

        
            //check if the get request catid is numeric then sign it value to the variable $cat_id 

            $cat_id = (isset($_GET['catid']) && is_numeric($_GET['catid'])) ? intval($_GET['catid']) : 0;
            
            //select all the data from the database depend on this ID

            $stmt = $db_cont->prepare("SELECT * FROM categories WHERE Cat_ID = ? LIMIT 1");
            $stmt->execute(array($cat_id));

            //fetch the data into row variable to use it inside the form later
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            //if the record is exist, the edit form will appeare and this form sends the values to categories.php?action=update 
            if($count > 0){?>

                <h1 class="text-center">Edit Category</h1>
                <div class="container">
                    <form action="categories.php?action=update&catid=<?php echo $row['Cat_ID']; ?>" method="post" class="form-horizontal"> 

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Category Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="cat-name" class="form-control" value="<?php echo $row['Cat_Name']; ?>">
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                                <input type="text" name="description" class="form-control" value="<?php echo $row['Cat_Description']; ?>">
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Ordering</label>
                            <div class="col-sm-10">
                                <input type="text" name="ordering" class="form-control" value="<?php echo $row['Cat_Ordering']; ?>">
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Parent ?</label>
                            <div class="col-sm-10">
                                <select name="parent" class="form-control">
                                    <option value="0">None</option>
                                    <?php
                                    
                                    $allCat = ultimateGet('*','categories','WHERE Cat_Parent = 0','Cat_ID','ASC');
                                    foreach($allCat as $oneCat){
                                        echo "<option value='" . $oneCat['Cat_ID'] ."'";
                                        if($row['Cat_Parent'] == $oneCat['Cat_ID']){ echo 'selected'; }
                                        echo ">". $oneCat['Cat_Name'] ."</option>";
                                    }

                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Visibility</label>
                            <div class="col-sm-10">
                                <div>
                                    <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($row['Cat_Visibility'] == 0){echo 'checked';} ?>>
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visibility" value="1" <?php if($row['Cat_Visibility'] == 1){echo 'checked';} ?>>
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Allow Commenting</label>
                            <div class="col-sm-10">
                                <div>
                                    <input id="com-yes" type="radio" name="commenting" value="0" <?php if($row['Cat_Allow_Comments'] == 0){echo 'checked';} ?>>
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="commenting" value="1" <?php if($row['Cat_Allow_Comments'] == 1){echo 'checked';} ?>>
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Allow Ads</label>
                            <div class="col-sm-10">
                                <div>
                                    <input id="ads-yes" type="radio" name="ads" value="0" <?php if($row['Cat_Allow_Ads'] == 0){echo 'checked';} ?>>
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="ads" value="1" <?php if($row['Cat_Allow_Ads'] == 1){echo 'checked';} ?>>
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>
                                        
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" name="save" value="Edit Category" class="btn btn-primary btn-lg">
                            </div>
                        </div>
                                                  
                    </form>
                </div>

        <?php           
            }else{

                $Msg = "Category is not exist in our database";
                RedirectTO($Msg , 6 , "Categories.php?action=manage" , "danger");
            }

    }elseif($action == 'update'){ // start update page

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            echo "<h1 class='text-center'>Update Category</h1>";
            //get the variable from the edit page form

            $id             = $_GET['catid'];
            $name           = $_POST['cat-name'];
            $description    = $_POST['description'];
            $ordering       = $_POST['ordering'];
            $parent         = $_POST['parent'];
            $visibile       = $_POST['visibility'];
            $comments       = $_POST['commenting'];
            $advertise      = $_POST['ads'];


            //check if there is a category name equal to the new category name but have catid not equal to my catid to not count the category that i am updating now

            $stmt2 = $db_cont->prepare("SELECT * FROM categories WHERE Cat_Name = ? AND Cat_ID != ?");
            $stmt2->execute(array($name,$id));
            $count = $stmt2->rowCount();

            if($count == 0){

                $stmt6 = $db_cont->prepare("UPDATE categories SET Cat_Name = ?, Cat_Description = ?,Cat_Parent = ?, Cat_Ordering = ?, Cat_Visibility = ?, Cat_Allow_Comments = ?, Cat_Allow_Ads = ?  WHERE Cat_ID = ?"); 
                $stmt6->execute(array($name,$description,$parent,$ordering,$visibile,$comments,$advertise,$id));

                //echo success message
                $Msg= $stmt6->rowCount() . ' updated records';
                RedirectTO($Msg , 6 , "categories.php?action=manage" , "success");

            }else{

                $Msg = "This Category Name Is Already Used ..... Try Another One";
                RedirectTO($Msg , 5 , "categories.php?action=manage" , "danger");

            }
           

        }else{

            $errorMsg = "sorry....you cant browse this page directly";
            RedirectTo($errorMsg, 6, "index.php");
        }

    }elseif($action == 'delete'){ // start delete page

        $stmt7 = $db_cont->prepare("DELETE FROM categories WHERE Cat_ID = :zuser");
        $stmt7->bindParam(":zuser",$_GET['catid']);
        $stmt7->execute();

        $Msg = $stmt7->rowCount() . " category deleted";
        RedirectTO($Msg , 6 , "categories.php?action=manage" , "success");


    }else{
        header('Location:categories.php?action=manage');
    }


    include $tpl . 'footer.php';

}else{

    header('Location:index.php');
    exit();
}




ob_end_flush();


?>