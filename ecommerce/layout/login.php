<?php 

ob_start();
session_start();

if(isset($_SESSION['user'])){
    header('Location:index.php');
    exit();
}

$pageTitle = 'Login';
include "init.php"; 

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashpass = sha1($password);


    //check if the user is exist in the database

    $stmt = $db_cont->prepare("SELECT 
                                   UserID, Username, Password 
                                FROM 
                                    users 
                                WHERE 
                                    Username = ? 
                                AND 
                                    Password = ? 
                                ");

    $stmt->execute(array($username,$hashpass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    if($count > 0){
         
        $_SESSION['user']   = $row['Username'];
        $_SESSION['UserID'] = $row['UserID'];
        header('Location:index.php');
        exit();
       

      }else{
          echo "you are not a member";
      }

      //if count > 0 then the database contain a record for this username


}


?>


<div class="container login-page">
    <h1 class="text-center"><span class="login">Login</span></h1>
    <form class="login" action="login.php" method="post">
        <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Username">
        <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Password">
        <input class="btn btn-primary btn-block" type="submit" value="Login" name="login">
        <a class="btn btn-info btn-block" href="signup.php">Create Account ?</a>
    </form>
    
    <div class="the-errors text-center">
        <?php  ?>
    </div>
</div>


  
<?php include $tpl . 'footer.php';

ob_end_flush();
?>