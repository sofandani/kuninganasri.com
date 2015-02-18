<?php
/*
Plugin Name: PS Auto Sitemap
Plugin URI: http://www.web-strategy.jp/wp_plugin/ps_auto_sitemap/
Description: Auto generator of a customizable and designed sitemap page.
Author: Hitoshi Omagari
Version: 1.1.8
Author URI: http://www.warna.info/
*/

class ps_auto_sitemap {

	var $sitemap_prepared_styles = array(
		'simple'		=> 'Simple',
		'simple2'		=> 'Simple2',
		'checker'		=> 'Checker',
		'marker'		=> 'Fluorescent marker',
		'document'		=> 'Document tree',
		'label'			=> 'Label',
		'music'			=> 'Music',
		'arrows'		=> 'Arrows',
		'business'		=> 'Business',
		'index'			=> 'Index',
		'urban'			=> 'Urban',
		'under_score'	=> 'Under score',
		'cube'			=> 'Cube'
	);
	
	var $option;


	function ps_auto_sitemap() {
		$this->__construct();
	}


	function __construct() {
		global $wp_version;
		$this->wp_version = $wp_version;
		
		add_action( 'init'			, array( &$this, 'ps_auto_sitemap_init') );
		add_action( 'publish_post'	, array( &$this, 'delete_sitemap_cache') );
		add_action( 'publish_page'	, array( &$this, 'delete_sitemap_cache') );
		add_filter( 'the_content'	, array( &$this, 'replace_sitemap_content') );
		add_action( 'admin_menu'	, array( &$this, 'add_sitemap_setting_menu') );
		add_action( 'wp_head'		, array( &$this, 'print_sitemap_prepare_css' ) );
		if ( version_compare( $wp_version, '2.6', '>=' ) ) {
			add_action( 'admin_print_styles'	, array( &$this, 'print_sitemap_admin_css' ) );
		} else {
			add_action( 'admin_head'			, array( &$this, 'print_sitemap_admin_css' ) );
		}
	}
	
	
	function ps_auto_sitemap_init() {
		$this->option = get_option( 'ps_sitemap' );
		if ( ! $this->option ) {
			$this->set_default_options();
			$this->option = get_option( 'ps_sitemap' );
		}
	}


	function replace_sitemap_content( $content ) {
		global $post;
		if ( $this->option['post_id'] && $post->ID == $this->option['post_id'] ) {
				if ( isset( $_GET['category'] ) && $category = get_category( (int)$_GET['category'] ) ) {
					$sitemap_content = $this->make_category_sitemap( $category );
				} else {
					$cache_dir = $this->check_cache_dir();
					if ( $cache_dir && file_exists( $cache_dir . '/site_map_cache.html' ) && $this->option['use_cache'] ) {
						$sitemap_content = file_get_contents( $cache_dir . '/site_map_cache.html' );
					} else {
						$sitemap_content = $this->create_sitemap_content();
					}
				}
			$content = preg_replace( '/(<p><!-- SITEMAP CONTENT REPLACE POINT --><\/p>|<!-- SITEMAP CONTENT REPLACE POINT -->)/', $sitemap_content, $content, 1 );
		}
		return $content;
	}


