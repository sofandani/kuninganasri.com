<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @file      tabshome.php
 * @package   kuas-beta
 * @author    Kuningan_Asri.
 * @link 	  http://kuas.com
 */
 ?> 

<div id="kuas-tabs" class="borderColorBaseKuas kuas-tabs kuastab-menus yellow initialTab-0 autoplayInterval-0 autoHeight-1 autoOrder-1 direction-vertical">
	<ul class="kuas-tabs_tab_container">
		<li><a><?php _e('Favorit KuAs', 'kuas-beta') ?></a></li>
		<li><a><?php _e('Tulisan Terkini', 'kuas-beta') ?></a></li>
		<li><a><?php _e('Tempat', 'kuas-beta') ?></a></li>
		<li><a><?php _e('KuAs TV', 'kuas-beta') ?></a></li>
	</ul>
	<div class="kuas-tabs_content_container borderColorBaseKuas bgColorBaseKuas_y3">
		<div class="kuas-tabs_content_inner">
			<div class="kuas-tabs_content">
			<?php kuas_beta_slider_category('is_sticky',10,'DESC','date',false); ?>
			</div>
			<div class="kuas-tabs_content">
			<?php
				//include carousel posts
				if ( kuas_beta_get_option( 'show_carousel' ) == 1 ){
					$carousel_cat_id = kuas_beta_get_option('carousel_category');
					kuas_beta_slider_category($carousel_cat_id,3,'DESC','date',false);
				}
			?>
			</div>
			<div class="kuas-tabs_content">
			<?php kuas_beta_slider_category('37,38,46',3,'rand',false); ?>
			</div>
			<div class="kuas-tabs_content">
				<h3 class="bgColorBaseKuas_y2 display-block headingColorBaseKuas" style="padding:10px">
				<?php printf( __( 'Saluran %s', 'kuas-beta' ), '<a class="normalTip" href="' . esc_url( site_url('kuas-tv') ) . '" title="' . __('Klik untuk melihat Video List','kuas-beta') . '">' . get_bloginfo('name') . ' TV</a>' ); ?>
				</h3>
				<div class="kuas-tabs-inner_content_child">
				<!--Content Youtube Channel (TV).-->
				<?php echo do_shortcode('[Youtube_Channel_Gallery feed="playlist" user="PLwaPMKnbolyKqUnzTj3xrelj0UvYJT447" player="0" maxitems="9" thumbwidth="150" thumbratio="16x9" thumbcolumns="3" title="0" description="0" thumbnail_alignment="top" thumb_window="0" cache="1" cache_time="24"]') ?>
				</div>
			</div>
		</div>
	</div>
</div>