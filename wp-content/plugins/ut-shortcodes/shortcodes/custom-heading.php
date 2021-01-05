<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'UT_Custom_Heading' ) ) {
	
    class UT_Custom_Heading {
        
        private $shortcode;
            
        function __construct() {
			
            /* shortcode base */
            $this->shortcode = 'ut_custom_heading';
            
            add_action( 'init', array( $this, 'ut_map_shortcode' ) );
            
            if( function_exists('vc_add_params') ) {
                vc_add_params( $this->shortcode, _vc_add_animation_settings() );
            }
            
            add_shortcode( $this->shortcode, array( $this, 'ut_create_shortcode' ) );
            
		}
        
        function ut_map_shortcode( $atts, $content = NULL ) {
            
            if( function_exists( 'vc_map' ) ) {
                                
                vc_map(
                    array(
                        'name'            => esc_html__( 'Custom Text Module', 'ut_shortcodes' ),
						'description'     => esc_html__( 'A block of text with advanced styling and font settings.', 'ut_shortcodes' ),
                        'base'            => $this->shortcode,
                        // 'icon'         => 'fa fa-font ut-vc-module-icon',
                        'icon'            => UT_SHORTCODES_URL . '/admin/img/vc_icons/custom-heading.png',
                        'category'        => 'Structual',
                        'class'           => 'ut-vc-icon-module ut-structual-module',
                        'content_element' => true,
                        'params'          => array(
                            
                            array(
                                'type'          => 'dropdown',
                                'heading'       => esc_html__( 'Element Tag', 'unitedthemes' ),
                                'param_name'    => 'tag',
                                'group'         => 'General',
                                'value'         =>  array(
                                    esc_html__( 'h1', 'ut_shortcodes' )  => 'h1',
                                    esc_html__( 'h2', 'ut_shortcodes' )  => 'h2',
                                    esc_html__( 'h3', 'ut_shortcodes' )  => 'h3',
                                    esc_html__( 'h4', 'ut_shortcodes' )  => 'h4',
                                    esc_html__( 'h5', 'ut_shortcodes' )  => 'h5',
                                    esc_html__( 'h6', 'ut_shortcodes' )  => 'h6',
                                    esc_html__( 'p', 'ut_shortcodes' )   => 'p',
                                    esc_html__( 'div', 'ut_shortcodes' ) => 'div',
                                )                                
                            ), 
                            
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Alignment', 'ut_shortcodes' ),
								'param_name'        => 'align',
								'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'left'  , 'ut_shortcodes' ) => 'left',
                                    esc_html__( 'center', 'ut_shortcodes' ) => 'center',
                                    esc_html__( 'right' , 'ut_shortcodes' ) => 'right',
                                ),
						  	),
                            
                            array(
                                'type'          => 'textarea',
                                'heading'       => esc_html__( 'Text', 'ut_shortcodes' ),
                                'param_name'    => 'content',
                                'group'         => 'General',
                                'value'         => esc_html__( 'The Power of Brooklyn!', 'ut_shortcodes' ),
                                'admin_label'   => true,

                            ),
                            
                            array(
                                'type'          => 'vc_link',
                                'heading'       => esc_html__( 'URL (Link)', 'ut_shortcodes' ),
                                'param_name'    => 'link',
                                'group'         => 'General',
                                'description'   => esc_html__( 'Add link to custom heading.', 'ut_shortcodes' ),
                            ),
                            
                            array(
								'type'              => 'gradient_picker',
								'heading'           => esc_html__( 'Color', 'ut_shortcodes' ),
								'param_name'        => 'color',
                                'edit_field_class'  => 'vc_col-sm-6',
								'group'             => 'General'
						  	),
                            
                            array(
								'type'              => 'gradient_picker',
								'heading'           => esc_html__( 'Hover Color', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Only available if link has been set.', 'ut_shortcodes' ),
								'param_name'        => 'hover_color',
                                'edit_field_class'  => 'vc_col-sm-6',
								'group'             => 'General'
						  	),
                            
                            array(
								'type'              => 'textfield',
								'heading'           => esc_html__( 'CSS Class', 'ut_shortcodes' ),
								'description'       => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'ut_shortcodes' ),
                                'param_name'        => 'class',
                                'group'             => 'General',
                                								
						  	), 
                            
                            // Border Settings
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Add Custom Border?', 'unitedthemes' ),
                                'param_name'        => 'custom_border',
                                'group'             => 'Border Settings',
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'no',
                                    esc_html__( 'yes', 'ut_shortcodes' ) => 'yes'                                    
                                )                                
                            ),
                            
                            array(
                                'type'              => 'checkbox',
                                'heading'           => esc_html__( 'Show Advanced Settings', 'unitedthemes' ),
                                'param_name'        => 'custom_border_advanced',
                                'group'             => 'Border Settings',
                                'value'             => 'false',
                                'dependency'        => array(
                                    'element' => 'custom_border',
                                    'value'   => 'yes',
                                ),
                            ),
                            
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Activate Border Top?', 'unitedthemes' ),
                                'param_name'        => 'custom_border_top',
                                'edit_field_class'  => 'vc_col-sm-3',
                                'group'             => 'Border Settings',
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'no',
                                    esc_html__( 'yes', 'ut_shortcodes' ) => 'yes'                                    
                                ) ,
                                'dependency'        => array(
                                    'element' => 'custom_border',
                                    'value'   => 'yes',
                                ),
                            ),
                            
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Activate Border Right?', 'unitedthemes' ),
                                'param_name'        => 'custom_border_right',
                                'edit_field_class'  => 'vc_col-sm-3',
                                'group'             => 'Border Settings',
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'no',
                                    esc_html__( 'yes', 'ut_shortcodes' ) => 'yes'                                    
                                ),
                                'dependency'        => array(
                                    'element' => 'custom_border',
                                    'value'   => 'yes',
                                ),
                            ),
                            
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Activate Border Bottom?', 'unitedthemes' ),
                                'param_name'        => 'custom_border_bottom',
                                'edit_field_class'  => 'vc_col-sm-3',
                                'group'             => 'Border Settings',
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'no',
                                    esc_html__( 'yes', 'ut_shortcodes' ) => 'yes'                                    
                                ),
                                'dependency'        => array(
                                    'element' => 'custom_border',
                                    'value'   => 'yes',
                                ),
                            ),                            
                            
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Activate Border Left?', 'unitedthemes' ),
                                'param_name'        => 'custom_border_left',
                                'edit_field_class'  => 'vc_col-sm-3',
                                'group'             => 'Border Settings',
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'no',
                                    esc_html__( 'yes', 'ut_shortcodes' ) => 'yes'                                    
                                ),
                                'dependency'        => array(
                                    'element' => 'custom_border',
                                    'value'   => 'yes',
                                ),
                            ),
                            
                            // Border Advanced Settings
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Border Top Color', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_top_color',
                                'edit_field_class'  => 'vc_col-sm-3',
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Border Right Color', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_right_color',
                                'edit_field_class'  => 'vc_col-sm-3',
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Border Bottom Color', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_bottom_color',
                                'edit_field_class'  => 'vc_col-sm-3',
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                        
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Border Left Color', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_left_color',
                                'edit_field_class'  => 'vc_col-sm-3',
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),

                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Border Top Style', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_top_style',
                                'edit_field_class'  => 'vc_col-sm-3',
								'group'             => 'Border Settings',
                                'value'             => array(
                                    esc_html__( 'solid' , 'ut_shortcodes' ) => 'solid',
                                    esc_html__( 'dotted', 'ut_shortcodes' ) => 'dotted',
                                    esc_html__( 'dashed', 'ut_shortcodes' ) => 'dashed',
                                    esc_html__( 'double', 'ut_shortcodes' ) => 'double',
                                ),
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Border Right Style', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_right_style',
                                'edit_field_class'  => 'vc_col-sm-3',
								'group'             => 'Border Settings',
                                'value'             => array(
                                    esc_html__( 'solid' , 'ut_shortcodes' ) => 'solid',
                                    esc_html__( 'dotted', 'ut_shortcodes' ) => 'dotted',
                                    esc_html__( 'dashed', 'ut_shortcodes' ) => 'dashed',
                                    esc_html__( 'double', 'ut_shortcodes' ) => 'double',
                                ),
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Border Bottom Style', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_bottom_style',
                                'edit_field_class'  => 'vc_col-sm-3',
								'group'             => 'Border Settings',
                                'value'             => array(
                                    esc_html__( 'solid' , 'ut_shortcodes' ) => 'solid',
                                    esc_html__( 'dotted', 'ut_shortcodes' ) => 'dotted',
                                    esc_html__( 'dashed', 'ut_shortcodes' ) => 'dashed',
                                    esc_html__( 'double', 'ut_shortcodes' ) => 'double',
                                ),
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Border Left Style', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_left_style',
                                'edit_field_class'  => 'vc_col-sm-3',
								'group'             => 'Border Settings',
                                'value'             => array(
                                    esc_html__( 'solid' , 'ut_shortcodes' ) => 'solid',
                                    esc_html__( 'dotted', 'ut_shortcodes' ) => 'dotted',
                                    esc_html__( 'dashed', 'ut_shortcodes' ) => 'dashed',
                                    esc_html__( 'double', 'ut_shortcodes' ) => 'double',
                                ),
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                                                        
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Top Width', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_top_width',
                                'edit_field_class'  => 'vc_col-sm-3',
                                'value'             => array(
                                    'default' => '1',
                                    'min'     => '0',
                                    'max'     => '50',
                                    'step'    => '1',
                                    'unit'    => 'px'
                                ),
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Right Width', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_right_width',
                                'edit_field_class'  => 'vc_col-sm-3',
                                'value'             => array(
                                    'default' => '1',
                                    'min'     => '0',
                                    'max'     => '50',
                                    'step'    => '1',
                                    'unit'    => 'px'
                                ),
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Bottom Width', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_bottom_width',
                                'edit_field_class'  => 'vc_col-sm-3',
                                'value'             => array(
                                    'default' => '1',
                                    'min'     => '0',
                                    'max'     => '50',
                                    'step'    => '1',
                                    'unit'    => 'px'
                                ),
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Left Width', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_left_width',
                                'edit_field_class'  => 'vc_col-sm-3',
                                'value'             => array(
                                    'default' => '1',
                                    'min'     => '0',
                                    'max'     => '50',
                                    'step'    => '1',
                                    'unit'    => 'px'
                                ),
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Top Padding', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_top_spacing',
                                'edit_field_class'  => 'vc_col-sm-3',
                                'value'             => array(
                                    'default' => '0',
                                    'min'     => '0',
                                    'max'     => '100',
                                    'step'    => '1',
                                    'unit'    => 'px'
                                ),
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Right Padding', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_right_spacing',
                                'edit_field_class'  => 'vc_col-sm-3',
                                'value'             => array(
                                    'default' => '0',
                                    'min'     => '0',
                                    'max'     => '100',
                                    'step'    => '1',
                                    'unit'    => 'px'
                                ),
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Bottom Padding', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_bottom_spacing',
                                'edit_field_class'  => 'vc_col-sm-3',
                                'value'             => array(
                                    'default' => '0',
                                    'min'     => '0',
                                    'max'     => '100',
                                    'step'    => '1',
                                    'unit'    => 'px'
                                ),
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Left Padding', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_left_spacing',
                                'edit_field_class'  => 'vc_col-sm-3',
                                'value'             => array(
                                    'default' => '0',
                                    'min'     => '0',
                                    'max'     => '100',
                                    'step'    => '1',
                                    'unit'    => 'px'
                                ),
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Top Left Radius', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_top_left_radius',
                                'edit_field_class'  => 'vc_col-sm-3',
                                'value'             => array(
                                    'default' => '0',
                                    'min'     => '0',
                                    'max'     => '50',
                                    'step'    => '1',
                                    'unit'    => '%'
                                ),
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Top Right Radius', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_top_right_radius',
                                'edit_field_class'  => 'vc_col-sm-3',
                                'value'             => array(
                                    'default' => '0',
                                    'min'     => '0',
                                    'max'     => '50',
                                    'step'    => '1',
                                    'unit'    => '%'
                                ),
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Bottom Right Radius', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_bottom_right_radius',
                                'edit_field_class'  => 'vc_col-sm-3',
                                'value'             => array(
                                    'default' => '0',
                                    'min'     => '0',
                                    'max'     => '50',
                                    'step'    => '1',
                                    'unit'    => '%'
                                ),
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Bottom Left Radius', 'ut_shortcodes' ),
                                'param_name'        => 'custom_border_bottom_left_radius',
                                'edit_field_class'  => 'vc_col-sm-3',
                                'value'             => array(
                                    'default' => '0',
                                    'min'     => '0',
                                    'max'     => '50',
                                    'step'    => '1',
                                    'unit'    => '%'
                                ),
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value' => 'true',
                                ),
						  	),
                            
                            // Simple Settings
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Border Color', 'ut_shortcodes' ),
                                'description'       => esc_html__( '', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_color',
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value_not_equal_to' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Border Style', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_style',
								'group'             => 'Border Settings',
                                'value'             => array(
                                    esc_html__( 'solid' , 'ut_shortcodes' ) => 'solid',
                                    esc_html__( 'dotted', 'ut_shortcodes' ) => 'dotted',
                                    esc_html__( 'dashed', 'ut_shortcodes' ) => 'dashed',
                                    esc_html__( 'double', 'ut_shortcodes' ) => 'double',
                                ),
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value_not_equal_to' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Width', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_width',
                                'value'             => array(
                                    'default' => '1',
                                    'min'     => '0',
                                    'max'     => '50',
                                    'step'    => '1',
                                    'unit'    => 'px'
                                ),
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value_not_equal_to' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Radius', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_radius',
                                'value'             => array(
                                    'default' => '0',
                                    'min'     => '0',
                                    'max'     => '50',
                                    'step'    => '1',
                                    'unit'    => '%'
                                ),
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value_not_equal_to' => 'true',
                                ),
						  	),
                            
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Spacing', 'ut_shortcodes' ),
								'param_name'        => 'custom_border_spacing',
                                'value'             => array(
                                    'default' => '0',
                                    'min'     => '0',
                                    'max'     => '100',
                                    'step'    => '1',
                                    'unit'    => 'px'
                                ),
								'group'             => 'Border Settings',
                                'dependency'        => array(
                                    'element' => 'custom_border_advanced',
                                    'value_not_equal_to' => 'true',
                                ),
						  	),
                                                       
                            
                            // Title Font Settings
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Choose Font Source', 'ut_shortcodes' ),
                                'param_name'        => 'font_source',
                                'group'             => 'Font Settings',
                                'value'             => array(
                                    esc_html__( 'Theme Default', 'ut_shortcodes' )  => 'default',
                                    esc_html__( 'Web Safe Fonts', 'ut_shortcodes' ) => 'websafe',
                                    esc_html__( 'Google Font', 'ut_shortcodes' )    => 'google',
									esc_html__( 'Custom Font', 'ut_shortcodes' ) => 'custom',
                                ),                                                                
                            ),
                            array(
                                'type'              => 'google_fonts',
                                'param_name'        => 'google_fonts',
                                'value'             => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
                                'group'             => 'Font Settings',
                                'settings'          => array(
                                    'fields' => array(
                                        'font_family_description' => __( 'Select font family.', 'ut_shortcodes' ),
                                        'font_style_description'  => __( 'Select font styling.', 'ut_shortcodes' ),
                                    ),
                                ),
                                'dependency'        => array(
                                    'element'           => 'font_source',
                                    'value'             => 'google',
                                ),
                            ),
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Websafe Fonts', 'ut_shortcodes' ),
                                'param_name'        => 'websafe_fonts',
                                'group'             => 'Font Settings',
                                'value'             => array(
                                    esc_html__( 'Arial', 'unite' )              => 'arial',
                                    esc_html__( 'Comic Sans', 'unite' )         => 'comic',
                                    esc_html__( 'Georgia', 'unite' )            => 'georgia',
                                    esc_html__( 'Helvetica', 'unite' )          => 'helvetica',
                                    esc_html__( 'Impact', 'unite' )             => 'impact',
                                    esc_html__( 'Lucida Sans', 'unite' )        => 'lucida_sans',
                                    esc_html__( 'Lucida Console', 'unite' )     => 'lucida_console',                                    
                                    esc_html__( 'Palatino', 'unite' )           => 'palatino',
                                    esc_html__( 'Tahoma', 'unite' )             => 'tahoma',
                                    esc_html__( 'Times New Roman', 'unite' )    => 'times',
                                    esc_html__( 'Trebuchet', 'unite' )          => 'trebuchet',
                                    esc_html__( 'Verdana', 'unite' )            => 'verdana'                            
                                ),
                                'dependency'        => array(
                                    'element'           => 'font_source',
                                    'value'             => 'websafe',
                                ),
                                
                            ),
							array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Custom Fonts', 'ut_shortcodes' ),
                                'param_name'        => 'custom_fonts',
                                'group'             => 'Font Settings',
                                'value'             => ut_get_custom_fonts(),
                                'dependency'        => array(
                                    'element'           => 'font_source',
                                    'value'             => 'custom',
                                ),
                                
                            ),
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Font Size', 'ut_shortcodes' ),
								'param_name'        => 'font_size',
                                'group'             => 'Font Settings',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    'min'   => '8',
                                    'max'   => '200',
                                    'step'  => '1',
                                    'unit'  => 'px'
                                ),								
						  	),
							array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Line_height', 'ut_shortcodes' ),
								'param_name'        => 'line_height',
                                'group'             => 'Font Settings',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    'default'   => '69',
                                    'min'       => '69',
                                    'max'       => '300',
                                    'step'      => '1',
                                    'unit'      => '%',
									'global'	=> '69', // according to "min"
                                ),
								
						  	),
							array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Letter Spacing', 'ut_shortcodes' ),
								'param_name'        => 'letter_spacing',
                                'group'             => 'Font Settings',
                                    'value'             => array(
                                    'default'   => '0',
                                    'min'       => '-0.2',
                                    'max'       => '0.2',
                                    'step'      => '0.01',
                                    'unit'      => 'em'
                                ),								
						  	),
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Title Text Transform', 'ut_shortcodes' ),
                                'description'       => esc_html__( '(optional)' , 'ut_shortcodes' ),
								'param_name'        => 'text_transform',
								'group'             => 'Font Settings',
                                'value'             => array(
                                    esc_html__( 'Select Text Transform' , 'ut_shortcodes' ) => '',
									esc_html__( 'none' , 'ut_shortcodes' ) 					=> 'none',
                                    esc_html__( 'capitalize' , 'ut_shortcodes' )            => 'capitalize',
                                    esc_html__( 'uppercase', 'ut_shortcodes' )              => 'uppercase',
                                    esc_html__( 'lowercase', 'ut_shortcodes' )              => 'lowercase'                                    
                                ),
						  	),
                            
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Font Weight', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Please keep in mind, that the selected font needs to support the font weight.', 'ut_shortcodes' ),
								'param_name'        => 'font_weight',
								'group'             => 'Font Settings',
                                'value'             => array(
                                    esc_html__( 'Select Font Weight' , 'ut_shortcodes' ),
                                    'lighter',
                                    'normal',
                                    'bold',
                                    'bolder',
                                    100,
                                    200,
                                    300,
                                    400,
                                    500,
                                    600,
                                    700,
                                    800,
                                    900,
                                ),
                                'dependency'        => array(
                                    'element'           => 'font_source',
                                    'value'             => array('websafe','default'),
                                ),                                
						  	),
                            
                            // CSS Editor
                            array(
                                'type'              => 'css_editor',
                                'param_name'        => 'css',
                                'group'             => esc_html__( 'Design Options', 'ut_shortcodes' ),
                            ),
                            
                        )                        
                        
                    )
                
                ); // end mapping
                
            } 
        
        }
        
        function ut_create_shortcode( $atts, $content = NULL ) {
            
            extract( shortcode_atts( array (
                
                // General Settigs
                'tag'            => 'h1',
                'align'          => 'left',
                'link'           => '',
                'color'          => '',
                'hover_color'    => '',
                
                // Border Settings
                'custom_border'          => 'no',
                'custom_border_advanced' => 'false',
                
                // Border Status
                'custom_border_top'     => 'no',
                'custom_border_left'    => 'no',
                'custom_border_bottom'  => 'no',
                'custom_border_right'   => 'no',
                
                // Border Simple Settings
                'custom_border_color'   => '',
                'custom_border_style'   => 'solid', 
                'custom_border_width'   => '1',
                'custom_border_radius'  => '',
                'custom_border_spacing' => '',
                
                // Border Advanced Settings
                'custom_border_top_color'           => '',
                'custom_border_top_style'           => 'solid',
                'custom_border_top_width'           => '1',
                'custom_border_top_left_radius'     => '',
                'custom_border_top_spacing'         => '',
                
                'custom_border_right_color'         => '',
                'custom_border_right_style'         => 'solid',
                'custom_border_right_width'         => '1',
                'custom_border_top_right_radius'    => '',
                'custom_border_right_spacing'       => '',
                
                'custom_border_bottom_color'        => '',
                'custom_border_bottom_style'        => 'solid',
                'custom_border_bottom_width'        => '1',
                'custom_border_bottom_right_radius' => '',
                'custom_border_bottom_spacing'      => '',
                
                'custom_border_left_color'          => '',
                'custom_border_left_style'          => 'solid',
                'custom_border_left_width'          => '1',
                'custom_border_bottom_left_radius'  => '',
                'custom_border_left_spacing'        => '',
                
                // Font Settings
                'font_size'      => '',
                'font_weight'    => '',
                'line_height'    => '',
				'letter_spacing' => '',
                'text_transform' => '',
                'font_source'    => 'theme',
                'google_fonts'   => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
                'websafe_fonts'  => '',
				'custom_fonts'	 => '',
                
                // Animation 
                'effect'                    => '',     
                'animate_once'              => 'yes',
                'animate_tablet'            => 'no',
                'animate_mobile'            => 'no',
                'delay'                     => 'no',
                'delay_timer'               => '100',
                'animation_duration'        => '',
                'animation_between'         => '',
                
                // Styling
                'css'       => '',
                'class'     => ''    
                
            ), $atts ) );
            
            // class array
            $classes = array();
            
            // add class
            $classes[] = $class;
            
            // animation attributes 
            $attributes = array();   
            
            // animation effect
            $dataeffect = NULL;
            
            if( !empty( $effect ) && $effect != 'none' ) {
                
                $attributes['data-effect']      = esc_attr( $effect );
                $attributes['data-animateonce'] = esc_attr( $animate_once );
                $attributes['data-delay'] = $delay == 'true' ? esc_attr( $delay_timer ) : 0;
                
                if( $animate_once == 'infinite' && !empty( $animation_between ) ) {
                    
                    if( strpos($animation_between, 's') === true ) {
                        $animation_between = str_replace('s' , '', $animation_between);                        
                    }
                    
                    $attributes['data-animation-between'] = esc_attr( $animation_between );
                    
                }
                
                if( !empty( $animation_duration ) ) {
                    
                    $attributes['data-animation-duration'] = esc_attr( ut_add_timer_unit( $animation_duration, 's' ) );
                    
                }                
                
                $classes[]  = 'ut-animate-element';
                $classes[]  = 'animated';
                
                if( $animate_tablet ) {
                    $classes[]  = 'ut-no-animation-tablet';
                }
                
                if( $animate_mobile ) {
                    $classes[]  = 'ut-no-animation-mobile';
                }
                
                if( $animate_once == 'infinite' && empty( $animation_between ) ) {
                    $classes[]  = 'infinite';
                }
                                
            }
            
            $attributes = implode(' ', array_map(
                function ($v, $k) { return sprintf("%s=\"%s\"", $k, $v); },
                $attributes,
                array_keys( $attributes )
            ) );
            
            
            $ut_font_css = false;
            
            /* initialize google font */
            if( $font_source && $font_source == 'google' ) {
                
                 $ut_google_font     = new UT_VC_Google_Fonts( $atts, 'google_fonts', $this->shortcode, $google_fonts );
                 $ut_font_css        = $ut_google_font->get_google_fonts_css_styles();
                        
            }
            
            $ut_font_css = is_array( $ut_font_css ) ? implode( '', $ut_font_css ) : $ut_font_css;
            
            // unique header ID
            $id = uniqid("ut_custom_heading");
            
            $css_style = '';
                
            if( $font_source && $font_source == 'websafe' ) {
                $css_style .= '#' . $id . ' { font-family: ' . get_websafe_font_css_family( $websafe_fonts ) . '; }';                
            }
			
			if( $font_source && $font_source == 'custom' && $custom_fonts ) {
                
				$font_family = get_term($custom_fonts,'unite_custom_fonts');
				
				if( isset( $font_family->name ) )
				$css_style .= '#' . $id . ' { font-family: "' . $font_family->name . '"; }';				
				
            }
			
			if( !$link && $color && ut_is_gradient( $color ) ) {
                    
				$classes[] = 'ut-element-with-gradient';
				$css_style.= ut_create_gradient_css( $color, '#' . $id , false, 'background' );


			} elseif( !$link && $color ) {

				$css_style.= '#' . $id . ' { color:' . $color . '; }';

			}
            
            if( $link ) {
                
				if( $color && ut_is_gradient( $color ) ) {
                    
					$classes[] = 'ut-element-with-gradient-link';
					$css_style.= ut_create_gradient_css( $color, '#' . $id . ' a', false, 'background' );


				} elseif( $color ) {

					$css_style.= '#' . $id . ' a { color:' . $color . '; }';

				}
				
				if( $hover_color && ut_is_gradient( $hover_color ) ) {
                    
					$classes[] = 'ut-element-with-gradient-link';
					$css_style.= ut_create_gradient_css( $hover_color, '#' . $id . ' a:hover', false, 'background' );
					$css_style.= ut_create_gradient_css( $hover_color, '#' . $id . ' a:active', false, 'background' );
					$css_style.= ut_create_gradient_css( $hover_color, '#' . $id . ' a:focus', false, 'background' );


				} elseif( $hover_color ) {

					$css_style.= '#' . $id . ' a:hover { color:' . $hover_color . '; }';
                    $css_style.= '#' . $id . ' a:active { color:' . $hover_color . '; }';
                    $css_style.= '#' . $id . ' a:focus { color:' . $hover_color . '; }';

				}
                
            }
            
            // Font Settings
            if( $ut_font_css ) {
                $css_style.= '#' . $id . ' { ' . $ut_font_css . ' }';
            }
            
            if( $font_size ) {
                $css_style.= '#' . $id . ' { font-size:' . $font_size . 'px; }';
            }
            
            if( $font_weight ) {
                $css_style.= '#' . $id . ' { font-weight:' . $font_weight . '; }';
            }
            
            if( $line_height ) {
                $css_style.= '#' . $id . ' { line-height:' . $line_height . '%; }';
            }
            
			if( $letter_spacing ) {
				
				// fallback letter spacing
				if( $letter_spacing >= 1 || $letter_spacing <= -1 ) {
					$letter_spacing = $letter_spacing / 100;	
				}
				
                $css_style.= '#' . $id . ' { letter-spacing:' . $letter_spacing . 'em; }';
				
            }
			
            if( $align ) {
                $css_style.= '#' . $id . ' { text-align:' . $align . '; }';
            }
            
            if( $text_transform ) {
                $css_style.= '#' . $id . ' { text-transform:' . $text_transform . '; }';
            }
            
            $availabes_borders   = array( 'top', 'right', 'left', 'bottom' );
            $final_border_styles = array();
            
            if( $custom_border == 'yes' )  {
                
                // Simple Border Settings
                if( $custom_border_advanced == 'false' ) {
                    
                    foreach( $availabes_borders as $single_border ) {

                        if( ${'custom_border_' . $single_border } == 'yes' ) {

                            // border style
                            $final_border_styles['border-' . $single_border . '-style'] = $custom_border_style;

                            // border color
                            if( !empty( $custom_border_color ) ) {
                               $final_border_styles['border-' . $single_border . '-color'] = $custom_border_color; 
                            }                    

                            // border width
                            $final_border_styles['border-' . $single_border . '-width'] = $custom_border_width . 'px';
                            
                            // border spacing
                            if( !empty( $custom_border_spacing ) ) {
                                $final_border_styles['padding-' . $single_border] = $custom_border_spacing . 'px';
                            }
                            
                        }

                    }
                    
                    // border radius
                    if( !empty( $custom_border_radius ) ) {
                        $final_border_styles['padding'] = $custom_border_radius . '%';
                    }
                    
                    $final_border_styles['display'] = 'inline-block';
                    
                    // final custom CSS
                    if( !empty( $final_border_styles ) ) {
                        
                        $css_val = '';
                        
                        foreach( $final_border_styles as $key => $item ) {
                            $css_val .= $key . ':' . $item . ';';
                        }
                        
                        rtrim($css_val, ';');
                        
                        $css_style.= '#' . $id . ' span { ' . $css_val . ' }';
                        
                    }
                    
                }
                
                // Advanced Border Settings
                if( $custom_border_advanced == 'true' ) {
                    
                    $border_radius = array(
                        'top'    => 'top_left',
                        'right'  => 'top_right',
                        'bottom' => 'bottom_right',
                        'left'   => 'bottom_left'
                    );
                    
                    foreach( $availabes_borders as $single_border ) {
                        
                        if( ${'custom_border_' . $single_border } == 'yes' ) {
                            
                            // border color
                            if( !empty( ${'custom_border_' . $single_border . '_color' } ) ) {
                                $final_border_styles['border-' . $single_border . '-color'] = ${'custom_border_' . $single_border . '_color' };
                            }
                            
                            // border style 
                            if( !empty( ${'custom_border_' . $single_border . '_style' } ) ) {
                                $final_border_styles['border-' . $single_border . '-style'] = ${'custom_border_' . $single_border . '_style' };
                            }
                            
                            // border width
                            if( !empty( ${'custom_border_' . $single_border . '_width' } ) ) {
                                $final_border_styles['border-' . $single_border . '-width'] = ${'custom_border_' . $single_border . '_width' } . 'px';
                            }
                            
                        }
                        
                        // border radius
                        if( !empty( ${'custom_border_' . $border_radius[$single_border] . '_radius' } ) ) {
                            $final_border_styles['border-' . str_replace( '_', '-', $border_radius[$single_border] ) . '-radius'] = ${'custom_border_' . $border_radius[$single_border] . '_radius'} . '%';
                        }                        
                    
                        // border spacing
                        if( !empty( ${'custom_border_' . $single_border . '_spacing' } ) ) {
                            $final_border_styles['padding-' . $single_border] = ${'custom_border_' . $single_border . '_spacing'} . 'px';
                        }
                        
                        $final_border_styles['display'] = 'inline-block';
                        
                    }
                    
                    // Final Custom CSS
                    if( !empty( $final_border_styles ) ) {
                        
                        $css_val = '';
                        
                        foreach( $final_border_styles as $key => $item ) {
                            $css_val .= $key . ':' . $item . ';';
                        }
                        
                        rtrim( $css_val, ';' );
                        
                        $css_style.= '#' . $id . ' span { ' . $css_val . ' }';
                        
                    }
                    
                }
            
            }
            
            // start output
            $output = '';
            
            // attach CSS
            if( !empty( $css_style ) ) {
                $output .= ut_minify_inline_css( '<style type="text/css" scoped>' . $css_style . '</style>' );            
            }
                        
            $output .= '<' . $tag . ' id="' . $id . '" class="ut-custom-heading-module ' . implode( ' ', array_unique( $classes ) ) . ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->shortcode, $atts ) . '" ' . $attributes . '>';
                
                $output .= '<span>';
            
                if( function_exists('vc_build_link') && $link ) {
                
                    $link = vc_build_link( $link );
                    
                    $target = !empty( $link['target'] ) ? $link['target'] : '_self';
                    $title  = !empty( $link['title'] )  ? $link['title'] : '';
                    $rel    = !empty( $link['rel'] )    ? 'rel="' . esc_attr( trim( $link['rel'] ) ) . '"' : '';
                    $link   = !empty( $link['url'] )    ? $link['url'] : '';
                    
                    if( $link ) {
                    
                        $output .= '<a title="' . esc_attr( $title ) . '" href="' . esc_url( $link ) . '" target="' . esc_attr( $target ) . '" ' . $rel . '>' . $content . '</a>';
                    
                    } else {
                        
                        $output .= $content;
                        
                    }
                    
                } else {
                
                    $output .= $content;
                
                }
            
                $output .= '</span>';
                
            $output .= '</' . $tag . '>';
            
            return $output;
        
        }
            
    }

}

new UT_Custom_Heading;