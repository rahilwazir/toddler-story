<?php
/**
 * Template Name: Parent Admin - Profile
 */
 
get_header('admin');
?>
<?php while (have_posts()) : the_post(); ?>
    <div class="process-loading"></div>
    <section id="form-section" class="container">
        
    </section>
<?php endwhile;
get_footer('admin');
