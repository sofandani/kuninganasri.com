<?php
/**
 * Plugin Name.
 *
 * @package   Plugin_Name_Admin
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package Plugin_Name_Admin
 * @author  Your Name <email@example.com>
 */
 
require_once(dirname(__FILE__).'/WPEFI_Admin_Base.php');
 
class WP_Wpefi_Admin extends WPEFI_Admin_Base{

        /**
         * Instance of this class.
         *
         * @since    1.0.0
         *
         * @var      object
         */
        protected static $instance = null;

        /**
         * Slug of the plugin screen.
         *
         * @since    1.0.0
         *
         * @var      string
         */
        protected $plugin_screen_hook_suffix = null;

        /**
         * Sections (tabs id => Tabs Titles)
         *
         * @since    1.0.0
         *
         * @var      array
         */
        protected $sections = null;


        /**
         * Initialize the plugin by loading admin scripts & styles and adding a
         * settings page and menu.
         *
         * @since     1.0.0
         */
        function __construct() {

                /*
                 * @TODO :
                 *
                 * - Uncomment following lines if the admin class should only be available for super admins
                 */
                /* if( ! is_super_admin() ) {
                        return;
                } */

                /*
                 * Call $plugin_slug from public plugin class.
                 *
                 * @TODO:
                 *
                 * - Rename "Plugin_Name" to the name of your initial plugin class
                 *
                 */
                $plugin = WP_Wpefi::get_instance();
                //We are using slug also for options name
                $this->plugin_slug  = $plugin->get_plugin_slug();
                $this->options_name = $this->plugin_slug .'_settings';
                
                $this->sections['general']                   = __( 'Export', $this->plugin_slug );
				
				$this->WPB_PLUGIN_NAME 		=   'Export Featured Images';
				$this->WPB_PLUGIN_VERSION 	=   WPEFI_VERSION;
				$this->WPB_SLUG			 	=   'export-featured-images';
				$this->WPB_PLUGIN_URL		=   WPEFI_PLUGIN_URL;
				
				$this->WPB_PREFIX			=   $this->plugin_slug;
				
                // Load admin style sheet and JavaScript.
                #add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
                #add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

                // Add the options page and menu item.
                add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

                // Add an action link pointing to the options page.
                $plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
                add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
				
				add_action('init' , array( $this, 'handle_export'));
				
				parent::__construct();

        }

        /**
         * Return an instance of this class.
         *
         * @since     1.0.0
         *
         * @return    object    A single instance of this class.
         */
        public static function get_instance() {

                /*
                 * @TODO :
                 *
                 * - Uncomment following lines if the admin class should only be available for super admins
                 */
                /* if( ! is_super_admin() ) {
                        return;
                } */

                // If the single instance hasn't been set, set it now.
                if ( null == self::$instance ) {
                        self::$instance = new self;
                }

                return self::$instance;
        }

        /**
         * Register and enqueue admin-specific style sheet.
         *
         * @TODO:
         *
         * - Rename "Plugin_Name" to the name your plugin
         *
         * @since     1.0.0
         *
         * @return    null    Return early if no settings page is registered.
         */
        public function enqueue_admin_styles() {

                if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
                        return;
                }

