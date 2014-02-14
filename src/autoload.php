<?php

//Symfony Autoloder
require_once __DIR__.'/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->register();
$loader->registerNamespace('RW', __DIR__);

//Include the constants
require_once get_template_directory() . '/src/rw_constants.php';

//Include helper functions
require_once SRC_DIR . '/rw_helpers.php';

//Hooks add/remove_filter/action
require_once SRC_DIR . '/rw_hooks.php';

//Start the engine
require_once SRC_DIR . '/rw.php';