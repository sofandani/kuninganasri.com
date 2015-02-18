<?php

/**********
* License
****************************************************************************
*	Copyright (C) 2011-2013 Damian Logghe and contributors
*
*	Permission is hereby granted, free of charge, to any person obtaining
*	a copy of this software and associated documentation files (the
*	"Software"), to deal in the Software without restriction, including
*	without limitation the rights to use, copy, modify, merge, publish,
*	distribute, sublicense, and/or sell copies of the Software, and to
*	permit persons to whom the Software is furnished to do so, subject to
*	the following conditions:
*
*	The above copyright notice and this permission notice shall be
*	included in all copies or substantial portions of the Software.
*
*	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
*	EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
*	MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
*	NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
*	LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
*	OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNWPEFIION
*	WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
****************************************************************************
 * @author      Damian Logghe <info@timersys.com>
 * @license     MIT License https://github.com/serbanghita/Mobile-Detect/blob/master/LICENSE.txt
 * @link        GitHub Repository: https://github.com/timersys/wp-plugin-base
 * @version     1.3
 */

/*
* I also took quite lot of code from http://alisothegeek.com/2011/04/wordpress-settings-api-tutorial-follow-up/
*
*/


// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


  
class WPEFI_Admin_Base {

    /**
     * Version of this class
     *
     * @since    1.3
     *
     * @var      strng
     */
	protected $version = '1.3';
	
	function __construct() {
	
		$this->WPB_ABS_PATH 	=   WP_PLUGIN_DIR . '/'. $this->WPB_SLUG;
		$this->WPB_REL_PATH		=	dirname( plugin_basename( __FILE__ ) );
		$this->WPB_PLUGIN_URL	=	plugins_url('', __FILE__ );// for domain mapping

		
		//Load all fields and defaults
		add_action( 'admin_init', array( &$this, 'get_settings' ),100 );
				
		//register database options and prepare fields with settings API
        add_action( 'admin_init', array( &$this, 'register_settings' ),100 );
		
		//load js and css 
		add_action( 'init',array(&$this,'load_base_scripts' ),10 );	
			
		
	}	
			

	/**
	 * Settings and defaults
	 * 
	 * @since 1.0
	 */
	public function get_settings() {
		
		require_once('wp-base/fields.php');
		if ( ! get_option( $this->options_name ))
			$this->initialize_settings();
	}

	/**
	* Register settings
	*
	* @since 1.0
	*/
	public function register_settings() {
		
		
	
		register_setting( $this->options_name, $this->options_name, array ( &$this, 'validate_settings' ) );
				
		foreach ( $this->sections as $slug => $title ) {
			add_settings_section( $slug, $title, array( &$this, 'display_section' ), $this->options_name );
		}
		
		$this->get_settings();
		
		foreach ( $this->settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}
		
	}
	
	/**
	 * Initialize settings to their default values
	 * 
	 * @since 1.0
	 */
	public function initialize_settings() {
		
		
		$default_settings = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' )
				$default_settings[$id] = $setting['std'];
		}
		
