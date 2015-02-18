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
<div id="sidebar" class="sidebar_big">

		<?php if ( ! dynamic_sidebar( 'sidebar-6' ) ) : ?>			
				
			<div class="widget widget_text">
				<h3><?php _e( 'KuAs Theme', 'kuas-beta' ); ?></h3>			
				<div class="textwidget"><?php _e( 'Sidebar Big Widget', 'kuas-beta' ); ?></div>
			</div>
							
		<?php endif; // end sidebar widget area 

				//LeTab Show Manually
				get_template_part('includes/tabsarchive');
				
		?>

		
</div><!-- /sidebar -->
		