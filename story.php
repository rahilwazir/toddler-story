<?php
/**
 * Template Name: Story
 *
 */

get_header(); ?>

<div class="faq">
    
    <section class=" story_top">
        <div class="wrapper">
            <?php $custom= new WP_Query('post_type=post&p=71');
              while($custom->have_posts()):$custom->the_post();s
            ?>
            <h1 class="coni"><?php echo the_title();?></h1>
            <div class="coni_para"><p><?php echo the_content(); ?></p></div><br clear="all"/>           
            <?php endwhile;?>
        </div>
    </section>
    
    <div class="wrapper">
    		<div class="toddler_story story_sec1">
                <a href="#" class="story_photo">
                <img src="<?php echo bloginfo('template_url');?>/images/HWork_baby_1.png">
                <div class="hover"></div>
                </a> 
                 <?php $custom= new WP_Query('post_type=post&p=73');
                    while($custom->have_posts()):$custom->the_post();s
                 ?>    
                    <h1><?php echo the_title();?></h1>
                    <p><?php echo the_content();?></p>
                    <?php endwhile;?>
                </div>
    </div>
   
   <section id="main_blog">
        <div class="wrapper">
                <div class="toddler_story story_sec1">
                   <a href="#" class="story_photo">
                   <img src="<?php echo bloginfo('template_url');?>/images/HWork_baby_2.png">
                   <div class="hover"></div>
                   </a> 
                    <?php $custom= new WP_Query('post_type=post&p=75');
                    while($custom->have_posts()):$custom->the_post();s
                     ?>
                        <h1><?php echo the_title();?></h1>
                        <p><?php the_content();?></p>
                        <?php endwhile;?>
                    </div>
        </div>
   </section>  
   
   
   
        <div class="wrapper">
                <div class="toddler_story story_sec1">
                   <a href="#" class="story_photo">
                   <img src="<?php echo bloginfo('template_url');?>/images/HWork_baby_3.png">
                   <div class="hover"></div>
                   </a> 
                    <?php $custom= new WP_Query('post_type=post&p=77');
                    while($custom->have_posts()):$custom->the_post();
                     ?>
                        <h1><?php echo the_title();?></h1>
                        <p><?php echo the_content();?></p>
                        <?php endwhile;?>
                    </div>
        </div>
   
   
   
   <section id="main_blog">
        <div class="wrapper">
                <div class="toddler_story story_sec_last">
                   <a href="#" class="story_photo">
                   <img src="<?php echo bloginfo('template_url');?>/images/HWork_baby_4.png">
                   <div class="hover"></div>
                   </a> 
                    <?php $custom= new WP_Query('post_type=post&p=79');
                    while($custom->have_posts()):$custom->the_post();
                     ?>
                        <h1><?php echo the_title();?></h1>
                        <p><?php echo the_content();?></p>
                        <?php endwhile;?>
                    </div>
        </div>
   </section>    
 
</div>  

<?php
get_footer();
?>
