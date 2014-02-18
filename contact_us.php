<?php
/**
 * Template Name: Contact us
 */
get_header();
?>
<div class="contact_us">
    <?php
        if (have_posts()):
            while (have_posts()): the_post();
                the_content();
            endwhile;
        endif;
    ?>
</div> 
<?php get_footer(); ?>