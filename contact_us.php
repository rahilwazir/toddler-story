<?php
/**
 * Template Name: Contact us
 */
get_header();
?>
<div class="contact_us">
    <section class="first_mid">
        <div class="wrapper">
            <?php
            if (have_posts()):
                while (have_posts()): the_post();
                    ?>
                    <h1 class="coni"><?php the_title(); ?></h1>
                    <div class="coni_para"><p >Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean interdum tellus ac velit faucibus dignissim, eros elementum porttitor tempor, massa ligula cursus libero, vel ullamcorper dui ipsum </p></div><br clear="all"/>
                </div>
                <?php
            endwhile;
        endif;
        ?>   
    </section>
    <section class="sec_mid">
        <div class="wrapper">

            <div class="mid_left" >
                <h1>Fill up the form</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean interdum tellus ac velit faucibus dignissim, eros elementum porttitor tempor, massa ligula cursus libero, vel ullamcorper dui ipsum </p>
                <?php echo do_shortcode('[contact-form-7 id="93" title="Contact form 1"]')?>
                 <br clear="all" />
            </div>
            <div class="mid_right">
                <h1>reach us</h1>
                <p>You can contact us on the following and email addresses and our team of professional will try to contact you back at the earliest.</p>
                <a href="#" class="anc1">info@toddlerstory.com</a><br clear="all"/>
                <div class="div_line"></div>
                <h1 class="follow">follow us</h1>
                <br clear="all"/>
                <?php echo do_shortcode('[cn-social-icon]');?>
                <div class="div_line"></div>
                <h1 class="find_us">find us</h1>
                <div class="map"><img src="<?php echo bloginfo('template_url');?>/images/g_map.png"/></div>  
            </div>
            <div class="clear"></div>
        </div><!--End of wrapper-->
   </section>
</div> 



<?php get_footer(); ?>