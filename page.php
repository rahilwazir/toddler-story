<?php
/**
 * The template for displaying all pages
 */
get_header();
?>
<div class="contact_us">
    <section class="first_mid clearfix">
        <div class="wrapper">
        <?php if (have_posts()): while (have_posts()): the_post(); ?>
                <h1 class="coni"><?php the_title();?></h1>
            <?php if ( !is_bbpress() ) { ?>
                <div class="coni_para"><p >Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean interdum tellus ac velit faucibus dignissim, eros elementum porttitor tempor, massa ligula cursus libero, vel ullamcorper dui ipsum </p></div><br clear="all"/>
            <?php } ?>
        </div>
        <?php endwhile; endif; ?>
</section>
 <section class="sec_mid clearfix">
     <div class="wrapper">
        	<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
						<div class="entry-thumbnail">
							<?php the_post_thumbnail(); ?>
						</div>
						<?php endif; ?>

						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentythirteen' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
					</div><!-- .entry-content -->

					</article><!-- #post -->

			<?php endwhile; ?>

        
     </div>
    </section>
</div> 
<?php get_footer(); ?>