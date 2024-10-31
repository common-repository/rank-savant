<?php

/**
 * The file that defines the core plugin class
 *
 * @link       https://ranksavant.getrocketship.com
 * @since      1.0.0
 *
*/

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
 */
class RANKSAVANT_INTEGRATION {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      RANKSAVANT_INTEGRATION_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

    public $helper;


	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->load_dependencies();
		$this->set_locale();
        $this->helper = new RANKSAVANT_INTEGRATION_HELPER();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_integrations_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

        /**
		 * The class responsible with commom functions
		 */
        require_once RANKSAVANT_INTEGRATION_PATH . 'includes/class-ranksavant-integration-helper.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once RANKSAVANT_INTEGRATION_PATH . 'includes/class-ranksavant-integration-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once RANKSAVANT_INTEGRATION_PATH . 'includes/class-ranksavant-integration-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once RANKSAVANT_INTEGRATION_PATH . 'admin/class-ranksavant-integration-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once RANKSAVANT_INTEGRATION_PATH . 'public/class-ranksavant-integration-public.php';

        /**
		 * The class responsible for defining all actions related with API calls.
		 */
		require_once RANKSAVANT_INTEGRATION_PATH . 'includes/class-ranksavant-integration-api.php';

        /**
		 * Integrate Elementor
		 */
		require_once RANKSAVANT_INTEGRATION_PATH . 'includes/integrations/elementor/class-ranksavant-elementor.php';

		$this->loader = new RANKSAVANT_INTEGRATION_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new RANKSAVANT_INTEGRATION_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new RANKSAVANT_INTEGRATION_Admin($this->helper);

        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
        $this->loader->add_action( 'init', $plugin_admin, 'handle_tools' );
        $this->loader->add_action( 'admin_notices', $plugin_admin, 'tools_notices' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new RANKSAVANT_INTEGRATION_Public($this->helper);
        $this->loader->add_action( 'init', $plugin_public, 'load_shortcode');
        $this->loader->add_action( 'safe_style_css', $plugin_public, 'safe_style_css');

	}

    /**
	 * Register all of the hooks related to third-party integrations
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_integrations_hooks() {

		$elementor = new RANKSAVANT_ELEMENTOR();
        $this->loader->add_action( 'elementor/widgets/register', $elementor, 'widget_manager');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    RANKSAVANT_INTEGRATION_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

}
