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
<div id="sidebar" class="sidebar_left">
		
		<?php if ( ! dynamic_sidebar( 'sidebar-2' ) ) : ?>			
				
			<div class="widget widget_text">
				<h3><?php _e( 'KuAs Theme', 'kuas-beta' ); ?></h3>			
				<div class="textwidget"><?php _e( 'Sidebar Left Widget', 'kuas-beta' ); ?></div>
			</div>			

		<?php endif; // end sidebar widget area ?>

		<?php /* Ads spot 1 */ if(function_exists('wp125_single_ad')): ?>
		<div id="kuas_pariwara_widget-1" class="kuas_pariwara_widget">
		<?php /* Ads spot 1 */ wp125_single_ad(1); ?>
		</div>
		<?php /* Ads spot 1 */  wp125_single_ad(8); endif; ?>

</div><!-- /sidebar -->
		