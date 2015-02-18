<?php
/**
 * KuAs Version 1.0 (betha) Theme functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * @file      functions.php
 * @package   kuas-beta
 * @author    Kuningan_Asri.
 * @link 	  http://kuas.com
 */

/*
 *
 * 1. KUAS SETUP THEME
 *
 */
/* Tell WordPress to run kuas_beta_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'kuas_beta_setup' );

if ( ! function_exists( 'kuas_beta_setup' ) ){ 
	function kuas_beta_setup() {
		load_theme_textdomain('kuas-beta', get_template_directory() . '/languages');
		/* Set the content width based on the theme's design and stylesheet. */
		if ( ! isset( $content_width ) ) $content_width = 600;
		
		/* This theme styles the visual editor with editor-style.css to match the theme style. */
		add_editor_style();
		
		/* Load up our theme options page and related code. */
		require( get_template_directory() . '/settings/theme-options.php' );
		require( get_template_directory() . '/settings/html.compressing.php' );
		require( get_template_directory() . '/includes/user.post.php' );
		require( get_template_directory() . '/widgets/kuas_widget_for_general_function.php' );
		require( get_template_directory() . '/widgets/kuas_header_content.php' );
		require( get_template_directory() . '/widgets/kuas_widget_tab.php' );	
		require( get_template_directory() . '/widgets/kuas_widget_weather.php' );
		require( get_template_directory() . '/widgets/kuas_widget_social_links.php' );
		require( get_template_directory() . '/widgets/kuas_widget_subcategories.php' );
		
		/* Add default posts and comments RSS feed links to <head>. */
		add_theme_support( 'automatic-feed-links' );
		
		/* This theme uses wp_nav_menu() in one location (primary secondary tertiary fourth fifth) */
		register_nav_menus( array(
			'primary' => __( 'Primary Navigation', 'kuas-beta' ),
			'secondary' => __( 'Secondary Navigation', 'kuas-beta' ),
			'tertiary' => __( 'Tertiary Navigation', 'kuas-beta' ),
		) );
		
		/* Add support for custom backgrounds. */
		$default_background_color = 'f7f8f9';
		add_theme_support( 'custom-background', array(
			// Let WordPress know what our default background color is.
			// This is dependent on our current color scheme.
			'default-color' => $default_background_color,
		) );
		
		/* This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images */
		if ( function_exists( 'add_theme_support' ) ) { 
			add_theme_support( 'post-thumbnails' );
		}
		
		/* Add custom image size for slider and featured category thumbnails. */
		add_image_size( 'small-thumb', 70, 40, true );	
		/* Add custom image size for featured category posts. */
		add_image_size( 'feat-thumb', 300, 170, true ); //featured post thumbnail	
		/* Add custom image size for carousel posts. */
		add_image_size( 'carousel-thumb', 190, 130, true ); //featured post thumbnail	
		/* Add custom image size for slider posts. */
		add_image_size( 'slider-image', 200, 200, true ); //featured post thumbnail
		
	}
}

add_filter( 'post_thumbnail_html', 'remove_wp_class_attribute_thumbnail', 10 );
add_filter( 'image_send_to_editor', 'remove_wp_class_attribute_thumbnail', 10 );

function remove_wp_class_attribute_thumbnail( $html ) {
   $html = preg_replace( '/wp-post-image/', "kuas-post-image", $html );
   return $html;
}

/**
 *
 * KUAS ACTIVATING EMBED FUNCTION [embed]http://provider.media/blah[/embed]
 *
 * @since KuAs 1.0
 *
 */
add_filter( 'embed_oembed_discover', 'viper_enable_oembed_discovery' );
function viper_enable_oembed_discovery() {
	return true;
}

/**
 *
 * KUAS CUSTOM EXCERPT & EXCEPRT MORE
 *
 * @since KuAs 1.0
 *
 */
/* Set the custom excerpt length to return first 30 words. */
function kuas_beta_custom_excerpt_length( $length ) {
	return 50;
}
add_filter( 'excerpt_length', 'kuas_beta_custom_excerpt_length', 999 );
/* Set the format for the more in excerpt, return ... instead of [...] */ 
function kuas_beta_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'kuas_custom_text_excerpt');

function kuas_custom_text_excerpt($string='',$limit=100){
	if(empty($string) OR $string==''){
		return false;
	}
	else {
		$data_text = esc_attr(strip_tags($string));
		$data_text = preg_replace('/\[+(.*)+\]/', '', $data_text);
		if(str_word_count($data_text) > 100){
		$data_text = substr($data_text,0, $limit);
		}
		else {
		$data_text = $data_text;
		}
		return $data_text ;
	}
}

/**
 *
 * KUAS REGISTER SIDEBAR
 *
 * @since KuAs 1.0
 *
 */
