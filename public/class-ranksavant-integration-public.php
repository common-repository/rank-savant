<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ranksavant.getrocketship.com
 * @since      1.0.0
 *
 */

/**
 * The public-facing functionality of the plugin.
 *
 */
class RANKSAVANT_INTEGRATION_Public {

    /**
     * helper
     *
     * @var mixed
    */
    public $helper;

    public $transient_time;

	/**
	 * Initialize the class and set its properties.
	 *
	 */
	public function __construct($helper ) {

		$this->helper = $helper;
        $this->transient_time = 6 * HOUR_IN_SECONDS;

	}

    public function safe_style_css($styles)
    {
        $styles[] = 'display';
        return $styles;
    }

    public function enqueue()
    {
        wp_enqueue_style( 
            'ranksavant-main', 
            RANKSAVANT_INTEGRATION_URL . 'public/css/ranksavant-custom.min.css', 
            [], 
            $this->helper->resource_version(RANKSAVANT_INTEGRATION_PATH . 'public/css/ranksavant-custom.min.css')
        );

        wp_enqueue_script( 
            'ranksavant-main', 
            RANKSAVANT_INTEGRATION_URL . 'public/js/ranksavant-custom.min.js', 
            [], 
            $this->helper->resource_version(RANKSAVANT_INTEGRATION_PATH . 'public/js/ranksavant-custom.min.js'),
            true
        );

    }

    public function load_shortcode(){
        add_shortcode( 'ranksavant-integration', array($this, 'handle_shortcode') );
    }

    public function handle_shortcode($atts)
    {
        $attributes = shortcode_atts( array(
            'container' => false
        ), $atts );
        $this->enqueue();
        ob_start();
        if($attributes['container']){
            $local_stored_data = get_transient( 'ranksavant_container_' . $attributes['container'] );
            if(false === $local_stored_data){
                $local_stored_data = $data_received = RANKSAVANT_INTEGRATION_API::get_container($attributes['container']);
                if('' === $data_received) return ob_get_clean();
                set_transient( 'ranksavant_container_' . $attributes['container'], $data_received, $this->transient_time );
            }
            $container_data = json_decode($local_stored_data, true);
            if(isset($container_data['status']) && true === $container_data['status'])
            {
                if(isset( $container_data['content'])):
                    echo wp_kses($container_data['content']['html'], RANKSAVANT_INTEGRATION_HELPER::kses_args());
                endif;
            }
        }

        return ob_get_clean();
    }
}
