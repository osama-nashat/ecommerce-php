<?php 

function lang($phrase){

    static $lang = [


        //dashboard navbar phrases
        'homepage'      => 'Home',
        'categories'    => 'Categories',
        'items'         => 'Items',
        'members'       => 'Members',
        'comments'      => 'Comments',
        'statistics'    => 'Statistics',
        'logs'          => 'Logs',
        'visit'         => 'visit shop',
        'edit'          => 'Edit Profile',
        'setting'       => 'Settings',
        'logout'        => 'Logout',
        




    ];

    return $lang[$phrase];

}


?>