		update_option( $this->options_name, $default_settings );
		
	}

	/**
	 * Create settings field
	 *
	 * @since 1.0
	 */
	public function create_setting( $args = array() ) {
		
		$defaults = array(
			'id'      => 'default_field',
			'title'   => __( 'Default Field', $this->WPB_PREFIX ),
			'desc'    => __( 'This is a default description.' , $this->WPB_PREFIX),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general',
			'choices' => array(),
			'onclick' => '',
			'disabled' => '',
			'class'   => ''
		);
			
		extract( wp_parse_args( $args, $defaults ) );
		
		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'onclick'	=> $onclick,
			'class'     => $class,
			'title'		=> $title,
			'disabled'	=> $disabled,
		);
		
		if ( $type == 'checkbox' )
			$this->checkboxes[] = $id;
		
		add_settings_field( $id, $title, array( $this, 'display_setting' ), $this->options_name, $section, $field_args );
	}

	/**
	 * HTML output callback for fields
	 *
	 * @since 1.0
	 */
	public function display_setting( $args = array() ) {
		
		extract( $args );
		
		$options = get_option( $this->options_name );
		
		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;
		
		$field_class = '';
		if ( $class != '' )
			$field_class = ' ' . $class;
		
		switch ( $type ) {
			
			case 'heading':
				echo '</td></tr><tr valign="top"><td colspan="2" class="'.$class.'"><h2>' . $std . '</h2>';
				echo '<p>' . $desc . '</p>';
				break;

			case 'paragraph':
				echo '<p>' . $desc . '</p>';
				break;
			
			case 'checkbox':
			
				if (!empty($choices) )
				{
					
					if( !is_array($options[$id]) && is_array($std))
					{ 
						$options[$id] = array();
						foreach( $std as $default)
						{
							$options[$id][$default] = 1;
						}	
						
					}
					foreach( $choices as $val => $label)
					{
						echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="'.$this->options_name.'[' . $id . '][' . $val . ']" value="1" ' . @checked( $options[$id][$val], 1, false ) . '  /> <label for="' . $id . '">' . $label . '</label><br>';
					}
				}
				else	
				{
					echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="'.$this->options_name.'[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';
				}
				if ( $desc != '' )
					echo '<span class="description">' . $desc . '</span>';
				break;
			
			case 'select':
				echo '<select class="select' . $field_class . '" name="'.$this->options_name.'[' . $id . ']"';
				if( $disabled == 'yes') echo ' disabled="disabled" ';
				echo '>';
				
				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';
				
				echo '</select>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="'.$this->options_name.'[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'textarea':
			
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="'.$this->options_name.'[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30"';
				if( $disabled == 'yes') echo ' disabled="disabled" ';
				echo '>' . wp_htmledit_pre( $options[$id] ) . '</textarea>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			case 'html':
				
				$text =  $options[$id]  == '' ? $std :  $options[$id] ;
				wp_editor(apply_filters( 'the_content', html_entity_decode($text) ),$id , array('textarea_name' => $this->options_name.'[' . $id . ']','media_buttons' => false,'quicktags' => false,'textarea_rows' => 15));

				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			case 'disabled':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="'.$this->options_name.'[' . $id . ']" disabled="disabled"  rows="5" cols="30">' . $std .'</textarea>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="'.$this->options_name.'[' . $id . ']" value="' . esc_attr( $options[$id] ) . '"';
				if( $disabled == 'yes') echo ' disabled="disabled" ';
				echo ' />';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'button':
		 		echo '<button class="button-primary' . $field_class . '" id="' . $id . '" name="'.$this->options_name.'[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" onclick="' . $onclick . '">' . $std . '</button>';
		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
		 		break;
		 					
			case 'color':
		 		echo '<input class="regular-text' . $field_class . ' colorpicker" type="text" id="' . $id . '" rel="color-' . $id . '" name="'.$this->options_name.'[' . $id . ']" placeholder="' . $std . '" value="';
		 		echo esc_attr( $options[$id] ) != '' ? esc_attr( $options[$id] ) : $std;
		 		echo '" /><div id="color-' . $id . '"></div>';
		 		
		 		if ( $desc != '' )
		 			echo '<span class="description">' . $desc . '</span>';
		 		break;	
			case 'code':
		 		echo '<div style="width:550px"><textarea class="code-area ' . $field_class . '" id="code-' . $id . '" name="'.$this->options_name.'[' . $id . ']" placeholder="' . $std . '">';
		 		echo esc_attr( $options[$id] ) != '' ? esc_attr( $options[$id] ) : $std;
		 		echo '</textarea></div>';
		 			echo '<script type="text/javascript">
				 				var editor_' . $id . ' = CodeMirror.fromTextArea(document.getElementById("code-' . $id . '"), {lineNumbers: true, matchBrackets: true});
		 			</script>';
		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		break;	
		 		
		 	case 'sortable':
		 		if( $disabled == '')
		 		{ 
		 			wp_enqueue_script('jquery-ui-sortable');
		 			echo '<script>
					  jQuery(function($) {
					    $( ".sortable-list" ).sortable({
					    	placeholder: "ui-state-highlight",
							stop: function( event, ui ) {
								var order="";
								$("#sortable-form span").each(function(){
									order += $(this).text()+",";
								});
								$.post(ajaxurl, {"action": "wsi_order", "order": order.replace(/^,|,$/g,"")} )
							}
					    });
					    $( ".sortable-list" ).disableSelection();
					  });
					  </script>';
				}	  
		 			echo '<div id="sortable-form"><ul class="sortable-list">';
		 			foreach (WP_Social_Invitations::get_providers() as $p => $p_name)
		 			{
		 				echo '<li class="'.$p.'"><span style="display:none">'.$p.'</span>'.$p_name.'</li>';
		 			}
		 			echo '</ul><div style="clear:both;"></div></div>';
		 			if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 	
		 		break;	
		 	case 'text':
			default:
		 		echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="'.$this->options_name.'[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '"';
		 		if( $disabled == 'yes') echo ' disabled="disabled" ';
		 		echo ' />';
		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
		 		break;


		 	
		}
		
	}
	
    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
	public function display_page() {
		
		require_once( dirname(__FILE__).'/wp-base/header.php');		
		
		$_GET['page'] != '' ? $page = $_GET['page'] : $page = '';
	
		do_action($page.'_wpb_render_box');
		
		echo '<form action="options.php" method="post" id="form">';
	
		settings_fields( $this->options_name );
		do_settings_sections($this->options_name );
		
		
		?>
		<p class="submit"><input name="Submit" type="submit" class="button-primary" value="<?php _e( 'Export', $this->WPB_PREFIX );?>" /></p>
		
		</form>
		
		<?php
		require_once( dirname(__FILE__).'/wp-base/sidebar.php');
		?>

	<script type="text/javascript">
	
		
		
		jQuery(document).ready(function($) {
			var sections = [];
			
			var footer_id = '#footer';
			//new version
			if( $('#wpfooter').length )
			{
				footer_id = '#wpfooter';
			}
			
			$('#right-sidebar').stickyMojo({footerID: footer_id, contentID: '#left-content'});
		<?php	
			foreach ( $this->sections as $section_slug => $section )
				echo "sections['$section'] = '$section_slug';";
		?>	
			var wrapped = $(".wrap h3").not('.nowrap').wrap("<div class=\"ui-tabs-panel\">");
			wrapped.each(function() {
				$(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
			}); 
			$(".ui-tabs-panel").each(function(index) {
				$(this).attr("id", sections[$(this).children("h3").text()]);
				if (index > 0)
					$(this).addClass("ui-tabs-hide");
			});
			
			$('p.submit').appendTo('#form');
			
			$("input[type=text], textarea").each(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
					$(this).css("color", "#999");
			});
			
			$("input[type=text], textarea").focus(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
					$(this).val("");
					$(this).css("color", "#000");
				}
			}).blur(function() {
				if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
					$(this).val($(this).attr("placeholder"));
					$(this).css("color", "#999");
				}
			});
			
			$("#ui-tabs a").eq(0).addClass("nav-tab-active");
			$("#ui-tabs a").click(function(){
			
				$("#ui-tabs a").removeClass("nav-tab-active");
				$(this).addClass("nav-tab-active");
				$('.ui-tabs-panel').hide();
				if( $(this).attr('href') == '#stats' || $(this).attr('href') == '#wsi_stats')
				{
					$('#right-sidebar').fadeOut();
				}
				else
				{
					$('#right-sidebar').fadeIn();
				}
				$($(this).attr('href')).fadeIn();
				return false;
			});
			
			$(".wrap h3, .wrap table").show();
			
			// This will make the "warning" checkbox class really stand out when checked.
			// I use it here for the Reset checkbox.
			$(".warning").change(function() {
				if ($(this).is(":checked"))
					$(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
				else
					$(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
			});
			$('.updated').delay(3000).fadeOut();

			
			
			// Browser compatibility
			if ($.browser.mozilla) 
			         $("form").attr("autocomplete", "off");
		});

      
		function sticky_relocate() {
			    var window_top = jQuery(window).scrollTop();
			    var div_top = jQuery('#sticky-anchor').offset().top;
			    if (window_top > div_top) {
			        jQuery('#sticky').addClass('stick').css('top',div_top);
			    } else {
			        jQuery('#sticky').removeClass('stick');
			    }
		}
	</script> 

	<?php
		
	}


	/**
	*	function that register plugin options 
	*/
 	 function register_options()
	{
		register_setting( $this->WPB_PREFIX.'_options', $this->WPB_PREFIX.'_settings' );
		
	}

	/**
	 * Description for section
	 *
	 * 
	 */
	public function display_section($section) {
		// common html text
		do_action($section['id'].'_wpb_print_box');
	}

	/**
	* Load scripts and styles
	*/
	function load_base_scripts()
	{
			
		
			if( isset($_GET['page']) && $_GET['page'] == $this->plugin_slug )
			{
				wp_enqueue_style('wsi-admin-css', plugins_url( 'assets/base/style.css', __FILE__ ) , '',$this->version );
				wp_enqueue_script('sticky', plugins_url( 'assets/base/sticky.js', __FILE__ ) ,array('jquery'),$this->version );
				//register optional scripts
				wp_register_script('codemirror', plugins_url( 'assets/base/codemirror-compressed.js', __FILE__ ) ,'',$this->version );
				wp_register_script('colorpicker-handle', plugins_url( 'assets/base/colorpicker.js', __FILE__ ) ,array('wp-color-picker'),$this->version );
				
				if( ! wp_script_is('wp-color-picker', 'registered') )
				{
					wp_register_script('wp-color-picker', plugins_url( 'assets/base/colorpicker.min.js', __FILE__ ) ,'',$this->version );
					wp_register_style('wp-color-picker', plugins_url( 'assets/base/colorpicker.css', __FILE__ ) ,'',$this->version );
				}
				
			}	
		
	}
	
	/**
	* Validate settings
	*
	* @since 1.0
	*/
	public function validate_settings( $input ) {
		
		if ( ! isset( $input['reset_plugin'] ) ) {
			$options = get_option( $this->options_name );
			
			if( is_array($this->checkboxes))
			{
				foreach ( $this->checkboxes as $id ) {
					if ( isset( $options[$id] ) && ! isset( $input[$id] ) )
						unset( $options[$id] );
				}
			}
			return $input;
		}
		return false;
		
	}
	/**
	 *
	 */
	function is_multi($a) {
    	$rv = array_filter($a,'is_array');
		if(count($rv)>0) return true;
		return false;
	}
	
	
}