                $screen = get_current_screen();
                if ( $this->plugin_screen_hook_suffix == $screen->id ) {
                    #    wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), WP_Wpefi::VERSION );
                }

        }

        /**
         * Register and enqueue admin-specific JavaScript.
         *
         * @TODO:
         *
         * - Rename "Plugin_Name" to the name your plugin
         *
         * @since     1.0.0
         *
         * @return    null    Return early if no settings page is registered.
         */
        public function enqueue_admin_scripts() {

                if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
                        return;
                }

                $screen = get_current_screen();
                if ( $this->plugin_screen_hook_suffix == $screen->id ) {
                        wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), WP_Wpefi::VERSION );
                }

        }

        /**
         * Register the administration menu for this plugin into the WordPress Dashboard menu.
         *
         * @since    1.0.0
         */
        public function add_plugin_admin_menu() {

                /*
                 * Add a settings page for this plugin to the Settings menu.
                 *
                 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
                 *
                 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
                 *
                 * @TODO:
                 *
                 * - Change 'Page Title' to the title of your plugin admin page
                 * - Change 'Menu Text' to the text for menu item for the plugin settings page
                 * - Change 'manage_options' to the capability you see fit
                 *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
                 *
                $this->plugin_screen_hook_suffix = add_options_page(
                        __( 'Page Title', $this->plugin_slug ),
                        __( 'Menu Text', $this->plugin_slug ),
                        'manage_options',
                        $this->plugin_slug,
                        array( $this, 'display_page' )
                );
                */
                $this->plugin_screen_hook_suffix = add_submenu_page(
                		'tools.php',
                        __( 'Export Featured Images', $this->plugin_slug ),
                        __( 'Export Featured Images', $this->plugin_slug ),
                        'manage_options',
                        $this->plugin_slug,
                        array( $this, 'display_page' )
                );

        }


        /**
         * Add settings action link to the plugins page.
         *
         * @since    1.0.0
         */
        public function add_action_links( $links ) {

                return array_merge(
                        array(
                                'Go to Export' => '<a href="' . admin_url( 'tools.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
                        ),
                        $links
                );

        }


		/**
		 *
		 *
		 *
		 */
       function handle_export(){
       
		   global $wpdb;
       		if( !isset($_POST['option_page']) || $_POST['option_page'] != $this->plugin_slug.'_settings' ) 
       			return;

       		define( 'WXR_VERSION', '1.2' );
       		
       		
       		/** Load WordPress export API */
	   		require_once( plugin_dir_path( __FILE__ ) . '/includes/export.php' );
       		
       		@$p_types = 	$_POST['wpefi_settings']['p_types'];
       		if( !empty($p_types) )
       		{
	       		foreach ( $p_types as $k => $val )
	       		{
	       			$p_types_a[] = $k; 
	       		}
				$post_types = $p_types_a;
				
				$esses = array_fill( 0, count($post_types), '%s' );
				$where = $wpdb->prepare( "{$wpdb->posts}.post_type IN (" . implode( ',', $esses ) . ')', $post_types );

       		}
       		
       		   			$sitename = sanitize_key( get_bloginfo( 'name' ) );
			if ( ! empty($sitename) ) $sitename .= '.';
			$filename = $sitename . 'wordpress.' . date( 'Y-m-d' ) . '.xml';
		
			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );

	
	
			if( !empty($posts_types) )
			{
				// put categories in order with no child going before its parent
				while ( $cat = array_shift( $categories ) ) {
					if ( $cat->parent == 0 || isset( $cats[$cat->parent] ) )
						$cats[$cat->term_id] = $cat;
					else
						$categories[] = $cat;
				}
			}

			$join = "LEFT JOIN {$wpdb->postmeta} ON ({$wpdb->posts}.ID = {$wpdb->postmeta}.post_id)";
			$where .= " AND {$wpdb->postmeta}.meta_key = '_thumbnail_id'";
			
			// grab a snapshot of post IDs, just in case it changes during the export
			$post_ids = $wpdb->get_col( "SELECT {$wpdb->postmeta}.meta_value FROM {$wpdb->posts} $join WHERE $where" );		
			

			
			echo '<?xml version="1.0" encoding="' . get_bloginfo('charset') . "\" ?>\n";
			
				?>
			<!-- This is a WordPress eXtended RSS file generated by WordPress as an export of your site. -->
			<!-- It contains information about your site's posts, pages, comments, categories, and other content. -->
			<!-- You may use this file to transfer that content from one site to another. -->
			<!-- This file is not intended to serve as a complete backup of your site. -->
			
			<!-- To import this information into a WordPress site follow these steps: -->
			<!-- 1. Log in to that site as an administrator. -->
			<!-- 2. Go to Tools: Import in the WordPress admin panel. -->
			<!-- 3. Install the "WordPress" importer from the list. -->
			<!-- 4. Activate & Run Importer. -->
			<!-- 5. Upload this file using the form provided on that page. -->
			<!-- 6. You will first be asked to map the authors in this export file to users -->
			<!--    on the site. For each author, you may choose to map to an -->
			<!--    existing user on the site or to create a new user. -->
			<!-- 7. WordPress will then import each of the posts, pages, comments, categories, etc. -->
			<!--    contained in this file into your site. -->
			
			<?php the_generator( 'export' ); ?>
			<rss version="2.0"
				xmlns:excerpt="http://wordpress.org/export/<?php echo WXR_VERSION; ?>/excerpt/"
				xmlns:content="http://purl.org/rss/1.0/modules/content/"
				xmlns:wfw="http://wellformedweb.org/CommentAPI/"
				xmlns:dc="http://purl.org/dc/elements/1.1/"
				xmlns:wp="http://wordpress.org/export/<?php echo WXR_VERSION; ?>/"
			>
			
			<channel>
				<title><?php bloginfo_rss( 'name' ); ?></title>
				<link><?php bloginfo_rss( 'url' ); ?></link>
				<description><?php bloginfo_rss( 'description' ); ?></description>
				<pubDate><?php echo date( 'D, d M Y H:i:s +0000' ); ?></pubDate>
				<language><?php bloginfo_rss( 'language' ); ?></language>
				<wp:wxr_version><?php echo WXR_VERSION; ?></wp:wxr_version>
				<wp:base_site_url><?php echo wxr_site_url(); ?></wp:base_site_url>
				<wp:base_blog_url><?php bloginfo_rss( 'url' ); ?></wp:base_blog_url>
			<?php if ( $post_ids ) {
				global $wp_query;
				$wp_query->in_the_loop = true; // Fake being in the loop.
			
				// fetch 20 posts at a time rather than loading the entire table into memory
				while ( $next_posts = array_splice( $post_ids, 0, 20 ) ) {
				$where = 'WHERE ID IN (' . join( ',', $next_posts ) . ')';
				$posts = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} $where" );
			
				// Begin Loop
				foreach ( $posts as $post ) {
					setup_postdata( $post );
					$is_sticky = is_sticky( $post->ID ) ? 1 : 0;
			?>
				<item>
					<?php /** This filter is documented in wp-includes/feed.php */ ?>
					<title><?php echo apply_filters( 'the_title_rss', $post->post_title ); ?></title>
					<link><?php the_permalink_rss() ?></link>
					<pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
					<dc:creator><?php echo wxr_cdata( get_the_author_meta( 'login' ) ); ?></dc:creator>
					<guid isPermaLink="false"><?php the_guid(); ?></guid>
					<description></description>
					<content:encoded><?php echo wxr_cdata( apply_filters( 'the_content_export', $post->post_content ) ); ?></content:encoded>
					<excerpt:encoded><?php echo wxr_cdata( apply_filters( 'the_excerpt_export', $post->post_excerpt ) ); ?></excerpt:encoded>
					<wp:post_id><?php echo $post->ID; ?></wp:post_id>
					<wp:post_date><?php echo $post->post_date; ?></wp:post_date>
					<wp:post_date_gmt><?php echo $post->post_date_gmt; ?></wp:post_date_gmt>
					<wp:comment_status><?php echo $post->comment_status; ?></wp:comment_status>
					<wp:ping_status><?php echo $post->ping_status; ?></wp:ping_status>
					<wp:post_name><?php echo $post->post_name; ?></wp:post_name>
					<wp:status><?php echo $post->post_status; ?></wp:status>
					<wp:post_parent><?php echo $post->post_parent; ?></wp:post_parent>
					<wp:menu_order><?php echo $post->menu_order; ?></wp:menu_order>
					<wp:post_type><?php echo $post->post_type; ?></wp:post_type>
					<wp:post_password><?php echo $post->post_password; ?></wp:post_password>
					<wp:is_sticky><?php echo $is_sticky; ?></wp:is_sticky>
			<?php	if ( $post->post_type == 'attachment' ) : ?>
					<wp:attachment_url><?php echo wp_get_attachment_url( $post->ID ); ?></wp:attachment_url>
			<?php 	endif; ?>
			<?php 	wxr_post_taxonomy(); ?>
			<?php	$postmeta = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->postmeta WHERE post_id = %d", $post->ID ) );
					foreach ( $postmeta as $meta ) :
						if ( apply_filters( 'wxr_export_skip_postmeta', false, $meta->meta_key, $meta ) )
							continue;
					?>
					<wp:postmeta>
						<wp:meta_key><?php echo $meta->meta_key; ?></wp:meta_key>
						<wp:meta_value><?php echo wxr_cdata( $meta->meta_value ); ?></wp:meta_value>
					</wp:postmeta>
			<?php	endforeach; ?>
			
				</item>
			<?php
				}
				}
			}				?>
			<?php do_action( 'rss2_head' ); ?>
			</channel>
			</rss><?php	
			die();
       }
}