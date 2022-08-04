<?php

    include 'connect.php';

    // Rout
    $tpl = 'includes/template/'; // template directory
    $lang= 'includes/language/'; // lang directory
    $func= 'includes/functions/'; // Func directory
    $css = 'layout/css/'; // css directoery
    $js  = 'layout/js/'; // js directory
    
    
    

    // Include Important File
    include $func . 'functions.php';
    include $lang . 'english.php';
    include $tpl . 'header.php';
    
    if(!isset($noNavbar)) { include $tpl . 'navbar.php'; }


?>