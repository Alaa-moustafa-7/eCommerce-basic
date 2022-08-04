<?php
    
    function lang( $phrase ){

        static $lang = array(
            // navbar link
            'HOME-ADMIN' => 'Home',
            'CATEGORIES' => 'Categories',
            'ITMES'      => 'Itmes',
            'MEMBERS'    => 'Members',
            'COMMENTS'   => 'Comments',
            'STATISTICS' => 'Statistics',
            'LOGS'       => 'Logs'

        );
            return $lang[$phrase];
    }
    
    