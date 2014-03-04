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
        <meta charset="<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="Cache-control" content="no-cache">
        <meta http-equiv="expires" content="-1">
        <meta name="robots" content="noindex,nofollow" />
        <title><?php wp_title('|', true, 'right'); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
        <!--[if lt IE 9]>
        <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
        <![endif]-->
        <?php wp_head(); global $hashtags; ?>
    </head>
    <body id="user-profile">
        <header id="header">
            <section class="container">
                <div class="wrap">
                    <a href="<?php echo esc_url(get_parent_admin_profile()); ?>" rel="home" class="fleft">
                        <img src="<?php echo bloginfo('template_url'); ?>/images/logo_parent_admin.png">
                    </a>
                    <nav class="fright" id="parent_main_links">
                        <a href="<?php echo $hashtags[0]; ?>">Create Child Profile</a>
                        <a href="<?php echo $hashtags[1]; ?>">Life Story</a>
                        <a href="<?php echo $hashtags[2]; ?>">My Relationship</a>
                        <a href="<?php echo $hashtags[3]; ?>">Baby Book</a>
                        <a href="<?php echo wp_logout_url(); ?>">Logout</a>
                    </nav>
                </div>
            </section>
        </header>
        <section id="secondary_menu">
            <section class="container">
                <nav id="secondary_menu_links">
                    <a href="<?php echo $hashtags[4]; ?>">
                        <span id="child_mng">My Children Management</span>
                    </a>
                    <a href="<?php echo $hashtags[5]; ?>">
                        <span id="user_info">Account Settings</span>
                    </a>
                    <a href="<?php echo $hashtags[10]; ?>">
                        <span id="user_sharing">Sharing</span>
                    </a>
                </nav>
            </section>
        </section>
        <section id="third-section">
            <section class="container">
                <div class="wrap_simple">
                    <h1 class="fleft">Welcome! Create Your First child's Story.</h1>
                    <a href="<?php echo $hashtags[0]; ?>" class="fright make-me-button">Add Child</a>
                </div>
            </section>
        </section>
        <section class="container">