<?php
class Ranksavant_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ranksavant_integration';
	}

    public function get_script_depends() {
		return array();
	}

	public function get_style_depends() {
		return array();
	}

	public function get_title() {
		return esc_html__( 'Rank Savant', 'rank-savant' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	public function get_categories() {
		return array( 'general' );
	}

	public function get_keywords() {
		return array( 'rank', 'savant', 'seo', 'ranksavant', 'local' );
	}

	protected function register_controls() {

		// Content Tab Start
        $containers = RANKSAVANT_INTEGRATION_API::get_containers_from_db();
        $containers_list = [];
        foreach ($containers as $container) {
            $details = maybe_unserialize($container['settings']);
            $containers_list[$container['id']] = $details['internal_title'];
        }
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Container', 'rank-savant' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'ranksavant_container',
			[
				'label' => esc_html__( 'Container', 'rank-savant' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => $containers_list
			]
		);

		$this->end_controls_section();

		// Content Tab End

		// Style Tab End

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
        if('empty' != $settings['ranksavant_container']){
            echo do_shortcode('[ranksavant-integration container=' . $settings['ranksavant_container'] . ']');
        }
	}
}
