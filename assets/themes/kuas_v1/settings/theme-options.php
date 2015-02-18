<?php
/**
 * The Theme Options page
 *
 * This page is implemented using the Settings API
 * http://codex.wordpress.org/Settings_API
 * Big thanks to Chip Bennett for the great article on how to implement the Settings API
 * http://www.chipbennett.net/2011/02/17/incorporating-the-settings-api-in-wordpress-themes/
 * 
 * @file      theme-options.php
 * @package   Max-Mag
 * @author    Sami Ch.
 * @link 	  http://gazpo.com
 */

 /**
 * Properly enqueue styles and scripts for our theme options page.
 *
 * This function is attached to the admin_enqueue_scripts action hook.
 *
 * @since Twenty Eleven 1.0
 *
 */
 

/**
 * Remove Color Scheme
 *
 * @since KuAs 1.0
 *
 */
if (!current_user_can('administrator') OR !is_admin()) {
	remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
}

function kuas_beta_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'kuas_beta_theme_options', get_template_directory_uri() . '/settings/theme-options.css', false, '1.0' );
	wp_enqueue_script( 'kuas_beta_theme_options', get_template_directory_uri() . '/settings/theme-options.js', array( 'jquery' ), '1.0' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'kuas_beta_admin_enqueue_scripts' );


global $pagenow;
if( ( 'themes.php' == $pagenow ) && ( isset( $_GET['activated'] ) && ( $_GET['activated'] == 'true' ) ) ) :


/**
 * Set default options on activation
 */
function kuas_beta_init_options() {
	$options = get_option( 'kuas_beta_options' );
	if ( false === $options ) {
		$options = kuas_beta_default_options();
	}
	update_option( 'kuas_beta_options', $options );
}

add_action( 'after_setup_theme', 'kuas_beta_init_options', 9 );
endif;

/**
 * Register the theme options setting
 */
function kuas_beta_register_settings() {
	register_setting( 'kuas_beta_options', 'kuas_beta_options', 'kuas_beta_validate_options' );	
}
add_action( 'admin_init', 'kuas_beta_register_settings' );

/**
 * Register the options page
 */
function kuas_beta_theme_add_page() {
	add_theme_page( __( 'Theme Options', 'kuas-beta' ), __( 'Theme Options', 'kuas-beta' ), 'edit_theme_options', 'theme_options', 'kuas_beta_theme_options_page' );
}
add_action( 'admin_menu', 'kuas_beta_theme_add_page');

/**
 * Set custom RSS feed links.
 *
 */
function kuas_beta_custom_feed( $output, $feed ) {
	$options = get_option('kuas_beta_options');
	$url = $options['rss_url'];	
	if ( $url ) {
		$outputarray = array( 'rss' => $url, 'rss2' => $url, 'atom' => $url, 'rdf' => $url, 'comments_rss2' => '' );
		$outputarray[$feed] = $url;
		$output = $outputarray[$feed];
	}
	return $output;
}
add_filter( 'feed_link', 'kuas_beta_custom_feed', 1, 2 );

/**
 * Set custom Favicon.
 *
 */
function kuas_beta_custom_favicon() {
	$options = get_option('kuas_beta_options');
	$favicon_url = $options['favicon_url'];	
	
    if (!empty($favicon_url)) {
		echo '<link rel="shortcut icon" href="'. $favicon_url. '" type="image/png" />'. "\n";
		echo '<link href="'.site_url('assets/images').'/KuAs_Logo_Apple_Touch.png" rel="apple-touch-icon-precomposed">'. "\n";
	}
}
function kuas_beta_get_custom_favicon_url() {
	$options = get_option('kuas_beta_options');
	$favicon_url = $options['favicon_url'];	
	return $favicon_url;
}
add_action('wp_head', 'kuas_beta_custom_favicon',1);

/**
 * Set custom CSS.
 *
 */