/* Register our sidebars and widgetized areas. */
if ( function_exists('register_sidebar') ) {
			
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'kuas-beta' ),
		'id' => 'sidebar-1',
		'description' => __( 'Sidebar Widget Kanan Utama', 'kuas-beta' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	) );

	register_sidebar( array(
		'name' => __( 'Left Sidebar', 'kuas-beta' ),
		'id' => 'sidebar-2',
		'description' => __( 'Sidebar Widget Kiri Utama', 'kuas-beta' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area', 'kuas-beta' ),
		'id' => 'sidebar-3',
		'description' => __( 'Footer Widget', 'kuas-beta' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	) );

	register_sidebar( array(
		'name' => __( 'Middle Pages Sidebar', 'kuas-beta' ),
		'id' => 'sidebar-4',
		'description' => __( 'Sidebar Widget Tengah Selain Beranda', 'kuas-beta' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	) );

	register_sidebar( array(
		'name' => __( 'Right Pages Sidebar', 'kuas-beta' ),
		'id' => 'sidebar-5',
		'description' => __( 'Sidebar Widget Kiri Selain Beranda', 'kuas-beta' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	) );

	register_sidebar( array(
		'name' => __( 'Big Sidebar', 'kuas-beta' ),
		'id' => 'sidebar-6',
		'description' => __( 'Sidebar Widget Besar', 'kuas-beta' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	) );
}

/**
 *
 * KUAS ADD HOOK/ACTION SCRIPT & STYLE
 *
 * @since KuAs 1.0
 *
 */

/* A safe way of adding JavaScripts to a WordPress generated page. */
if (!is_admin()){
    add_action('init', 'kuas_beta_js');
	add_action('init', 'kuas_beta_styles_loader');
	add_action('wp_enqueue_scripts', 'kuas_beta_custom_js');
}
if (!function_exists('kuas_beta_js')) {
    function kuas_beta_js() {
    	wp_enqueue_script('jquery-libs', get_template_directory_uri() . '/js/kuas_all_libs.js', array());
    	if(wp_is_mobile()){
    		wp_enqueue_script('maps-libs', get_template_directory_uri() . '/js/kuas_mobile_libs.js', array());
    	}
    	else {
    		wp_enqueue_script('google-map', 'http://maps.google.com/maps/api/js?v=3&sensor=false&libraries=places', array());
    		wp_enqueue_script('maps-libs', get_template_directory_uri() . '/js/kuas_web_libs.js', array());
    	}
		wp_localize_script('jquery',
			'kuas_ajax_var', array(
			'kuas_domain'=>site_url(), 'kuas_uri'=>$_SERVER['REQUEST_URI'],
			'kuas_ajax_url'=>site_url('ajax.php'),
			'kuas_ajax_msg' => __('Mengirim informasi, please wait...'),
			'kuas_ajax_date' => date('Ymd'),
			'kuas_locale' => WPLANG,
			'kuas_ssc'=>wp_create_nonce('ajax-login-nonce'),
			'nonce'=>wp_create_nonce('ajax-nonce'),
			'kuas_is_mobile'=>wp_is_mobile()
		));
		if(is_single() OR is_page()){
			global $post;
			wp_localize_script('kuas_beta_custom', 'kuas_data_post', array('idp'=>$post->ID,'ttl'=>get_the_title($post->ID),'time'=>get_post_time('U',true,$post->ID) ) );
		}			
    }	
}	
if (!function_exists('kuas_beta_custom_js')) {
    function kuas_beta_custom_js() {  			
		wp_enqueue_script('kuas_beta_custom', get_template_directory_uri() . '/js/custom.min.js', array('jquery'));
    }	
}
if (!function_exists('kuas_beta_styles_loader')) {
    function kuas_beta_styles_loader() {
    	if(wp_is_mobile()){
    	wp_enqueue_style( 'style', get_template_directory_uri().'/style-mobile.css' );
    	}
    	else {
		wp_enqueue_style( 'style', get_stylesheet_uri() );
		//wp_enqueue_style( 'style', get_template_directory_uri().'/style-developer.css' );
		wp_enqueue_style( 'kuas_fonts', get_template_directory_uri().'/font/font.css' );
    	}
		//wp_enqueue_style( 'select2', get_template_directory_uri().'/css/select2.css' );		
    }
}

/**
 * Custom Body Class from Browser Detected
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_browser_body_class')){
	add_filter('body_class','kuas_browser_body_class');
	function kuas_browser_body_class($classes) {
		global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
		if($is_lynx) $classes[] = 'is_mobile lynx browser-lynx';
		elseif($is_gecko) $classes[] = 'is_mobile gecko browser-gecko';
		elseif($is_opera) $classes[] = 'is_mobile opera browser-opera';
		elseif($is_NS4) $classes[] = 'is_mobile ns4 browser-ns4';
		elseif($is_iphone) $classes[] = 'is_mobile iphone browser-iphone';
		elseif($is_safari) $classes[] = 'is_mobile safari browser-safari';
		elseif($is_chrome) $classes[] = 'is_mobile chrome browser-chrome';
		elseif($is_IE) $classes[] = 'is_mobile ie browser-ie';
		else $classes[] = 'is_mobile unknown browser-unknown';
		return $classes;
	}
}

/**
 * Custom Mobile Browser Detection Manual Function
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_detect_mobile')){
	function kuas_detect_mobile(){
		$useragent=$_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
			return true;
		}
		else {
			return false;
		}
	}
}

/**
 * Custom Mobile Browser Detection Message
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_mobile_detection_message')){
	function kuas_mobile_detection_message($print=1){		
		$output = '<div id="attention_message" class="display-block aligncenter textaligncenter '.join( ' ', get_body_class() ).'">';
		$output.= __('Anda menggunakan perangkat smartphone & tablet','kuas-beta');
		$output.= '.<br />'.__('Untuk lebih optimal, gunakan browser PC atau Laptop','kuas-beta').'.</div>';
		if($print==1 OR $print=='' OR empty($print)){
			echo $output;
		}
		else {
			return $output;
		}
	}
	if(wp_is_mobile()){
	add_action('before_content_container','kuas_mobile_detection_message');
	}
}

/**
 * Custom Redirect Function with inject meta header
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_force_redirect')){
	function kuas_force_redirect($url=''){
		$url = $url? $url : site_url('404');
		print('<meta http-equiv="refresh" content="0;url='.$url.'">');
	}
}

/**
 * Custom Login AJAX Rightway
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_beta_ajax_login_form')){
	function kuas_beta_ajax_login_form($echo=1){
		if(!is_user_logged_in()){
		$output = '<div id="login_form_containner" class="borderColorBaseKuas bgColorBaseKuas_y3">';
        $output .= '<h2 class="headingColorBaseKuas bgColorBaseKuas_y2 shadowBottom">'.get_bloginfo('name').' Login</h2>';	    
	    $output .= '<form id="login_front" action="login" method="post">';
        $output .= '<p class="status"></p>';
        $output .= '<label for="username">Username</label>';
        $output .= '<input id="username" type="text" name="username" size="50">';
        $output .= '<label for="password">Password</label>';
        $output .= '<input id="password" type="password" name="password" size="50">';
        //$output .= '<div class="clear"></div>';
        //$output .= '<a class="lost" href="'.wp_lostpassword_url().'">'.__('Lost your password?','kuas-beta').'</a>';
        $output .= '<div class="clear"></div>';
        $output .= '<input class="submit_button" type="submit" value="Login" name="submit">';
        $output .= '<a class="close" href="#close_login_box"><small>'.__('(close)','kuas-beta').'</small></a>';
	    //$output .= wp_nonce_field( 'ajax-login-nonce', 'security' );
	    $output .= '</form></div>';
	    if($echo=1){ echo $output;}else{ return $output;}
		}
	}
	add_action('inner_body_script_bottom','kuas_beta_ajax_login_form',10,1);
}

/**
 * Custom Login AJAX Init
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_ajax_login_init')){
	function kuas_ajax_login_init(){
	    wp_register_script('ajax-login-script', get_template_directory_uri() . '/js/login.js', array('jquery') ); 
	    wp_enqueue_script('ajax-login-script');    
	    add_action( 'wp_ajax_nopriv_ajaxlogin', 'kuas_ajax_login' );// Enable the user with no privileges to run ajax_login() in AJAX
	}
}
//Execute the action only if the user isn't logged in
if(!is_user_logged_in()){
    add_action('init', 'kuas_ajax_login_init');
}

/**
 * Custom Login AJAX
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_ajax_login')){
	function kuas_ajax_login(){
	    // First check the nonce, if it fails the function will break
	    check_ajax_referer( 'ajax-login-nonce', 'kuas_ssc' );
	    // Nonce is checked, get the POST data and sign user on
	    $info = array();
	    $info['user_login'] = $_POST['username'];
	    $info['user_password'] = $_POST['password'];
	    $info['remember'] = true;
	    $user_signon = wp_signon( $info, false );
	    if ( is_wp_error($user_signon) ){
	        echo json_encode(array('loggedin'=>false, 'message'=>__('Salah username atau password.')));
	    } else {
	        echo json_encode(array('loggedin'=>true, 'message'=>__('Login berhasil, memuat ulang...')));
	    }
	    die();
	}
}

/**
 * Add custom user menu frontend navigation bar
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_user_front_menu')){
	function kuas_user_front_menu(){
		$output = '';
		if (is_user_logged_in()) { 
			$current_user_login = wp_get_current_user();
			$output .= '<a href="'.esc_url( get_author_posts_url( $current_user_login->ID ) ).'" title="'.sprintf(__('Menuju ke profile anda (%1$s)', 'kuas-beta'),$current_user_login->display_name).'" class="achor-top-nav nav-user login_button">';
			$output .= '<span class="meta_login meta_login_control">'.get_avatar($current_user_login->ID, '25' ).'</span></a>';
			$output .= '<a href="'.wp_logout_url( $_SERVER['REQUEST_URI'] ).'" title="'.__('Keluar', 'kuas-beta').'" class="fixedTip logout_front_user">'.__('keluar','kuas-beta').'</a>';
		} 
		else {
			$output.= '<a href="#" title="'.__('Masuk', 'kuas-beta').'" class="normalTip achor-top-nav nav-user achor_login_front" id="show_login_front">';
			$output.= '<div class="nav-user-login bgImgCategory _size30"></div>';
			$output.= '</a>';
		}
		return $output;
	}
}

/**
 *
 * KUAS COMMENTS HTML MARKUP
 *
 * @since KuAs 1.0
 *
 */
/* Function for the custom template for comments and pingbacks. */
if ( ! function_exists( 'kuas_beta_comments' ) ) {

	function kuas_beta_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>
			<li class="pingback">
				<span class="title" ><?php _e('Pingback:', 'kuas-beta') ?></span> <?php comment_author_link(); ?>
			<?php
			
			break;		
			default :
		?>
			<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
				
				<?php
				if ( '0' != $comment->comment_parent ){
					$class_coment_spesific = 'child_thread';
				} else {
					$class_coment_spesific = 'parent_thread';
				}
				?>
				
				<div id="comment-<?php comment_ID(); ?>" class="comment bgColorBaseKuas_y3 <?php echo $class_coment_spesific ?>">
					<div class="comment-meta">			
						<div class="comment-author vcard">
							<?php
								$avatar_size = 55;
								if ( '0' != $comment->comment_parent ) $avatar_size = 35;

								echo get_avatar( $comment, $avatar_size );

								/* translators: 1: comment author, 2: date and time */
								printf( '%1$s <span class="date-and-time">%2$s</span>',
										sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
										sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
										esc_url( get_comment_link( $comment->comment_ID ) ), human_time_diff( get_comment_time('U'), current_time('timestamp') ),
										/* translators: 1: date, 2: time */
										sprintf( __( '%1$s', 'kuas-beta' ), human_time_diff( get_comment_time('U'), current_time('timestamp') ) )
									)
								);
							?>					
						</div><!-- /comment-author /vcard -->

						<?php if ( $comment->comment_approved == '0' ) : ?>
							<em class="comment-awaiting-moderation"><?php _e( 'Tunggu moderasi.', 'kuas-beta' ); ?></em>
							<br />
						<?php endif; ?>

					</div>

					<div class="comment-content"><?php comment_text(); ?></div>

					<div class="reply">
						<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Tanggapi', 'kuas-beta' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</div><!-- ./reply -->
				</div><!-- /comment -->
			<?php
			break;
		endswitch;		
	}
}

if(!function_exists('kuas_beta_attach_symbol_emot_approved')){
	//if(is_user_logged_in()){
	add_action('attach_stuff_before_comment_form', 'kuas_beta_attach_symbol_emot_approved');
	//}
	function kuas_beta_attach_symbol_emot_approved(){
		$content = '<a href="#show_emot" class="show_smiley_code_cheat">'.__('Smiley Code').'?</a>';		
		$content .= '<div class="font_emot_glossarium">';
		$content .= '<ul id="font_emot_list">';
		$content .= '<li>:)</li><li>:smile:</li>';
		$content .= '<li>:(</li><li>:sad:</li>';
		$content .= '<li>;)</li><li>:wink:</li>';
		$content .= '<li>:P</li><li>:razz:</li>';
		$content .= '<li>-_-</li><li>:sleep:</li>';
		$content .= '<li>:o</li><li>:eek:</li>';
		$content .= '<li>8)</li><li>B)</li><li>:cool:</li>';
		$content .= '<li>:/</li>';
		$content .= '<li>O:)</li><li>:saint:</li>';
		$content .= '<li>^_^</li><li>:lol:</li>';
		$content .= '<li>x(</li><li>:angry:</li>';
		$content .= '<li>:x</li><li>:mad:</li>';
		$content .= '<li>:\'(</li><li>:cry:</li>';
		$content .= '<li>:devil:</li><li>:twisted:</li><li>:shock:</li><li>:grin:</li>';   
		$content .= '</ul></div>';
		print $content;
	}
}

/**
 *
 * KUAS ADVANCE SEARCH FUNCTION
 *
 * @since KuAs 1.0
 *
 */
function kuas_advanced_search_query($query) {
    if($query->is_search()) {
        if (isset($_GET['saluran'])) {
            $query->set('category_name', $_GET['saluran']);
        }    
        return $query;
    }
}
add_action('pre_get_posts', 'kuas_advanced_search_query', 1000);

/**
 *
 * KUAS PAGINATION FUNCTION
 *
 * @since KuAs 1.0
 *
 * Pagination for archive, taxonomy, category, tag and search results pages
 *
 * @global $wp_query http://codex.wordpress.org/Class_Reference/WP_Query
 * @return Prints the HTML for the pagination if a template is $paged
 */
if (!function_exists('kuas_beta_pagination')){
	function kuas_beta_pagination() {
		global $wp_query;
	 
		$big = 999999999; // This needs to be an unlikely integer
	 
		// For more options and info view the docs for paginate_links()
		// http://codex.wordpress.org/Function_Reference/paginate_links
		$paginate_links = paginate_links( array(
			'base' => str_replace( $big, '%#%', get_pagenum_link($big) ),
			'current' => max( 1, get_query_var('paged') ),
			'total' => $wp_query->max_num_pages,
			'mid_size' => 5
		) );
	 
		// Display the pagination if more than one page is found
		if ( $paginate_links ) {
			echo '<div class="pagination">';
			echo $paginate_links;
			echo '</div><!--// end .pagination -->';
		}
	}
}

/**
 *
 * KUAS Bloginfo Shortcode
 *
 * @since KuAs 1.0
 *
 * Get Bloginfo snippet with shortcode extract
 *
 */
if (!function_exists('kuas_bloginfo_shortcode')) {
	add_shortcode('bloginfo', 'kuas_bloginfo_shortcode');
	function kuas_bloginfo_shortcode( $atts ) {
	   extract(shortcode_atts(array(
	       'key' => '',
	   ), $atts));
	   return get_bloginfo($key);
	}
}

/**
 *
 * KUAS CATEGORY/CATEGORIES FUNCTION
 *
 * @since KuAs 1.0
 *
 * Get Categories Slug by ID
 *
 */
if (!function_exists('kuas_beta_get_cat_slug')) {
	function kuas_beta_get_cat_slug($cat_id) {
		$cat_id = (int) $cat_id;
		$category = &get_category($cat_id);
		return $category->slug;
	}
}

/**
 *
 * KUAS SHOW COUNT CATEGORY POST (category.php)
 *
 * @since KuAs 1.0
 *
 * Show Count Categories Post (with auto get terms every page category)
 * Is auto generate ID from global var WP
 *
 */
if (!function_exists('kuas_show_count_category')) {
	function kuas_show_count_category() {
		$count = '';
		if(is_category()) {	
			global $wp_query;
			$cat_ID = get_query_var('cat');
			$categories = get_the_category();		
			foreach($categories as $cat) {		
				$id = $cat->cat_ID;			
				if($id == $cat_ID) {
					$count = $cat->category_count;			
				}		
			}	
		}
		return $count;
	}
}
/**
 *
 * KUAS SHOW COUNT CATEGORY POST (category.php)
 *
 * @since KuAs 1.0
 *
 * Get Categories Count by ID
 * Is manual generate by ID category current page
 *
 */
if (!function_exists('kuas_get_category_count')) {
	function kuas_get_category_count($input='') {
		global $wpdb;
		if($input=='' || empty($input)) {
			$category = get_the_category();
			return $category[0]->category_count;
		}
		elseif(is_numeric($input)) {
			$SQL = "SELECT $wpdb->term_taxonomy.count FROM $wpdb->terms, $wpdb->term_taxonomy WHERE $wpdb->terms.term_id=$wpdb->term_taxonomy.term_id AND $wpdb->term_taxonomy.term_id=$input";
			return $wpdb->get_var($SQL);
		}
		else {
			$SQL = "SELECT $wpdb->term_taxonomy.count FROM $wpdb->terms, $wpdb->term_taxonomy WHERE $wpdb->terms.term_id=$wpdb->term_taxonomy.term_id AND $wpdb->terms.slug='$input'";
			return $wpdb->get_var($SQL);
		}
	}
}

/**
 *
 * KUAS CUSTOM MENU FALLBACK
 *
 * @since KuAs 1.0
 *
 * Set custom Fallback menu for navigation
 *
 */
if (!function_exists('kuas_beta_menu_fallback')) {
	function kuas_beta_menu_fallback() { ?>
			<ul class="menu">
				<?php
					wp_list_pages(array(
						'number' => 5,
						'exclude' => '',
						'title_li' => '',
						'sort_column' => 'post_title',
						'sort_order' => 'ASC',
					));
				?>  
			</ul>
	    <?php
	}
}

/**
 *
 * CURRENCY NUMERICAL TO K
 *
 * @since KuAs 1.0
 *
 * Convert Numeric Value Zero tail, like 1.000.000 become 1m or 1.000 become 1k
 *
 */
if (!function_exists('thousandsCurrencyFormat')) {
	function thousandsCurrencyFormat($num) {
		$x = round($num);
		$x_number_format = number_format($x);
		$x_array = explode(',', $x_number_format);
		if(WPLANG == 'id_ID'){
		$x_parts = array('rb', 'jt', 'm', 't');
		} 
		else {
		$x_parts = array('k', 'm', 'b', 't');	
		}
		$x_count_parts = count($x_array) - 1;
		$x_display = $x;
		$x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
		$x_display .= $x_parts[$x_count_parts - 1];
		return $x_display;
	}
}

/**
 * Custom Reverse Million Format (using for google+ count button share)
 *
 * @since KuAs 1.0
 *
 */
if (!function_exists('ReverseThousandsCurrencyFormat')) {
	function ReverseThousandsCurrencyFormat($num) {
		if(preg_match('/(k)/',$num) || preg_match('/(rb)/',$num)){
			$x_display = '000';
		}
		elseif(preg_match('/(m)/',$num) || preg_match('/(jt)/',$num)){
			$x_display = '000000';
		}
		elseif(preg_match('/(b)/',$num) || preg_match('/(ml)/',$num)){
			$x_display = '000000000';
		}
		elseif(preg_match('/(t)/',$num)){
			$x_display = '000000000000';
		}
		else {
			$x_display = '';
		}
		$result = preg_replace('/[^0-9+]/', '', $num).$x_display;
		return intval($result);
	}
}

/**
 *
 * MANUALLY ADD RANDOM ARRAY FUNCTION
 *
 * @since KuAs 1.0
 *
 * Miscellaneous function for array squance values become 1 show up value
 *
 */
if (!function_exists('randomArrayVar')) {
	function randomArrayVar($array){
		if (!is_array($array)){
			return $array;
		}
		return $array[array_rand($array)];
	}
}

/**
 *
 * KUAS SLICE TEXT with LIMIT RETURN WORDS
 *
 * @since KuAs 1.0
 *
 * Limit Text displaying from strings with removing miscellaneous character and spliting by whitespace
 * Used for Title post, title content or for any content if it is on the narrow
 *
 */
if (!function_exists('kuas_beta_slice_title_limit')){
	function kuas_beta_slice_title_limit($str,$limits){
		$strfix = str_replace(array('-','_',',', '<', '>', '&', '{', '}', '(', ')', '.', '*', '"'), array(''),$str);
		$title_post_trim_space = explode(" ", $strfix);
		$titlefixes = array();
		$count_operator = 1;
		foreach ($title_post_trim_space as $titlefix) {
			$titlefix = array_push($titlefixes,$titlefix);
			if($count_operator++ == $limits) break;
		}
		return $titlefixes = join(' ',$titlefixes);		
	}
}

/**
 *
 * KUAS SNIPPET TEXT (like limit words)
 *
 * @since KuAs 1.0
 *
 * Adpot from views post plugin to limit all text strings, with other function than simply slicing method
 *
 */
if(!function_exists('kuas_beta_snippet_text')) {
	function kuas_beta_snippet_text($text, $length = 0) {
		if (defined('MB_OVERLOAD_STRING')) {
		  $text = @html_entity_decode($text, ENT_QUOTES, get_option('blog_charset'));
		 	if (mb_strlen($text) > $length) {
				return htmlentities(mb_substr($text,0,$length), ENT_COMPAT, get_option('blog_charset')).'...';
		 	} else {
				return htmlentities($text, ENT_COMPAT, get_option('blog_charset'));
		 	}
		} else {
			$text = @html_entity_decode($text, ENT_QUOTES, get_option('blog_charset'));
		 	if (strlen($text) > $length) {
				return htmlentities(substr($text,0,$length), ENT_COMPAT, get_option('blog_charset')).'...';
		 	} else {
				return htmlentities($text, ENT_COMPAT, get_option('blog_charset'));
		 	}
		}
	}
}

/**
 *
 * KUAS MULTI EXPLODE FUNCTION
 *
 * @since KuAs 1.0
 *
 */
if (!function_exists('kuas_beta_multiexplode')){
	function kuas_beta_multiexplode ($delimiters,$string) {
	    $ready = str_replace($delimiters, $delimiters[0], $string);
	    $launch = explode($delimiters[0], $ready);
	    return  $launch;
	}
}

/**
 *
 * KUAS FIX <p> or <br/> for SHORTCODE
 *
 * @since KuAs 1.0
 *
 */
if (!function_exists('kuas_fix_p_shortcode')){
	function kuas_fix_p_shortcode($content) {
		$array = array(
			'<p>[' => '[',
			']</p>' => ']',
			']<br />' => ']'
		);

		$content = strtr($content, $array);

		return $content;
	}
}

/**
 * Custom Excerpt
 *
 * @since KuAs 1.0
 *
 */
function kuas_beta_custom_excerpt($text, $excerpt,$limit=55){
    if ($excerpt) return $excerpt;

    $text = strip_shortcodes( $text );

    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]&gt;', $text);
    $text = strip_tags($text);
    $excerpt_length = apply_filters('excerpt_length', $limit);
    $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
    $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
    if ( count($words) > $excerpt_length ) {
            array_pop($words);
            $text = implode(' ', $words);
            $text = $text . $excerpt_more;
    } else {
            $text = implode(' ', $words);
    }

    return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}

/**
 *
 * KUAS TIME AGO REPLACING the_time() function add_filter()
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_beta_time_ago')){	 
	// Filter our kuas-beta_time_ago() function into WP's the_time() function
	add_filter('the_time', 'kuas_beta_time_ago');
	add_filter('get_the_time', 'kuas_beta_time_ago');
	function kuas_beta_time_ago() {
		global $post; 
		$date = get_post_time('G', true, $post);
		return kuas_convert_time_ago($date);
	}
}

/**
 * Custom Time Ago Convert Manually, because WP time_human_diff is can't translating
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_convert_time_ago')){
	function kuas_convert_time_ago($date){
		$chunks = array(
			array( 60 * 60 * 24 * 365 , __( 'tahun', 'kuas-beta' ), __( 'tahun', 'kuas-beta' ) ),
			array( 60 * 60 * 24 * 30 , __( 'bulan', 'kuas-beta' ), __( 'bulan', 'kuas-beta' ) ),
			array( 60 * 60 * 24 * 7, __( 'pekan', 'kuas-beta' ), __( 'pekan', 'kuas-beta' ) ),
			array( 60 * 60 * 24 , __( 'hari', 'kuas-beta' ), __( 'hari', 'kuas-beta' ) ),
			array( 60 * 60 , __( 'jam', 'kuas-beta' ), __( 'jam', 'kuas-beta' ) ),
			array( 60 , __( 'menit', 'kuas-beta' ), __( 'menit', 'kuas-beta' ) ),
			array( 1, __( 'detik', 'kuas-beta' ), __( 'detik', 'kuas-beta' ) )
		);
		if ( !is_numeric( $date ) ) {
			$time_chunks = explode( ':', str_replace( ' ', ':', $date ) );
			$date_chunks = explode( '-', str_replace( ' ', '-', $date ) );
			$date = gmmktime( (int)$time_chunks[1], (int)$time_chunks[2], (int)$time_chunks[3], (int)$date_chunks[1], (int)$date_chunks[2], (int)$date_chunks[0] );
		}
		$current_time = current_time( 'mysql', $gmt );
		$newer_date = ( !$newer_date ) ? strtotime( $current_time ) : $newer_date; 
		$since = $newer_date - $date;
		if ( 0 > $since ) return __( 'suatu hari', 'kuas-beta' );
		for ( $i = 0, $j = count($chunks); $i < $j; $i++) {
			$seconds = $chunks[$i][0];
			if ( ( $count = floor($since / $seconds) ) != 0 ) break;
		}
		$output = ( 1 == $count ) ? '1 '. $chunks[$i][1] : $count . ' ' . $chunks[$i][2]; 
		if ( !(int)trim($output) ){
			$output = '0 ' . __( 'detik', 'kuas-beta' );
		}
		$output .= __(' yg lalu', 'kuas-beta'); 
		return $output;		
	}
}

/**
 * Display custom Author contact
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_beta_author_contact_list')){
	function kuas_beta_author_contact_list($id_user,$display=true,$auto_meta='auto'){
		$author_description = get_the_author_meta('description',$id_user);
		$twitter = get_the_author_meta('twitter',$id_user);
		$facebook = get_the_author_meta('facebook',$id_user);
		$path = get_the_author_meta('path',$id_user);
		$instagram = get_the_author_meta('instagram',$id_user);
		$line_chat = get_the_author_meta('line_chat',$id_user);
		$gplus = get_the_author_meta('gplus',$id_user);

		$output = '';
		if($twitter OR $facebook OR $path OR $instagram OR $line_chat OR $gplus){
			$output .= '<ul id="author_contact">';

			if($twitter){
			$output .= '<li><a href="http://twitter.com/'.$twitter.'" class="iconSocialMedia twitter" target="_blank"><i>twitter</i></a></li>';
			}
			if($facebook){
			$output .= '<li><a href="'.$facebook.'" class="iconSocialMedia facebook" target="_blank"><i>facebook</i></a></li>';
			}
			if($path){
			$output .= '<li><a href="javascript:void(0)" title="Nama di Path: '.$path.'" class="iconSocialMedia path normalTip"><i>path</i></a></li>';
			}
			if($instagram){
			$output .= '<li><a href="http://instagram.com/'.$instagram.'" class="iconSocialMedia instagram" target="_blank"><i>instagram</i></a></li>';
			}
			if($line_chat){
			$output .= '<li><a href="javascript:void(0)" title="add LINE ID: '.$line_chat.'" class="iconSocialMedia line_chat normalTip"><i>LINE</i></a></li>';
			}
			if($gplus){
			$output .= '<li><a href="'.$gplus.'" class="iconSocialMedia gplus" target="_blank"><i>gplus</i></a></li>';
			}

			$output .= '</ul>';
		} else {
			if($auto_meta=='auto')
			$output .= $author_description;
		}

		if($display==true){
		return _e($output,'kuas-beta');
		} elseif($display==false) {
		return __($output,'kuas-beta');			
		} else {
		return $output;	
		}
	}
}

/**
 *
 * KUAS HTML MARKUP FOR LIST POST (categoies, archive, search, tags)
 *
 * @since KuAs 1.0
 *
 */
if (!function_exists('kuas_beta_list_post')){
	function kuas_beta_list_post($type='excerpt',$meta=1){
		if( have_posts() ){
		$count_loop_posts = 0;
		while ( have_posts() ) : the_post(); ?>	

			<?php if ( is_sticky() ) : ?>
			<div class="post sticky-post">
			<?php else: ?>
			<div class="post">
			<?php endif; ?>

				<?php if($type!='title'):
				if(has_post_thumbnail( get_the_ID() ) ):
				?>
				<div class="post-image">
					<a href="<?php the_permalink(); ?>">
					<?php
						if ( is_sticky() ) :
							the_post_thumbnail( 'thumbnail', array('title'=>'','class' => 'sepiaKuasEffect') );
						else:
							the_post_thumbnail( 'thumbnail' );
						endif;
					?>
					</a>
				</div>
				<div class="right">	
				<?php else: ?>
				<div>	
				<?php endif;
				endif;
				?>

					<?php /*if ( is_sticky() ) : ?>
					<div class="sticky"><?php _e( 'Info Penting', 'kuas-beta' ); ?></div>
					<?php endif;*/

				switch($type) {
				case 'simple':	
				?>		

					<h3><a href="<?php the_permalink(); ?>" class="post-title normalTip" title="<?php printf( __('Lanjutkan membaca: %s', 'kuas-beta'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php _e(kuas_beta_slice_title_limit(get_the_title(),7),'kuas-beta'); ?></a></h3>			
					<?php if($meta==1): ?>
					<div class="post-meta">
						<span class="date"><?php the_time('j F, Y'); ?></span>
						<span class="category"><?php the_category(', '); ?></span> 
						<br />
						<span class="share-count"><?php kuas_render_social_share_count(get_permalink(),'count',get_the_ID(),0,1) ?></span>
						<span class="sep"> - </span>
						<?php
							if( the_views(false) > 0 && wp_is_mobile()==false ){
								echo '<span class="viewed-count">';
								the_views(true).' '._e('dibaca','kuas-beta');
								echo '</span><span class="sep"> - </span> ';
							}
						?>
						<span class="more">
						<a href="<?php the_permalink(); ?>" class="normalTip bold" title="<?php printf( __('Lanjutkan membaca: %s', 'kuas-beta'), the_title_attribute('echo=0') ); ?>"><span><?php _e('Ikut Baca! &raquo;', 'kuas-beta'); ?></span></a>
						</span>
					</div>
					<?php endif; ?>

				<?php
				break;
				case 'excerpt':
				?>	

					<h2><a href="<?php the_permalink(); ?>" class="post-title normalTip"  title="<?php printf( __('Lanjutkan membaca: %s', 'kuas-beta'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php _e(kuas_beta_slice_title_limit(get_the_title(),3),'kuas-beta'); ?></a></h2>																			
					<div class="exceprt"><?php the_excerpt(); ?></div>
					<?php if($meta==1): ?>		
					<div class="post-meta">					
						<span class="category"><?php _e('Saluran:','kuas-beta'); ?><br/><?php the_category(', '); ?></span>
						<br />
						<span class="share-count"><?php kuas_render_social_share_count(get_permalink(),'count',get_the_ID(),0,1) ?></span>
						<span class="sep"> - </span>
						<span class="date"><?php the_time(get_option('date_format')); ?></span>
						<span class="sep"> - </span>
						<?php
						if(function_exists('the_views')){
							if( the_views(false) > 0 && wp_is_mobile()==false ){
								echo '<span class="viewed-count">';
								the_views(true).' '._e('dibaca','kuas-beta');
								echo '</span><span class="sep"> - </span> ';
							}
						}
						?>
						<span class="more">
						<a href="<?php the_permalink(); ?>" class="normalTip bold" title="<?php printf( __('Lanjutkan membaca: %s', 'kuas-beta'), the_title_attribute('echo=0') ); ?>"><span><?php _e('Ikut Baca! &raquo;', 'kuas-beta'); ?></span></a>
						</span>						
						<?php /*the_tags( '<span class="sep"> - </span><span class="tags">' . __('Topik: ', 'kuas-beta' ) . ' ', ", ", "</span>" )
						<span class="sep"> - </span> 
						<span class="viewed-count"><?php if( the_views(false) > 0 ){the_views(true).' '._e('dibaca','kuas-beta');}else{ _e('belum dibaca','kuas-beta'); } ?></span>*/ ?>
						<?php /*if ( comments_open() ) :
							<span class="sep"> - </span>
							<span class="comments list-content-post"><?php comments_popup_link( __('0', 'kuas-beta'), __( '1', 'kuas-beta'), __('%', 'kuas-beta')); ?></span>			
						endif;*/ ?>			
					</div>					
					<?php /* <div class="more">
					<a href="<?php the_permalink(); ?>" class="normalTip bold read_more floating borderRadius2 display-inline-block"><span><?php _e('Baca ', 'kuas-beta'); ?></span></a>
					</div> */ 
					endif; ?>

				<?php
				break;
				case 'title':	
				?>		
					<div>
					<h3><a href="<?php the_permalink(); ?>" class="post-title normalTip" title="<?php printf( __('Lanjutkan membaca: %s', 'kuas-beta'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php _e(kuas_beta_slice_title_limit(get_the_title(),7),'kuas-beta'); ?></a></h3>			
					<?php if($meta==1): ?>
					<div class="post-meta type-<?php echo $type ?>">
						<span class="date"><?php the_time('j F, Y'); ?></span>
						<span class="sep"> - </span>
						<span class="category"><?php the_category(', '); ?></span> 
						<br />
						<span id="simple-share-count" class="share-count"><?php kuas_render_social_share_count(get_permalink(),'init',get_the_ID(),0,1) ?></span>
						<?php
						if(function_exists('the_views')){
							if( the_views(false) > 0 && wp_is_mobile()==false){
								echo '<span class="sep"> - </span><span class="viewed-count">';the_views(true).' '._e('dibaca','kuas-beta');
								echo '</span>';
							}
						}
						?>
					</div>
					<?php endif; ?>

				<?php
				break;				 
				}
				?>

				</div> <!-- Right -->


			</div> <!-- post -->		

		<?php
		if(wp_is_mobile()==false){
			$count_loop_posts++;
			if($count_loop_posts%3 == 0 && !is_author()){
				if(function_exists('wp125_single_ad')){
					echo '<div class="post"><div id="kuas_pariwara_widget-7" class="kuas_pariwara_widget">';
					wp125_single_ad(4,true);
					echo '</div></div>';
				}
			}
		}
		?>
		<?php endwhile;
		}
		else {
			echo '<div id="error_message">'.__('Tidak ada tulisan disini','kuas-beta').'</div>';
		}
	}
}

/**
 *
 * KUAS SUB CATEGORIES LIST (only title) DEFINED BY ID PARENTS CATEGORIES
 *
 * @since KuAs 1.0
 *
 */
if (!function_exists('kuas_beta_subcategory_list')){
	function kuas_beta_subcategory_list($id_cat, $class='wide', $limit=10, $show_no_post=1, $title=1){
		$output  = '';
		if( empty($id_cat) ){ $id_cat = '3'; }

		// Define categroy ID = 152
		$parent_sub_categories_id = $id_cat;
		$parent_sub_categories_name = get_cat_name( $parent_sub_categories_id );
		$parent_sub_categories_link = get_category_link( $parent_sub_categories_id );
		$parent_sub_categories_slug = kuas_beta_get_cat_slug( $parent_sub_categories_id );
		$parent_sub_categories_count = kuas_get_category_count($id_cat);
		// Define Sub Categories
		$sub_categories =  get_categories('child_of='. $parent_sub_categories_id . '&hide_empty=0&orderby=count&order=DESC');
		
		$output .= '<div id="sub-category" class="p-'.$class.' head_is-'.$title.' sub-categories-parent categories-'.$parent_sub_categories_slug.' borderColorBaseKuas">';

			if( $title==1 ){
			$output .= '<h3 id="sub-categories-parent-title" class="h-'.$class.' headingColorBaseKuas">';
				$output .= '<i class="'.$parent_sub_categories_slug.' iconicballs bgImgCategory _size30"></i>';
				$output .= '<a href="'.$parent_sub_categories_link.'" class="bgColorBaseKuas_y2 display-block">';
					$output .= kuas_beta_slice_title_limit($parent_sub_categories_name,1);
				$output .= '</a>';
			$output .= '</h3>';
			}

			$output .= '<div id="sub-categories-container" class="c-'.$class.' bgColorBaseKuas_y3">';

			$build_html_sub_categories = '';
			$loop = 1;
			foreach ($sub_categories as $key_categories => $sub_categories_result) {
				$sub_cat_parent_id = $sub_categories_result->parent;
				$sub_cat_count = $sub_categories_result->category_count;
				$sub_cat_term_id = $sub_categories_result->term_id;
				$sub_cat_name = $sub_categories_result->cat_name;
				$sub_catname = $sub_categories_result->name;
				$sub_cat_id = $sub_categories_result->cat_ID;
				$sub_cat_slug = $sub_categories_result->slug;
				$sub_cat_link = get_category_link( $sub_cat_term_id );
				$sub_cat_count = $sub_categories_result->count;

				if( ($show_no_post==1 && $sub_cat_count > 0) || $show_no_post==0 ){
					if($sub_cat_count > 0){
						$title_achor = sprintf(__('Ada %1$s tulisan di saluran %2$s %3$s','kuas-beta'),$sub_cat_count,kuas_beta_slice_title_limit($parent_sub_categories_name,1),$sub_catname);
					} else {
						$title_achor = sprintf(__('Tidak ada tulisan di saluran %s :(','kuas-beta'),$sub_catname);
					}

					$build_html_sub_categories .= '<li id="'.$sub_cat_id.'" class="cat_'.$sub_cat_slug.' hover-effect">';
					$build_html_sub_categories .= '<div class="container-sub-categori bgImgCategory icon _size30 subcat-'.$sub_cat_term_id.'">';
					if($class=='tail'){
						$build_html_sub_categories .= '<a href="'.$sub_cat_link.'" class="normalTip" title="'.$title_achor.'">'.kuas_beta_slice_title_limit($sub_catname,3).' <span class="count-sub-category">'.thousandsCurrencyFormat($sub_cat_count).'</span></a></div></li>';
					}
					else{
						$build_html_sub_categories .= '<span class="count-sub-category">'.thousandsCurrencyFormat($sub_cat_count).'</span><a href="'.$sub_cat_link.'" class="normalTip" title="'.$title_achor.'">'.kuas_beta_slice_title_limit($sub_catname,1).'</a></div></li>';
					}
					
					if( is_numeric($limit) && $loop++ == $limit ) break;
				}
			}

			$view_all_sub_categories = '<li id="0" class="see-all-sub-categories"><a href="'.$parent_sub_categories_link.'" class="normalTip" title="Total '.$parent_sub_categories_count.' tulisan">';
			if($class=='wide'){
			$view_all_sub_categories .= __('Semua','kuas-beta').' &raquo;</a></li>';
			} 
			else {
			$view_all_sub_categories .= sprintf(__('Lihat Semua %s &raquo;','kuas-beta'),kuas_beta_slice_title_limit($parent_sub_categories_name,1)).'</a></li>';	
			}
			$output .= '<ul id="cat-'.$parent_sub_categories_id.'">'.$build_html_sub_categories.$view_all_sub_categories.'</ul>';

			$output .= '</div>';
		$output .= '</div>';
		return $output;			
	}
}

/**
 *
 * KUAS FEATURED CATEGORIES (display pretty RECENT POST by CATEGORIES SELECTED)
 *
 * @since KuAs 1.0
 *
 */
if (!function_exists('kuas_beta_featured_post')){
	function kuas_beta_featured_post( $id_category, $limit_post_top='1', $limit_post_child='3', $limit_carousel='2', $limit_excerpt=150, $orderby=array('date','date'), $order=array('DESC','DESC'), $carousel=true ){
		//Define output variable
		$output = '';

		//Define variable data for loop access
		$cat_id = $id_category; 
		$cat_name = get_cat_name($cat_id);
		$cat_url = get_category_link( $cat_id );
		$cat_slug = kuas_beta_get_cat_slug( $cat_id );
		$feat_categories_count = kuas_get_category_count($cat_id);

		//Set condition carousel effect with diferent class on function parameter
		if($carousel==true){ $carousel = 'feat-carousel';
		} elseif($carousel==false){ $carousel = 'no-carousel';
		} else{ $carousel = 'feat-carousel-autofalse'; }

		//Build markup HTML parents
		$output .= '<div class="category '.$cat_slug.' icon">';
			$output .= '<h3 class="cat-title">';
				$output .= '<i class="'.$cat_slug.' iconicballs iconic_feat bgImgCategory _size30"></i>'; /* iconicball class for title icon */
				$output .= '<a href="'.esc_url( $cat_url ).'" class="bgColorBaseKuas_y2 shadowBottom">'.$cat_name.'</a>';
			$output .= '</h3>';

				//Set condition limit top post by one value
				if($limit_post_top == 1){
				$output .= '<div class="feat-post">';
					//Define Post Query Parameter SQL WP
					$post_query = 'cat='.$cat_id.'&posts_per_page='.$limit_post_top.'&orderby='.$orderby[0].'&order='.$order[0];
						//Define fetch Query Post from Parameter Post Query
						query_posts( $post_query );
							//Set condition if fetch Query have post data on SQL
							if (have_posts()) :
								//Begin looping fetch data SQL WP with while
								while (have_posts()) : the_post();
									//Get post Images thumbnail & build markup HTML
									$output .= '<a href="'.get_permalink(get_the_ID()).'" class="thumb_link_feat">'.get_the_post_thumbnail( get_the_ID(),( 'medium' )).'</a>';
										//Build markup HTML for achor title post top
										$output .= '<h3 class="bgColorBaseKuas_y2">';
											$output .= '<a href="'.get_permalink(get_the_ID()).'" rel="bookmark" class="normalTip" title="'.get_the_title(get_the_ID()).'">';
												//Display only first 26 characters in the title.	
												$short_title = substr(get_the_title(get_the_ID()),0,26);
												$output .= $short_title; 
												if (strlen($short_title) >25){ 
													$output .= '...'; 
												} 
											$output .= '</a>';
										$output .= '</h3>';

									//Set condition top post exerpt limit
									if($limit_excerpt > 0){						
									$output .= '<p style="height:'.((int) $limit_excerpt-30).'px">';
										//Display only first 150 characters in the slide description.								
										$excerpt = get_the_excerpt();																
										$output .= substr($excerpt,0, $limit_excerpt);									
										if (strlen($excerpt) > $limit_excerpt){ 
											$output .= ' <a href="'.esc_url( $cat_url ).'" class="fixedTip" title="'.__('Lanjutkan membaca','kuas-beta').'&rarr; '.get_the_title(get_the_ID()).'">[...]</a>'; 
										} 
									$output .= '</p>';
									}
								//End while post loop
								endwhile;
							//End if have post data query
							endif;
						//Set reset request data SQL query WP after looping post
						wp_reset_query();
				$output .= '</div>';
				}
				else {
				//Set condition if post top value is 0 with display default markup HTML for replacing margin position on the top
				$output .= '<div style="clear:both;margin-top:43px;"></div>';
				}
				//Build markup HTML for looping post child on same categories with top post
				if($limit_post_child > 0){
				$output .= '<div class="more-posts '.$carousel.'" data-limit="'.$limit_carousel.'">';
					$output .= '<ul>';
					//Define Post Query Parameter SQL WP
					$post_query = 'cat='.$cat_id.'&posts_per_page='.$limit_post_child.'&offset=1&orderby='.$orderby[1].'&order='.$order[1];
						//Define fetch Query Post from Parameter Post Query
						query_posts( $post_query );
							//Set condition if fetch Query have post data on SQL
							if (have_posts()) :
								//Begin looping fetch data SQL WP with while
								while (have_posts()) : the_post();
									$output .= '<li class="post">';
										$output .= '<a href="'.get_permalink(get_the_ID()).'" class="achor-images-feat-post">';
											$output .= get_the_post_thumbnail( get_the_ID(), 'thumbnail', array('title' => ''.get_the_title(get_the_ID()).'' ));
										$output .= '</a>';
										$output .= '<div class="right">';
											$output .= '<span class="small-text display-block">';
												$output .= '<a href="'.get_permalink(get_the_ID()).'" class="fixedTip url-feat-more-post" rel="bookmark" title="'.get_the_title(get_the_ID()).'">';

														// display only first 22 characters in the title.	
														$title_more_feat = get_the_title(get_the_ID());
														$short_title = substr($title_more_feat,0,80);
														//
														$excerpt = get_the_excerpt();																
														$total_excerpt = strlen($excerpt);
														$total_title = strlen($short_title);
														//
														$output .= $short_title;

												$output .= '</a> ';

												$output .= substr( $excerpt,0, 90-strlen($short_title) ).' <a href="'.esc_url( $cat_url ).'" class="fixedTip" title="'.__('Lanjutkan membaca','kuas-beta').'&rarr; '.get_the_title(get_the_ID()).'">[...]</a>';
	
											$output .= '</span>';

										$output .= '</div>';
									$output .= '</li>';
								endwhile;
							endif;					
							wp_reset_query();
					$output .= '</ul>';
				$output .= '</div> <!-- /more posts -->';
			$output .= '<a href="'.esc_url( $cat_url ).'" class="read_more bold borderRadius2 display-block text-align-center normalTip" title="'.sprintf(__('Lihat semua saluran %1$s - Total %2$s tulisan', 'kuas-beta'),$cat_name,$feat_categories_count).'">'.__('Lihat Semua', 'kuas-beta').' &raquo;</a>';
			}
		$output .= '</div><!-- /category -->';		
		return $output;
	}	
}

/**
 *
 * KUAS FEATURED CATEGORIES MOBILE version (display pretty RECENT POST by CATEGORIES SELECTED)
 *
 * @since KuAs 1.0
 *
 */
if (!function_exists('kuas_beta_featured_post_mobile')){
	function kuas_beta_featured_post_mobile($number_featured_show=4,$print=1){
		$output = '';
		$number_featured_show = $number_featured_show+1;
		for($k=0;$k<$number_featured_show;$k++){
			$cat_id = kuas_beta_get_option( 'feat_cat'.$k );
			if ( $cat_id != 0) {
				//Define variable data for loop access
				$cat_name = get_cat_name($cat_id);
				$cat_url = get_category_link( $cat_id );
				$cat_slug = kuas_beta_get_cat_slug( $cat_id );
				$feat_categories_count = kuas_get_category_count($cat_id);

				$output .= '<div id="feat-post">';
					//Define Post Query Parameter SQL WP
					$post_query = 'cat='.$cat_id.'&posts_per_page=1&orderby=rand&order=DESC';
					//Define fetch Query Post from Parameter Post Query
					query_posts( $post_query ); 
						if (have_posts()) :
							//Begin looping fetch data SQL WP with while
							while (have_posts()) : the_post();
								$output .= '<a href="'.esc_url( $cat_url ).'" class="thumb_link_feat '.$k.'"><i class="'.$cat_slug.' iconicballs iconic_feat bgImgCategory _size30"></i>';
								$output .= '<h3 class="headingColorBaseKuas bgColorBaseKuas_y1">'.$cat_name.'</h3>';
								$output .= '<span class="sepiaKuasEffect">'.get_the_post_thumbnail( get_the_ID(), 'feat-thumb', array('title' => get_the_title(get_the_ID()) )).'</span>';
								$output .= '</a>';
							endwhile;
						endif;
						wp_reset_query();
				$output .= '</div>';
			}
		}

		if($print==true){
		echo $output;
		} 
		else {
		return $output;
		}				
	}
}

/**
 *
 * KUAS SLIDER CATEGORIES (display slider categrories order by recents)
 *
 * @since KuAs 1.0
 *
 */
if (!function_exists('kuas_beta_slider_category')){
	function kuas_beta_slider_category($carousel_cat_id=0,$limit_post=10,$order='DESC',$orderby='date',$auto_data=false){	
			//if no category is selected for carousel, show latest posts
			$sticky = get_option( 'sticky_posts' );
			if ( $carousel_cat_id == 0 && is_numeric($carousel_cat_id)) {
				$post_to_exclude[] = $sticky[0];
				$post_query = array('ignore_sticky_posts'=>1,'post__not_in'=>$post_to_exclude,'caller_get_posts'=>1,'posts_per_page'=>$limit_post,'order'=>$order,'orderby'=>$orderby);
			}
			elseif($carousel_cat_id == 'is_sticky'){
				$post_query = array('post__in'=>$sticky,'order'=>$order,'caller_get_posts'=>0,'ignore_sticky_posts'=>0,'posts_per_page'=>$limit_post,'orderby'=>$orderby);
			}
			else {
				$post_query = array('cat'=>$carousel_cat_id,'posts_per_page'=>$limit_post,'order'=>$order,'orderby'=>$orderby);
			}

			if($auto_data == true){$auto_datastring='true';}elseif($auto_data == false){$auto_datastring='false';}

		?>

		<div id="carousel" class="bgColorBaseKuas_y3">
			<div class="title bgColorBaseKuas_y2">
				
				<div class="cat">
					<h3 class="headingColorBaseKuas">
						<?php
							if ($carousel_cat_id == 0 && is_numeric($carousel_cat_id)) {
								 printf(__('%s Tulisan', 'kuas-beta'),$limit_post);						
							} 
							elseif(preg_match('/,/', $carousel_cat_id )){
								$slice_multi_categories = explode(',', $carousel_cat_id);
								$new_multi_cat_names = array();
								foreach ($slice_multi_categories as $cat_ids) {
									$cat_ids = array_push($new_multi_cat_names, kuas_beta_slice_title_limit(get_cat_name($cat_ids),1) );
								}
								$new_multi_cat_names = join(', ',$new_multi_cat_names);
								 _e($new_multi_cat_names.' Kuningan', 'kuas-beta');
							}
							elseif($carousel_cat_id == 'is_sticky'){
								_e('Favorit Mamang Kuningan Asri', 'kuas-beta');
							}
							else {
								$carousel_cat_name = get_cat_name($carousel_cat_id);
								$carousel_cat_url = get_category_link( $carousel_cat_id );
								?>
								<a href="<?php echo esc_url( $carousel_cat_url ); ?>" ><?php echo $carousel_cat_name; ?></a>
							<?php
							}
						?>		
					</h3>
				</div>
				
				<?php if($limit_post > 3): ?>
				<div class="buttons">
					<div class="prev"><img src="<?php echo get_template_directory_uri(); ?>/images/prev.png" alt="prev" /></div>
					<div class="next"><img src="<?php echo get_template_directory_uri(); ?>/images/next.png" alt="next" /></div>
				</div>
				<?php endif; ?>

			</div>
			
			<div class="carousel-posts carousel-auto<?php echo $auto_datastring ?>">				
				<ul>
					<?php query_posts( $post_query ); if( have_posts() ) : while( have_posts() ) : the_post(); ?>
					<li>

						<?php if ( is_sticky() ) : ?>
						<a href="<?php the_permalink() ?>" class="achor-thumb-post-slider slider-sticky sticky-post">
						<?php the_post_thumbnail( 'carousel-thumb' , array('class' => 'sepiaKuasEffect')); ?></a>
						<?php else: ?>
						<a href="<?php the_permalink() ?>">
						<?php the_post_thumbnail( 'carousel-thumb'); ?></a>
						<?php endif; ?>

						<h4 align="center"> 
							<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'kuas-beta'), the_title_attribute('echo=0')); ?>">
								<?php 
									// display only first 22 characters in the title.	
									$short_title = substr(the_title('','',FALSE),0,22);
									echo $short_title; 
									if (strlen($short_title) >21){ 
										echo '...'; 
									} 
								?>	
							</a>
						</h4>
						
						<div class="post-meta">
							<span class="date"><center><?php the_time('j F'); ?></center></span> 
							<?php
							/*<span class="sep">-</span> 
							<span class="comments"><?php comments_popup_link( __('no comments', 'kuas-beta'), __( '1 comment', 'kuas-beta'), __('% comments', 'kuas-beta')); ?></span>*/
							?>
						</div>
						
						<div class="post-excerpt">
								<?php 
									// display only first 150 characters in the description.								
									$excerpt = get_the_excerpt();																
									echo substr($excerpt,0, 100);									
									if (strlen($excerpt) > 99){ 
										echo '...'; 
									} 
								?>			
						</div>	
						<a href="<?php the_permalink(); ?>" class="bold read_more borderRadius2 display-block text-align-center"><?php _e('Baca &raquo;', 'kuas-beta'); ?></a>
					</li>
							
					<?php endwhile; endif; ?>
					<?php wp_reset_query(); ?>
				</ul>				
			</div>			
		</div><!-- /carousel -->		
	<?php
	}
}

