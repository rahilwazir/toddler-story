<?php
/**
 * Template Name: Register
 */
get_header(); the_post(); ?>
<section class="wrapper rcplr rcp-register">
    <?php echo do_shortcode('[register_form]'); ?>
    <br clear="all">
    <?php rw_register_link(); ?>
</section>
<?php get_footer(); ?>