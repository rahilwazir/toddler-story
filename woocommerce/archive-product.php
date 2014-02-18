<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

get_header('shop');
?>

<?php
/**
 * woocommerce_before_main_content hook
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
//do_action('woocommerce_before_main_content');
?>
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
<?php do_action('woocommerce_archive_description'); ?>

<?php if (have_posts()) : ?>

    <?php
    /**
     * woocommerce_before_shop_loop hook
     *
     * @hooked woocommerce_result_count - 20
     * @hooked woocommerce_catalog_ordering - 30
     */
    do_action('woocommerce_before_shop_loop');
    ?>

    <?php woocommerce_product_loop_start(); ?>

    <?php woocommerce_product_subcategories(); ?>
  
      
    <?php while (have_posts()) : the_post(); ?>

        <?php #woocommerce_get_template_part('content', 'product'); ?>
  
                       <li class="bg_li">
                         <a href="<?php echo the_permalink();?>" class="no_css"> <?php the_post_thumbnail();?></a>
                           <div class="li_div">
                               <h1><a href="<?php echo the_permalink();?>" class="no_css"><?php the_title(); ?></a></h1>
                                    <p><?php the_content();?>
                       <?php do_action( 'woocommerce_after_shop_loop_item' );?>
                           </div>
                       </li>
                      
                  
        
        
        
   
    <?php endwhile; // end of the loop.  ?>
        
         </div>
    <?php woocommerce_product_loop_end(); ?>

    <?php
    /**
     * woocommerce_after_shop_loop hook
     *
     * @hooked woocommerce_pagination - 10
     */
    do_action('woocommerce_after_shop_loop');
    ?>

<?php elseif (!woocommerce_product_subcategories(array('before' => woocommerce_product_loop_start(false), 'after' => woocommerce_product_loop_end(false)))) : ?>

    <?php woocommerce_get_template('loop/no-products-found.php'); ?>

<?php endif; ?>

<?php
/**
 * woocommerce_after_main_content hook
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');
?>

</div><!--End of shipping_page_right-->

<div class="clear"></div>
   </div>
</section>
<?php
/**
 * woocommerce_sidebar hook
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action('woocommerce_sidebar');
?>

<?php get_footer('shop'); ?>