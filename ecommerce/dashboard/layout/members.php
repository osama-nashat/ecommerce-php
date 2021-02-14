<?php 

/*
======================================================================
== Manage Members Page
== You Can Add | Edit | Delete Members From Here
======================================================================
*/


session_start();

if(isset($_SESSION['username'])){

    
    $pageTitle = "Members";
    include "init.php"; 

    $page_req = "";

    if(isset($_GET['action'])){

        $page_req = $_GET['action'];

    }else{

        $page_req = "manage";
    }


    if($page_req == 'manage'){ //start manage  
    
    
        $pending = "";

        //check if there is get request (page) and if it equal (pending) and if true the value of the variable $pending is going to change and that will change the sql command to return the pending members only
        if(isset($_GET['page']) && $_GET['page'] == 'pending'){
            $pending = 'AND RegStatus = 0';
        }


        // select all the users from the database except the admins
        // or select pending members if there is a get request (page = pending)
        $stmt = $db_cont->prepare("SELECT * FROM users WHERE GroupID != 1 $pending");
        $stmt->execute();

        //fetch the data from the databse
        $row = $stmt->fetchAll();
    
    ?>
       
        <h1 class="text-center">Manage <?php if($pending != ""){echo "Pending";} ?> Members</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table manage-members text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Avatar</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>FullName</td>
                        <td>Registered Date</td>
                        <td>Control</td>
                    </tr>
            <?php 
                    
                foreach($row as $member){ //now $member will contain a row of the users table in every loop until there is no more rows in var $row which contain all the database rows
                    
                    echo '<tr>';
                    echo    '<td>'. $member['UserID']   .'</td>';
                    echo    '<td>';
                                if(empty($member['User_Avatar'])){

                                }else{
                                    echo '<img src="uploads/avatars/'. $member['User_Avatar'] .'" alt="avatar">';
                                }
                    echo    '</td>';
                    echo    '<td>'. $member['Username'] .'</td>';
                    echo    '<td>'. $member['Email']    .'</td>';
                    echo    '<td>'. $member['FullName'] .'</td>';
                    echo    '<td>'. $member['RegDate']  .'</td>';
                    echo    '<td>
                                <a href="members.php?action=edit&userid='. $member['UserID'] .'" class="btn btn-success" style="margin-right:5px"><i class="fa fa-edit"></i>  Edit</a>
                                <a href="members.php?action=delete&userid='. $member['UserID'] .'" class="btn btn-danger" style="margin-right:5px"><i class="fas fa-times"></i>  Delete</a>';
                                
                                if($member['RegStatus'] == 0){
                                  echo  '<a href="members.php?action=pending&userid='. $member['UserID'] .'" class="btn btn-primary"><i class="fas fa-check"></i>  Activate</a>';
                                }
                                

                    echo    '</td>';
                    echo '</tr>';
                }        
                    
            ?>

                </table>
            </div>
            <a href="members.php?action=add" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i>  add new member</a>
        </div>

<?php  }elseif($page_req == 'add'){  //start add ?>
       
           
        <h1 class="text-center">Add New Member</h1>

        <div class="container">
            <form action="members.php?action=insert" method="post" class="form-horizontal" enctype="multipart/form-data"> 

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10">
                        <input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="username to login">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control" autocomplete="new-password" placeholder="Enter New Password" required="required">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="text" name="email" class="form-control" autocomplete="off" required="required" placeholder="Enter Email">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="fullname" class="form-control" autocomplete="off" required="required" placeholder="Enter FullName">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Profile Picture</label>
                    <div class="col-sm-10">
                        <input type="file" name="avatar" class="form-control" autocomplete="off" required="required">
                    </div>
                </div>
                                
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" name="save" value="Add Member" class="btn btn-primary btn-lg">
                    </div>
                </div>
                                
                            
            </form>
        </div>


<?php }elseif($page_req == 'insert'){ // insert page



    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        echo "<h1 class='text-center'>Insert Page</h1>";

        // get the upload variables from the form

        $avatarName     = $_FILES['avatar']['name'];
        $avatarSize     = $_FILES['avatar']['size'];
        $avatarTmp      = $_FILES['avatar']['tmp_name'];
        $avatarType     = $_FILES['avatar']['type'];

        // list of allowed file typed to upload

        $avatarAllowedExtentions = array("jpeg","jpg","png","gif");

        // get the avatar extention

        $avatarExtention = strtolower(end(explode('.',$avatarName)));

        


        $user   = $_POST['username'];
        $pass   = $_POST['password'];
        $email  = $_POST['email'];
        $full   = $_POST['fullname'];


        $hashpass = sha1($pass);
        
        

        //validate the values coming from the form

        $formErrors = array();

        if(strlen($user) < 4){
            $formErrors[] = 'username cant be less than <strong>4 characters</strong>';
        }

        if(empty($user)){
            $formErrors[] = 'username cant be <strong>empty</strong>';
        }

        //we check the emptieness for $pass not for $hashpass becuase the sha1 hashed the empty string also so if the password feild is empty the condition will not work

        if(empty($pass)){
            $formErrors[] = 'password cant be <strong>empty</strong>';
        }

        if(empty($email)){
            $formErrors[] = 'email cant be <strong>empty</strong>';
        }

        if(empty($full)){
            $formErrors[] = 'full name cant be <strong>empty</strong>';
        }

        if(! empty($avatarName) && ! in_array($avatarExtention,$avatarAllowedExtentions)){
            $formErrors[] = 'this type of files is not <strong>allowed</strong>';
        }

        if(empty($avatarName)){
            $formErrors[] = 'avatar is <strong>required</strong>';
        }

        // check if the avatar size is larger than 4 megabyte
        if($avatarSize > 4194304){
            $formErrors[] = 'avatar cant be larger than <strong>4MB</strong>';
        }

        


        //update the database with this values if the errors array is empty
        if(empty($formErrors)){

            // be sure that we wont have a two avatars with the same name
            $avatar = rand(0,100000) . '_' . $avatarName;

            // move the uploaded files from the temporary to the path in our host concatinated with the file name
            move_uploaded_file($avatarTmp,"uploads\avatars\\" . $avatar);

            // check if there is a username equal to the new username
            
            if(checkItem("Username","users",$user) == 0){

                $stmt = $db_cont->prepare("INSERT INTO users (Username, Password, Email, FullName, RegStatus, RegDate) VALUES (?,?,?,?,1,now(),?)"); 
                $stmt->execute(array($user,$hashpass,$email,$full,$avatar));

                //echo success message
                $Msg = $stmt->rowCount() . ' Record Inserted';
                RedirectTO($Msg , 6 , "members.php?action=manage" , "success");

            }else{
                // show a message and redirect to manage page

                $Msg = "This Username Is Already Used .... Try Another One";

                RedirectTO($Msg,6,"members.php?action=manage","danger");
                
            }

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





    }elseif($page_req == 'edit'){ //start edit page


            //check if the get request userid is numeric then sign it value to the variable $userid 

            $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
            
            //select all the data from the database depend on this ID

            $stmt = $db_cont->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
            $stmt->execute(array($userid));

            //fetch the data into row variable to use it inside the form later
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            //if the record is exist, the edit form will appeare and this form sends the values to members.php?action=update 
            if($count > 0){?>

                <h1 class="text-center">Edit Member</h1>
                        <div class="container">
                            <form action="members.php?action=update" method="post" class="form-horizontal">

                                <input type=hidden name="userid" value="<?php echo $userid; ?>"> 

                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Username</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="username" class="form-control" autocomplete="off" required="required" value="<?php echo $row['Username'] ?>">
                                    </div>
                                </div>

                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>">
                                        <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Enter New Password">
                                    </div>
                                </div>

                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="email" class="form-control" autocomplete="off" required="required" value="<?php echo $row['Email'] ?>">
                                    </div>
                                </div>

                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Full Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="fullname" class="form-control" autocomplete="off" required="required" value="<?php echo $row['FullName'] ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" name="save" value="save" class="btn btn-primary btn-lg">
                                    </div>
                                </div>
                                
                            
                            </form>
                        </div>

    <?php   }else{

            $Msg = "user is not exist in our database";
            RedirectTO($Msg , 6 , "members.php?action=manage" , "danger");
        }

    }elseif($page_req == 'update'){ //start update

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            echo "<h1 class='text-center'>Update Page</h1>";
            //get the variable from the edit page form

            $id     = $_POST['userid'];
            $user   = $_POST['username'];
            $email  = $_POST['email'];
            $full   = $_POST['fullname'];

            //password trick

            $pass   = "";
            if(empty($_POST['newpassword'])){
                $pass = $_POST['oldpassword'];
            }else{
                $pass = sha1($_POST['newpassword']);
            }

            //validate the values coming from the form

            $formErrors = array();

            if(strlen($user) < 4){
                $formErrors[] = '<div class="alert alert-danger">username cant be less than <strong>4 characters</strong></div>';
            }

            if(empty($user)){
                $formErrors[] = '<div class="alert alert-danger">username cant be <strong>empty</strong></div>';
            }

            if(empty($email)){
                $formErrors[] = '<div class="alert alert-danger">email cant be <strong>empty</strong></div>';
            }

            if(empty($full)){
                $formErrors[] = '<div class="alert alert-danger">full name cant be <strong>empty</strong></div>';
            }

            


            //update the database with this values if the errors array is empty
            if(empty($formErrors)){

                //check if there is a username equal to the new username but have userid not equal to my userid to not count the user that i am updating now

                $stmt2 = $db_cont->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
                $stmt2->execute(array($user,$id));
                $count = $stmt2->rowCount();

                if($count == 0){

                    $stmt = $db_cont->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?"); 
                    $stmt->execute(array($user,$email,$full,$pass,$id));
    
                    //echo success message
                    $Msg= $stmt->rowCount() . ' updated records';
                    RedirectTO($Msg , 6 , "members.php?action=manage" , "success");

                }else{

                    $Msg = "This Username Is Already Used ..... Try Another One";
                    RedirectTO($Msg , 5 , "members.php?action=manage" , "danger");

                }

                

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

    }elseif( $page_req == 'delete'){ // delete member page

        $stmt = $db_cont->prepare("DELETE FROM users WHERE UserID = :zuser");
        $stmt->bindParam(":zuser",$_GET['userid']);
        $stmt->execute();

        $Msg = $stmt->rowCount() . " user deleted";
        RedirectTO($Msg , 6 , "members.php?action=manage" , "success");



    }elseif( $action = 'pending' ){ // activate pending members page

        $stmt3 = $db_cont->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = :zuser");
        $stmt3->bindParam(":zuser",$_GET['userid']);
        $stmt3->execute();

        $Msg = $stmt3->rowCount() . "user activated";
        RedirectTO($Msg , 6 , "members.php?action=manage" , 'success');


    }else{ //redirect to manage page

        header('Location:members.php?action=manage');

    }






    

    include $tpl . 'footer.php';

}else{

    header('Location:index.php');
    exit();
}





















?>