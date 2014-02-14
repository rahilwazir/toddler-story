<?php
/**
 * Template Name: Login
 */
get_header(); the_post(); ?>
<section class="wrapper rcplr rcp-register">
    <?php the_content(); ?>
    <?php rw_login_link(); ?>
</section>
<?php get_footer(); ?>