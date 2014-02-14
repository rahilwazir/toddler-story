<?php
/**
 * Template Name:FAQ
 */
get_header();
?>
<div class="faq">
    <section class="first_mid">
        <div class="wrapper">
            <?php
            if (have_posts()):
                while (have_posts()): the_post();
                    ?>
                    <h1 class="coni">
                        <?php
                        if (is_page('faq')) {
                            echo "frequently ask question";
                        } else {
                            echo the_title();
                        }?></h1>
                    <div class="coni_para"><p >Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean interdum tellus ac velit faucibus dignissim, eros elementum porttitor tempor, massa ligula cursus libero, vel ullamcorper dui ipsum </p></div><br clear="all"/>
                </div>
                <?php
            endwhile;
        endif;
        ?>   
    </section>
    <section class="sec_mid">
        <div class="wrapper">
          <?php $custom=new WP_Query('cat=15');
          while($custom->have_posts()):$custom->the_post();
          ?>
            <h1><?php the_title();?></h1>
            <div class="para_div"><p><?php the_content();?></p></div>
            <?php endwhile;?>
         <div class="clear"></div>
        </div><!--End of wrapper-->
    </section>
</div> 



<?php get_footer(); ?>