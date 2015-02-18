<?php
/**
 * The template for displaying Search Results pages.
 *
 * @file      search.php
 * @package   kuas-beta
 * @author    Sami Ch.
 * @link 	  http://gazpo.com
 */
?>
<?php get_header(); ?>

	<?php if (have_posts()) : ?>

			<div id="content">

				<h2 class="page-title textShadowBaseKuasLight icon">&ldquo;<?php wp_title('', true); ?>&rdquo;</h2>

				<div id="wrapper_post_list">
					<div id="posts-list">
					<?php if ( function_exists('kuas_beta_list_post') ) { kuas_beta_list_post('excerpt',0); } ?>	
					</div>

					<div id="pagination">
						<?php if ( function_exists('kuas_beta_pagination') ) { kuas_beta_pagination(); } ?>	
					</div>
				
				</div>
			
			</div><!-- /content -->

			<?php
				if(wp_is_mobile()==false){
				get_sidebar('big');
				}
			?>

		<?php else : ?>	
	
			<div id="content" class="wide-content galau_cari bgKuasLogoStandar250 bgNoRepeat nobackground-color">
	
				<div id="post-0" class="post">
				<h2 align="center"><?php _e( 'Galau (galat layauw)', 'kuas-beta' ); ?></h2>
				<p>
				<?php _e('Kata kunci yang dicari tidak ada di database kami. Coba deh cari kata kunci lain :)', 'kuas-beta'); ?>							
				</p>
				<?php get_search_form(); ?>	
				</div>
			</div><!-- /content -->
		
		<?php endif; ?>
	
<?php get_footer(); ?>