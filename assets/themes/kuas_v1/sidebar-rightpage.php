<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @file      index.php
 * @package   kuas-beta
 * @author    Kuningan_Asri.
 * @link 	  http://kuas.com
 */
 ?> 
<div id="sidebar" class="sidebar_right single">

		<?php 
		if ( ! dynamic_sidebar( 'sidebar-5' ) ) : endif;  
		
		if(is_single()){
			if ( ! dynamic_sidebar( 'sidebar-4' ) ) : endif;
		}
		?>

		<?php if(is_category() OR is_tag() OR is_archive()){ 
			if ( ! dynamic_sidebar( 'sidebar-4' ) ) :  endif; 
		} ?>
		
		<?php
		// Ads spot 1 & 2
		if(is_single() OR is_page()):
		wp125_single_ad(1);
		wp125_single_ad(2);
		endif;
		?>
		<?php
		// Ads spot 3
		if(is_category() OR is_tag() OR is_archive()):
		wp125_single_ad(3);
		wp125_single_ad(8);
		wp125_single_ad(2);
		endif;
		?>
</div>
		