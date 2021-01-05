<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'UT_C7_Shortcode' ) ) {
	
    class UT_C7_Shortcode {
        
        private $shortcode;
            
        function __construct() {
			
            /* shortcode base */
            $this->shortcode = 'ut_c7_shortcode';
            
            add_action( 'init', array( $this, 'ut_map_shortcode' ) );
            add_shortcode( $this->shortcode, array( $this, 'ut_create_shortcode' ) );	
            
		}
        
        function ut_map_shortcode( $atts, $content = NULL ) {
            
            if( function_exists( 'vc_map' ) ) {
                                
                vc_map(
                    array(
                        'name'            => esc_html__( 'Contact Form 7', 'ut_shortcodes' ),
                        'description'     => esc_html__( 'Load your desired contact form directly into the site.', 'ut_shortcodes' ),
						'base'            => $this->shortcode,
                        //'icon'            => 'fa fa-envelope ut-vc-module-icon',
                        'icon'            => UT_SHORTCODES_URL . '/admin/img/vc_icons/contact-form-7.png',
                        'category'        => 'Plugins',
                        'class'           => 'ut-vc-icon-module ut-plugin-module',
                        'content_element' => true,
                        'params'          => array(
                            
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Select Contact Form 7', 'ut_shortcodes' ),
								'param_name'        => 'form_id',
								'group'             => 'General',
                                'value'             => ut_get_c7_forms()
						  	),
                            
                            array(
                                'type'              => 'css_editor',
                                'param_name'        => 'css',
                                'group'             => esc_html__( 'Design Options', 'ut_shortcodes' ),
                            )
                            
                        )                        
                        
                    )
                
                ); // end mapping
                
            } 
        
        }
        
        function ut_create_shortcode( $atts ) {
                
            extract( shortcode_atts( array (
                'form_id'   => '',
                'css'       => ''
            ), $atts ) ); 
            
            if( !$form_id ) {                
                return '<div class="wpb_content_element ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->shortcode, $atts ) . '">' . esc_html__( 'No Contact Form 7 selected!', 'ut_shortcodes' ) . '</div>';                
            }
            
            if( defined( 'WPB_VC_VERSION' ) ) {                 
                return '<div class="wpb_content_element ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->shortcode, $atts ) . '">' . do_shortcode( '[contact-form-7 id="' . esc_attr( $form_id ) . '"]' ) . '</div>';             
            }  
            
            /* start output */
            return do_shortcode( $content );
        
        }
            
    }

}

new UT_C7_Shortcode;