<?php 

// Error Reporting

ini_set('display_errors' , 'On');
error_reporting(E_ALL);


include '../dashboard/layout/connect.php';

//Routs

$tpl  = "../includes/template/"; //dashboard template directory
$css  = "css/"; //dashboard css directory
$js   = "js/"; //dashboard js directory
$lang = "../includes/langueges/"; // langueges file directory
$func = "../includes/functions/";

//include important files

include $func . 'functions.php';
include $lang . 'english.php';
include $tpl . 'header.php';

if(!isset($noNav)){include  $tpl . 'navbar.php';}




?>



