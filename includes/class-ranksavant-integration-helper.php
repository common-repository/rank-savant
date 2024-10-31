<?php

/**
 * RANKSAVANT_INTEGRATION_HELPER
 */
class RANKSAVANT_INTEGRATION_HELPER
{

    function __construct(){
        //
    }

    public function resource_version($file_path){
		$version_of_resource = RANKSAVANT_INTEGRATION_VERSION;
		if( wp_get_environment_type() === 'development') {
			$version_of_resource = filemtime($file_path);
		}
		return $version_of_resource;
	}

    public static function kses_args()
    {
        $kses_defaults = wp_kses_allowed_html( 'post' );
        $svg_args = array(
            'svg'   => array(
                'class'           => true,
                'aria-hidden'     => true,
                'aria-labelledby' => true,
                'role'            => true,
                'xmlns'           => true,
                'width'           => true,
                'height'          => true,
                'viewbox'         => true,
            ),
            'g'     => array( 
                'fill' => true, 
                'filter' => true,
            ),
            'circle' => array(
                'cx' => true, 
                'cy' => true, 
                'r' => true, 
                'fill' => true,
            ),
            'title' => array( 
                'title' => true ,
            ),
            'path'  => array(
                'd'    => true,
                'fill' => true,
                'opacity' => true,
            ),
            'defs' => true,
            'filter' => array(
                'id' => true,
                'x' => true,
                'y' => true,
                'width' => true,
                'height' => true,
                'filterunits' => true,
                'color-interpolation-filters' => true,
            ),
            'feflood' => array(
                'flood-opacity' => true,
                'result' => true,
            ),
            'fecolormatrix' => array(
                'in' => true,
                'type' => true,
                'values' => true,
                'result' => true,
            ),
            'feoffset' => array(
                'dy' => true,
            ),
            'fegaussianblur' => array(
                'stddeviation' => true,
            ),
            'feblend' => array(
                'mode' => true,
                'in' => true,
                'in2' => true,
                'result' => true,
            ),
        );
        return array_merge( $kses_defaults, $svg_args );
    }
}