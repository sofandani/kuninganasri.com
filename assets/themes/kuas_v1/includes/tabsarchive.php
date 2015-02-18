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
		<li><a><?php _e('KuAs TV', 'kuas-beta') ?></a></li>
	</ul>
	<div class="kuas-tabs_content_container borderColorBaseKuas bgColorBaseKuas_y3">
		<div class="kuas-tabs_content_inner">
			<div class="kuas-tabs_content">
				<h3 class="bgColorBaseKuas_y2 display-block headingColorBaseKuas" style="padding:10px">
				<?php printf( __( 'Saluran %s', 'kuas-beta' ), '<a class="normalTip" href="' . esc_url( site_url('kuas-tv') ) . '" title="Klik untuk melihat ' . __('Video List') . '">' . get_bloginfo('name') . ' TV</a>' ); ?>
				</h3>
				<div class="kuas-tabs-inner_content_child">
				<!--Content Youtube Channel (TV).-->
				<?php echo do_shortcode('[Youtube_Channel_Gallery feed="playlist" user="PLwaPMKnbolyKqUnzTj3xrelj0UvYJT447" player="0" maxitems="9" thumbwidth="150" thumbratio="16x9" thumbcolumns="3" title="0" description="0" thumbnail_alignment="top" thumb_window="0" cache="1" cache_time="24"]') ?>
				</div>
			</div>
		</div>
	</div>
</div>