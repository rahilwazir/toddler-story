<?php
/**
 * Template Name: Home
 */
get_header();
?>

<section>
    <div class="banner_bg"></div>
</section>
<section>
    <div class="home_content">
         <?php $custom = new WP_Query('post_type=post&p=36');
           while ($custom->have_posts()): $custom->the_post();
         ?>
        
        <?php echo the_content();?>
        <?php endwhile;?>

        <div class="four_box">
            <div class="baby_book">
                <?php
                $custom = new WP_Query('post_type=post&p=4');
                while ($custom->have_posts()): $custom->the_post();
                    ?>
                    <div class="img_sec"><?php echo the_post_thumbnail(); ?></div>
                    <h1><?php echo the_title(); ?></h1>
                    <p><?php echo the_content(); ?></p>
                <?php endwhile; ?>
                <div class="bottom"></div>
            </div>

            <div class="buy_sell">
                <?php
                $custom = new WP_Query('post_type=post&p=7');
                 while ($custom->have_posts()): $custom->the_post();
                 ?>
                    <div class="img_sec"><?php echo the_post_thumbnail(); ?></div>
                    <h1><?php echo the_title(); ?></h1>
                    <p><?php echo the_content(); ?></p>
                <?php endwhile; ?>
                <div class="bottom"></div>
            </div>

            <div class="forum">
                 <?php
                $custom = new WP_Query('post_type=post&p=10');
                 while ($custom->have_posts()): $custom->the_post();
                 ?>
                    <div class="img_sec"><?php echo the_post_thumbnail(); ?></div>
                    <h1><?php echo the_title(); ?></h1>
                    <p><?php echo the_content(); ?></p>
                <?php endwhile; ?>
                <div class="bottom"></div>
            </div>

            <div class="gallery">
                 <?php
                $custom = new WP_Query('post_type=post&p=13');
                 while ($custom->have_posts()): $custom->the_post();
                 ?>
                    <div class="img_sec"><?php echo the_post_thumbnail(); ?></div>
                    <h1><?php echo the_title(); ?></h1>
                    <p><?php echo the_content(); ?></p>
                <?php endwhile; wp_reset_postdata(); ?>
                <div class="bottom"></div>
            </div>

            <br clear="all" />
        </div>

        <?php the_post(); the_content(); ?>
    </div>
</section>

<?php
get_footer();
?>