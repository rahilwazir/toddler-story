<?php
/**
 * Template Name: Register
 */
get_header(); the_post(); ?>
<section class="wrapper rcplr rcp-register">
    <?php the_content(); ?>
    <br clear="all">
    <?php rw_register_link(); ?>
</section>
<?php get_footer(); ?>