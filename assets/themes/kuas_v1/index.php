<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @file      index.php
 * @package   kuas-beta
 * @author    Kuningan_Asri.
 * @link 	  http://kuas.com
 */
?> 
<?php
//Handled direct access index.php in directories search or linking to 404
if(function_exists('get_header')):
get_header(); 
?>

<?php
if (is_home() && $paged < 2 ){
?>
	<div id="premiere-content">

	<?php				
	if ( kuas_beta_get_option( 'show_feat_cats' ) == 1) { ?>
		
		<div id="featured-categories">

		<?php
			if(wp_is_mobile()){
				echo '<h2 class="headingColorBaseKuas">'.sprintf(__('Topik Utama %s','kuas-beta'), get_bloginfo( 'name' )).'</h2>';
				echo '<div class="clear"></div>';
				kuas_beta_featured_post_mobile(4,1);
				echo '<div class="clear"></div><hr class="clear glyph">';
				echo '<h2 class="headingColorBaseKuas">'.sprintf(__('Tempat Menarik di %s','kuas-beta'), get_bloginfo( 'name' )).'</h2>';
				echo '<div class="clear"></div>';
				kuas_post_by_venue_id_mobile(4,1);
			}
			else{
				$number_featured_show = array(	1=>array('limit_top'=>1, 'limit_child'=>2, 'limit_excerpt'=>0, 'limit_carousel'=>2, 'orderby'=>array('date','date'),'order'=>array('DESC','DESC'), 'carousel'=>false),	//Categories 1
												2=>array('limit_top'=>1, 'limit_child'=>10, 'limit_excerpt'=>0, 'limit_carousel'=>2, 'orderby'=>array('date','date'),'order'=>array('DESC','DESC'), 'carousel'=>true),	//Categories 2
												3=>array('limit_top'=>1, 'limit_child'=>2, 'limit_excerpt'=>0, 'limit_carousel'=>2, 'orderby'=>array('rand','rand'),'order'=>array('DESC','DESC'), 'carousel'=>false),	//Categories 3
												4=>array('limit_top'=>1, 'limit_child'=>2, 'limit_excerpt'=>0, 'limit_carousel'=>2, 'orderby'=>array('date','date'),'order'=>array('DESC','DESC'), 'carousel'=>false)	//Categories 4									   										   
										);
				foreach ($number_featured_show as $k=>$feat) {
					if ( kuas_beta_get_option( 'feat_cat'.$k ) != 0) {
						echo (kuas_beta_featured_post(kuas_beta_get_option( 'feat_cat'.$k ), $feat['limit_top'], $feat['limit_child'], $feat['limit_carousel'], $feat['limit_excerpt'], array($feat['orderby'][0],$feat['orderby'][1]), array($feat['order'][0], $feat['order'][1]), $feat['carousel']) );
					}
					else {
						echo (kuas_beta_featured_post('recents', $feat['limit_top'], $feat['limit_child'], $feat['limit_carousel'], $feat['limit_excerpt'], array($feat['orderby'][0],$feat['orderby'][1]), array($feat['order'][0], $feat['order'][1]), $feat['carousel']) );
					}
				}
			}
		?>

		</div> <!-- /featured-categories -->	

	<?php 
	} 
	?>

	</div>
	
<?php 
}
?>

<?php
if(wp_is_mobile()){

}
else{
	get_sidebar('left');
}
?>

<div id="content">		
		
		<?php
			//show on homepage only
			if (is_home() && $paged < 2 ){

				//Sub Category
				if ( kuas_beta_get_option( 'show_subcategories' ) != 0) {
					$id_cat = kuas_beta_get_option( 'sub_categories' );
					if(wp_is_mobile()){
						echo '<hr class="clear glyph"><div id="featured-categories"><h2 class="headingColorBaseKuas">'.__('Komunitas','kuas-beta').' '.get_bloginfo( 'name' ).'</h2></div>';
					}
					_e(kuas_beta_subcategory_list($id_cat, 'wide', false, 1), 'kuas-beta');
				}

				if(function_exists('wp125_single_ad') && wp_is_mobile()==false){
					echo '<div class="post">';
					wp125_single_ad(4,true);
					echo '</div>';
				}
			
				if(wp_is_mobile()==false){
					//LeTab Show Manually
					get_template_part('includes/tabshome');
				}
			} //is_home 
		
			//include latest posts
			if ( kuas_beta_get_option( 'show_posts_list' ) != 0) { ?>
				<div id="posts-list">
					
					<?php
					if ( function_exists('kuas_beta_list_post') ) { kuas_beta_list_post(); }	
					if ( function_exists('kuas_beta_pagination') ) { kuas_beta_pagination(); }
					?>	

				</div>			
			<?php
			}

			//no option is set in the homepage, display posts list.
			if (( kuas_beta_get_option( 'show_slider' ) == 0) and 
				( kuas_beta_get_option( 'show_feat_cats' ) == 0) and 
				( kuas_beta_get_option( 'show_carousel' ) == 0) and 
				( kuas_beta_get_option( 'show_posts_list' ) == 0)){
			?>
				<div class="no-posts-notice">
					<div id="error_message"><?php _e('Please enable theme settings from the theme options', 'kuas-beta'); ?></div>
				</div>

				<div id="posts-list">
					
					<?php
					if ( function_exists('kuas_beta_list_post') ) { kuas_beta_list_post(); }	
					if ( function_exists('kuas_beta_pagination') ) { kuas_beta_pagination(); }
					?>	

				</div>
			
			<?php }	?>
		
</div><!-- /content -->
 
<?php
	if(wp_is_mobile()==false){
		get_sidebar('right');
	}
	get_footer();
else:
	print('<meta http-equiv="refresh" content="0;url=http://'.$_SERVER['HTTP_HOST'].'/404">');
endif;
?>