	function create_sitemap_content() {

		if ( $this->option['post_tree'] == '1' ) {
			$category_tree = $this->make_category_tree( $this->option['ex_cat_ids'] );
			$post_list = $this->make_post_list( $this->option['ex_post_ids'], $category_tree, $this->option['disp_level'], 1, false );
		} else {
			$post_list = '';
		}

		$page_on_front = get_option( 'page_on_front' );
		$ex_pages = $this->option['ex_post_ids'];
		if ( get_option( 'show_on_front') == 'page' && $page_on_front ) {
			if ( $ex_pages ) {
				$ex_pages .= ',' . $page_on_front;
			} else {
				$ex_pages = $page_on_front;
			}
		}
		$sitemap_content = "<ul id=\"sitemap_list\" class=\"sitemap_disp_level_" . $this->option['disp_level'] . "\">\n";
		if ($this->option['home_list'] ) {
			$sitemap_content .= '<li class="home-item"><a href="' . get_bloginfo( 'url' ) . '" title="' . get_bloginfo( 'name' ) . '">' . esc_html( get_bloginfo( 'name' ) ) . "</a></li>\n";
		}
		if ( $this->option['disp_first'] == 'post' ) {
			$sitemap_content .= $post_list;
			if ( $this->option['page_tree'] == '1' ) {
				$sitemap_content .= wp_list_pages( 'title_li=&echo=0&exclude=' . $ex_pages . '&depth=' . $this->option['disp_level'] );
			}
		} else {
			if ( $this->option['page_tree'] == '1' ) {
				$sitemap_content .= wp_list_pages( 'title_li=&echo=0&exclude=' . $ex_pages . '&depth=' . $this->option['disp_level'] );
			}
			$sitemap_content .= $post_list;
		}
		
		$sitemap_content .= "</ul>\n";
		if ( ! $this->option['suppress_link'] ) {
			$sitemap_content .= '<address style="text-align: right; font-size: x-small;">Powered by <a href="http://www.web-strategy.jp/" target="_blank">Prime Strategy Co.,LTD.</a></address>' . "\n";
		}
		
		if ( ( $cache_dir = $this->check_cache_dir() ) && $this->option['use_cache'] ) {
			$this->check_htaccess( $cache_dir );
			$handle = @fopen( $cache_dir . '/site_map_cache.html', 'w' );
			if ( $handle ) {
				fwrite( $handle, $sitemap_content );
				fclose( $handle );
			}
		}
		return $sitemap_content;
	}


	function make_category_tree( $ex_cat_ids ) {

		$branches = array();
		$categories = get_categories( 'exclude=' . $ex_cat_ids );

		foreach( $categories as $cat ) {
			if ( $cat->category_parent == 0 ) {
				$category_tree[$cat->term_id] = array();
			} else {
				$branches[$cat->category_parent][$cat->term_id] = array();
			}
		}

		if ( count( $branches ) ) {
			foreach( $branches as $foundation => $branch ) {
				foreach( $branches as $key => $val ) {
					if ( array_key_exists( $foundation, $val ) ) {
						$branches[$key][$foundation] = &$branches[$foundation];
						break 1;
					}
				}
			}
		
			foreach ( $branches as $foundation => $branch ) {
				if ( isset( $category_tree[$foundation] ) ) {
					$category_tree[$foundation] = $branch;
				}
			}
		}
		return $category_tree;
	}


	function make_post_list( $ex_post_ids, $category_tree, $depth, $cur_depth = 1 , $child = true ) {
		global $wpdb;

		if ( ! is_array( $category_tree ) ) { return; }
		
		$ex_post_ids = $this->form_ids_for_sql();

		if ( $child ) {
			$post_list = "\n<ul>\n";
		} else {
			$post_list = '';
		}

		foreach( $category_tree as $cat_id => $category ) {
			$post_list .= '<li class="cat-item cat-item-' . $cat_id . '"><a href="' . get_category_link( $cat_id ). '" title="' . get_the_category_by_ID( $cat_id ) . '">' . esc_html( get_the_category_by_ID( $cat_id ) ) . '</a>';

			if ( !$this->option['disp_posts'] || $this->option['disp_posts'] == 'combine' ) {
				if ( ! $depth || $depth > $cur_depth ) {
					$post_list .= $category_posts = $this->make_posts_list_in_category( $ex_post_ids, $cat_id, count( $category ) );
				}
			} else {
				$cur_category = get_category( $cat_id );
				if ( $cur_category->count ) {
					$post_list .= '<span class="posts_in_category"><a href="' . esc_url( add_query_arg( array( 'category' => $cat_id ), $_SERVER['REQUEST_URI'] ) ) . '"title="'. esc_attr( __( 'Show posts in this category.', 'ps_auto_sitemap' ) ) .'">' . esc_html( __( 'Show posts in this category.', 'ps_auto_sitemap' ) ) . '</a></span>' . "\n";
				}
			}

			if ( count( $category ) && ( ! $depth || $depth > $cur_depth ) ) {
				$post_list .= $this->make_post_list( $ex_post_ids, $category, $depth, $cur_depth + 1, ! $category_posts );
				if ( $category_posts ) {
					$post_list .= "</ul>\n";
				}
			}
			$post_list .= "</li>\n";
		}
		if ( $child ) {
			$post_list .= "</ul>\n";
		}
		return $post_list;
	}


