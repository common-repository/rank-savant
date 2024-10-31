<?php

class RANKSAVANT_ELEMENTOR{

    public function widget_manager($widgets_manager)
    {
        require_once RANKSAVANT_INTEGRATION_PATH . 'includes/integrations/elementor/widgets/class-ranksavant-elementor-widget.php';
        $widgets_manager->register( new \Ranksavant_Elementor_Widget() );
    }

}