function kuas_beta_inline_css() {
    $options = get_option('kuas_beta_options');
	$inline_css = $options['inline_css'];
    if (!empty($inline_css)) {
		echo '<!-- Custom CSS Styles -->' . "\n";
        echo '<style type="text/css" media="screen">' . "\n";
		echo $inline_css . "\n";
		echo '</style>' . "\n";
	}
}
add_action('wp_head', 'kuas_beta_inline_css');


/**
 * Add tracking code in the footer.
 *
 */
function kuas_beta_stats_tracker() {
    $options = get_option('kuas_beta_options');
	$stats_tracker = $options['stats_tracker'];
    if (!empty($stats_tracker)) {
        echo $stats_tracker;
	}
}
add_action('inner_body_script_top', 'kuas_beta_stats_tracker');

/**
 * Set meta description.
 *
 */
function kuas_beta_meta_desc() {
    $options = get_option('kuas_beta_options');
	$meta_desc = $options['meta_desc'];
	return $meta_desc;
}
//add_action('wp_head', 'kuas_beta_meta_desc');


/**
 * Set Google site verfication code
 *
 */
function kuas_beta_google_verification() {
    $options = get_option('kuas_beta_options');
	$google_verification = $options['google_verification'];
   
   if (!empty($google_verification)) {
		echo '<meta name="google-site-verification" content="' . $google_verification . '" />' . "\n";
	}
}
add_action('kuas_meta_head', 'kuas_beta_google_verification');


/**
 * Set Bing site verfication code
 *
 */
function kuas_beta_bing_verification() {	
    $options = get_option('kuas_beta_options');
	$bing_verification = $options['bing_verification'];	
	
    if (!empty($bing_verification)) {
        echo '<meta name="msvalidate.01" content="' . $bing_verification . '" />' . "\n";
	}
}
add_action('wp_head', 'kuas_beta_bing_verification');

/**
 * Output the options page
 */
