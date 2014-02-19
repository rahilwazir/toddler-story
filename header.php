<?php use \RW\Modules\ParentModule; ?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
    <!--<![endif]-->
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width">
        <title><?php wp_title('|', true, 'right'); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
        <!--[if lt IE 9]>
        <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
        <![endif]-->
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>      
        <header class="header">
            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="logo">
                <img src="<?php echo bloginfo('template_url'); ?>/images/logo.png">
            </a>

            <div class="nav">
                <?php wp_nav_menu(array('menu' => 'top-menu')); ?>
            </div>

            <div id="menu">
                <?php wp_nav_menu(array('menu' => 'top-menu')); ?>
            </div>
            <div class="login_sec">
            <?php if(is_user_logged_in() === false) : ?>
                <a href="<?php echo get_permalink(332); ?>" data-load-popup="value" class="basic signin window">Sign In</a>
                <a href="<?php echo get_permalink(330); ?>" data-load-popup="value_R" class="basic join window">Join</a>
            <?php
                else :
                    if ( ParentModule::currentUserisParent() ) :
            ?>
                <a href="<?php echo get_parent_admin_profile(); ?>" class="signin window">Profile</a>
                <a href="<?php echo wp_logout_url(); ?>" class="join window">Logout</a>
            <?php
                    endif;
                endif;
            ?>
            </div>

            <section class="lang-dropper">
                <?php generate_language_dropdown(array(
                    'sidebar' => true
                )); ?>
            </section>
            <br clear="all" />
        </header>