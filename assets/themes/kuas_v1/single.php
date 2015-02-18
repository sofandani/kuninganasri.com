<?php
/**
 * The Template for displaying all single posts.
 *
 * @file      single.php
 * @package   kuas-beta
 * @author    Kuningan_Asri.
 * @link 	  http://kuas.com
 */
?>
<?php get_header(); 
global $wp_query;
?>

<div id="content">		

	<div id="wrapper_content">	
		<?php

		do_action('kuas_header_post_action');
		
		if (have_posts()) {
			while (have_posts()) : the_post(); ?>

				<?php if ( is_sticky() ) : ?>
				<div  id="post-<?php the_ID(); ?>" <?php post_class('single-post sticky-post'); ?>>
				<?php else: ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
				<?php endif; ?>

					<h1 class="page-title <?php echo basename(get_permalink()); ?>"><?php the_title(); ?></h1>

					<?php if(wp_is_mobile()==false){ ?>
					<div id="social-count-post" data-post="post-<?php the_ID(); ?>" data-title="<?php the_title(); ?>" data-permalink="<?php the_permalink(); ?>" >
					<?php
					if(isset($_GET['preview'])){
					$this_post_share_process = 0;
					}
					else {
					$this_post_share_process = 1;
					}
						kuas_render_social_share_count(get_permalink(),'summary',get_the_ID(),$this_post_share_process,1);
						kuas_lazy_share(get_the_ID(),1);
						do_action('kuas_social_count_action');
					?>
					</div>

					<?php } else { ?>
					<div class="clear" style="margin:10px 0">
					<div style="margin-right:30px;"><?php kuas_render_social_share_count(get_permalink(),'count',get_the_ID(),0,1); echo ' / '; _e('dibagikan ke media sosial','kuas-beta'); ?></div>
					<?php do_action('kuas_social_count_action'); ?></div>
					<?php } ?>

					<div class="post-entry">
						<?php the_content(); ?>				
					</div><!-- /post-entry -->

					<?php

					if(function_exists('wp125_single_ad') && wp_is_mobile()==false){
						wp125_single_ad(4,true);
					}

					if(is_singular('post')):
					?>					
					
					<div id="post-info">

					<?php if ( ( kuas_beta_get_option( 'show_author' ) == 1 ) ) { ?>

						<div class="author">
								<?php if (function_exists('get_avatar')) { ?>
									<a href="<?php _e(get_author_posts_url(get_the_author_meta('ID'))) ?>">
									<?php _e(get_avatar( get_the_author_meta('email'), '75' ), 'kuas-beta'); ?></a>
								<?php } ?>
							<div class="author-meta">
								<div class="name"><?php _e('oleh', 'kuas-beta') ?> <strong><?php the_author_posts_link(); ?></strong>,
								<br/><a href="<?php _e(get_author_posts_url(get_the_author_meta('ID'))) ?>#post-<?php _e(get_the_author_meta('user_login')) ?>"><?php printf(__('lihat %s tulisan lainnya &raquo;', 'kuas-beta'),count_user_posts(get_the_author_meta('ID'))) ?></a></div>
								<?php kuas_beta_author_contact_list(get_the_author_meta('ID'),true) ?>
							</div>					
						</div><!-- /author-meta -->
					
					<?php } ?>					

						<div class="post-meta">	
							<ul>
							<li class="post-date"><?php _e('dimuat ','kuas-beta'); the_time(get_option('date_format')); ?></li>
							<li class="viewed-count"><font size="3"><?php
							if(function_exists('the_views')){
								if( the_views(false) > 0 ){the_views(true).' '._e('dibaca','kuas-beta');}else{ _e('belum dibaca','kuas-beta'); }
							}
							?></font></li>			
							<li class="category"><?php _e('Saluran:', 'kuas-beta').' '.the_category(' / '); ?></li>
							<li class="tag"><?php the_tags( '<span class="tags">' . __('Topik: ', 'kuas-beta' ) . ' #', " #", "</span>" ) ?></li>
							</ul>							
						</div><!-- /post-meta -->
			
                  	</div> <!-- Post-Info -->
					
					<?php endif; ?>

					</div><!-- post -->
					<?php wp_link_pages(array('before' => '<div class="pagination">' . __('Halaman:', 'kuas-beta'), 'after' => '</div>')); ?>
								
				<?php 
				do_action('kuas_between_post_comments');
				comments_template( '', true ); ?>
            
			<?php endwhile; ?> 
		
			<?php if (  $wp_query->max_num_pages > 1 ) { ?>
       
				<div class="navigation">
					<div class="previous"><?php next_posts_link( __( '&#8249; Info Lawas', 'kuas-beta' ) ); ?></div>
					<div class="next"><?php previous_posts_link( __( 'Info Baru &#8250;', 'kuas-beta' ) ); ?></div>
				</div>

			<?php }

			wp_reset_postdata();

			} 
			else { ?>
		
			<div id="post-0" class="post">
				<h1><?php _e( 'Tidak Ada', 'kuas-beta' ); ?></h1>
				<p><?php _e('Maaf, tapi tidak ada hasil yang ditemukan untuk arsip yang diminta. Mungkin pencarian akan membantu.', 'kuas-beta'); ?></p>			
				<?php get_search_form(); ?>	
			</div>
			
		<?php } 

		do_action('kuas_footer_post_action');

		?> <!-- /have_posts -->		
	
	</div>	
</div><!-- /content -->

<?php 
if(wp_is_mobile()==false){
get_sidebar('rightpage');
if( is_single() &&  is_singular('post') ): get_sidebar('middle'); endif; 
}
?>
<?php get_footer(); ?>