function kuas_beta_theme_options_page() { ?>
	
	<div id="gazpo-admin" class="wrap"> 
			
			<div class="options-form">
			
				<?php $theme_name = function_exists('wp_get_theme') ? wp_get_theme() : ''; ?>
				<?php screen_icon(); echo "<h2>" . $theme_name ." ". __('Theme Options', 'kuas-beta') . "</h2>"; ?>
					
					<?php if ( isset( $_GET['settings-updated'] ) ) : ?>
						<div class="updated fade"><p><?php _e('Theme settings updated successfully', 'kuas-beta'); ?></p></div>
					<?php endif; ?>
				
					<form action="options.php" method="post">
						
						<?php settings_fields( 'kuas_beta_options' ); ?>
						<?php $options = get_option('kuas_beta_options'); ?>		
			
						<div class="options_blocks">
					
					<h3 class="block_title"><a href="#"><?php _e('Homepage Settings', 'kuas-beta'); ?></a></h3>
					<div class="block">
						
						<!-- Custom Logo -->					 
						<div class="field">
							<label for="kuas_beta_options[logo_url]"><?php _e('Logo URL:', 'kuas-beta'); ?></label>
							<input id="kuas_beta_options[logo_url]" name="kuas_beta_options[logo_url]" type="text" value="<?php echo esc_attr($options['logo_url']); ?>" />
							
							<span><?php _e( 'Enter full URL of logo image starting with <strong>http:// </strong>.', 'kuas-beta' ); ?></span>
						</div>

						<!-- Custom Favicon -->
						<div class="field">
							<label for="kuas_beta_options[favicon_url]"><?php _e('Favicon URL', 'kuas-beta'); ?></label>
							<input id="kuas_beta_options[favicon_url]" name="kuas_beta_options[favicon_url]" type="text" value="<?php echo esc_attr($options['favicon_url']); ?>" />
							
							<span><?php _e( 'Enter full URL of favicon image starting with <strong>http:// </strong>.', 'kuas-beta' ); ?></span>
						</div>
						
						<!-- Custom RSS URL -->
						<div class="field">
							<label for="kuas_beta_options[rss_url]"><?php _e('Custom RSS URL', 'kuas-beta'); ?></label>
							<input id="kuas_beta_options[rss_url]" name="kuas_beta_options[rss_url]" type="text" value="<?php echo esc_attr($options['rss_url']); ?>" />
							<span><?php _e( 'Enter full URL of RSS Feeds link starting with <strong>http:// </strong>. Leave blank to use default RSS Feeds.', 'kuas-beta' ); ?></span>
						</div>
						
						<hr size="1" color="cccccc" />						
						<!-- Custom Slider Categories -->
						<div class="field">
							<label for="kuas_beta_options[show_slider]"><?php _e('Enable Slider', 'kuas-beta'); ?></label>
							<input id="kuas_beta_options[show_slider]" name="kuas_beta_options[show_slider]" type="checkbox" value="1" <?php isset($options['show_slider']) ? checked( '1', $options['show_slider'] ) : checked('0', '1'); ?> />							
							<span><?php _e( 'Check to enable the slider on homepage.', 'kuas-beta' ); ?></span>
						</div>
						<!-- -->
						<div class="field">														
							<label for="kuas_beta_options[slider_category]"><?php _e('Slider Category', 'kuas-beta'); ?></label>
							<?php 
							$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  ?>
							<select id="slider_category" name="kuas_beta_options[slider_category]">
								<option <?php selected( 0 == $options['slider_category'] ); ?> value="0"><?php _e( '--none--', 'kuas-beta' ); ?></option>
								<?php foreach( $categories as $category ) : ?>
								<option <?php selected( $category->term_id == $options['slider_category'] ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>
								<?php endforeach; ?>
							</select>						
							 <span><?php _e( 'Select the category for the slider. Select <strong>none</strong> to show latest posts.', 'kuas-beta' ); ?></span>					
						</div>
						
						<hr size="1" color="cccccc" />						
						<!-- Custom Carouesel Categories -->
						<div class="field">
							<label for="kuas_beta_options[show_carousel]"><?php _e('Enable Carousel', 'kuas-beta'); ?></label>
							<input id="kuas_beta_options[show_carousel]" name="kuas_beta_options[show_carousel]" type="checkbox" value="1" <?php isset($options['show_carousel']) ? checked( '1', $options['show_carousel'] ) : checked('0', '1'); ?> />
							<span><?php _e( 'Check to enable the carousel on homepage.', 'kuas-beta' ); ?></span>					
						</div>
						<!-- -->
						<div class="field">														
							<label for="kuas_beta_options[carousel_category]"><?php _e('Carousel Category', 'kuas-beta'); ?></label>
							<?php 
							$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  ?>
							<select id="carousel_category" name="kuas_beta_options[carousel_category]">
								<option <?php selected( 0 == $options['carousel_category'] ); ?> value="0"><?php _e( '--none--', 'kuas-beta' ); ?></option>
								<?php foreach( $categories as $category ) : ?>
								<option <?php selected( $category->term_id == $options['carousel_category'] ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>
								<?php endforeach; ?>
							</select>						
							 <span><?php _e( 'Select the category for the carousel. Select <strong>none</strong> to show latest posts.', 'kuas-beta' ); ?></span>				
						</div>	
						
						<hr size="1" color="cccccc" />
						<!-- Custom Featured Catgories -->
						<div class="field">
							<label for="kuas_beta_options[show_feat_cats]"><?php _e('Enable Featured Categories', 'kuas-beta'); ?></label>
							<input id="kuas_beta_options[show_feat_cats]" name="kuas_beta_options[show_feat_cats]" type="checkbox" value="1" <?php isset($options['show_feat_cats']) ? checked( '1', $options['show_feat_cats'] ) : checked('0', '1'); ?> />
							 
							<span><?php _e( 'Check to enable the featured categories.', 'kuas-beta' ); ?></span>
						</div>
						<!-- 1 -->
						<div class="field">														
							<label for="kuas_beta_options[feat_cat1]"><?php _e('Featured Category 1', 'kuas-beta'); ?></label>
							<?php 
							$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  ?>
							<select id="feat_cat1" name="kuas_beta_options[feat_cat1]">
								<option <?php selected( 0 == $options['feat_cat1'] ); ?> value="0"><?php _e( '--none--', 'kuas-beta' ); ?></option>
								<?php foreach( $categories as $category ) : ?>
								<option <?php selected( $category->term_id == $options['feat_cat1'] ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>
								<?php endforeach; ?>
							</select>						
							<span><?php _e( 'Select the first featured category.', 'kuas-beta' ); ?></span>				
						</div>	
						<!-- 2 -->
						<div class="field">														
							<label for="kuas_beta_options[feat_cat2]"><?php _e('Featured Category 2', 'kuas-beta'); ?></label>
							<?php 
							$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  ?>
							<select id="feat_cat2" name="kuas_beta_options[feat_cat2]">
								<option <?php selected( 0 == $options['feat_cat2'] ); ?> value="0"><?php _e( '--none--', 'kuas-beta' ); ?></option>
								<?php foreach( $categories as $category ) : ?>
								<option <?php selected( $category->term_id == $options['feat_cat2'] ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>
								<?php endforeach; ?>
							</select>						
							<span><?php _e( 'Select the second featured category.', 'kuas-beta' ); ?></span>				
						</div>
						<!-- 3 -->
						<div class="field">														
							<label for="kuas_beta_options[feat_cat3]"><?php _e('Featured Category 3', 'kuas-beta'); ?></label>
							<?php 
							$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  ?>
							<select id="feat_cat3" name="kuas_beta_options[feat_cat3]">
								<option <?php selected( 0 == $options['feat_cat3'] ); ?> value="0"><?php _e( '--none--', 'kuas-beta' ); ?></option>
								<?php foreach( $categories as $category ) : ?>
								<option <?php selected( $category->term_id == $options['feat_cat3'] ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>
								<?php endforeach; ?>
							</select>						
							<span><?php _e( 'Select the third featured category.', 'kuas-beta' ); ?></span>				
						</div>
						<!-- 4 -->
						<div class="field">														
							<label for="kuas_beta_options[feat_cat4]"><?php _e('Featured Category 4', 'kuas-beta'); ?></label>
							<?php 
							$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  ?>
							<select id="feat_cat4" name="kuas_beta_options[feat_cat4]">
								<option <?php selected( 0 == $options['feat_cat4'] ); ?> value="0"><?php _e( '--none--', 'kuas-beta' ); ?></option>
								<?php foreach( $categories as $category ) : ?>
								<option <?php selected( $category->term_id == $options['feat_cat4'] ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>
								<?php endforeach; ?>
							</select>						
							<span><?php _e( 'Select the forth featured category.', 'kuas-beta' ); ?></span>				
						</div>
						
						<hr size="1" color="cccccc" />
						<!-- Custom Display Sub-Categories -->
						<div class="field">
							<label for="kuas_beta_options[show_subcategories]"><?php _e('Enable Sub Categories', 'kuas-beta'); ?></label>
							<input id="kuas_beta_options[show_subcategories]" name="kuas_beta_options[show_subcategories]" type="checkbox" value="1" <?php isset($options['show_subcategories']) ? checked( '1', $options['show_subcategories'] ) : checked('0', '1'); ?> />							
							<span><?php _e( 'Check to enable the sub categories on homepage.', 'kuas-beta' ); ?></span>
						</div>
						<!-- -->
						<div class="field">														
							<label for="kuas_beta_options[sub_categories]"><?php _e('Sub Category', 'kuas-beta'); ?></label>
							<?php 
							$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  ?>
							<select id="sub_categories" name="kuas_beta_options[sub_categories]">
								<option <?php selected( 0 == $options['sub_categories'] ); ?> value="0"><?php _e( '--none--', 'kuas-beta' ); ?></option>
								<?php foreach( $categories as $category ) : ?>
								<option <?php selected( $category->term_id == $options['sub_categories'] ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>
								<?php endforeach; ?>
							</select>						
							 <span><?php _e( 'Select the category for the sub categories block.', 'kuas-beta' ); ?></span>					
						</div>
						
						<hr size="1" color="cccccc" />
						<!-- Custom Enable/Disable Recent post -->
						<div class="field">
							<label for="kuas_beta_options[show_posts_list]"><?php _e('Latest Posts List', 'kuas-beta'); ?></label>
							<input id="kuas_beta_options[show_posts_list]" name="kuas_beta_options[show_posts_list]" type="checkbox" value="1" <?php isset($options['show_posts_list']) ? checked( '1', $options['show_posts_list'] ) : checked('0', '1'); ?> />
							<span><?php _e( 'Check to show latest posts list on homepage.', 'kuas-beta' ); ?></span>
						</div>

						<!-- Custom Enable/Disable Extra User Fields -->
						<div class="field">
							<label for="kuas_beta_options[show_extra_user_fields]"><?php _e('Show Extra User Info', 'kuas-beta'); ?></label>
							<input id="kuas_beta_options[show_extra_user_fields]" name="kuas_beta_options[show_extra_user_fields]" type="checkbox" value="1" <?php isset($options['show_extra_user_fields']) ? checked( '1', $options['show_extra_user_fields'] ) : checked('0', '1'); ?> />
							<span><?php _e( 'Check to show User Fields Contact (Twitter/Facebook/g+/Path/LINE).', 'kuas-beta' ); ?></span>
						</div>

						<!-- Submit Form -->
						<div class="submit">
							<input type="submit" name="kuas_beta_options[submit]" class="button-primary" value="<?php _e( 'Save Settings', 'kuas-beta' ); ?>" />
                        </div>
						
					</div><!-- /block -->
					
					<h3 class="block_title"><a href="#"><?php _e('Post and Page Settings', 'kuas-beta'); ?></a></h3>
					<div class="block">
						
						<div class="field">
							<label for="kuas_beta_options[show_author]"><?php _e('Display Author Info', 'kuas-beta'); ?></label>
							 <input id="kuas_beta_options[show_author]" name="kuas_beta_options[show_author]" type="checkbox" value="1" <?php isset($options['show_author']) ? checked( '1', $options['show_author'] ) : checked('0', '1'); ?> />
							<span><?php _e( 'Check to display the author information in the post.', 'kuas-beta' ); ?></span>
						</div>
						
						<div class="field">
							<label for="kuas_beta_options[show_page_comments]"><?php _e('Enable comments on pages', 'kuas-beta'); ?></label>
							 <input id="kuas_beta_options[show_page_comments]" name="kuas_beta_options[show_page_comments]" type="checkbox" value="1" <?php isset($options['show_page_comments']) ? checked( '1', $options['show_page_comments'] ) : checked('0', '1'); ?> />
							<span><?php _e( 'Check to enable the comments on pages.', 'kuas-beta' ); ?></span>
						</div>
						
						<div class="field">
							<label for="kuas_beta_options[show_media_comments]"><?php _e('Enable comments on media', 'kuas-beta'); ?></label>
							 <input id="kuas_beta_options[show_media_comments]" name="kuas_beta_options[show_media_comments]" type="checkbox" value="1" <?php isset($options['show_media_comments']) ? checked( '1', $options['show_media_comments'] ) : checked('0', '1'); ?> />
							<span><?php _e( 'Check to enable the comments on media posts.', 'kuas-beta' ); ?></span>
						</div>									
						
						<div class="submit">
							<input type="submit" name="kuas_beta_options[submit]" class="button-primary" value="<?php _e( 'Save Settings', 'kuas-beta' ); ?>" />
                        </div>
					
					</div><!-- /block -->
					
					<h3 class="block_title"><a href="#"><?php _e('Ads and Custom Styles', 'kuas-beta'); ?></a></h3>
					<div class="block">
						
						<div class="field">
							<label for="kuas_beta_options[ad468]"><?php _e('Header ad code.', 'kuas-beta'); ?></label>
							 <textarea id="kuas_beta_options[ad468]" class="textarea" cols="50" rows="30" name="kuas_beta_options[ad468]"><?php echo esc_attr($options['ad468']); ?></textarea>
							  <span><?php _e( 'Enter complete code for header ad.', 'kuas-beta' ); ?></span>							
						</div>
						
						<div class="field">
							<label for="kuas_beta_options[inline_css]"><?php _e('Enter your custom CSS styles.', 'kuas-beta'); ?></label>
							 <textarea id="kuas_beta_options[inline_css]" class="textarea" cols="50" rows="30" name="kuas_beta_options[inline_css]"><?php echo esc_attr($options['inline_css']); ?></textarea>
							 <span><?php _e( 'You can enter custom CSS styles. It will overwrite the default style.', 'kuas-beta' ); ?></span>							
						</div>
						
						<div class="submit">
							<input type="submit" name="kuas_beta_options[submit]" class="button-primary" value="<?php _e( 'Save Settings', 'kuas-beta' ); ?>" />
                        </div>
					
					</div><!-- /block -->
					
					<h3 class="block_title"><a href="#"><?php _e('Webmaster Tools', 'kuas-beta'); ?></a></h3>
					<div class="block">
						
						<div class="field">
							<label for="kuas_beta_options[meta_desc]"><?php _e('Meta Description', 'kuas-beta'); ?></label>
							<textarea id="kuas_beta_options[meta_desc]" class="textarea" cols="50" rows="10" name="kuas_beta_options[meta_desc]"><?php echo esc_attr($options['meta_desc']); ?></textarea>
							<span><?php _e( 'A short description of your site for the META Description tag. Keep it less than 149 characters.', 'kuas-beta' ); ?></span>
						</div>
						
						<div class="field">
							<label for="kuas_beta_options[stats_tracker]"><?php _e('Statistics Tracking Code', 'kuas-beta'); ?></label>
							<textarea id="kuas_beta_options[stats_tracker]" class="textarea" cols="50" rows="10" name="kuas_beta_options[stats_tracker]"><?php echo esc_attr($options['stats_tracker']); ?></textarea>
							<span><?php _e( 'If you want to add any tracking code (eg. Google Analytics). It will appear in the header of the theme.', 'kuas-beta' ); ?></span>
						</div>
						
						<div class="field">
							<label for="kuas_beta_options[google_verification]"><?php _e('Google Site Verification', 'kuas-beta'); ?></label>
							<input id="kuas_beta_options[google_verification]" type="text" name="kuas_beta_options[google_verification]" value="<?php echo esc_attr($options['google_verification']); ?>" />
							<span><?php _e( 'Enter your ID only.', 'kuas-beta' ); ?></span>
						</div>
						
						<div class="field">
							<label for="kuas_beta_options[bing_verification]"><?php _e('Bing Site Verification', 'kuas-beta'); ?></label>
							<input id="kuas_beta_options[bing_verification]" type="text" name="kuas_beta_options[bing_verification]" value="<?php echo esc_attr($options['bing_verification']); ?>" />
							<span><?php _e( 'Enter the ID only. <strong>Yahoo</strong> search is powered by Bing, so it will be automatically verified by Yahoo as well.', 'kuas-beta' ); ?></span>
						</div>
						
						<div class="submit">
							<input type="submit" name="kuas_beta_options[submit]" class="button-primary" value="<?php _e( 'Save Settings', 'kuas-beta' ); ?>" />
                        </div>						
						
					</div><!-- /block -->
					
					<div class="support-block">
					</div><!-- /block -->			
					
					
				</div> <!-- /option_blocks -->			
						
						<input type="submit" name="kuas_beta_options[submit]" class="button-primary" value="<?php _e( 'Save Settings', 'kuas-beta' ); ?>" />
						<input type="submit" name="kuas_beta_options[reset]" class="button-secondary" value="<?php _e( 'Reset Defaults', 'kuas-beta' ); ?>" />
					</form>
		
			</div> <!-- /options-form -->
	</div> <!-- /gazpo-admin -->
	<?php
}

/**
 * Return default array of options
 */
 
function kuas_beta_default_options() {
	$options = array(
		'logo_url' => get_template_directory_uri().'/images/logo.png',
		'favicon_url' => '',
		'rss_url' => '',
		'show_slider' => 1,
		'slider_category' => 0,
		'show_carousel' => 1,
		'carousel_category'=> 0,
		'show_feat_cats' => 1,
		'feat_cat1'=> 0,
		'feat_cat2'=> 0,
		'feat_cat3'=> 0,
		'feat_cat4'=> 0,
		'show_subcategories' => 1,
		'sub_categories'=> 0, 
		'show_posts_list' => 1,
		'show_extra_user_fields' => 0,
		'show_author' => 1,
		'show_page_comments' => 1,
		'show_media_comments' => 1,
		'ad468' => '<a href='.get_site_url().'><img src='.get_template_directory_uri().'/images/ad468.png /></a>',
		'inline_css' => '',
		'meta_desc' => '',
		'stats_tracker' => '',
		'google_verification' => '',
		'bing_verification' => '',
	);
	return $options;
}

/**
 * Sanitize and validate options
 */
function kuas_beta_validate_options( $input ) {
	$submit = ( ! empty( $input['submit'] ) ? true : false );
	$reset = ( ! empty( $input['reset'] ) ? true : false );
	if( $submit ) :
	
		$input['logo_url'] = esc_url_raw($input['logo_url']);
		$input['favicon_url'] = esc_url_raw($input['favicon_url']);
		$input['rss_url'] = esc_url_raw($input['rss_url']);
		
		if ( ! isset( $input['show_slider'] ) )
			$input['show_slider'] = null;
			$input['show_slider'] = ( $input['show_slider'] == 1 ? 1 : 0 );
	
		if ( ! isset( $input['show_carousel'] ) )
			$input['show_carousel'] = null;
			$input['show_carousel'] = ( $input['show_carousel'] == 1 ? 1 : 0 );
		
		if ( ! isset( $input['show_feat_cats'] ) )
			$input['show_feat_cats'] = null;
			$input['show_feat_cats'] = ( $input['show_feat_cats'] == 1 ? 1 : 0 );
	
		if ( ! isset( $input['show_author'] ) )
			$input['show_author'] = null;
			$input['show_author'] = ( $input['show_author'] == 1 ? 1 : 0 );
		
		if ( ! isset( $input['show_page_comments'] ) )
			$input['show_page_comments'] = null;
			$input['show_page_comments'] = ( $input['show_page_comments'] == 1 ? 1 : 0 );
		
		if ( ! isset( $input['show_media_comments'] ) )
			$input['show_media_comments'] = null;
			$input['show_media_comments'] = ( $input['show_media_comments'] == 1 ? 1 : 0 );

		if ( ! isset( $input['show_subcategories'] ) )
			$input['show_subcategories'] = null;
			$input['show_subcategories'] = ( $input['show_subcategories'] == 1 ? 1 : 0 );

		if ( ! isset( $input['show_posts_list'] ) )
			$input['show_posts_list'] = null;
			$input['show_posts_list'] = ( $input['show_posts_list'] == 1 ? 1 : 0 );

		if ( ! isset( $input['show_extra_user_fields'] ) )
			$input['show_extra_user_fields'] = null;
			$input['show_extra_user_fields'] = ( $input['show_extra_user_fields'] == 1 ? 1 : 0 );		
		
		$input['ad468'] = wp_kses_stripslashes($input['ad468']);
		$input['inline_css'] = wp_kses_stripslashes($input['inline_css']);
		$input['meta_desc'] = wp_kses_stripslashes($input['meta_desc']);
	
		$input['google_verification'] = wp_filter_post_kses($input['google_verification']);
		$input['bing_verification'] = wp_filter_post_kses($input['bing_verification']);
	
		$input['stats_tracker'] = wp_kses_stripslashes($input['stats_tracker']);
	
		$categories = get_categories( array( 'hide_empty' => 0, 'hierarchical' => 0 ) );
		$cat_ids = array();
		foreach( $categories as $category )
			$cat_ids[] = $category->term_id;
			
		if( !in_array( $input['slider_category'], $cat_ids ) && ( $input['slider_category'] != 0 ) )
			$input['slider_category'] = $options['slider_category'];
			
		if( !in_array( $input['carousel_category'], $cat_ids ) && ( $input['carousel_category'] != 0 ) )
			$input['carousel_category'] = $options['carousel_category'];
		
		if( !in_array( $input['feat_cat1'], $cat_ids ) && ( $input['feat_cat1'] != 0 ) )
			$input['feat_cat1'] = $options['feat_cat1'];
		
		if( !in_array( $input['feat_cat2'], $cat_ids ) && ( $input['feat_cat2'] != 0 ) )
			$input['feat_cat2'] = $options['feat_cat2'];
			
		if( !in_array( $input['feat_cat3'], $cat_ids ) && ( $input['feat_cat3'] != 0 ) )
			$input['feat_cat3'] = $options['feat_cat3'];
			
		if( !in_array( $input['feat_cat4'], $cat_ids ) && ( $input['feat_cat4'] != 0 ) )
			$input['feat_cat4'] = $options['feat_cat4'];

		if( !in_array( $input['sub_categories'], $cat_ids ) && ( $input['sub_categories'] != 0 ) )
			$input['sub_categories'] = $options['sub_categories'];

		return $input;
		
	elseif( $reset ) :
	
		$input = kuas_beta_default_options();
		return $input;
		
	endif;
}

if ( ! function_exists( 'kuas_beta_get_option' ) ){
	/**
	 * Used to output theme options is an elegant way
	 * @uses get_option() To retrieve the options array
	 */
	function kuas_beta_get_option( $option ) {
		$options = get_option( 'kuas_beta_options', kuas_beta_default_options() );
		return $options[ $option ];
	}
}


/*
 *
 * KUAS ADD EXTRA USER FIELDS & REMOVE EXTRA USER FIELDS
 *
 */
if(!function_exists('kuas_beta_add_extra_user_contact')){
	function kuas_beta_add_extra_user_contact($profile_fields) {
		// Add new fields
		$profile_fields['line_chat'] = 'LINE Username';
		$profile_fields['path'] = 'Path Name';
		$profile_fields['instagram'] = 'Instagram Username';
		$profile_fields['twitter'] = 'Twitter Username';
		$profile_fields['facebook'] = 'Facebook URL';
		$profile_fields['gplus'] = 'Google+ URL';
		$profile_fields['cover_profile'] = 'Cover Profile URL';
		return $profile_fields;
	}
	if(kuas_beta_get_option( 'show_extra_user_fields' ) != 0){
	add_filter('user_contactmethods', 'kuas_beta_add_extra_user_contact');
	}
}

if(!function_exists('kuas_beta_delete_extra_user_contact')){
	function kuas_beta_delete_extra_user_contact($profile_fields) {
		// Remove old fields
		unset($profile_fields['line_chat']);
		unset($profile_fields['path']);
		unset($profile_fields['instagram']);
		unset($profile_fields['twitter']);
		unset($profile_fields['facebook']);
		unset($profile_fields['gplus']);
		unset($profile_fields['cover_profile']);
		return $profile_fields;
	}
	if(kuas_beta_get_option( 'show_extra_user_fields' ) == 0){
	add_filter('user_contactmethods', 'kuas_beta_delete_extra_user_contact',10,1);
	}
}