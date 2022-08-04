<?php

    /* 
        categories  => [ Mange | Edit | Update | Add | Isert | Delete | Status ]
    */

    $do =  isset($_GET['do']) ? $_GET['do'] : 'Add';
    

    // Check if pages
    if ($do == 'Add'){
        echo 'Welcome you are in manage category page';
        echo '<a href="?do=insert">insert new category +  </a>';
    }elseif ($do == 'update'){
        echo 'come here';
    }elseif ($do == 'insert'){
        echo 'data succesfly';
    }else{
        echo 'Error';
    }