<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ranksavant.com/
 * @since      1.0.0
 *
 */

/**
 * The admin-specific functionality of the plugin.
 *
 */
class RANKSAVANT_INTEGRATION_Admin {

    /**
     * helper
     *
     * @var mixed
    */
    public $helper;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $helper ) {
        $this->helper = $helper;
	}

    /**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
        if (self::is_ranksavant_integration_page()) {
		    wp_enqueue_style( RANKSAVANT_INTEGRATION_DOMAIN, RANKSAVANT_INTEGRATION_URL . 'admin/css/ranksavant-integration-admin.css', array(), filemtime(RANKSAVANT_INTEGRATION_PATH . 'admin/css/ranksavant-integration-admin.css'), 'all' );
        }
	}

    /**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
        if (self::is_ranksavant_integration_page()) {
		    wp_enqueue_script( RANKSAVANT_INTEGRATION_DOMAIN, RANKSAVANT_INTEGRATION_URL . 'admin/js/ranksavant-integration-admin.js', array( 'jquery' ), filemtime(RANKSAVANT_INTEGRATION_PATH . 'admin/js/ranksavant-integration-admin.js'), false );
        }
	}

    public function admin_menu() {
        add_management_page( 'Rank Savant Tools', 'Rank Savant', 'install_plugins', 'ranksavant', array( $this, 'admin_page' ) );
    }

	public function admin_page() {
        $api_key = RANKSAVANT_INTEGRATION_API::get_key();
        $status = RANKSAVANT_INTEGRATION_API::get_status();
        $reason = RANKSAVANT_INTEGRATION_API::get_error_reason();
        $containers = RANKSAVANT_INTEGRATION_API::get_containers_from_db();
		include RANKSAVANT_INTEGRATION_PATH . 'admin/partials/tools.php';
	}

    public function handle_tools() {
        //Clear Cache
        if (
            isset($_POST['nonce-ranksavant-cache']) && 
            wp_verify_nonce(
                sanitize_text_field(
                    wp_unslash(
                        $_POST['nonce-ranksavant-cache']
                    )
                ),
                'ranksavant-cache'
            )   
        ) {
            global $wpdb;
            $db_name = $wpdb->prefix . 'options';
            $results = $wpdb->get_results(
                $wpdb->prepare("SELECT option_name from {$db_name} WHERE option_name LIKE '_transient_ranksavant_container_%'")
            );
            if (count($results)) {
                foreach ($results as $result) {
                    $transient_name = str_replace('_transient_', '', $result->option_name);
                    delete_transient($transient_name);
                }
            }
        }

        //Save key
        if (isset($_POST['nonce-ranksavant-api-key']) && 
            isset($_POST['ranksavant-api-key']) &&
            wp_verify_nonce(
                sanitize_text_field(
                    wp_unslash(
                        $_POST['nonce-ranksavant-api-key']
                    )
                ), 
                'ranksavant-api-key'
            )
        ){
            $api_key = sanitize_text_field(wp_unslash($_POST['ranksavant-api-key']));
            $old_key = RANKSAVANT_INTEGRATION_API::get_key();
            if ( false === $old_key) {
                $autoload = 'no';
                add_option( 'ranksavant-key', $api_key, '', $autoload );
            } else {
                update_option( 'ranksavant-key', $api_key );
            }
            RANKSAVANT_INTEGRATION_API::get_containers();
        }

        //Syncronise containers
        if (isset($_POST['nonce-ranksavant-containers']) && wp_verify_nonce(sanitize_text_field(
            wp_unslash($_POST['nonce-ranksavant-containers'])), 'ranksavant-get-containers')) {
            RANKSAVANT_INTEGRATION_API::get_containers();
        }
    }

    public function tools_notices() {
        if (
            ( isset($_POST['nonce-ranksavant-cache']) && wp_verify_nonce(sanitize_text_field(
                wp_unslash($_POST['nonce-ranksavant-cache'])), 'ranksavant-cache') )
        ) {
            printf(
                '<div class="notice notice-success is-dismissible"><p>%s</p></div>',
                esc_html__('The memory was successfully purged', 'rank-savant')
            );
        }
    }

    /**
     * is_ranksavant_integration_page
     *
     * @return bool
     */
    public static function is_ranksavant_integration_page(): bool {
        if ( isset($_GET['page']) ) { // phpcs:ignore
            if ( 'ranksavant' === $_GET['page'] ) { // phpcs:ignore
				return true;
			}
        }
        return false;
    }
}
