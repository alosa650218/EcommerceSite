<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    ipanorama
 * @subpackage ipanorama/includes
 */
class iPanorama {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that powe the plugin.
	 *
	 * @since 1.0.0
	 */
	protected $loader;
	
	/**
	 * The unique identifier of this plugin. Case sensitive. Use lowercase for the name.
	 *
	 * @since 1.0.0
	 */
	protected $plugin_name;
	
	/**
	 * The current version of the plugin.
	 *
	 * @since 1.0.0
	 */
	protected $version;
	
	/**
	 * The post type for the plugin
	 *
	 * @since 1.0.0
	 */
	protected $post_type;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'ipanorama';
		$this->version = '1.3.5';
		$this->post_type = 'ipnrm_item';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - iPanorama_Loader. Orchestrates the hooks of the plugin.
	 * - iPanorama_i18n. Defines internationalization functionality.
	 * - iPanorama_Admin. Defines all hooks for the admin area.
	 * - iPanorama_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks with WordPress.
	 *
	 * @since 1.0.0
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ipanorama-loader.php'; // the class responsible for orchestrating the actions and filters of the core plugin
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ipanorama-i18n.php'; // the class responsible for defining internationalization functionality of the plugin
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ipanorama-admin.php'; // the class responsible for defining all actions that occur in the admin area
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ipanorama-public.php'; // the class responsible for defining all actions that occur in the public-facing side of the site

		$this->loader = new iPanorama_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the iPanorama_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since 1.0.0
	 */
	private function set_locale() {
		$plugin_i18n = new iPanorama_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since 1.0.0
	 */
	private function define_admin_hooks() {
		if (is_admin()) {
			$plugin_admin = new iPanorama_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_post_type() );
			
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
			$this->loader->add_action( 'admin_head', $plugin_admin, 'hide_minor_publishing' );
			
			// add documentation link to the plugin
			$plugin_basename = plugin_basename( plugin_dir_path( dirname( __FILE__ ) ) . $this->plugin_name . '.php' );
			$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );
			
			// add menu item
			$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init' );
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
			
			// register custom post type
			$this->loader->add_action( 'init', $plugin_admin, 'add_plugin_custom_post_type' );
			
			// ajax
			$this->loader->add_action( 'wp_ajax_' . $this->get_post_type() . '_global_settings', $plugin_admin, 'update_global_settings' );
			//$this->loader->add_action( 'wp_ajax_nopriv_' . $this->get_post_type() . '_global_settings', $plugin_admin, 'update_global_settings' );
			
			// post settings
			$this->loader->add_action( 'save_post_' . $this->get_post_type(), $plugin_admin, 'save_post' );
			$this->loader->add_filter( 'post_updated_messages', $plugin_admin, 'post_updated_messages' );
			$this->loader->add_filter( 'post_row_actions', $plugin_admin, 'remove_quick_edit');
			$this->loader->add_filter( 'manage_edit-' . $this->post_type . '_columns', $plugin_admin, 'manage_post_columns' );
			$this->loader->add_filter( 'manage_' . $this->post_type . '_posts_custom_column', $plugin_admin, 'manage_posts_custom_column' );
			$this->loader->add_filter( 'views_edit-' . $this->post_type, $plugin_admin, 'view_edit' );
			$this->loader->add_filter( 'wp_insert_post_data', $plugin_admin, 'insert_post_data', 99, 2 );
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since 1.0.0
	 */
	private function define_public_hooks() {
		$plugin_public = new iPanorama_Public( $this->get_plugin_name(), $this->get_version(), $this->get_post_type() );
		
		$this->loader->add_action( 'init', $plugin_public, 'register_scripts');
		$this->loader->add_action( 'wp_footer', $plugin_public, 'print_scripts');
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		
		$plugin_public->public_init();
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since 1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}
	
	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since 1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since 1.0.0
	 */
	public function get_version() {
		return $this->version;
	}
	
	/**
	 * Retrieve the post type of the plugin.
	 *
	 * @since 1.0.0
	 */
	public function get_post_type() {
		return $this->post_type;
	}
}
