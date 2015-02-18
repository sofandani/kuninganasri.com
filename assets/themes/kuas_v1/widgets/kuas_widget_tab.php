<?php
/*
 * Widget Name: Social Links Widget
 * Widget URI: http://kuas.com/
 * Description: A widget to display the social links in footer or sidebar.
 * Version: 1.0
 * Author: KuAs.
 * Author URI: http://kuas.com/
 */

add_action( 'widgets_init', 'kuas_beta_tabs_widgets' );

function kuas_beta_tabs_widgets() {
	register_widget( 'kuas_beta_tabs_widget' );
}

class kuas_beta_tabs_widget extends WP_Widget {

function kuas_beta_tabs_widget() {
	/* Widget settings */
	$widget_ops = array( 'classname' => 'widget_tabs', 'description' => __('Membuat tab multi konten brooh.', 'kuas-beta') );

	/* Create the widget */
	$this->WP_Widget( 'kuas_beta_tabs_widget', __('KuAs: Multi Tabs', 'kuas-beta'), $widget_ops );
}
	
function widget( $args, $instance ) {
		extract( $args );
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$control_tab = $instance['control_tab'];
		$header_tab = $instance['header_tab'];
		$content_tab = $instance['content_tab'];
		$autoplay_interval = $instance['autoplay_interval'];
		$initial_tab = $instance['initial_tab'];
		$auto_height = $instance['auto_height'];
		$color_tab = $instance['color_tab'];
		
		$symbol_exlplode = array('-','_',',','.');
		$uraikan_control = kuas_beta_multiexplode($symbol_exlplode, $control_tab);
		$uraikan_content = kuas_beta_multiexplode($symbol_exlplode, $content_tab);
		$uraikan_header = kuas_beta_multiexplode($symbol_exlplode, $header_tab);

		echo $before_widget;

		echo '<div id="tabs-widget" class="container_tabs">
			  <div id="kuas-tabs" class="borderColorBaseKuas kuas-tabs kuastab-menus '.$color_tab.' initialTab-'.$initial_tab.' autoplayInterval-'.$autoplay_interval.' autoHeight-'.$auto_height.'">';

		//if ( $title ){ echo $before_title . $title . $after_title; }

			echo '<ul class="kuas-tabs_tab_container">';
			foreach($uraikan_control as $k=>$controltab){
				if($k==0){
				echo '<li class="active"><a>'.$controltab.'</a></li>';	
				}
				else{
				echo '<li><a>'.$controltab.'</a></li>';
				}
			}
			echo '</ul>';

			echo '<div class="kuas-tabs_content_container borderColorBaseKuas bgColorBaseKuas_y3">';

				echo '<div class="kuas-tabs_content_inner">';
					foreach($uraikan_content as $k=>$content_tab){
					echo '<div class="kuas-tabs_content">';
						//if($uraikan_header[$k]!=0){
							echo '<h3 class="bgColorBaseKuas_y2 display-block headingColorBaseKuas" style="padding:10px">'.$uraikan_header[$k].'</h3>';
							echo '<div class="kuas-tabs-inner_content_child">'.$content_tab.'</div>';
						//}
						//else {
						//	echo $content_tab;
						//}
					echo '</div>';
					}
				echo '</div>';

			echo '</div>';
        	echo '</div></div>';
		echo $after_widget;
	}
	
function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']				= strip_tags( $new_instance['title'] );
		$instance['control_tab'] 		= $new_instance['control_tab'];
		$instance['header_tab'] 		= $new_instance['header_tab'];
		$instance['content_tab'] 		= $new_instance['content_tab'];
		$instance['autoplay_interval'] 	= $new_instance['autoplay_interval'];
		$instance['initial_tab'] 		= $new_instance['initial_tab'];
		$instance['auto_height'] 		= $new_instance['auto_height'];
		$instance['color_tab'] 			= $new_instance['color_tab'];
		return $instance;
	}

	function form( $instance ) {
	
		/* Set up some default widget settings. */
		$defaults = array(
		'title' => 'Kuas Tabs',
		'control_tab' => 'Tab 1, Tab 2, Tab 3',
		'header_tab' => 'Header Tab 1, Header Tab 2, Header Tab 3',
		'content_tab' => 'Content Tab 1, Content Tab 2, Content Tab 3',
		'autoplay_interval' => 0,
		'initial_tab' => 0,
		'auto_height' => 0,
		'color_tab' => 'yellow',
		
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<!-- Widget Title: Text Input -->
		<h3>Separate allowed is:<br/> , - _ . | </h3>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'control_tab' ); ?>"><?php _e('Control Tab (use separate character):', 'kuas-beta') ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id( 'control_tab' ); ?>" name="<?php echo $this->get_field_name( 'control_tab' ); ?>" style="height: 60px;"><?php echo $instance['control_tab']; ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'header_tab' ); ?>"><?php _e('Header Tab (use separate character):', 'kuas-beta') ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id( 'header_tab' ); ?>" name="<?php echo $this->get_field_name( 'header_tab' ); ?>" style="height: 60px;"><?php echo $instance['header_tab']; ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'content_tab' ); ?>"><?php _e('Content Tab (use separate character):', 'kuas-beta') ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id( 'content_tab' ); ?>" name="<?php echo $this->get_field_name( 'content_tab' ); ?>" style="height: 200px;"><?php echo $instance['content_tab']; ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'autoplay_interval' ); ?>"><?php _e('Auto Play Interval:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'autoplay_interval' ); ?>" name="<?php echo $this->get_field_name( 'autoplay_interval' ); ?>" value="<?php echo $instance['autoplay_interval']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'auto_height' ); ?>"><?php _e('Auto Height:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'auto_height' ); ?>" name="<?php echo $this->get_field_name( 'auto_height' ); ?>" value="<?php echo $instance['auto_height']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'initial_tab' ); ?>"><?php _e('Initial Tab:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'initial_tab' ); ?>" name="<?php echo $this->get_field_name( 'initial_tab' ); ?>" value="<?php echo $instance['initial_tab']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'color_tab' ); ?>"><?php _e('Color Tab:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'color_tab' ); ?>" name="<?php echo $this->get_field_name( 'color_tab' ); ?>" value="<?php echo $instance['color_tab']; ?>" />
		</p>						
	<?php
	}
}

?>