	function make_posts_list_in_category( $ex_post_ids, $cat_id, $has_child ) {
		global $wpdb;
		
		$post_list_in_category = '';

		$query = "
SELECT	`posts`.`ID`,
		`posts`.`post_title`
FROM	$wpdb->posts as `posts`
INNER JOIN	$wpdb->term_relationships as `relation`
ON		( `posts`.`ID` = `relation`.`object_id` )
INNER JOIN $wpdb->term_taxonomy as `taxonomy`
ON		(`relation`.`term_taxonomy_id` = `taxonomy`.`term_taxonomy_id` )
INNER JOIN $wpdb->terms as `terms`
ON		( `taxonomy`.`term_id` = `terms`.`term_id` )
WHERE	`posts`.`post_status` = 'publish'
AND		`posts`.`post_type` = 'post'
AND		`posts`.`ID` NOT IN ( $ex_post_ids )
AND		`terms`.`term_id` = '$cat_id'
GROUP BY	`posts`.`ID`
ORDER BY	`posts`.`post_date` DESC";
		$category_posts = $wpdb->get_results( $query, ARRAY_A );
		if ( $category_posts ) {
			$post_list_in_category .= "\n<ul>\n";
			foreach( $category_posts as $post ) {
				$post_list_in_category .= "\t" . '<li class="post-item post-item-' . $post['ID'] . '"><a href="' . get_permalink( $post['ID'] ) . '" title="' . esc_attr( $post['post_title'] ) . '">' . esc_html( $post['post_title'] ) . "</a></li>\n";
			}
			if ( ! $has_child ) {
				$post_list_in_category .= "</ul>\n";
			}
		}
		return $post_list_in_category;
	}


	function add_sitemap_setting_menu() {
		add_options_page( 'PS Auto Sitemap setting', 'PS Auto Sitemap', 'manage_options', basename(__FILE__), array( &$this, 'sitemap_setting') );
	}


	function make_category_sitemap( $category ) {
		if ( ! $_GET['category'] || ! is_object( $category ) ) { return; }
		
		$ex_post_ids = $this->form_ids_for_sql();
		
		$sitemap_content = '<ul id="sitemap_list">' . "\n";
		$sitemap_content .= '<li class="cat-item cat-item-' . $category->term_id . '"><a href="' . get_category_link( $category->term_id ). '" title="' . esc_attr( $category->name ) . '">' . esc_html( $category->name ) . '</a>';
		$sitemap_content .= $this->make_posts_list_in_category( $ex_post_ids, (int)$_GET['category'], false );
		$sitemap_content .= '</li>' . "\n";
		$sitemap_content .= '</ul>' . "\n";
		
		return $sitemap_content;
	}


	function form_ids_for_sql() {
		$ex_post_ids = preg_replace( '/[^\d,]/', '', $this->option['ex_post_ids'] );
		$ex_post_ids = preg_replace( '/,+/', ',', $this->option['ex_post_ids'] );
		$ex_post_ids = trim( $ex_post_ids, ',' );
		$ex_post_ids = "'" . str_replace( ',', "','", $ex_post_ids ) . "'";
		
		return $ex_post_ids;
	}


