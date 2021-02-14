<?php 



/*
** ultimate get function v2.0
** function to get all records from any table from the database
*/

function ultimateGet($field,$table,$where = NULL,$orderField,$orderType = 'DESC'){

    global $db_cont;
    $ultimate = $db_cont->prepare("SELECT $field FROM $table $where ORDER BY $orderField $orderType");
    $ultimate->execute();
    $all = $ultimate->fetchAll();
    return $all;

}





/*
** get categories function v1.0
** function to get categories from the database
*/

function getcat(){

    global $db_cont;
    $stmt4 = $db_cont->prepare("SELECT * FROM categories ORDER BY Cat_ID ASC");
    $stmt4->execute();
    $cats = $stmt4->fetchAll();
    return $cats;

}



/*
** get Items function v2.0
** function to get items from the database
** $where   ==> the id of the category or member that you want to get its items
** $value   ==> the value to get execute with
** $approve ==> if you didnt pass this parameter the function will get the approved items only
*/

function getitems($where,$value,$approve = NULL){

    global $db_cont;

    if($approve == NULL){
        $sql = 'AND Item_Approve = 1';
    }else{
        $sql = NULL;
    }

    $stmt5 = $db_cont->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY Item_ID DESC");
    $stmt5->execute(array($value));
    $items = $stmt5->fetchAll();
    return $items;

}


/*
** check if user is not activated
** function to check the regStatus of the user
**
*/

function checkUserStatus($user){

    global $db_cont;
    $stmtx = $db_cont->prepare("SELECT 
                                    Username, RegStatus
                                FROM 
                                    users 
                                WHERE 
                                    Username = ? 
                                AND 
                                    RegStatus = 0
                                ");

    $stmtx->execute(array($user));
    $status = $stmtx->rowCount();

    return $status;
}




/*
** check item function V1.0
** $select  ==> the item to select from the database [Example : Username , ItemID , CategoryName]
** $from    ==> the table to select from [Example : users , items , categories]
** $value   ==> the value of select [Example : osama , phone , electronics]
*/

function checkItem($select, $from, $value){

    global $db_cont;

    $stmt = $db_cont->prepare("SELECT $select FROM $from WHERE $select = :zuser");
    $stmt->bindParam(":zuser",$value);
    $stmt->execute();

    return ($stmt->rowCount());
}





















/*
** get title function V1.0
** title function echo the page title in case that the page have the var $pageTitle
** and echo the default title in the other pages
*/

function getTitle(){

    global $pageTitle;

    if(isset($pageTitle)){

        echo $pageTitle;

    }else{

        echo "default";
    }
}




/*
** (Redirect to ) function V2.0
** $Msg ==> the message that will be shown to the user
** $seconds  ==> number of seconds before redirecting
** $pageTo   ==> the page that will be redirecting to
** $msgType  ==> the type of the message [ danger or success ]
*/

function RedirectTO($Msg , $seconds = 3 , $pageTo , $msgType){

    echo '<div class="alert alert-' . $msgType . '">' . $Msg . '</div>';
    echo '<div class="alert alert-success">you will be redirect in ' . $seconds . ' seconds</div>';
    header("refresh:$seconds;url=$pageTo");
    exit();

}




/*
** Calculate Items Function v1.0
** $select  ==>  the name of the column that will be calculated [Example : UserID , ItemID , CategoryName]
** $from    ==>  the table which have that column [Example : users , items , categories]
** $cond1   ==>  the left side of the ( WHERE ) condition [Example : WHERE UserID = 0] (UserID is the left side)
** $cond2   ==>  the right side of the ( WHERE ) condition [Example : WHERE UserID = 0] (0 is the right side)
** $condUse ==>  choose (true) if you want to use the (WHERE) condition in your sql command or (false) if you dont
*/

function calcItems($select, $from , $cond1, $cond2,$condUse){

    global $db_cont;

    if($condUse == 'true'){

        $stmt2 = $db_cont->prepare("SELECT COUNT($select) FROM $from WHERE $cond1 = $cond2");
        $stmt2->execute();
    
        $usersNum = $stmt2->fetchColumn();

        return $usersNum;

    }else{

        $stmt2 = $db_cont->prepare("SELECT COUNT($select) FROM $from");
        $stmt2->execute();
    
        $usersNum = $stmt2->fetchColumn();

        return $usersNum;

    }

    


}



/*
** Get Latest Records Function v1.0
** function to get latest items from the database [Example : users , items , comments]
** $select  ==> the item to select from the database [Example : Username , ItemID , CategoryName]
** $from    ==> the table to select from [Example : users , items , categories]
** $order   ==> the descending ordering by [Example : userID , ItemID]
** $limit   ==> the number of records to get [Example : 1 , 5 , 10]
*/

function getLatest($select , $from , $order , $limit = 5){

    global $db_cont;
    $stmt4 = $db_cont->prepare("SELECT $select FROM $from ORDER BY $order DESC LIMIT $limit");
    $stmt4->execute();
    $row = $stmt4->fetchAll();
    return $row;

}








?>