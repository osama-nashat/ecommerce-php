<?php 


$page_req = "";

if(isset($_GET['action'])){

    $page_req = $_GET['action'];

}else{

    $page_req = "manage";
}


if($page_req == 'manage'){
    echo "welcome you are in manage page";
    echo '<a href="page.php?action=add">add category + <a>';
}elseif($page_req == 'add'){
    echo "welcome you are in add page";
}elseif($page_req == 'insert'){
    echo "welcome you are in insert page";
}else{
    echo "welcome you are in manage page";
}









?>