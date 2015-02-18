<?php

class WP125_Widget extends WP_Widget {

	function WP125_Widget() {
		parent::WP_Widget(
			'wp125',
			'WP125',
			array( 'description' => 'Displays your ads' )
		);
	}

	function widget($args, $instance) {
		extract($args);
		echo $before_widget;
		if (get_option("wp125_widget_title")!='') {
			echo "\n".$before_title; echo get_option("wp125_widget_title"); echo $after_title;
		}
		wp125_write_ads();
		echo $after_widget;
	}
	
	function form($instance) {
	}

	function update($new_instance, $old_instance) {
	}

}

?>