/**
 * Extract category ID from current post/ID post
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_beta_extract_categories_from_post')){
	function kuas_beta_extract_categories_from_post($id_post){
		$array_category = array();
		$extract_cat = get_the_category($id_post);
		foreach($extract_cat as $categories){			
			$id_cat_arr = $categories->cat_ID;
			$id_cat_arr = array_push($array_category,$id_cat_arr);
		}
		return $array_category;
	}
}

/**
 * Extract categories from current category
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_beta_extract_post_same_categories')){
	function kuas_beta_extract_post_same_categories($premiere_parram,$limit_post_from_cat=3,$excerpt=false,$display=false){
		$print_category = '';
		if(empty($premiere_parram)) {
			$print_category .= 'Kesalahan: ID/array kosong';
		}
		elseif(is_array($premiere_parram)){
			$array_category = $premiere_parram;
			$print_category .= '<!-- is array -->';
		}
		elseif(is_numeric($premiere_parram)){
			$array_category = kuas_beta_extract_categories_from_post($premiere_parram);
			$print_category .= '<!-- is numeric -->';
		}
		else{
			$print_category .= 'Kesalahan: Bukan array, numeric ataupun empty';
		}

		/*
		 * Build Recent Post From same categories on post with each categories extract
		 */
		foreach($array_category as $k=>$cat_ID){
			$category_post_count = kuas_get_category_count($cat_ID);
			$print_category .= '<div id="more-post-cat-'.$k.'" class="widget widget_more_post_cat widget_postlist">';
			$print_category .= '<h4 class="headingColorBaseKuas">'.get_cat_name($cat_ID).' ('.thousandsCurrencyFormat($category_post_count).')</h4>';
			$print_category .= '<div class="container_widget_postlist"><ul>';

			$parameter = array(
				'posts_per_page'   => $limit_post_from_cat,
				'offset'           => 0,
				'category'         => $cat_ID,
				'orderby'          => 'rand',
				'order'            => 'DESC',
				'exclude'          => $premiere_parram,
				'post_type'        => 'post',
				'post_status'      => 'publish',
				'suppress_filters' => true );
			$posts = get_posts( $parameter );

			foreach($posts as $array_post){				
				$wdg_post_id = $array_post->ID;
				$wdg_post_link = get_permalink( $array_post->ID );
				$wdg_post_title = get_the_title($wdg_post_id);
				$wdg_post_title_limit = kuas_beta_slice_title_limit($wdg_post_title,7);
				$wdg_post_excerpt_limit = substr( $wdg_post_excerpt,0, 70-strlen($wdg_post_title_limit) );
				$get_comments = get_comments(array('post_id'=>$wdg_post_id,'count'=>true));
				//if($get_comments > 0){$post_comment = $get_comments.' komentar';}else{$post_comment = 'nihil komen';}

				if(is_sticky($array_post->ID)){$add_achor_class = 'class="sticky-post"';} else {$add_achor_class = '';}

				if($wdg_post_id!= $post->ID){
					$print_category .= '<li><a href="'.$wdg_post_link.'" title="" id="'.$wdg_post_id.'" '.$add_achor_class.'>';
					if(has_post_thumbnail( $wdg_post_id )){
						if(is_sticky($array_post->ID)){
							$thumb_class = 'sepiaKuasEffect more-post-cat-image';
						}
						else {
							$thumb_class = 'more-post-cat-image';
						}
					$print_category .= '<div class="content_widget_postlist">'.get_the_post_thumbnail( $wdg_post_id, 'thumbnail',array('class'=>$thumb_class, 'align'=>"absmiddle") ).'</div>';
					$print_category .= '<div class="meta_widget_postlist ">';
					}
					else {
					$print_category .= '<div class="meta_widget_postlist no_thumb_postlist">';
					}
					$print_category .= '<span class="title_catpost">'.$wdg_post_title_limit.' </span><br/>';
					if($excerpt==true){
						$print_category .= '<span class="excerpt_catpost">'.kuas_custom_text_excerpt($array_post->post_content,300).'...</span><br/>';
					}
					$print_category .= '<span id="simple-share-count" class="share-count">'.kuas_render_social_share_count(get_permalink($wdg_post_id),'init',$wdg_post_id,0,0).'</span>';
					$print_category .= '<span class="comment_catpost meta_info_postlist">'.$post_comment.'</span></div></a></li>';
				} 
				else {
					$print_category .= '<li class="reading_catpost_list">';
					$print_category .= '<div class="content_widget_postlist ">'.get_the_post_thumbnail( $wdg_post_id, 'thumbnail',array('class'=>"more-post-cat-image", 'align'=>"absmiddle") ).'</div>';
					$print_category .= '<div class="meta_widget_postlist "><span class="title_catpost">'.$wdg_post_title_limit.' </span><br/>';
					$print_category .= '<span class="comment_catpost meta_info_postlist">'.$post_comment.' (lagi dibaca)</span></div></li>';
				}/**/
			}

			$print_category .= '</div></ul>';
			$print_category .= '<div class="see_more_cat"><a href="'.get_category_link($cat_ID).'" class="normalTip" title="Ada '.$category_post_count.' tulisan di saluran '.get_cat_name($cat_ID).'" id="'.$cat_ID.'">';
			$print_category .= __('Lihat semua saluran ini &raquo;','kuas-beta').'</a></div></div>';				

		}
		//echo $print_category;
		if($display==true){
		echo $print_category;
		} 
		else {
		return $print_category;
		}
	}
}

