<?php
	$options = get_option( $this->options_name );
	/* General Settings
	===========================================*/

	
	
	$p_types = get_post_types( '', 'names' ); 
	if( !empty( $p_types))
	{

	$this->settings['p_types'] = array(
		'section' => 'general',
		'title'   => __( 'Post Types' , $this->plugin_slug),
		'type'    => 'checkbox',
		'std'     => array('yes'),
		'desc'    => __( 'Select which posts types you want to export images from', $this->plugin_slug),		
		'choices' => $p_types
		);
	}

