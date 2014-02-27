<?php

/***************** CONSTANTS *****************/
//URI Constants
define('SRC_URI', get_stylesheet_directory_uri() . '/src');
define('RW_URI', SRC_URI . '/RW');
define('ADMIN_URI', RW_URI . '/Admin');

//Directory Constants
define('SRC_DIR', get_stylesheet_directory() . '/src');
define('RW_DIR', SRC_DIR . '/RW');
define('ADMIN_DIR', RW_DIR . '/Admin');

//HASHTAGS for dynamic contents
define('HASHTAGS', serialize(array(
    0 => 'create',
    1 => 'lifestory',
    2 => 'relation',
    3 => 'babybook',
    4 => 'childmanagement',
    5 => 'userinfo',
    6 => 'goToStory',
    7 => 'editInfo'
)));

$hashtags = unserialize(HASHTAGS);