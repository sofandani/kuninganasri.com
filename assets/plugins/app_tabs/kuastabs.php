<?php
/*
Plugin Name: App KuAsTabs
Plugin URI: http://kuas.com
Description: KuAsTabs for WordPress is tabed content.
Author: KuAs
Version: 1.0
Author URI: http://www.kuas.com
*/

add_action( 'init', 'mce_wrapper_init', 9);

	add_action( 'wp_enqueue_scripts', 'load_frontend_scripts', 20 );

	add_shortcode('kuastabs', 'kuastabs');
	add_shortcode('kuastabs_tab_container', 'kuastabs_tab_container');
	add_shortcode('kuastabs_tab', 'kuastabs_tab');
	add_shortcode('kuastabs_content_container', 'kuastabs_content_container');
	add_shortcode('kuastabs_content_head', 'kuastabs_content_head');
	add_shortcode('kuastabs_inner_content', 'kuastabs_inner_content');
	add_shortcode('kuastabs_content', 'kuastabs_content');

function mce_wrapper_init() {
	if (is_admin())
		new mcewrapper();
}

function load_frontend_scripts(){
	$url = rtrim(WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)));
	wp_register_script( 'kuastabs-script', $url . '/kuastabs.js', array('jquery'), '1.32' );
	wp_enqueue_script('kuastabs-script');

	wp_register_style('kuastabs-style', $url . '/kuastabs.css');
	wp_enqueue_style('kuastabs-style');
}

class mcewrapper {

	var $pluginname = 'KuAsTabs';
	var $internalVersion = 600;

	/**
	 * mcewrapper::mcewrapper()
	 * the constructor
	 *
	 * @return void
	 */
	function mcewrapper()  {

		// Modify the version when tinyMCE plugins are changed.
		add_filter('tiny_mce_version', array (&$this, 'change_tinymce_version') );

		// init process for button control
		add_action('init', array (&$this, 'addbuttons') );
	}



	/**
	 * mcewrapper::addbuttons()
	 *
	 * @return void
	 */
	function addbuttons() {

		// Don't bother doing this stuff if the current user lacks permissions
		if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') )
			return;

		// Add only in Rich Editor mode
		if ( get_user_option('rich_editing') == 'true') {

			// add the button for wp2.5 in a new way
			add_filter("mce_external_plugins", array (&$this, 'add_tinymce_plugin' ));
			add_filter('mce_buttons', array (&$this, 'register_button' ), 0);
		}
	}

	/**
	 * mcewrapper::register_button()
	 * used to insert button in wordpress 2.5x editor
	 *
	 * @return $buttons
	 */
	function register_button($buttons) {

		array_push($buttons, 'separator', $this->pluginname );

		return $buttons;
	}

	/**
	 * mcewrapper::add_tinymce_plugin()
	 * Load the TinyMCE plugin : editor_plugin.js
	 *
	 * @return $plugin_array
	 */
	function add_tinymce_plugin($plugin_array) {

		$plugin_array[$this->pluginname] = plugins_url( 'editor_plugin.js', __FILE__ );

		return $plugin_array;
	}

	/**
	 * mcewrapper::change_tinymce_version()
	 * A different version will rebuild the cache
	 *
	 * @return $version
	 */
	function change_tinymce_version($version) {
		$version = $version + $this->internalVersion;
		return $version;
	}


}


function kuastabs( $atts, $content = null ) {
	$merged = ( shortcode_atts( array(
		'width' => '',
		'initialtab' => 1,
		'autoplayinterval' => 0,
		'color' => 'yellow'
	), $atts ) );

	$colors_avail = array('yellow');

	if(is_numeric($merged['width'])){
		$width = 'width:' . $merged['width'] . 'px';
	}else{
		$width = '';
	}
	if(!in_array($merged['color'], $colors_avail)) $merged['color'] = 'yellow';
	/*if(is_numeric($merged['initialTab'])){
		$merged['initialTab'] = $merged['initialTab']-1;
	}else{
		$merged['initialTab'] = 0;
	}
	if(!is_numeric($merged['autoplayInterval'])) $merged['autoplayInterval'] = 0;
	*/
	$classes = $merged['color'] . ' initialTab-' . ($merged['initialtab']-1)  . ' autoplayInterval-' . $merged['autoplayinterval'];

	$output = '<div id="kuas-tabs" class="borderColorBaseKuas kuas-tabs kuastab-menus '. $classes .'" style="'. $width .'">';
	$output .= do_shortcode(kuas_fix_p_shortcode($content));
	$output .= '</div>';

	return $output;
}

function kuastabs_tab_container( $atts, $content = null ) {
	$output  = '<ul class="kuas-tabs_tab_container">';
	$output .= (do_shortcode($content));
	$output .= '</ul>';

	return $output;
}

function kuastabs_tab( $atts, $content = null ) {
	$output  = '<li><a>';
	$output .= (do_shortcode($content));
	$output .= '</a></li>';

	return $output;
}

function kuastabs_content_container( $atts, $content = null ) {
	$output  = '<div class="kuas-tabs_content_container borderColorBaseKuas bgColorBaseKuas_y3">';
	$output .= '<div class="kuas-tabs_content_inner">';
	$output .= (do_shortcode($content));
	$output .= '</div>';
	$output .= '</div>';

	return $output;
}

function kuastabs_content_head( $atts, $content = null ) {
	$output  = '<h3 class="kuas-tabs_head_content bgColorBaseKuas_y2 display-block headingColorBaseKuas" style="padding:10px">';
	$output .= (do_shortcode($content));
	$output .= '</h3>';

	return $output;
}

function kuastabs_inner_content( $atts, $content = null ) {
	$output  = '<div class="kuas-tabs-inner_content_child">';
	$output .= (do_shortcode($content));
	$output .= '</div>';

	return $output;
}

function kuastabs_content( $atts, $content = null ) {
	$output  = '<div class="kuas-tabs_content">';
	$output .= (do_shortcode($content));
	$output .= '</div>';

	return $output;
}
