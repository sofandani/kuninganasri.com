<?php
/**
 * The Tag page
 *
 * This page is used to display Tag Archive pages
 * 
 * @file      tag.php
 * @package   kuas-beta
 * @author    Sami Ch.
 * @link 	  http://gazpo.com
 */
 ?> 
<?php get_header(); ?>

	<div id="content" >	
		<h2 class="page-title textShadowBaseKuasLight icon"><?php printf( __( 'Topik:<br />%s', 'kuas-beta' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?></h2>

		<?php
			$tag_description = tag_description();
				if ( ! empty( $tag_description ) )
						echo apply_filters( 'tag_archive_meta', '<div class="archive-meta">' . $tag_description . '</div>' );
		?>
		
		<div id="wrapper_post_list">
		<div id="posts-list">
			
			<?php
			if ( function_exists('kuas_beta_list_post') ) { kuas_beta_list_post(); }	
			?>	

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
get_sidebar('rightpage');
get_sidebar('middle');
}
?>
<?php get_footer(); ?>