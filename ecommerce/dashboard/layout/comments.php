<?php 

/*
======================================================================
== Manage Comments Page
== You Can Approve | Edit | Delete Comments From Here
======================================================================
*/


session_start();

if(isset($_SESSION['username'])){

    
    $pageTitle = "Comments";
    include "init.php"; 

    $page_req = "";

    if(isset($_GET['action'])){

        $page_req = $_GET['action'];

    }else{

        $page_req = "manage";
    }


    if($page_req == 'manage'){ //start manage  


        // select all the comments from the database 
        $stmt = $db_cont->prepare("SELECT 
                                        comments.*, items.Item_Name, users.Username
                                    FROM 
                                        comments
                                    INNER JOIN 
                                        items
                                    ON
                                        items.Item_ID = comments.Item_ID
                                    INNER JOIN 
                                        users
                                    ON
                                        users.UserID = comments.User_ID");
        $stmt->execute();

        //fetch the data from the databse
        $rows = $stmt->fetchAll();
    
        ?>
       
        <h1 class="text-center">Manage Comments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Comment</td>
                        <td>Item Name</td>
                        <td>User Name</td>
                        <td>Added Date</td>
                        <td>Control</td>
                    </tr>
            <?php 
                    
                foreach($rows as $row){ //now $row will contain a row of the comments table in every loop until there is no more rows in var $rows which contain all the database rows
                    
                    echo '<tr>';
                    echo    '<td>'. $row['C_ID']   .'</td>';
                    echo    '<td>'. $row['Comment'] .'</td>';
                    echo    '<td>'. $row['Item_Name']    .'</td>';
                    echo    '<td>'. $row['Username'] .'</td>';
                    echo    '<td>'. $row['C_Date']  .'</td>';
                    echo    '<td>
                                <a href="comments.php?action=edit&cid='. $row['C_ID'] .'" class="btn btn-success" style="margin-right:5px"><i class="fa fa-edit"></i>  Edit</a>
                                <a href="comments.php?action=delete&cid='. $row['C_ID'] .'" class="btn btn-danger" style="margin-right:5px"><i class="fas fa-times"></i>  Delete</a>';
                                
                                if($row['C_Status'] == 0){
                                  echo  '<a href="comments.php?action=Approve&cid='. $row['C_ID'] .'" class="btn btn-primary"><i class="fas fa-check"></i>  Approve</a>';
                                }
                    echo    '</td>';
                    echo '</tr>';
                }        
                    
            ?>

                </table>
            </div>
        </div>

        <?php  

    }elseif($page_req == 'edit'){ //start edit page


        //check if the get request cid is numeric then sign it value to the variable $comid 

        $comid = (isset($_GET['cid']) && is_numeric($_GET['cid'])) ? intval($_GET['cid']) : 0;
        
        //select all the data from the database depend on this ID

        $stmt = $db_cont->prepare("SELECT * FROM comments WHERE C_ID = ?");
        $stmt->execute(array($comid));

        //fetch the data into row variable to use it inside the form later
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        //if the record is exist, the edit form will appeare and this form sends the values to comments.php?action=update 
        if($count > 0){?>

            <h1 class="text-center">Edit Comment</h1>
            <div class="container">
                <form action="comments.php?action=update" method="post" class="form-horizontal">

                    <input type=hidden name="comid" value="<?php echo $comid; ?>"> 

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="comment"><?php echo $row['Comment']; ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" name="save" value="save" class="btn btn-primary btn-lg">
                        </div>
                    </div>
                    
                </form>
            </div>

        <?php  
    
        }else{

            $Msg = "comment is not exist in our database";
            RedirectTO($Msg , 6 , "comments.php?action=manage" , "danger");
        }

    }elseif($page_req == 'update'){ //start update

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            echo "<h1 class='text-center'>Update Page</h1>";
            //get the variable from the edit page form

            $cid     = $_POST['comid'];
            $comment   = $_POST['comment'];

            $stmt = $db_cont->prepare("UPDATE comments SET Comment = ? WHERE C_ID = ?"); 
            $stmt->execute(array($comment,$cid));

            //echo success message
            $Msg= $stmt->rowCount() . ' updated records';
            RedirectTO($Msg , 6 , "comments.php?action=manage" , "success");

           

        }else{

            $errorMsg = "sorry....you cant browse this page directly";
            RedirectTo($errorMsg, 6, "index.php");
        }

    }elseif( $page_req == 'delete'){ // delete comment page

        $stmt = $db_cont->prepare("DELETE FROM comments WHERE C_ID = :zcomm");
        $stmt->bindParam(":zcomm",$_GET['cid']);
        $stmt->execute();

        $Msg = $stmt->rowCount() . " comment deleted";
        RedirectTO($Msg , 6 , "comments.php?action=manage" , "success");



    }elseif( $action = 'Approve' ){ // activate non Approved comments page

        $stmt3 = $db_cont->prepare("UPDATE comments SET C_Status = 1 WHERE C_ID = :zcomm");
        $stmt3->bindParam(":zcomm",$_GET['cid']);
        $stmt3->execute();

        $Msg = $stmt3->rowCount() . "comment approved";
        RedirectTO($Msg , 6 , "comments.php?action=manage" , 'success');


    }else{ //redirect to manage page

        header('Location:comments.php?action=manage');

    }



    include $tpl . 'footer.php';

}else{

    header('Location:index.php');
    exit();
}



?>