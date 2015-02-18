<?php
/**
 * Plugin Name.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-plugin-name-admin.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package Plugin_Name
 * @author  Your Name <email@example.com>
 */
class WP_Wpefi {

        /**
         * Plugin version, used for cache-busting of style and script file references.
         *
         * @since   1.0.0
         *
         * @var     string
         */
        const VERSION = WPEFI_VERSION;
        

        /**
         * @TODO - Rename "plugin-name" to the name your your plugin
         *
         * Unique identifier for your plugin.
         *
         *
         * The variable name is used as the text domain when internationalizing strings
         * of text. Its value should match the Text Domain file header in the main
         * plugin file.
         *
         * @since    1.0.0
         *
         * @var      string
         */
        protected $plugin_slug = 'wpefi';

        /**
         * Instance of this class.
         *
         * @since    1.0.0
         *
         * @var      object
         */
        protected static $instance = null;

        /**
         * Options of the Plugin in DB
         *
         * @since    1.0.0
         *
         * @var      array
         */
        protected static $_options = null;


        /**
         * Initialize the plugin by setting localization and loading public scripts
         * and styles.
         *
         * @since     1.0.0
         */
        private function __construct() {

                // Load plugin text domain
                add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
                		
				self::$_options = get_option($this->plugin_slug.'_settings');
                

                // Activate plugin when new blog is added
                add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

               


        }

        /**
         * Return the plugin slug.
         *
         * @since    1.0.0
         *
         *@return    Plugin slug variable.
         */
        public function get_plugin_slug() {
                return $this->plugin_slug;
        }


        
        /**
         * Return an instance of this class.
         *
         * @since     1.0.0
         *
         * @return    object    A single instance of this class.
         */
        public static function get_instance() {

                // If the single instance hasn't been set, set it now.
                if ( null == self::$instance ) {
                        self::$instance = new self;
                        self::$instance->includes();
                }

                return self::$instance;
        }
		
		/**
         * Include required files
         *
         * @access private
         * @since 1.4
         * @return void
         */
        private function includes() {
                global $wpefi_settings;
                
                require_once WPEFI_PLUGIN_DIR . 'public/includes/settings.php';
                $wpefi_settings = wpefi_get_settings();
        }        
        /**
         * Fired when the plugin is activated.
         *
         * @since    1.0.0
         *
         * @param    boolean    $network_wide    True if WPMU superadmin uses
         *                                       "Network Activate" action, false if
         *                                       WPMU is disabled or plugin is
         *                                       activated on an individual blog.
         */
        public static function activate( $network_wide ) {

                if ( function_exists( 'is_multisite' ) && is_multisite() ) {

                        if ( $network_wide  ) {

                                // Get all blog ids
                                $blog_ids = self::get_blog_ids();

                                foreach ( $blog_ids as $blog_id ) {

                                        switch_to_blog( $blog_id );
                                        self::single_activate();

                                }

                                restore_current_blog();

                        } else {
                                self::single_activate();
                        }

                } else {
                        self::single_activate();
                }

        }

        /**
         * Fired when the plugin is deactivated.
         *
         * @since    1.0.0
         *
         * @param    boolean    $network_wide    True if WPMU superadmin uses
         *                                       "Network Deactivate" action, false if
         *                                       WPMU is disabled or plugin is
         *                                       deactivated on an individual blog.
         */
        public static function deactivate( $network_wide ) {

                if ( function_exists( 'is_multisite' ) && is_multisite() ) {

                        if ( $network_wide ) {

                                // Get all blog ids
                                $blog_ids = self::get_blog_ids();

                                foreach ( $blog_ids as $blog_id ) {

                                        switch_to_blog( $blog_id );
                                        self::single_deactivate();

                                }

                                restore_current_blog();

                        } else {
                                self::single_deactivate();
                        }

                } else {
                        self::single_deactivate();
                }

        }

        /**
         * Fired when a new site is activated with a WPMU environment.
         *
         * @since    1.0.0
         *
         * @param    int    $blog_id    ID of the new blog.
         */
        public function activate_new_site( $blog_id ) {

                if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
                        return;
                }

                switch_to_blog( $blog_id );
                self::single_activate();
                restore_current_blog();

        }

        /**
         * Get all blog ids of blogs in the current network that are:
         * - not archived
         * - not spam
         * - not deleted
         *
         * @since    1.0.0
         *
         * @return   array|false    The blog ids, false if no matches.
         */
        private static function get_blog_ids() {

                global $wpdb;

                // get an array of blog ids
                $sql = "SELWPEFI blog_id FROM $wpdb->blogs
                        WHERE archived = '0' AND spam = '0'
                        AND deleted = '0'";

                return $wpdb->get_col( $sql );

        }

        /**
         * Fired for each blog when the plugin is activated.
         *
         * @since    1.0.0
         */
        private static function single_activate() {
        		if ( ! function_exists ('register_post_status') ){
					deactivate_plugins (basename (dirname (__FILE__)) . '/' . basename (__FILE__));
					wp_die( __( "This plugin requires WordPress 3.0 or newer. Please update your WordPress installation to activate this plugin.", $this->WPB_PREFIX ) );
				}
				do_action( 'wpefi_deactivate' );
        }

        /**
         * Fired for each blog when the plugin is deactivated.
         *
         * @since    1.0.0
         */
        private static function single_deactivate() {
                // @TODO: Define deactivation functionality here
                
                do_action( 'wpefi_activate' );
        }

        /**
         * Load the plugin text domain for translation.
         *
         * @since    1.0.0
         */
        public function load_plugin_textdomain() {

                $domain = $this->plugin_slug;
                $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

                load_textdomain( $domain, $domain . '/languages/' . $domain . '-' . $locale . '.mo' );
                load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

        }

        /**
         * Register and enqueue public-facing style sheet.
         *
         * @since    1.0.0
         */
        public function enqueue_styles() {
                wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
        }

        /**
         * Register and enqueues public-facing JavaScript files.
         *
         * @since    1.0.0
         */
        public function enqueue_scripts() {
                wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
        }

        /**
         * NOTE:  Actions are points in the execution of a page or process
         *        lifecycle that WordPress fires.
         *
         *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
         *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
         *
         * @since    1.0.0
         */
        public function action_method_name() {
                // @TODO: Define your action hook callback here
        }

        /**
         * NOTE:  Filters are points of execution in which WordPress modifies data
         *        before saving it or sending it to the browser.
         *
         *        Filters: http://codex.wordpress.org/Plugin_API#Filters
         *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
         *
         * @since    1.0.0
         */
        public function filter_method_name() {
                // @TODO: Define your filter hook callback here
        }

}