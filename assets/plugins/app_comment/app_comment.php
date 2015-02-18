<?php
/*
Plugin Name: App Comment
Plugin URI: http://wordpress.org/extend/plugins/wp-ajaxify-comments/
Description: WP-Ajaxify-Comments hooks into your current theme and adds AJAX functionality to the comment form.
Author: Jan Jonas
Author URI: http://janjonas.net
Version: 0.17.2
License: GPLv2
Text Domain: wpac
*/ 

//Original Name: wp-ajaxify-comments

/*  
	Copyright 2012, Jan Jonas, (email : mail@janjonas.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('WPAC_PLUGIN_NAME', 'WP-Ajaxify-Comments');
define('WPAC_SETTINGS_URL', 'admin.php?page='.WPAC_PLUGIN_NAME);
define('WPAC_DOMAIN', 'wpac');
define('WPAC_SESSION_VAR', WPAC_DOMAIN.'_session');
define('WPAC_OPTION_PREFIX', WPAC_DOMAIN.'_'); // used to save options in version <=0.8.0
define('WPAC_OPTION_KEY', WPAC_DOMAIN); // used to save options in version >= 0.9.0

define('WPAC_WP_ERROR_PLEASE_TYPE_COMMENT', '<strong>ERROR</strong>: please type a comment.');
define('WPAC_WP_ERROR_COMMENTS_CLOSED', 'Sorry, comments are closed for this item.');
define('WPAC_WP_ERROR_MUST_BE_LOGGED_IN', 'Sorry, you must be logged in to post a comment.');
define('WPAC_WP_ERROR_FILL_REQUIRED_FIELDS', '<strong>ERROR</strong>: please fill the required fields (name, email).');
define('WPAC_WP_ERROR_INVALID_EMAIL_ADDRESS', '<strong>ERROR</strong>: please enter a valid email address.');
define('WPAC_WP_ERROR_POST_TOO_QUICKLY', 'You are posting comments too quickly. Slow down.');
define('WPAC_WP_ERROR_DUPLICATE_COMMENT', 'Duplicate comment detected; it looks as though you&#8217;ve already said that!');

function wpac_get_config() {

	return array(
		array(
			'section' => __('General', WPAC_DOMAIN),
			'options' => array(
				'enable' => array(
					'type' => 'boolean',
					'default' => '0',
					'label' => __('Enabled plugin', WPAC_DOMAIN),
				),
				'debug' => array(
					'type' => 'boolean',
					'default' => '0',
					'label' => __('Debug mode', WPAC_DOMAIN),
				),
			),
		),
		array(
			'section' => __('Selectors', WPAC_DOMAIN),
			'options' => array(
				'selectorCommentForm' => array(
					'type' => 'string',
					'default' => '#commentform',
					'label' => __('Comment form selector', WPAC_DOMAIN),
				),
				'selectorCommentsContainer' => array(
					'type' => 'string',
					'default' => '#comments',
					'label' => __('Comments container selector', WPAC_DOMAIN),
				),
				'selectorCommentPagingLinks' => array(
						'type' => 'string',
						'default' => '#comments [class^=\'nav-\'] a',
						'label' => __('Comment paging links selector', WPAC_DOMAIN),
				),
				'selectorRespondContainer' => array(
					'type' => 'string',
					'default' => '#respond',
					'label' => __('Respond container selector', WPAC_DOMAIN),
				),
				'selectorErrorContainer' => array(
					'type' => 'string',
					'default' => 'p:parent',
					'label' => __('Error container selector', WPAC_DOMAIN),
				),
			),
		),
		array(
			'section' => __('Popup overlay', WPAC_DOMAIN),
			'options' => array(
				'popupCornerRadius' => array(
					'type' => 'int',
					'default' => '5',
					'label' => __('Corner radius (px)', WPAC_DOMAIN),
					'pattern' => '/^[0-9]*$/',
				),
				'popupMarginTop' => array(
					'type' => 'int',
					'default' => '10',
					'label' => __('Margin top (px)', WPAC_DOMAIN),
					'pattern' => '/^[0-9]*$/',
				),
				'popupWidth' => array(
					'type' => 'int',
					'default' => '30',
					'label' => __('Width (%)', WPAC_DOMAIN),
					'pattern' => '/^(100|[1-9][0-9]|[1-9])$/',
				),
				'popupPadding' => array(
					'type' => 'int',
					'default' => '5',
					'label' => __('Padding (px)', WPAC_DOMAIN),
					'pattern' => '/^[0-9]*$/',
				),
				'popupFadeIn' => array(
					'type' => 'int',
					'default' => '400',
					'label' => __('Fade in time (ms)', WPAC_DOMAIN),
					'pattern' => '/^[0-9]*$/',
				),
				'popupFadeOut' => array(
					'type' => 'int',
					'default' => '400',
					'label' => __('Fade out time (ms)', WPAC_DOMAIN),
					'pattern' => '/^[0-9]*$/',
				),
				'popupTimeout' => array(
					'type' => 'int',
					'default' => '3000',
					'label' => __('Timeout (ms)', WPAC_DOMAIN),
					'pattern' => '/^[0-9]*$/',
				),
				'popupBackgroundColorLoading' => array(
					'type' => 'string',
					'default' => '#000',
					'label' => __('Loading background color', WPAC_DOMAIN),
				),
				'popupTextColorLoading' => array(
					'type' => 'string',
					'default' => '#fff',
					'label' => __('Loading text color', WPAC_DOMAIN),
				),
				'popupBackgroundColorSuccess' => array(
					'type' => 'string',
					'default' => '#008000',
					'label' => __('Success background color', WPAC_DOMAIN),
				),
				'popupTextColorSuccess' => array(
					'type' => 'string',
					'default' => '#fff',
					'label' => __('Success text color', WPAC_DOMAIN),
				),			
				'popupBackgroundColorError' => array(
					'type' => 'string',
					'default' => '#f00',
					'label' => __('Error background color', WPAC_DOMAIN),
				),
				'popupTextColorError' => array(
					'type' => 'string',
					'default' => '#fff',
					'label' => __('Error text color', WPAC_DOMAIN),
				),			
				'popupOpacity' => array(
					'type' => 'int',
					'default' => '70',
					'label' => __('Opacity (%)', WPAC_DOMAIN),
					'pattern' => '/^(100|[1-9][0-9]|[1-9])$/',
				),
				'popupTextAlign' => array(
					'type' => 'string',
					'default' => 'center',
					'label' => __('Text align (left|center|right)', WPAC_DOMAIN),
					'pattern' => '/^(left|center|right)$/',
				),
				'popupTextFontSize' => array(
					'type' => 'string',
					'default' => __('Default font size', WPAC_DOMAIN),
					'label' => __('Font size (e.g. "14px", "1.1em", &hellip;)', WPAC_DOMAIN),
				),			
				'popupZindex' => array(
					'type' => 'int',
					'default' => '1000',
					'label' => __('Z-Index', WPAC_DOMAIN),
					'pattern' => '/^[0-9]*$/',
				),
			),
		),
		array(
			'section' => __('Miscellaneous', WPAC_DOMAIN),
			'options' => array(
				'scrollSpeed' => array(
					'type' => 'int',
					'default' => '500',
					'label' => __('Scroll speed (ms)', WPAC_DOMAIN),
					'pattern' => '/^[0-9]*$/',
				),
				'autoUpdateIdleTime' => array(
					'type' => 'int',
					'default' => '0',
					'label' => __('Auto update idle time (ms)', WPAC_DOMAIN),
					'description' => __('Leave empty or set to 0 to disable the auto update feature.', WPAC_DOMAIN),
				),
			),
		),
		array(
			'section' => __('Texts', WPAC_DOMAIN),
			'options' => array(
				'textPosted' => array(
						'type' => 'string',
						'default' => __('Your comment has been posted. Thank you!', WPAC_DOMAIN),
						'label' => __('Comment posted', WPAC_DOMAIN),
				),
				'textPostedUnapproved' => array(
						'type' => 'string',
						'default' => __('Your comment has been posted and is awaiting moderation. Thank you!', WPAC_DOMAIN),
						'label' => __('Comment posted unapproved', WPAC_DOMAIN),
				),
				'textReloadPage' => array(
						'type' => 'string',
						'default' => __('Reloading page. Please wait&hellip;', WPAC_DOMAIN),
						'label' => __('Reloading page', WPAC_DOMAIN),
				),
				'textPostComment' => array(
						'type' => 'string',
						'default' => __('Posting your comment. Please wait&hellip;', WPAC_DOMAIN),
						'label' => __('Post comment', WPAC_DOMAIN),
				),
				'textRefreshComments' => array(
						'type' => 'string',
						'default' => __('Loading comments. Please wait&hellip;', WPAC_DOMAIN),
						'label' => __('Refresh comments', WPAC_DOMAIN),
				),
				'textUnknownError' => array(
						'type' => 'string',
						'default' => __('Something went wrong, your comment has not been posted.', WPAC_DOMAIN),
						'label' => __('Unknown error occured', WPAC_DOMAIN),
				),
				'textErrorTypeComment' => array(
						'type' => 'string',
						'default' => __(WPAC_WP_ERROR_PLEASE_TYPE_COMMENT),
						'label' => __('Error "Please type a comment"', WPAC_DOMAIN),
						'specialOption' => true,
				),
				'textErrorCommentsClosed' => array(
						'type' => 'string',
						'default' => __(WPAC_WP_ERROR_COMMENTS_CLOSED),
						'label' => __("Error 'Comments closed'", WPAC_DOMAIN),
						'specialOption' => true,
				),
				'textErrorMustBeLoggedIn' => array(
						'type' => 'string',
						'default' => __(WPAC_WP_ERROR_MUST_BE_LOGGED_IN),
						'label' => __("Error 'Must be logged in'", WPAC_DOMAIN),
						'specialOption' => true,
				),
				'textErrorFillRequiredFields' => array(
						'type' => 'string',
						'default' => __(WPAC_WP_ERROR_FILL_REQUIRED_FIELDS),
						'label' => __("Error 'Fill in required fields'", WPAC_DOMAIN),
						'specialOption' => true,
				),
				'textErrorInvalidEmailAddress' => array(
						'type' => 'string',
						'default' => __(WPAC_WP_ERROR_INVALID_EMAIL_ADDRESS),
						'label' => __("Error 'Invalid email address'", WPAC_DOMAIN),
						'specialOption' => true,
				),
				'textErrorPostTooQuickly' => array(
						'type' => 'string',
						'default' => __(WPAC_WP_ERROR_POST_TOO_QUICKLY),
						'label' => __("Error 'Post too quickly'", WPAC_DOMAIN),
						'specialOption' => true,
				),
					
				'textErrorDuplicateComment' => array(
						'type' => 'string',
						'default' => __(WPAC_WP_ERROR_DUPLICATE_COMMENT),
						'label' => __("Error 'Duplicate comment'", WPAC_DOMAIN),
						'specialOption' => true,
				),
			),
		),
		array(
			'section' => __('Expert settings', WPAC_DOMAIN),
			'options' => array(
				'callbackOnBeforeSelectElements' => array(
					'type' => 'multiline',
					'default' => '',
					'label' => sprintf(__("'%s' callback", WPAC_DOMAIN), 'OnBeforeSelectElements'),
					'specialOption' => true,
					'description' => __('Parameter: dom (jQuery DOM element)', WPAC_DOMAIN)
				),
				'callbackOnBeforeSubmitComment' => array(
					'type' => 'multiline',
					'default' => '',
					'label' => sprintf(__("'%s' callback", WPAC_DOMAIN), 'OnBeforeSubmitComment'),
					'specialOption' => true,
				),
				'callbackOnBeforeUpdateComments' => array(
					'type' => 'multiline',
					'default' => '',
					'label' => sprintf(__("'%s' callback", WPAC_DOMAIN), 'OnBeforeUpdateComments'),
					'specialOption' => true,
				),
				'callbackOnAfterUpdateComments' => array(
					'type' => 'multiline',
					'default' => '',
					'label' => sprintf(__("'%s' callback", WPAC_DOMAIN), 'OnAfterUpdateComments'),
					'specialOption' => true,
				),
				'asyncCommentsThreshold' => array(
					'type' => 'int',
					'label' => __('Load comments async threshold', WPAC_DOMAIN),
					'pattern' => '/^[0-9]*$/',
					'description' => __('Load comments asynchronously with secondary AJAX request if more than the specified number of comments exist. Leave empty to disable this feature.', WPAC_DOMAIN),
					'specialOption' => true,
				),
				'disableUrlUpdate' => array(
					'type' => 'boolean',
					'default' => '0',
					'label' => __('Disable URL updating', WPAC_DOMAIN),
				),
			)
		)
	);
}
	
function wpac_enqueue_scripts() {
	$version = wpac_get_version();
	wp_enqueue_script('jsuri', plugins_url('jsuri.min.js', __FILE__), array(), $version);
	wp_enqueue_script('app_comment_lib', plugins_url('app_comment_lib.js', __FILE__), array('jquery'), $version);
	//wp_enqueue_script('jQueryBlockUi', plugins_url('app_comment_lib.js', __FILE__), array('jquery'), $version);
	//wp_enqueue_script('jQueryIdleTimer', plugins_url('idle-timer.js', __FILE__), array('jquery'), $version);	
	wp_enqueue_script('wpAjaxifyComments', plugins_url('app_comment.js', __FILE__), array('jquery', 'app_comment_lib', 'jsuri'), $version);
}

function wpac_get_version() {
	if (!function_exists('get_plugins')) require_once(ABSPATH .'wp-admin/includes/plugin.php');
	$data = get_plugin_data(__FILE__);
    return $data['Version'];
}

function wpac_plugins_loaded() {
	$dir = dirname(plugin_basename(__FILE__)).DIRECTORY_SEPARATOR.'languages'.DIRECTORY_SEPARATOR;
	load_plugin_textdomain(WPAC_DOMAIN, false, $dir);
}
add_action('plugins_loaded', 'wpac_plugins_loaded');

function wpac_js_escape($s) {
	return str_replace('"',"\\\"", $s);
}

$wpac_options;
function wpac_load_options() {

	global $wpac_options;
	
	// Test if options have already been loaded
	if ($wpac_options !== null) return;

	$wpac_options = get_option(WPAC_OPTION_KEY);
	if (is_array($wpac_options)) return;
	
	// Upgrade options from <= 0.8.0 and delete old options after migration
	$wpac_config = wpac_get_config();
	$wpac_options = array();
	foreach($wpac_config as $config) {
		foreach($config['options'] as $optionName => $option) {
			$value = get_option(WPAC_OPTION_PREFIX.$optionName, null);
			if ($value !== null) $wpac_options[$optionName] = $value;
		}
	}
	update_option(WPAC_OPTION_KEY, $wpac_options);
	foreach($wpac_config as $config) {
		foreach($config['options'] as $optionName => $option) {
			delete_option(WPAC_OPTION_PREFIX.$optionName);
		}
	}
}

function wpac_get_option($option) {
	global $wpac_options;
	wpac_load_options();
	return (isset($wpac_options[$option])) ? $wpac_options[$option] : null;
}

function wpac_update_option($option, $value) {
	global $wpac_options;
	wpac_load_options();
	$wpac_options[$option] = $value;
}

function wpac_delete_option($option) {
	global $wpac_options;
	wpac_load_options();
	unset($wpac_options[$option]);
}

function wpac_save_options() {
	global $wpac_options;
	wpac_load_options();
	update_option(WPAC_OPTION_KEY, $wpac_options);
}

function wpac_initialize() {

	if (wpac_get_option('enable')) {

		// Skip JavaScript options output if request is a WPAC-AJAX request
		if (wpac_is_ajax_request()) return;
		
		global $post;
		
		echo '<script type="text/javascript">';

		echo 'if (!window["WPAC"]) var WPAC = {};';
		
		// Options
		echo 'WPAC._Options = {';
		$wpac_config = wpac_get_config();
		foreach($wpac_config as $config) {
			foreach($config['options'] as $optionName => $option) {
				if (isset($option['specialOption']) && $option['specialOption']) continue;
				$value = trim(wpac_get_option($optionName));
				if (strlen($value) == 0) $value = $option['default'];
				echo $optionName.':';
				switch ($option['type']) {
					case 'int': echo $value.','; break;
					case 'boolean': echo $value ? 'true,' : 'false,'; break;
					default: echo '"'.wpac_js_escape($value).'",';
				}
			}
		}
		echo 'commentsEnabled:'.((is_page() || is_single()) && comments_open($post->ID) ? 'true' : 'false').',';
		echo 'version:"'.wpac_get_version().'"};';

		// Callbacks
		echo 'WPAC._Callbacks = {';
		echo '"onBeforeSelectElements": function(dom) {'.wpac_get_option('callbackOnBeforeSelectElements').'},';
		echo '"onBeforeUpdateComments": function() {'.wpac_get_option('callbackOnBeforeUpdateComments').'},';
		echo '"onAfterUpdateComments": function() {'.wpac_get_option('callbackOnAfterUpdateComments').'},';
		echo '"onBeforeSubmitComment": function() {'.wpac_get_option('callbackOnBeforeSubmitComment').'},';
		echo '};';
		
		echo '</script>';
		
	}
}

function wpac_is_login_page() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}

function wpac_add_settings_link($links, $file) {
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
	if ($file == $this_plugin){
		$settings_link = '<a href="'.WPAC_SETTINGS_URL.'">Settings</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}
add_filter('plugin_action_links', 'wpac_add_settings_link', 10, 2);

function wpac_admin_notice() {
	if (basename($_SERVER['PHP_SELF']) == 'plugins.php') {
		if (!wpac_get_option('enable')) {
			// Show error if plugin is not enabled
			echo '<div class="error"><p><strong>'.sprintf(__('%s is not enabled!', WPAC_DOMAIN), WPAC_PLUGIN_NAME).'</strong> <a href="'.WPAC_SETTINGS_URL.'">'.__('Click here to configure the plugin.', WPAC_DOMAIN).'</a></p></div>';
		} else if (wpac_get_option('debug')) {
			// Show info if plugin is running in debug mode
			echo '<div class="updated"><p><strong>'.sprintf(__('%s is running in debug mode!', WPAC_DOMAIN), WPAC_PLUGIN_NAME).'</strong> <a href="'.WPAC_SETTINGS_URL.'">'.__('Click here to configure the plugin.', WPAC_DOMAIN).'</a></p></div>';
		}
	}
}
add_action('admin_notices', 'wpac_admin_notice');

function wpac_init()
{
	// Start session
	if (!session_id()) {
		@session_cache_limiter('private, must-revalidate');
		@session_cache_expire(0);
		@session_start();	
	}

	// Update session var and add header if session var is defined
	if (isset($_SESSION[WPAC_SESSION_VAR]) && $_SESSION[WPAC_SESSION_VAR]) {
		$currentUrl = 'http'.((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && $_SERVER['HTTPS'] != 'off') ? 's' : '')
			.'://'.$_SERVER['SERVER_NAME'].($_SERVER['SERVER_PORT'] != '80' ? ':'.$_SERVER['SERVER_PORT'] : '').$_SERVER['REQUEST_URI'];
		$sessionUrl = $_SESSION[WPAC_SESSION_VAR]['url'];
		if ($sessionUrl !== $currentUrl && strpos($sessionUrl, $currentUrl.'#') !== 0) {	
			$_SESSION[WPAC_SESSION_VAR] = null;
		} else  {
			header('X-WPAC-UNAPPROVED: '.$_SESSION[WPAC_SESSION_VAR]['unapproved']);
			header('X-WPAC-URL: '.$sessionUrl);
		}
	}
}
add_action('init', 'wpac_init');

function wpac_comment_post_redirect($location)
{
	global $comment;

	// Save comment data in session
	$_SESSION[WPAC_SESSION_VAR] = array(
		'url' => $location, 
		'unapproved' => ($comment && $comment->comment_approved == '0') ? '1' : '0',
	);
	
	return $location;
}
add_action('comment_post_redirect', 'wpac_comment_post_redirect');

function wpac_option_page() {
	if (!current_user_can('manage_options'))  {
		wp_die('You do not have sufficient permissions to access this page.');
	} 

	$wpac_config = wpac_get_config();
	
	$errors = array();
	
	if (!empty($_POST) && isset($_POST['wpac']) && check_admin_referer('wpac_update_settings','wpac_nonce_field'))
	{
		foreach($_POST['wpac'] as $section => $options) {
		
			foreach ($options as $optionName => $value) {

				if (!isset($wpac_config[$section])) continue;
				if (!isset($wpac_config[$section]['options'][$optionName])) continue;
			
				$value = trim(stripslashes($value));
				$pattern = isset($wpac_config[$section]['options'][$optionName]['pattern']) ? $wpac_config[$section]['options'][$optionName]['pattern'] : null;
				$type = $wpac_config[$section]['options'][$optionName]['type'];
				
				if (strlen($value) > 0) {
					$error = $pattern ? (preg_match($pattern, $value) !== 1) : null;
					if ($error) {
						$errors[] = $optionName;
					} else {
						if ($type == 'int') $value = intval($value);
						wpac_update_option($optionName, $value);
					}
				} else {
					wpac_delete_option($optionName);
				}
			
			}
		
		}
		
		if (count($errors) == 0) {
			wpac_save_options();
			echo '<div class="updated"><p><strong>'.__('Settings saved successfully.', WPAC_DOMAIN).'</strong></p></div>';
		} else {
			echo '<div class="error"><p><strong>'.__('Settings not saved! Please correct the red marked input fields.', WPAC_DOMAIN).'</strong></p></div>';
		}
	}
  
  ?>
	<div class="wrap">
		<h2><?php printf(__('Plugin Settings: %s', WPAC_DOMAIN), WPAC_PLUGIN_NAME.' '.wpac_get_version()); ?></h2>
	
		<div class="postbox-container" style="width: 100%;" >
	
			<form name="wp-ajaxify-comments-settings-update" method="post" action="">
				<?php if (function_exists('wp_nonce_field') === true) wp_nonce_field('wpac_update_settings','wpac_nonce_field'); ?>	 
	
				<div id="poststuff">
					<div class="postbox">
				
						<h3 id="plugin-settings"><?php _e('Plugin Settings', WPAC_DOMAIN); ?></h3>
						<div class="inside">
	
							<table class="form-table">
	
		<?php
		
			$section = 0;
			foreach($wpac_config as $config) {
				echo '<tr><th colspan="2" style="white-space: nowrap;"><h4>'.$config['section'].'</h4></th></tr>';
				foreach($config['options'] as $optionName => $option) {
	
					$color = in_array($optionName, $errors) ? 'red' : '';
	
					echo '<tr><th scope="row" style="white-space: nowrap;"><label for="'.$optionName.'" style="color: '.$color.'">'.$option['label'].'</label></th><td>';
					
					$value = (isset($_POST['wpac']) && $_POST['wpac'][$section][$optionName]) ? stripslashes($_POST['wpac'][$section][$optionName]) : wpac_get_option($optionName);
					$name = 'wpac['.$section.']['.$optionName.']';
					
					if ($option['type'] == 'boolean') {
						echo '<input type="hidden" name="'.$name.'" value="">';
						echo '<input type="checkbox" name="'.$name.'" id="'.$optionName.'" '.($value ? 'checked="checked"' : '').' value="1"/>';
					} else {
						$escapedValue = htmlentities($value, ENT_COMPAT | ENT_HTML401, 'UTF-8');
						if ($option['type'] == 'multiline') {
							echo '<textarea name="'.$name.'" id="'.$optionName.'" rows="5" cols="40" style="width: 300px; color: '.$color.'">'.$escapedValue.'</textarea>';
						} else {
							echo '<input type="text" name="'.$name.'" id="'.$optionName.'" value="'.$escapedValue.'" style="width: 300px; color: '.$color.'"/>';
						} 
						if (isset($option['default']) && $option['default']) echo '<br/>'.sprintf(__('Leave empty for default value %s', WPAC_DOMAIN), '<em>'.$option['default'].'</em>');
						if (isset($option['description']) && $option['description']) echo '<br/><em style="width:300px; display: inline-block">'.$option['description'].'</em>';
					}
					echo '</td></tr>';
				}
				$section++;
			}
		
		?>
		
							</table>
							<p class="submit">
							  <input type="hidden" name="action" value="wpac_update_settings"/>
							  <input type="submit" name="wpac_update_settings" class="button-primary" value="<?php _e('Save Changes', WPAC_DOMAIN); ?>"/>
							</p>
						</div>
					</div>
				</div>
	
			</form>	
		
		</div>
	
		<div class="postbox-container" style="width: 100%;" >
	
		</div>
	</div>
<?php }

function wpac_is_ajax_request() {
	return isset($_SERVER['HTTP_X_WPAC_REQUEST']) && $_SERVER['HTTP_X_WPAC_REQUEST']; 
}

function wpac_admin_menu() {
	add_options_page(WPAC_PLUGIN_NAME, WPAC_PLUGIN_NAME, 'manage_options', WPAC_PLUGIN_NAME, 'wpac_option_page');
}

function comments_query_filter($query) {
	// No comment filtering if request is a fallback or WPAC-AJAX request  
	if ($_REQUEST['WPACFallback'] || wpac_is_ajax_request()) return $query;
	
	// Test asyncCommentsThreshold 
	$asyncCommentsThreshold = wpac_get_option('asyncCommentsThreshold');
	$commentsCount = count($query);
	if (strlen($asyncCommentsThreshold) == 0 || $commentsCount == 0 || $asyncCommentsThreshold > $commentsCount) return $query;
	
	// Filter/remove comments and set options to load comments with secondary AJAX request 
	echo '<script type="text/javascript">WPAC._Options["loadCommentsAsync"] = true;</script>';
	return array();
}

function wpac_filter_gettext($translation, $text, $domain) {
	if ($domain != 'default') return $translation;
	
	$customWordpressTexts = array(
		WPAC_WP_ERROR_PLEASE_TYPE_COMMENT => 'textErrorTypeComment',
		WPAC_WP_ERROR_COMMENTS_CLOSED => 'textErrorCommentsClosed',
		WPAC_WP_ERROR_MUST_BE_LOGGED_IN => 'textErrorMustBeLoggedIn',
		WPAC_WP_ERROR_FILL_REQUIRED_FIELDS => 'textErrorFillRequiredFields',
		WPAC_WP_ERROR_INVALID_EMAIL_ADDRESS => 'textErrorInvalidEmailAddress',
		WPAC_WP_ERROR_POST_TOO_QUICKLY => 'textErrorPostTooQuickly',
		WPAC_WP_ERROR_DUPLICATE_COMMENT => 'textErrorDuplicateComment',
	);

 	if (array_key_exists($text, $customWordpressTexts)) {
		$customText = wpac_get_option($customWordpressTexts[$text]);
		if ($customText) return $customText;
	}
	return $translation;
}

if (!is_admin() && !wpac_is_login_page()) {
	if (wpac_get_option('enable')) {
		add_filter('comments_array', 'comments_query_filter');
		add_action('wp_head', 'wpac_initialize');
		add_action('init', 'wpac_enqueue_scripts');
		add_filter('gettext', 'wpac_filter_gettext', 20, 3);
	}
} else {
	add_action('admin_menu', 'wpac_admin_menu');
}

?>