<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'UT_Divider' ) ) {
	
    class UT_Divider {
        
        private $shortcode;
            
        function __construct() {
			
            /* shortcode base */
            $this->shortcode = 'ut_divider';
            
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
                        'name'            => esc_html__( 'Divider Module', 'ut_shortcodes' ),
                        'base'            => $this->shortcode,
                        'icon'            => UT_SHORTCODES_URL . '/admin/img/vc_icons/title-divider.png',
                        'category'        => 'Structual',                        
                        'class'           => 'ut-vc-icon-module ut-structual-module',
                        'params'          => array(
                            
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Divider Style', 'ut_shortcodes' ),
                                'group'             => 'General',
								'param_name'        => 'divider',
                                'admin_label'       => true,
                                'value'             => array(
                                    esc_html__( 'Style 1'  , 'ut_shortcodes' ) => 'bklyn-divider-style-1',
                                    esc_html__( 'Style 2'  , 'ut_shortcodes' ) => 'bklyn-divider-style-2',
                                    esc_html__( 'Style 3'  , 'ut_shortcodes' ) => 'bklyn-divider-style-3',
                                    esc_html__( 'Style 4'  , 'ut_shortcodes' ) => 'bklyn-divider-style-4',
                                    esc_html__( 'Style 5'  , 'ut_shortcodes' ) => 'bklyn-divider-style-5',
                                    esc_html__( 'Style 6'  , 'ut_shortcodes' ) => 'bklyn-divider-style-6',
                                    esc_html__( 'Style 7'  , 'ut_shortcodes' ) => 'bklyn-divider-style-7',
                                    esc_html__( 'Style 8'  , 'ut_shortcodes' ) => 'bklyn-divider-style-8',
                                    esc_html__( 'Style 9'  , 'ut_shortcodes' ) => 'bklyn-divider-style-9',
                                    esc_html__( 'Style 10' , 'ut_shortcodes' ) => 'bklyn-divider-style-10',
                                    esc_html__( 'Style 11' , 'ut_shortcodes' ) => 'bklyn-divider-style-11',
                                    esc_html__( 'Style 12' , 'ut_shortcodes' ) => 'bklyn-divider-style-12',
                                    esc_html__( 'Style 13' , 'ut_shortcodes' ) => 'bklyn-divider-style-13',
                                    esc_html__( 'Style 14' , 'ut_shortcodes' ) => 'bklyn-divider-style-14',
                                    esc_html__( 'Style 15' , 'ut_shortcodes' ) => 'bklyn-divider-style-15',
                                    esc_html__( 'Style 16' , 'ut_shortcodes' ) => 'bklyn-divider-style-16',
                                    esc_html__( 'Style 17' , 'ut_shortcodes' ) => 'bklyn-divider-style-17',
                                    esc_html__( 'Style 18' , 'ut_shortcodes' ) => 'bklyn-divider-style-18',
                                    esc_html__( 'Style 19' , 'ut_shortcodes' ) => 'bklyn-divider-style-19',
                                    esc_html__( 'Style 20' , 'ut_shortcodes' ) => 'bklyn-divider-style-20',                                    
                                ),
						  	),
                                                       
                            array(
								'type'              => 'textfield',
								'heading'           => esc_html__( 'CSS Class', 'ut_shortcodes' ),
								'description'       => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'ut_shortcodes' ),
                                'param_name'        => 'class',
								'group'             => 'General'
						  	),
                            
                            /* Icon */
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Add Icon to Divider?', 'ut_shortcodes' ),
                                'param_name'        => 'add_icon',
                                'group'             => 'Icon',
                                'value'             => array(
                                    esc_html__( 'yes'  , 'ut_shortcodes' ) => 'true',
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'false'
                                ),
                            ),
                            array(
								'type'              => 'iconpicker',
                                'heading'           => esc_html__( 'Choose Icon', 'ut_shortcodes' ),
                                'param_name'        => 'icon',
                                'group'             => 'Icon',
                                'dependency'        => array(
                                    'element' => 'add_icon',
                                    'value'   => 'true',
                                )
                            ),
                            array(
								'type'              => 'attach_image',
                                'heading'           => esc_html__( 'or upload an own Icon', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'recommended size 48x48', 'ut_shortcodes' ),
                                'param_name'        => 'imageicon',
                                'group'             => 'Icon',
                                'dependency'        => array(
                                    'element' => 'add_icon',
                                    'value'   => 'true',
                                )                                
                            ),
                            
                            /* divider spacing*/
                            array(
                                'type'              => 'ut_css_editor',
                                'heading'           => esc_html__( 'Spacing', 'ut_shortcodes' ),
                                'param_name'        => 'spacing',
                                'group'             => 'Spacing',
                            ),
                                                        
                        )                        
                        
                    )
                
                ); // end mapping
                
            }
        
        }
        
        function ut_create_shortcode( $atts, $content = NULL ) {
            
            extract( shortcode_atts( array (
                
                'divider'            => 'bklyn-divider-style-1',
                'spacing'            => '',
                
                // Animation
                'effect'             => '',       
                'animate_once'       => 'yes',
                'animate_tablet'     => 'no',
                'animate_mobile'     => 'no',
                'delay_timer'        => '100',
                'animation_duration' => '',
                'animation_between'  => '',
                
                'css'                => '',
                'class'              => ''
                
            ), $atts ) );
            
            /* classes */
            $classes    = array( $class );
            $classes[]  = $divider;
            
            
            /* animation effect */
            $attributes = array();
            
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
                        
            /* start output */
            $output = '';
            
            $output .= '<hr class="bklyn-divider ' . implode(' ', $classes ) . '">';
                        
            return $output;            
            
        }        
            
    }

}

new UT_Divider;