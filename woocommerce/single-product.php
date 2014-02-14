<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header('shop'); ?>
<section class="first_mid">
    <div class="wrapper">
     <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>

    <h1 class="coni">
        <?php  if(is_woocommerce('used-items')){
            echo "BUY/SELL";
            }else{
            woocommerce_page_title();     
            }
            
        ?>
    </h1>

<?php endif; ?>
    <div class="coni_para"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean interdum tellus ac velit faucibus dignissim, eros elementum porttitor tempor, massa ligula cursus libero, vel ullamcorper dui ipsum </p></div><br clear="all">
    </div>
</section>

<section class="sec_mid">
    <div class="wrapper">
        <div class="long_search">
               <div id="sb-search" class="sb-search">
               <form>
                   <input class="sb-search-input" placeholder="Search Product,item etc" type="text" value="" name="search" id="search"><input class="sb-search-submit anc" type="submit" value="search">
                   <span class="sb-icon-search">Search</span>
               </form>
               </div>
                   <br clear="all">
        </div>
        <div class="left_side">
            <div>
                 <?php dynamic_sidebar('sidebar-4');?>
            </div>
            
        </div><!--End of shipping_page_left-->
       
<div class="shipping_page_right">   
    <div class='right_side'>
		<?php while ( have_posts() ) : the_post(); ?>

			<?php woocommerce_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>
  
	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
                //do_action('woocommerce_before_main_content');
                
	?>
     
   </div>
</div>
    <div class="clear"></div>
</section>

	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		//do_action('woocommerce_sidebar');
	?>

<?php get_footer('shop'); ?>