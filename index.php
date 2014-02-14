<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
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
                <?php endwhile; ?>
                <div class="bottom"></div>
            </div>

            <br clear="all" />
        </div>

        <div class="video_sec">
            <div class="left">
                <h1>Watch Your Baby story in <span class="orange">2 minutes!</span></h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean interdum tellus ac velit faucibus dignissim, eros elementum porttitor tempor, massa ligula cursus libero, vel ullamcorper dui ipsum </p>
                <a href="#" class="get_started">Get Started</a>
            </div>
            <div class="right"><img src="<?php echo bloginfo('template_url'); ?>/images/video_img.png" /></div>
            <br clear="all" />
        </div>

        <br clear="all" />
    </div>
</section>

<?php
get_footer();
?>