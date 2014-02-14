<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
get_header();
?>
<section class="wrapper">
    <div id="primary" class="content-area">
        <div id="content" class="site-content" role="main">

            <header class="page-header">
                <h1 class="page-title"><?php _e('Not Found', 'rw'); ?></h1>
            </header>

            <div class="page-wrapper">
                <div class="page-content">
                    <h2><?php _e('This is somewhat embarrassing, isn&rsquo;t it?', 'rw'); ?></h2>
                    <p><?php _e('It looks like nothing was found at this location. Maybe try a search?', 'twentythirteen'); ?></p>

                    <?php get_search_form(); ?>
                </div><!-- .page-content -->
            </div><!-- .page-wrapper -->

        </div><!-- #content -->
    </div><!-- #primary -->
</section>
<?php get_footer(); ?>