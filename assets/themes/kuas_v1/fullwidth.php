<?php
/**
 * Template Name: Full Width
 *
 * The template for displaying the full width page.
 * 
 * @file      fullwidth.php
 * @package   kuas-beta
 * @author    Sami Ch.
 * @link 	  http://gazpo.com
 */
?>
<?php get_header(); ?>

	<div id="content" class="wide-content">
	
		<?php if (have_posts()) { ?>		
			<?php while (have_posts()) : the_post(); ?>
		
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
					<h2 class="title-pages"><?php the_title(); ?></h2>
					
					<div class="post-meta">	
							<?php if ( kuas_beta_get_option( 'show_page_comments' ) == 1 ) { ?>
								<span class="comments"><?php comments_popup_link( __('no comments', 'kuas-beta'), __( '1 comment', 'kuas-beta'), __('% comments', 'kuas-beta')); ?></span>
							<?php } ?>						
					</div><!-- /post-meta -->
			
					<div class="post-entry">
						<?php the_content(); ?>				
					</div><!-- /post-entry -->
							
				</div><!-- post -->
				<?php if ( kuas_beta_get_option( 'show_page_comments' ) == 1 ) { ?>
					<?php comments_template( '', true ); ?>
				<?php } ?>
			<?php endwhile; ?> 
		
			<?php if (  $wp_query->max_num_pages > 1 ) { ?>
       
				<div class="navigation">
					<div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'kuas-beta' ) ); ?></div>
					<div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'kuas-beta' ) ); ?></div>
				</div>
			<?php } ?>
		
		<?php } else { ?>
			
			<div id="post-0" class="post">
				<h1><?php _e( 'Tidak Ada', 'kuas-beta' ); ?></h1>
				<p><?php _e('Maaf, tapi tidak ada hasil yang ditemukan untuk arsip yang diminta. Mungkin pencarian akan membantu.', 'kuas-beta'); ?></p>
				<?php get_search_form(); ?>	
			</div>
		
		<?php } ?> <!-- /have_posts -->		
		
</div><!-- /content -->
	
<?php get_footer(); ?>
