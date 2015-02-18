<?php
/**
 * The template for displaying date-based Archive pages.
 *
 * @file      archive.php
 * @package   kuas-beta
 * @author    Sami Ch.
 * @link 	  http://gazpo.com
 * 
 **/
?> 
<?php get_header(); ?>
	<div id="content" >	
		<h2 class="page-title textShadowBaseKuasLight icon">
			<?php if ( is_day() ) : ?>
				<?php printf( __( 'Arsip Harian: %s', 'kuas-beta' ), '<br /><span>' . get_the_date() . '</span>' ); ?>
			<?php elseif ( is_month() ) : ?>
				<?php printf( __( 'Arsip Bulanan: %s', 'kuas-beta' ), '<br /><span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'kuas-beta' ) ) . '</span>' ); ?>
			<?php elseif ( is_year() ) : ?>
				<?php printf( __( 'Arsip Tahunan: %s', 'kuas-beta' ), '<br /><span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'kuas-beta' ) ) . '</span>' ); ?>
			<?php else: ?>
				<?php 
				_e('Arsip Lencana dan Penghargaan','kuas-beta');
				//$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
				//_e( '&quot;'.$curauth->display_name.'&quot; punya '.count_user_posts($curauth->ID).' tulisan, <span>di web '.get_bloginfo('name').' </span>', 'kuas-beta' ); ?>
			<?php endif; ?>
		</h2>	

		<div id="wrapper_post_list">	
		<div id="posts-list">
			
			<?php if ( function_exists('kuas_beta_list_post') ) { kuas_beta_list_post('excerpt'); } ?>	

		</div>

			<?php
			if ( function_exists('kuas_beta_pagination') ) { kuas_beta_pagination(); }
			?>	
		</div>
	</div>
	
<?php
if(wp_is_mobile()){

} 
else {
get_sidebar('big');
}
?>
<?php get_footer(); ?>