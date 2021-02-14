<?php 

    $noNav = "";
    $pageTitle = "login";
    session_start();
    if(isset($_SESSION['username'])){
        header('Location:dashboard.php');
    }
    include "init.php"; 
   

    if(isset($_POST['login'])){

        $username   = $_POST['username'];
        $password   = $_POST['password'];
        $hashedpass = sha1($password);

        //check if the user is exist in the database

        $stmt = $db_cont->prepare("SELECT 
                                        UserID, Username, Password 
                                    FROM 
                                        users 
                                    WHERE 
                                        Username = ? 
                                    AND 
                                        Password = ? 
                                    AND 
                                        GroupID = 1
                                    LIMIT 1");
        $stmt->execute(array($username,$hashedpass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        
        if($count > 0){
         
          $_SESSION['username'] = $row['Username'];
          $_SESSION['ID'] = $row['UserID'];
          header('Location:dashboard.php');
          exit();
         

        }else{
            echo "you are not an admin";
        }

        //if count > 0 then the database contain a record for this username
        
    }
?>


<form class="login" action="index.php" method="POST">
    <h4 class="text-center">Admin Login</h4>
    <input class="form-control" type="text" placeholder="username" name="username" autocomlete="off">
    <input class="form-control" type="password" placeholder="password" name="password" autocomplete="new-password">
    <input class="btn btn-primary btn-block" type="submit" value="login" name="login">
</form>




<?php include $tpl . 'footer.php' ?>