if(!function_exists('related_post_by_categories_mobile')){
	function related_post_by_categories_mobile($print=1){
		global $post;
		$id_post = $post->ID;
		$output = '<h1 class="page-title" align="center">'.__('Rekomendasi Tulisan Lain','kuas-beta').'</h1>';
		$output .= kuas_beta_extract_post_same_categories($id_post,3,true,false);

		if($print==true OR $print=='' OR empty($print)){
		echo $output;
		} 
		else {
		return $output;
		}
	}
	if(wp_is_mobile()){
	add_action('kuas_between_post_comments','related_post_by_categories_mobile');
	}
}
/**
 * Custom slug class from wp_list_categories
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('add_slug_class_wp_list_categories')){
	add_filter('wp_list_categories', 'add_slug_class_wp_list_categories');
	function add_slug_class_wp_list_categories($list) {
	$cats = get_categories('hide_empty=0');
		foreach($cats as $cat) {
		$find = 'cat-item-' . $cat->term_id . '"';
		$replace = 'cat_' . $cat->slug . ' cat-item-' . $cat->term_id . '"';
		$list = str_replace( $find, $replace, $list );
		$find = 'cat-item-' . $cat->term_id . ' ';
		$replace = 'cat_' . $cat->slug . ' cat-item-' . $cat->term_id . ' ';
		$list = str_replace( $find, $replace, $list );
		}
		return $list;
	}
}

/**
 * Custom list categories with callback wp_list_categories
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('add_slug_class_wp_list_categories')){
	add_filter('wp_list_categories', 'add_slug_class_wp_list_categories');
	function add_slug_class_wp_list_categories($list) {
	$cats = get_categories('hide_empty=0');
		foreach($cats as $cat) {
		$find = 'cat-item-' . $cat->term_id . '"';
		$replace = 'cat_' . $cat->slug . ' cat-item-' . $cat->term_id . '"';
		$list = str_replace( $find, $replace, $list );
		$find = 'cat-item-' . $cat->term_id . ' ';
		$replace = 'cat_' . $cat->slug . ' cat-item-' . $cat->term_id . ' ';
		$list = str_replace( $find, $replace, $list );
		}
		return $list;
	}
}

/**
 * Display and render all category parents
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_beta_render_all_categories')){
	function kuas_beta_render_all_categories($exclude='',$echo=1){
		if(is_string($exclude)){ $exclude = $exclude; } elseif(is_numeric($exclude)){$exclude = "$exclude"; } else{$exclude = '';}
		$args = array(
			'show_option_all'    => '',
			'orderby'            => 'name',
			'order'              => 'ASC',
			'style'              => 'list',
			'show_count'         => 0,
			'hide_empty'         => 1,
			'use_desc_for_title' => 1,
			'child_of'           => 0,
			'feed'               => '',
			'feed_type'          => '',
			'feed_image'         => '',
			'exclude'            => '',
			'exclude_tree'       => $exclude,
			'include'            => '',
			'hierarchical'       => 0,
			'title_li'           => '',//__( 'Categories' ),
			'show_option_none'   => '',//__('No categories'),
			'number'             => null,
			'echo'               => 0,
			'depth'              => 1,
			'current_category'   => 0,
			'pad_counts'         => 0,
			'taxonomy'           => 'category',
			'walker'             => null
		);
		$wp_list = wp_list_categories($args);
		$output = '<ul id="all_categories_sidebar" class="attach_image">'.$wp_list.'</ul>';
		if($echo==1){echo $output;}
		else{return $output;}
	}
}

/**
 * Custom Breadcrumbs site
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_beta_breadcrumbs')){
	function kuas_beta_breadcrumbs(){
	    global $wp_query;

	    if( !is_home() && !is_front_page() && !is_404() && !is_search() && !is_archive() || is_category() ){
	        echo '<div id="breadcrumbs_wrap" class="bgColorBaseKuas_y1"><ul class="breadcrumbs">';
	        echo '<li><a href="'. get_settings('home') .'">'. get_bloginfo('name') .'</a></li>';
	        if ( is_category() ){
	            $catTitle = single_cat_title( "", false );
	            $cat = get_cat_ID( $catTitle );
	            echo '<li>'. get_category_parents( $cat, TRUE, "" ) .'</li>';
	        }
	        elseif ( is_single() ){
		        $category = get_the_category();
	        	if($category){
		            $category_id = get_cat_ID( $category[0]->cat_name );
		            echo '<li>'. get_category_parents( $category_id, TRUE, "" ).'</li>';
		            echo '<li><li><a href="">'.the_title('','', FALSE) .'</a></li>';
	        	} 
	        	else {
					echo '<li><a href="'.dirname(get_permalink()).'">'.ucwords(basename(dirname(get_permalink()))).'</a></li>';
	        		echo '<li><a href="">'.ucwords( str_replace( '-',' ',basename(get_permalink()) ) ).'</a></li>';
	        	}
	        }
	        elseif ( is_page() ){
	            $post = $wp_query->get_queried_object();
	            if ( $post->post_parent == 0 ){

	                echo '<li><a href="">'.the_title('','', FALSE).'</a></li>';
	            }
	            else {
	                $title = the_title('','', FALSE);
	                $ancestors = array_reverse( get_post_ancestors( $post->ID ) );
	                array_push($ancestors, $post->ID);
	                foreach ( $ancestors as $ancestor ){
	                    if( $ancestor != end($ancestors) ){
	                        echo '<li><a href="'. get_permalink($ancestor) .'">'. strip_tags( apply_filters( 'single_post_title', get_the_title( $ancestor ) ) ) .'</a></li>';
	                    }
	                    else {
	                        echo '<li>'. strip_tags( apply_filters( 'single_post_title', get_the_title( $ancestor ) ) ) .'</li>';
	                    }
	                }
	            }
	        }
	        echo "</ul></div>";
	    }
	}
}

/**
 * custom BadgeOS earned count from user
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_beta_badgeos_custom_count_earned')){
	function kuas_beta_badgeos_custom_count_earned($id_user,$render=1){
		if(class_exists('BadgeOS')){
		//Standar penulisan fungsi diatas wajib di cantumkan untuk custom BadgeOS
		//Kemudian tulis kode yang dibutuhkan setelahnya

			$kuas_badgeos_count = array();
			$achievements = badgeos_get_user_achievements( array( 'user_id' => absint( $id_user ) ) );
			$count_reset_value = 0;
			foreach ( $achievements as $achievement ) {
				if ( get_post_type( $achievement->ID ) != 'step' ) {
					$count_reset_value++;
					$count_reset_value = array_push($kuas_badgeos_count,$count_reset_value);
				}
			}	
			return $count_reset_value;	

		//Batas isi kode fungsi custom
		}			
	}
}

/**
 * Custom BadgeOS earned list from current user
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_beta_get_user_earn_badgeos_custom')){
	function kuas_beta_get_user_earn_badgeos_custom($user_id,$element=array('parent'=>'ul','child'=>'li','class'=>''),$limit=0,$size_bagde=array(100,100),$render=1){
		if(class_exists('BadgeOS')){
		//Standar penulisan fungsi diatas wajib di cantumkan untuk custom BadgeOS
		//Kemudian tulis kode yang dibutuhkan setelahnya			

			$achievements = badgeos_get_user_achievements( array( 'user_id' => absint( get_the_author_meta('ID') ) ) );
			$achievements = array_reverse( $achievements );
			$return = '<'.$element['parent'].' class="'.$element['class'].'">';
			$count = 0;
			foreach ( $achievements as $achievement ) {
				if ( get_post_type( $achievement->ID ) != 'step' ) {
					$achievement_post = get_post( $achievement->ID );
					$permalink  = get_permalink( $achievement->ID );
					$title      = get_the_title( $achievement->ID );
					$img        = badgeos_get_achievement_post_thumbnail( $achievement->ID, $size_bagde, 'wp-post-image' );
					$thumb      = $img ? $img : '';
					$class      = 'widget-badgeos-item-title';
					$item_class = $thumb ? ' has-thumb' : '';
					$excerpt 	= !empty( $achievement_post->post_excerpt ) ? $achievement_post->post_excerpt : $achievement_post->post_content;

					$return .= '<'.$element['child'].' href="'. esc_url( $permalink ) .'" title="'. $excerpt .'" class="widget-achievements-listing-item'. esc_attr( $item_class ) .' normalTip">';
					$return .= $thumb;
					$return .= '</'.$element['child'].'>';
					$count++;
					if(is_numeric($limit) && $limit!=0 && $count == $limit) break;
				}
			}
			$return .= '</'.$element['parent'].'>';
			return $return;

		//Batas isi kode fungsi custom
		}
	}
}

/**
 * Custom BadgeOS earned current user become widget
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_beta_user_badgeos_widget_custom_markup')){
	function kuas_beta_user_badgeos_widget_custom_markup($user_id,$element=array('parent'=>'ul','child'=>'li','class'=>''),$limit=0,$size_bagde=array(100,100),$render=1,$message){
		if(class_exists('BadgeOS')){
		//Standar penulisan fungsi diatas wajib di cantumkan untuk custom BadgeOS
		//Kemudian tulis kode yang dibutuhkan setelahnya	
			if(empty($message)){
			$print_message = 'Selamat! Sekarang anda bisa mengirimkan permintaan untuk mencetak lencana anda dalam bentuk sticker - Kirimkan lencana yang didapat melalui tautan ini';
			} else {
			$print_message = $print_message;	
			}

			$output = '<div id="kuas_beta_badgeos_markup_html" class="widget widget_badgeos_markup_html">';
				$output .= '<h4 class="shadowBottom">Lencana & Penghargaan</h4>';
				$output .= kuas_beta_get_user_earn_badgeos_custom($user_id,$element,$limit,$size_bagde);;
				if ( is_user_logged_in() && get_current_user_id() == $user_id) {
					if(badgeos_get_users_points($user_id) >= 2000 && kuas_beta_badgeos_custom_count_earned($user_id) >= 10){
						$output .= '<div class="request_bagde_print">';
						$output .= '<a href="'.get_bloginfo('url').'/cetak-sticker-lencana" class="request_sticker_badge bgElement fixedTip" title="'.$print_message.'"">Request lencana sticker cetak?</a>';
						$output .= '</div>';
					}
				}	
			$output .= '</div>';

			if($render==1){ echo $output; }
			else{ return $output; }

		//Batas isi kode fungsi custom
		}
	}
}

/**
 * Diasable Core Updates # 3.0+
 *
 * @since KuAs 1.0
 *
 *
 */