	function sitemap_setting() {
		global $wp_version;
		
		$user_locale = get_locale();
		$lang_file = dirname( __file__) . '/language/ps_auto_sitemap-' . $user_locale . '.mo';

		if ( file_exists( $lang_file ) ) {
			load_textdomain( 'ps_auto_sitemap', $lang_file );
		}
		$ret = false;

		if( isset( $_POST['_wpnonce'] ) && $_POST['_wpnonce'] ) {
			check_admin_referer( 'ps_auto_sitemap' );
			$sitemap_option_keys = array( 'home_list', 'post_tree', 'page_tree', 'post_id', 'disp_level',  'disp_first','disp_posts', 'ex_cat_ids', 'ex_post_ids', 'prepared_style', 'use_cache', 'suppress_link' );
			
			foreach ( $sitemap_option_keys as $key ) {
				switch ( $key ) {
				case 'home_list' :
				case 'post_tree' :
				case 'page_tree' :
				case 'use_cache' :
				case 'suppress_link' :
					if ( ! isset( $_POST['ps_sitemap_' . $key] ) ) {
						$_POST['ps_sitemap_' . $key] = '';
					} else {
						$this->validate_bool( $_POST['ps_sitemap_' . $key] );
					}
					break;
				case 'post_id' :
					if ( isset( $_POST['ps_sitemap_' . $key] ) && $_POST['ps_sitemap_' . $key] != '' ) {
						if ( function_exists( 'mb_convert_kana' ) ) {
							$_POST['ps_sitemap_' . $key] = mb_convert_kana( $_POST['ps_sitemap_' . $key], 'as', 'UTF-8' );
						}
						$_POST['ps_sitemap_' . $key] = preg_replace( '/[^\d]/', '', $_POST['ps_sitemap_' . $key] );
						$this->validate_positive_int( $_POST['ps_sitemap_' . $key] );
					}
					break;
				case 'disp_level' :
					$_POST['ps_sitemap_' . $key] = preg_replace( '/[^\d]/', '', $_POST['ps_sitemap_' . $key] );
					if ( $_POST['ps_sitemap_' . $key] !== '0' ) {
						$this->validate_positive_int( $_POST['ps_sitemap_' . $key] );
					}
					break;
				case 'disp_first' :
					if ( ! in_array( $_POST['ps_sitemap_' . $key], array( 'post', 'page' ) ) ) {
						wp_die( 'unvalid post data exist.' );
					}
					break;
				case 'disp_posts' :
					if ( ! in_array( $_POST['ps_sitemap_' . $key], array( 'combine', 'divide' ) ) ) {
						wp_die( 'unvalid post data exist.' );
					}
					break;
				case 'ex_cat_ids' :
				case 'ex_post_ids' :
					if ( $_POST['ps_sitemap_' . $key] != '' ) {
						if ( function_exists( 'mb_convert_kana' ) ) {
							$_POST['ps_sitemap_' . $key] = mb_convert_kana( $_POST['ps_sitemap_' . $key], 'as', 'UTF-8' );
						}
						$_POST['ps_sitemap_' . $key] = preg_replace( '/、/', ',', $_POST['ps_sitemap_' . $key] );
						$_POST['ps_sitemap_' . $key] = preg_replace( '/\./', ',', $_POST['ps_sitemap_' . $key] );
						$_POST['ps_sitemap_' . $key] = preg_replace( '/[^\d,]/', '', $_POST['ps_sitemap_' . $key] );
						$this->validate_num_comma( $_POST['ps_sitemap_' . $key] );
					}
					break;
				case 'prepared_style' :
					if ( $_POST['ps_sitemap_' . $key] == '' || ! $this->sitemap_prepared_styles[$_POST['ps_sitemap_' . $key]] ) {
						$_POST['ps_sitemap_' . $key] = '';
					}
					break;
				default :
				}
				$this->option[$key] = $_POST['ps_sitemap_' . $key];
			}
			$ret = update_option( 'ps_sitemap', $this->option );
			if ( $ret ) {
				$this->delete_sitemap_cache();
			}
		}
		?>
		<div class=wrap>
			<?php if ( function_exists( 'screen_icon' ) ) { screen_icon(); } ?>
			<h2>PS Auto Sitemap</h2>
			<?php if ( $ret ) { ?>
			<div id="message" class="updated">
				<p><?php _e('The settings of PS Auto Sitemap has changed successfully.', 'ps_auto_sitemap' );?></p>
			</div>
			<?php } elseif ( isset( $_POST['ps_sitemap_submit'] ) && $_POST['ps_sitemap_submit'] && ! $ret ) { ?>
			<div id="notice" class="error">
				<p><?php _e('The settings has not been changed. There were no changes or failed to update the data base.', 'ps_auto_sitemap' );?></p>
			</div>
			<?php }
					if ( ( ! $this->check_cache_dir() ) && $this->option['use_cache'] ) {	?>
			<div id="notice" class="error">
				<p><?php _e('PS Auto Sitemap isn\'t using cache system currently, because cache or parent directorty isn\'t writable. Please check owner and permission of upload directory.', 'ps_auto_sitemap' );?></p>
			</div>

			<?php } ?>
			<form method="post" action="">
				<?php wp_nonce_field( 'ps_auto_sitemap' ); ?>
				<table class="form-table">
					<tr>
						<th><?php _e( 'Display home list', 'ps_auto_sitemap' ); ?></th>
						<td><input type="checkbox" name="ps_sitemap_home_list" id="ps_sitemap_home_list" value="1"<?php if ( $this->option['home_list'] == '1' ) : ?> checked="checked"<?php endif; ?> /> <label for="ps_sitemap_home_list"><?php _e( 'Display', 'ps_auto_sitemap' ); ?></label></td>
					</tr>
					<tr>
						<th><?php _e( 'Display post tree', 'ps_auto_sitemap' ); ?></th>
						<td><input type="checkbox" name="ps_sitemap_post_tree" id="ps_sitemap_post_tree" value="1"<?php if ( $this->option['post_tree'] == '1' ) : ?> checked="checked"<?php endif; ?> /> <label for="ps_sitemap_post_tree"><?php _e( 'Display', 'ps_auto_sitemap' ); ?></label></td>
					</tr>
					<tr>
						<th><?php _e( 'Display page tree', 'ps_auto_sitemap' ); ?></th>
						<td><input type="checkbox" name="ps_sitemap_page_tree" id="ps_sitemap_page_tree" value="1"<?php if ( $this->option['page_tree'] == '1' ) : ?> checked="checked"<?php endif; ?> /> <label for="ps_sitemap_page_tree"><?php _e( 'Display', 'ps_auto_sitemap' ); ?></label></td>
					</tr>
					<tr>
						<th><?php _e( 'PostID of the sitemap', 'ps_auto_sitemap' ); ?></th>
						<td><input type="text" name="ps_sitemap_post_id" id="ps_sitemap_post_id" value="<?php echo $this->option['post_id']; ?>" ><br />
						<?php _e( '* Please input display sitemap post\'s ID.', 'ps_auto_sitemap' ); ?>
						</td>
					</tr>
					<tr>
						<th><?php _e( 'Depth level', 'ps_auto_sitemap' ); ?></th>
						<td>
							<select name="ps_sitemap_disp_level" id="ps_sitemap_disp_level">
								<option value="0"<?php if ( $this->option['disp_level'] == '0' ) : ?> selected="selected"<?php endif; ?>><?php _e( 'no limit', 'ps_auto_sitemap' ); ?></option>
								<option value="1"<?php if ( $this->option['disp_level'] == '1' ) : ?> selected="selected"<?php endif; ?>><?php _e( 'level 1', 'ps_auto_sitemap' ); ?></option>
								<option value="2"<?php if ( $this->option['disp_level'] == '2' ) : ?> selected="selected"<?php endif; ?>><?php _e( 'level 2', 'ps_auto_sitemap' ); ?></option>
								<option value="3"<?php if ( $this->option['disp_level'] == '3' ) : ?> selected="selected"<?php endif; ?>><?php _e( 'level 3', 'ps_auto_sitemap' ); ?></option>
								<option value="4"<?php if ( $this->option['disp_level'] == '4' ) : ?> selected="selected"<?php endif; ?>><?php _e( 'level 4', 'ps_auto_sitemap' ); ?></option>
								<option value="5"<?php if ( $this->option['disp_level'] == '5' ) : ?> selected="selected"<?php endif; ?>><?php _e( 'level 5', 'ps_auto_sitemap' ); ?></option>
								<option value="6"<?php if ( $this->option['disp_level'] == '6' ) : ?> selected="selected"<?php endif; ?>><?php _e( 'level 6', 'ps_auto_sitemap' ); ?></option>
								<option value="7"<?php if ( $this->option['disp_level'] == '7' ) : ?> selected="selected"<?php endif; ?>><?php _e( 'level 7', 'ps_auto_sitemap' ); ?></option>
								<option value="8"<?php if ( $this->option['disp_level'] == '8' ) : ?> selected="selected"<?php endif; ?>><?php _e( 'level 8', 'ps_auto_sitemap' ); ?></option>
								<option value="9"<?php if ( $this->option['disp_level'] == '9' ) : ?> selected="selected"<?php endif; ?>><?php _e( 'level 9', 'ps_auto_sitemap' ); ?></option>
								<option value="10"<?php if ( $this->option['disp_level'] == '10' ) : ?> selected="selected"<?php endif; ?>><?php _e( 'level 10', 'ps_auto_sitemap' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th><?php _e( 'Display first', 'ps_auto_sitemap' ); ?></th>
						<td>
							<input type="radio" name="ps_sitemap_disp_first" id="ps_sitemap_disp_first_post" value="post"<?php if ( $this->option['disp_first'] == 'post' ) : ?> checked="checked"<?php endif; ?> />
							<label for="ps_sitemap_disp_first_post"><?php _e( 'Post', 'ps_auto_sitemap' ); ?></label>
							<input type="radio" name="ps_sitemap_disp_first" id="ps_sitemap_disp_first_page" value="page"<?php if ( $this->option['disp_first'] == 'page' ) : ?> checked="checked"<?php endif; ?> />
							<label for="ps_sitemap_disp_first_page"><?php _e( 'Page', 'ps_auto_sitemap' ); ?></label>
						</td>
					</tr>
					<tr>
						<th><?php _e( 'Display of categories &amp; posts', 'ps_auto_sitemap' ); ?></th>
						<td>
							<input type="radio" name="ps_sitemap_disp_posts" id="ps_sitemap_disp_posts_combine" value="combine"<?php if ( !isset( $this->option['disp_posts'] ) || $this->option['disp_posts'] == 'combine' ) : ?> checked="checked"<?php endif; ?> />
							<label for="ps_sitemap_disp_posts_combine"><?php _e( 'Combine', 'ps_auto_sitemap' ); ?></label>
							<input type="radio" name="ps_sitemap_disp_posts" id="ps_sitemap_disp_posts_divide" value="divide"<?php if ( $this->option['disp_posts'] == 'divide' ) : ?> checked="checked"<?php endif; ?> />
							<label for="ps_sitemap_disp_posts_divide"><?php _e( 'Divide', 'ps_auto_sitemap' ); ?></label>
						</td>
					</tr>
					<tr>
						<th><?php _e( 'Excluded categories', 'ps_auto_sitemap' ); ?></th>
						<td><input type="text" name="ps_sitemap_ex_cat_ids" id="ps_sitemap_ex_cat_ids" value="<?php echo $this->option['ex_cat_ids']; ?>" ><br />
						<?php _e( '* Please input category ID of exclude categories. (Separated by comma)', 'ps_auto_sitemap' ); ?>
						</td>
					</tr>
					<tr>
						<th><?php _e( 'Exclude posts', 'ps_auto_sitemap' ); ?></th>
						<td><input type="text" name="ps_sitemap_ex_post_ids" id="ps_sitemap_ex_post_ids" value="<?php echo $this->option['ex_post_ids']; ?>" ><br />
						<?php _e( '* Please input post ID of exclude posts. (Separated by comma)', 'ps_auto_sitemap' ); ?>
						</td>
					</tr>
					<tr>
						<th><?php _e( 'Select style', 'ps_auto_sitemap' ); ?></th>
						<td>
							<select name="ps_sitemap_prepared_style" id="ps_sitemap_prepared_style">
								<option value=""<?php if ( $this->option['prepared_style'] == '' ) : ?> selected="selected"<?php endif; ?>><?php _e( 'no style', 'ps_auto_sitemap' ); ?></option>
<?php foreach ( $this->sitemap_prepared_styles as $style_code => $style_name ) : ?>
								<option value="<?php echo $style_code ?>"<?php if ( $this->option['prepared_style'] == $style_code ) : ?> selected="selected"<?php endif; ?>><?php _e( $style_name, 'ps_auto_sitemap' ); ?></option>
<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th><?php _e( 'Using cache', 'ps_auto_sitemap' ); ?></th>
						<td><input type="checkbox" name="ps_sitemap_use_cache" id="ps_sitemap_use_cache" value="1"<?php if ( $this->option['use_cache'] == '1' ) : ?> checked="checked"<?php endif; ?> /> <label for="ps_sitemap_use_cache"><?php _e( 'use', 'ps_auto_sitemap' ); ?></label></td>
					</tr>
					<tr>
						<th><?php _e( 'Hide developper link', 'ps_auto_sitemap' ); ?></th>
						<td><input type="checkbox" name="ps_sitemap_suppress_link" id="ps_sitemap_suppress_link" value="1"<?php if ( $this->option['suppress_link'] == '1' ) : ?> checked="checked"<?php endif; ?> /> <label for="ps_sitemap_suppress_link"><?php _e( 'Hide', 'ps_auto_sitemap' ); ?></label></td>
					</tr>
				</table>
				<div class="ps_sitemap_submit_buttons">
					<input type="submit" name="ps_sitemap_submit" class="button-primary" value="<?php _e( 'Save Changes' ); ?>" />
				</div>
			</form>
			<div class="ps_sitemap_installation">
				<h3><?php _e( 'Usage', 'ps_auto_sitemap' ); ?></h3>
				<ol>
					<li><?php _e( 'Post a page that will use as the sitemap page.', 'ps_auto_sitemap' ); ?></li>
					<li><?php _e( 'Insert following code in the content area. (<strong>Use HTML mode</strong>)', 'ps_auto_sitemap' ); ?>
					<br /><code>&lt;!-- SITEMAP CONTENT REPLACE POINT --&gt;</code></li>
					<li><?php _e( 'Define the sitemap\'s ID at "<strong>PostID of the sitemap</strong>" field of the setings.', 'ps_auto_sitemap' ); ?></li>
				</ol>
			</div>
			<div id="ps_sitemap_author">This plugin developed by <a href="http://www.prime--strategy.co.jp/">Prime Strategy Co.,LTD.</a></div>
		</div>
		<?php
	}


	function print_sitemap_admin_css() {
		if( isset( $_GET['page'] ) && $_GET['page'] == 'ps_auto_sitemap.php' ) {
			if ( defined( 'WP_PLUGIN_URL' ) ) {
				echo '<link rel="stylesheet" href="' . WP_PLUGIN_URL . str_replace( str_replace( '\\', '/', WP_PLUGIN_DIR ), '', str_replace( '\\', '/', dirname( __file__ ) ) ) . '/css/ps_auto_sitemap_admin.css" type="text/css" media="all" />' . "\n";
			} else {
				echo '<link rel="stylesheet" href="' . get_option('siteurl') . '/' . str_replace( ABSPATH, '', dirname( __file__ ) ) . '/css/ps_auto_sitemap_admin.css" type="text/css" media="all" />' . "\n";
			}
		}
	}


	function print_sitemap_prepare_css() {
		if ( is_singular() ) {
			global $post;
			$option = get_option( 'ps_sitemap' );
			if( $post->ID == $this->option['post_id'] && $this->option['prepared_style'] != '' ) {
				if ( defined( 'WP_PLUGIN_URL' ) ) {
					echo '<link rel="stylesheet" href="' . WP_PLUGIN_URL . str_replace( str_replace( '\\', '/', WP_PLUGIN_DIR ), '', str_replace( '\\', '/', dirname( __file__ ) ) ) . '/css/ps_auto_sitemap_' . $this->option['prepared_style'] . '.css" type="text/css" media="all" />' . "\n";
				} else {
					echo '<link rel="stylesheet" href="' . get_option('siteurl') . '/' . str_replace( ABSPATH, '', dirname( __file__ ) ) . '/css/ps_auto_sitemap_' . $this->option['prepared_style'] . '.css" type="text/css" media="all" />' . "\n";
				}
			}
		}
	}


	function validate_bool( $val ) {
		if ( $val !== true && $val !== false && $val !== 1 && $val !== 0 && $val !== '1' && $val !== '0' ) {
			wp_die( 'unvalid post data exist.' );
		}
	}


	function validate_positive_int( $val ) {
		if ( ! preg_match( '/^[1-9]+[\d]*$/', $val ) ) {
			wp_die( 'unvalid post data exist.' );
		}
	}


	function validate_num_comma( $val ) {
		if ( ! preg_match( '/^[1-9]+[\d]*(,[1-9]+[\d]*)*$/', $val ) ) {
			wp_die( 'unvalid post data exist.' );
		}
	}


	function set_default_options() {
		$option = array(
			'home_list'			=> '1',
			'post_tree'			=> '1',
			'page_tree'			=> '1',
			'post_id'			=> '',
			'disp_level'		=> '0',
			'disp_first'		=> 'post',
			'disp_posts'		=> 'combine',
			'ex_cat_ids'		=> '',
			'ex_post_ids'		=> '',
			'prepared_style'	=> '',
			'use_cache' =>		'1',
			'suppress_link'		=> ''
		);
		update_option( 'ps_sitemap', $option );
	}


	function check_cache_dir() {
		$uploads = wp_upload_dir();

		if ( $uploads['error'] !== false ) { return false; }

		$cache_dir = $uploads['basedir'] . '/ps_auto_sitemap';
		if ( is_writable( $uploads['basedir'] ) ) {
			if ( file_exists( $cache_dir ) ) {
				if ( is_writable( $cache_dir ) ) {
					return $cache_dir;
				}
			} else {
				if( mkdir( $cache_dir ) ) {
					return $cache_dir;
				}
			}
		}
		return false;
	}


	function delete_sitemap_cache() {
		$uploads = wp_upload_dir();
		if ( $uploads['error'] !== false ) { return false; }
		
		$cache_file = $uploads['basedir'] . '/ps_auto_sitemap/site_map_cache.html';
		if ( file_exists( $cache_file ) ) {
			unlink( $cache_file );
		}
	}


	function check_htaccess( $cache_dir ) {
		if ( file_exists( $cache_dir . '/.htaccess' ) ) { return; }
		$handle = @fopen( $cache_dir . '/.htaccess', 'w' );
		if ( $handle ) {
			fwrite( $handle, "order deny,allow\ndeny from all" );
			fclose( $handle );
		}
	}


}

$ps_auto_sitemap = new ps_auto_sitemap();


if ( ! function_exists( 'esc_attr' ) ) {
function esc_attr( $text ) {
	return attribute_escape( $text );
}
}

if ( ! function_exists( 'esc_html' ) ) {
function esc_html( $text ) {
	return wp_specialchars( $text );
}
}

if ( ! function_exists( 'esc_url' ) ) {
function esc_url( $url, $protocols = null, $_context = 'display' ) {
	return clean_url( $url, $protocols, $context );
}
}