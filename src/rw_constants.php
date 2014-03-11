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
define('URLHASH', '#/');

$pagesConstants = array (
    0 => 'create',
    1 => 'lifeStory',
    2 => 'relation',
    3 => 'babyBook',
    4 => 'childManagement',
    5 => 'accountSettings',
    6 => 'goToStory',
    7 => 'editInfo',
    8 => 'deleteComment',
    9 => 'addComment',
    10 => 'sharing'
);

$pagesConstantsMapped = array_map(function($val) {
    return URLHASH . $val;
}, $pagesConstants);

define('HASHTAGS', serialize($pagesConstantsMapped));

$hashtags = unserialize(HASHTAGS);