add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );
wp_clear_scheduled_hook( 'wp_version_check' );

/**
 * Remove Feed URL
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_remove_rss_url')){
	function kuas_remove_rss_url() {
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'rsd_link' );	      
	}
	add_action('init', 'kuas_remove_rss_url');
}

/**
 * Remove Generator Meta WP and replace with web meta content useful
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_get_avatar_url')){
	function kuas_get_avatar_url($get_avatar){
	    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
	    return $matches[1];
	}
}
if(!function_exists('kuas_complete_version_removal')){
	function kuas_complete_version_removal() {
		$output = '';
		if(is_single() OR is_page()){
			global $wp_query;
			if (have_posts()) {
				while (have_posts()) : the_post();
				$current_excerpt = get_the_excerpt();
				$description_meta_post_excerpt = $current_excerpt? $current_excerpt : get_the_title();
				$categories = get_the_category();
				$separator = ', '; $output_cat = '';
				if($categories){
					foreach($categories as $category) {
						$output_cat .= esc_attr( sprintf( __( "%s" ), $category->name ) ).$separator;
					}
					$results_meta_post_categories = trim($output_cat, $separator);
				}
				$title_trim_meta = explode(' ',get_the_title());
				$separator_title_post = ', '; $output_title_post = '';
				foreach($title_trim_meta as $title_trim) {
					$output_title_post .= esc_attr( sprintf( __( "%s" ), $title_trim ) ).$separator_title_post;
				}
				$trim_meta_post_title = trim($output_title_post, $separator_title_post);

				$output .= '<meta itemprop="url" content="'.get_permalink().'">';
				$output .= '<meta itemprop="datePublished" content="'.get_post_time('c').'">';
				$output .= '<meta itemprop="image" content="'.wp_get_attachment_url( get_post_thumbnail_id( get_the_ID()) ).'">';
				$output .= '<meta itemprop="articleSection" content="'.kuas_beta_slice_title_limit($description_meta_post_excerpt,100).'">';
				$output .= '<meta itemprop="name" content="'.get_the_title().'">';
				$output .= '<meta itemprop="author" content="'.get_the_author_meta('display_name').'">';
				$output .= '<meta itemprop="publisher" content="'.get_bloginfo('name').'">';

				$output .= '<meta name="author" content="'.get_the_author_meta('display_name').'">';
				$output .= '<meta name="description" content="'.kuas_beta_slice_title_limit($description_meta_post_excerpt,100).'">';
				$output .= '<meta name="keywords" content="'.strtolower(get_the_title().', '.$trim_meta_post_title.', '.$results_meta_post_categories).'">';
				$output .= '<meta name="date" content="'.get_post_time('c').'">';
				$output .= '<link data-page-subject="true" href="'.wp_get_attachment_url( get_post_thumbnail_id( get_the_ID()) ).'" rel="image_src" />';

				$output .= '<meta property="og:url" content="'.get_permalink().'" data-page-subject="true" />';
				$output .= '<meta property="og:title" content="'.get_the_title().'" data-page-subject="true" />';
				$output .= '<meta property="og:description" content="'.kuas_beta_slice_title_limit($description_meta_post_excerpt,100).'" data-page-subject="true" />';
				$output .= '<meta property="og:type" content="article" data-page-subject="true" />';
				$output .= '<meta property="og:image" content="'.wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() )).'" data-page-subject="true" />';
				$output .= '<meta content="'.get_post_time('c').'" data-page-subject="true" property="og:article:published_time" />';
				$output .= '<meta content="https://www.facebook.com/pages/Kuningan-Asri/507919092624612" data-page-subject="true" property="og:article:publisher" />';

				$output .= '<meta name="twitter:card" content="summary_large_image" data-page-subject="true" />';
				$output .= '<meta name="twitter:site" content="@Kuningan_Asri" data-page-subject="true" />';
				$output .= '<meta name="twitter:domain" content="'.site_url().'" data-page-subject="true" />';
				$output .= '<meta name="twitter:creator" content="@'.get_the_author_meta('twitter').'" data-page-subject="true" />';
				//$output .= '<meta content="photo" data-page-subject="true" name="twitter:card" />';
				//$output .= '<meta content="560" data-page-subject="true" name="twitter:image:width" />';
				//$output .= '<meta content="750" data-page-subject="true" name="twitter:image:height" />';
				$output .= '<meta name="twitter:url" content="'.get_permalink().'" data-page-subject="true" />';
				$output .= '<meta name="twitter:title" content="'.get_the_title().'" data-page-subject="true" />';
				$output .= '<meta name="twitter:description" content="'.kuas_beta_slice_title_limit($description_meta_post_excerpt,100).'" data-page-subject="true" />';
				$output .= '<meta name="twitter:image" content="'.wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() )).'" data-page-subject="true" />';
				endwhile;
			}
		}
		elseif(is_author()){
			global $wp_query;
			if (have_posts()) {
				the_post();
				rewind_posts();
				$output .= '<meta name="author" content="'.get_the_author_meta('display_name').'">';
				$output .= '<meta name="description" content="'.get_the_author_meta( 'description' ).'">';
				$output .= '<meta name="keywords" content="'.strtolower( get_the_author_meta('display_name') .', '. get_bloginfo('description') ).'">';
				$output .= '<meta name="date" content="'.get_the_author_meta( 'user_registered' ).'">';
				$output .= '<link rel="image_src" href="'.kuas_get_avatar_url(get_avatar( get_the_author_meta( 'ID' ), 250 )).'" data-page-subject="true" />';

if(get_the_author_meta('facebook')){
				$output .= '<meta property="og:url" content="'.esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ).'" data-page-subject="true" />';
				$output .= '<meta property="og:title" content="'.get_the_author_meta('display_name').' | '.get_bloginfo('name').'" data-page-subject="true" />';
				$output .= '<meta property="og:description" content="'.get_the_author_meta( 'description' ).'" data-page-subject="true" />';
				$output .= '<meta property="og:type" content="article" data-page-subject="true" />';
				$output .= '<meta property="og:image" content="'.kuas_get_avatar_url(get_avatar( get_the_author_meta( 'ID' ), 250 )).'" data-page-subject="true" />';
}

if(get_the_author_meta('twitter')){
				$output .= '<meta name="twitter:card" content="summary_large_image" data-page-subject="true" />';
				$output .= '<meta name="twitter:site" content="@Kuningan_Asri" data-page-subject="true" />';
				$output .= '<meta name="twitter:domain" content="'.site_url().'" data-page-subject="true" />';
				$output .= '<meta name="twitter:creator" content="@'.get_the_author_meta('twitter').'" data-page-subject="true" />';
				$output .= '<meta name="twitter:url" content="'.esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ).'" data-page-subject="true" />';
				$output .= '<meta name="twitter:title" content="'.get_the_author_meta('display_name').' | '.get_bloginfo('name').'" data-page-subject="true" />';
				$output .= '<meta name="twitter:description" content="'.get_the_author_meta( 'description' ).'" data-page-subject="true" />';
				$output .= '<meta name="twitter:image" content="'.kuas_get_avatar_url(get_avatar( get_the_author_meta( 'ID' ), 250 )).'" data-page-subject="true" />';
}

				$output .= '<meta itemprop="name" content="'.get_the_author_meta('display_name').' | '.get_bloginfo('name').'">';
				$output .= '<meta itemprop="description" content="'.get_the_author_meta( 'description' ).'">';
				$output .= '<meta itemprop="image" content="'.kuas_get_avatar_url(get_avatar( get_the_author_meta( 'ID' ), 250 )).'">';
			}
		}
		else {
			$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );
			$separator = ', '; $output_cat_meta_web = '';
			foreach( $categories as $category ) {
				$output_cat_meta_web .= esc_attr( sprintf( __( "%s" ), $category->name ) ).$separator;
			}
			$results_meta_web_categories = trim($output_cat_meta_web, $separator);
			$output .= '<meta name="author" content="'.get_bloginfo('name').'" data-page-subject="true" />';
			$output .= '<meta name="description" content="'.kuas_beta_meta_desc().'" data-page-subject="true" />';
			$output .= '<meta name="keywords" content="'.strtolower($results_meta_web_categories).'">';
			$output .= '<meta name="date" content="'.date(DATE_ATOM).'" data-page-subject="true" />';
						
			$output .= '<meta property="og:url" content="'.site_url().'"/>';
			$output .= '<meta property="og:title" content="'.get_bloginfo('name').' | '.get_bloginfo('description').'"/>';
			$output .= '<meta property="og:description" content="'.kuas_beta_meta_desc().'" />';
			$output .= '<meta property="og:type" content="blog" />';
			$output .= '<meta property="og:image" content="'.site_url('assets/images').'/KuAs_Logo_Apple_Touch.png" />';
			
			$output .= '<meta name="twitter:card" content="summary_large_image" data-page-subject="true" />';
			$output .= '<meta name="twitter:site" content="@Kuningan_Asri" data-page-subject="true" />';
			$output .= '<meta name="twitter:domain" content="'.site_url().'" data-page-subject="true" />';
			$output .= '<meta name="twitter:creator" content="@Kuningan_Asri" data-page-subject="true" />';
			$output .= '<meta name="twitter:url" content="'.site_url().'" data-page-subject="true" />';
			$output .= '<meta name="twitter:title" content="'.get_bloginfo('name').' | '.get_bloginfo('description').'" data-page-subject="true" />';
			$output .= '<meta name="twitter:description" content="'.kuas_beta_meta_desc().'" data-page-subject="true" />';
			$output .= '<meta name="twitter:image" content="'.site_url('assets/images').'/KuAs_Logo_Apple_Touch.png" data-page-subject="true" />';

			$output .= '<meta itemprop="name" content="'.get_bloginfo('name').' | '.get_bloginfo('description').'">';
			$output .= '<meta itemprop="description" content="'.kuas_beta_meta_desc().'">';
			$output .= '<meta itemprop="image" content="'.site_url('assets/images').'/KuAs_Logo_Apple_Touch.png">';
		}
		$output .= '<meta property="fb:app_id" content="427637250695040" />';
		$output .= '<meta property="fb:admins" content="1048400768" />';
		$output .= '<meta content="'.get_bloginfo('name').'" data-page-subject="true" property="og:site_name" />';		
		//$output .= '<meta property="fb:page_id" content="507919092624612" />';
		$output .= '<meta content="pq9NX2VtbShQbwsWSV3f3C28FH7JXJm6" name="readability-verification">';
		$output .= '<link rel="profile" href="http://gmpg.org/xfn/11" />';
		return $output;
	}
	add_filter('the_generator', 'kuas_complete_version_removal');
}

/**
 * Remove admin logo
 *
 * @since KuAs 1.0
 *
 */
