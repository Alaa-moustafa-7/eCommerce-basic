<?php

    // Error Reporting
    ini_set('display_error', 'on');
    error_reporting(E_ALL);

    include 'admin/connect.php';

    $sessionUser = '';
    if(isset($_SESSION['user'])){
        $sessionUser = $_SESSION['user'];
    }

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

