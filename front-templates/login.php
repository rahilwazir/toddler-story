<?php
/**
 * Template Name: Login
 */
get_header(); the_post(); ?>
<section class="wrapper rcplr rcp-register">
    <?php echo do_shortcode('[login_form redirect="' . get_parent_admin_profile() . '"]'); ?>
    <?php rw_login_link(); ?>
</section>
<?php get_footer(); ?>