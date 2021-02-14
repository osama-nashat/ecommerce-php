<?php 

include 'connect.php';

//Routs

$tpl  = "../includes/template/"; //dashboard template directory
$css  = "../../dashboard/layout/css/"; //dashboard css directory
$js   = "../../dashboard/layout/js/"; //dashboard js directory
$lang = "../includes/langueges/"; // langueges file directory
$func = "../includes/functions/";

//include important files

include $func . 'functions.php';
include $lang . 'english.php';
include $tpl . 'header.php';

if(!isset($noNav)){include  $tpl . 'navbar.php';}




?>