if(!function_exists('kuas_remove_wp_logo')){
	function kuas_remove_wp_logo() { 
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wp-logo'); 
	}
	add_action( 'wp_before_admin_bar_render', 'kuas_remove_wp_logo' );
}

/**
 * Remove admin bar for all user except admin/super user
 *
 * @since KuAs 1.0 
 */
if(!function_exists('kuas_remove_admin_bar')){
	add_action('after_setup_theme', 'kuas_remove_admin_bar');
	function kuas_remove_admin_bar() {
		if (!current_user_can('administrator') && !is_admin()) {
		  	show_admin_bar(false);
		}
	}
}

/**
 * Change Logo default
 *
 * @since KuAs 1.0 
 */
if(!function_exists('my_custom_login_logo')){
	function my_custom_login_logo() {
	    echo '<style type="text/css">
	    	body { background-color: #ffc600 !important; background-image: url('.get_bloginfo('url').'/assets/images/KuAs_Logo_250.png) !important; background-size: 40% !important; background-repeat:no-repeat !important; background-position: center 10% !important; }
	        navbar { display:block; background-color: #ffa800; -webkit-box-shadow: 0 2px 3px rgba(0,0,0,.25); box-shadow: 0 2px 3px rgba(0,0,0,.25); padding:5px 10px !important; }
	        h1 a { background-image:url('.get_bloginfo('url').'/assets/images/KuAs_Typo_H64.png) !important; background-size:175px 35px !important; width:175px !important; height:35px !important; padding:0 !important; margin:0 !important;}
	        form {  background-color:rgba(0,0,0,0.7) !important; border-color: rgba(0,0,0,0.9) !important; }
	        form label, .login #backtoblog a, .login #nav a, .login #backtoblog a:hover, .login #nav a:hover { color:#fff !important; }
	        div#login { width: 500px !important; padding: 10% 0 0 !important; }
	        .login #backtoblog { float:left !important; display:inline-block !important; }
	        .login #nav { width:initial; float:right !important; display:inline-block !important; }
	    </style>';
	}
	add_action('login_head', 'my_custom_login_logo');
}

/**
 * Define no function if function is no exist
 *
 * @since KuAs 1.0 
 */
if(!function_exists('kuas_beta_no_function')){
	function kuas_beta_no_function(){
		return exit('N/A');
	}
}
?>