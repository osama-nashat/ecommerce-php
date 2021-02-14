<?php 

ob_start();
$pageTitle= 'Register';
include "init.php"; 

if(isset($_POST['signup'])){

    $user       = $_POST['username'];
    $password1  = $_POST['password1'];
    $password2  = $_POST['password2'];
    $email      = $_POST['email'];

    // make an array for the errors coming from the validate later
    $formErrors = array();

    // sanitize the username field
    $filteredUser = filter_var($user,FILTER_SANITIZE_STRING);

    // validate the username
    if(strlen($filteredUser) < 4){
        $formErrors[] = 'Username Must Be More Than 4 Charachters';
    }

    // validate the passwords

    if(empty($password1)){
        $formErrors[] = 'Password Cant Be Empty';
    }

    // hash the password1 and password2
    $hashedPass1 = sha1($password1);
    $hashedPass2 = sha1($password2);

    // check if the two passwords are equal
    if($hashedPass1 !== $hashedPass2){
        $formErrors[] = 'Passwords Are Not Match';
    }

    // sanitize the email field
    $filteredEmail = filter_var($email,FILTER_SANITIZE_EMAIL);

    // validate the email field 
    if(filter_var($filteredEmail,FILTER_VALIDATE_EMAIL) != true){
        $formErrors[] = 'This Email Is Not Valide';
    }


    if(empty($formErrors)){

        // add the new user to the datebase
        
        // check if there is a username equal to the new username
            
        if(checkItem("Username","users",$filteredUser) == 0){

            $stmt = $db_cont->prepare("INSERT INTO users (Username, Password, Email, RegStatus, RegDate) VALUES (?,?,?,0,now())"); 
            $stmt->execute(array($filteredUser,$hashedPass1,$filteredEmail));

            //echo success message
            echo "you have signed up successfully";
            

        }else{

            // add an error to the errors array
            $formErrors[] = 'This Username Is Already Used';
            
            
        }
    }




}


?>


<div class="container login-page">
    <h1 class="text-center"><span class="signup">Signup</span></h1>
    <form class="signup" action="signup.php" method="post">
        <input pattern=".{4,8}" title="username must be 4-8 chars" class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your Username" required>
        <input minlength="4" class="form-control" type="password" name="password1" autocomplete="new-password" placeholder="Type Your Password" required>
        <input class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Retype Your Password">
        <input class="form-control" type="email" name="email" placeholder="Type Your Email">
        <input class="btn btn-primary btn-block" type="submit" value="Signup" name="signup">
        <a class="btn btn-info btn-block" href="login.php">Have Account ?</a>
    </form>

    <div class="the-errors text-center">
        <?php 
        
        if(! empty($formErrors)){
            foreach($formErrors as $error){
                echo '<p class="msg">' . $error . '</p>';
            }
        }
        

        ?>
    </div>
</div>









   
<?php 
    include $tpl . 'footer.php';
    ob_end_flush();
?>