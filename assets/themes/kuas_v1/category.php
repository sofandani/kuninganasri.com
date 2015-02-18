<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @file      category.php
 * @package   kuas-beta
 * @author    Sami Ch.
 * @link 	  http://gazpo.com
 * 
 **/
?>
<?php 
get_header(); 
$kuas_show_count_category = thousandsCurrencyFormat( kuas_show_count_category() );
?>

	<div id="content" >	
	
	<?php
	if( have_posts() ) {
	?>
		<h2 class="page-title textShadowBaseKuasLight icon">
			<?php
				if($kuas_show_count_category > 0){
					printf( __( 'Anda ada di saluran <span>%1$s</span><br />Total %2$s Tulisan', 'kuas-beta' ), single_cat_title( '', false ), $kuas_show_count_category );
				} else {
					printf( __( 'Anda ada di,<br/>Saluran <span>%s</span>', 'kuas-beta' ), single_cat_title( '', false ) );
				}
			?>
		</h2>

		<?php
			$category_description = category_description();
				if ( ! empty( $category_description ) )
					echo apply_filters( 'category-archive-meta', '<div class="archive-meta">' . $category_description . '</div>' );
		?>
	<div id="wrapper_post_list">
		<div id="posts-list">
			<?php
			if ( function_exists('kuas_beta_list_post') ) { kuas_beta_list_post(); }
			?>	
		</div>
		<?php if ( function_exists('kuas_beta_pagination') ) { kuas_beta_pagination(); } ?>
	</div>
	<?php
	}
	else {
	?>
	<div id="wrapper_post_list">
		<div id="error_message">
		<?php printf( __( 'Tidak ada tulisan di saluran <strong>%s</strong>', 'kuas-beta' ), single_cat_title( '', false ) ); ?>
		</div>
	</div>
	<?php
	}	
	?>
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