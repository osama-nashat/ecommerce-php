<?php 

session_start(); //start the session

session_unset(); //unset the data in the session

session_destroy(); //destroy the session

header('Location:login.php');
exit();


?>