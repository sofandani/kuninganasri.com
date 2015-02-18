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
<div id="sidebar" class="sidebar_right">
		
	<?php if(!is_page()){
			if ( ! dynamic_sidebar( 'sidebar-1' ) ) : endif;
		}
		else {
		if ( ! dynamic_sidebar( 'sidebar-4' ) ) : endif;
		}
		?>
		
		<?php
		if(function_exists('wp125_single_ad')): wp125_single_ad(2); endif; 

		// Ads spot 3
		if(is_page()): wp125_single_ad(1); else: wp125_single_ad(3); endif;		
		?>

</div><!-- /sidebar -->
		