<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'UT_Animated_Image' ) ) {
	
    class UT_Animated_Image {
        
        private $shortcode;
            
        function __construct() {
			
            /* shortcode base */
            $this->shortcode = 'ut_animated_image';
            
            add_action( 'init', array( $this, 'ut_map_shortcode' ) );
            add_shortcode( $this->shortcode, array( $this, 'ut_create_shortcode' ) );	
            
		}
        
        function ut_map_shortcode( $atts, $content = NULL ) {
            
            if( function_exists( 'vc_map' ) ) {
                                
                vc_map(
                    array(
                        'name'            => esc_html__( 'Animated Single Image', 'ut_shortcodes' ),
						'description'     => esc_html__( 'With a bunch of cool, fun, and cross-browser animations and lightbox support.', 'ut_shortcodes' ),
                        'base'            => $this->shortcode,
                        'category'        => 'Media',
                        //'icon'            => 'fa fa-image ut-vc-module-icon',
                        'icon'            => UT_SHORTCODES_URL . '/admin/img/vc_icons/animated-single-image.png',
                        'class'           => 'ut-vc-icon-module ut-media-module',
                        'content_element' => true,
                        'params'          => array(
                            
                            array(
								'type'              => 'attach_image',
								'heading'           => esc_html__( 'Image', 'ut_shortcodes' ),
								'param_name'        => 'image',
								'group'             => 'General'
						  	),
                            
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Image Size', 'ut_shortcodes' ),
                                'param_name'        => 'size',
                                'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'Thumbnail (cropped)' , 'ut_shortcodes' ) => 'thumbnail',
                                    esc_html__( 'Medium (cropped)' , 'ut_shortcodes' )    => 'medium',
                                    esc_html__( 'Large (cropped)' , 'ut_shortcodes' )     => 'large',
                                    esc_html__( 'Original' , 'ut_shortcodes' )  		  => 'full',
									esc_html__( 'Custom Size' , 'ut_shortcodes' )         => 'custom'
									
                                )
                            ),
                    		array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Custom Size Width', 'ut_shortcodes' ),
								'description'       => esc_html__( 'Value in px. e.g. 800', 'ut_shortcodes' ),
                                'param_name'        => 'custom_width',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'group'             => 'General',
								'dependency'        => array(
                                    'element' => 'size',
                                    'value'   => 'custom',
                                )
                            ),
							array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Custom Size Height', 'ut_shortcodes' ),
								'description'       => esc_html__( 'Value in px. e.g. 600', 'ut_shortcodes' ),
                                'param_name'        => 'custom_height',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'group'             => 'General',
								'dependency'        => array(
                                    'element' => 'size',
                                    'value'   => 'custom',
                                )
                            ),							
							array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Crop Images?', 'ut_shortcodes' ),
								'description'		=> __('What does Soft Crop mean? A soft crop will never cut off any of the image, it will scale the image down until it fits within the dimensions specified, maintaining its original aspect ratio. What does Hard Crop mean? The image will be scaled and then cropped to the exact dimensions you have specified. Depending on the aspect ratio of the image in relation to the crop size, it might happen that the image will be cut off.', 'ut_shortcodes'),
                                'param_name'        => 'custom_crop',
                                'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'yes, please! (Hard Crop)' , 'ut_shortcodes' ) => 'on',
                                    esc_html__( 'no, thanks! (Soft Crop)' , 'ut_shortcodes' )  => 'off',                                    
                                ),
								'dependency'        => array(
                                    'element' => 'size',
                                    'value'   => 'custom',
                                )
                            ),
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Image Opacity', 'ut_shortcodes' ),
								'param_name'        => 'image_opacity',
                                'group'             => 'General',
                                'value'             => array(
                                    'default' => '100',
                                    'min'     => '0',
                                    'max'     => '100',
                                    'step'    => '1',
                                    'unit'    => '%'
                                ),

						  	),
                    
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Image Border Radius', 'ut_shortcodes' ),
								'param_name'        => 'image_border_radius',
                                'group'             => 'General',
                                'value'             => array(
                                    'default'   => '0',
                                    'min'       => '0',
                                    'max'       => '100',
                                    'step'      => '1',
                                    'unit'      => 'px'
                                ),								
						  	),
                    
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Image Alignment', 'ut_shortcodes' ),
                                'param_name'        => 'align',
                                'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'left'   , 'ut_shortcodes' ) => 'left',
                                    esc_html__( 'center' , 'ut_shortcodes' ) => 'center',
                                    esc_html__( 'right'  , 'ut_shortcodes' ) => 'right',
                                )
                            ),
                            
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Link Image? (Open in Lightbox)', 'ut_shortcodes' ),
                                'param_name'        => 'link_type',
                                'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'No Link' , 'ut_shortcodes' ) => 'none',
                                    esc_html__( 'Custom Link' , 'ut_shortcodes' ) => 'custom',
                                    esc_html__( 'Open in Lightbox' , 'ut_shortcodes' ) => 'image',
                                )
                            ),                            
                            
							array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Image Size in Lightbox', 'ut_shortcodes' ),
                                'param_name'        => 'lightbox_size',
                                'description'       => esc_html__( 'What does Soft Crop mean? A soft crop will never cut off any of the image, it will scale the image down until it fits within the dimensions specified, maintaining its original aspect ratio. Also keep in mind, that using sizes these sizes except "HD Ready" and "Full" will force an image re-calculation the first time the setting is used. Means if your max_execution time is low, you might have to reload your website a few times until your server was able to process all images.', 'ut_shortcodes' ),
                                'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'HD Ready (1280x720 soft cropped)', 'ut_shortcodes' ) => 'hd',
                                    esc_html__( 'Full HD (1920x1280 soft cropped)', 'ut_shortcodes' ) => 'full_hd',
                                    esc_html__( 'WQHD (2560x1440 soft cropped)', 'ut_shortcodes' ) => 'wqhd',
                                    esc_html__( 'Retina 4k (4096x2304 soft cropped)', 'ut_shortcodes' ) => 'retina_4k',
                                    esc_html__( 'Retina 5k (5120x2880 soft cropped)', 'ut_shortcodes' ) => 'retina_5k',
                                    esc_html__( 'Original (Full Size no cropping)', 'ut_shortcodes' ) => 'full',
                                ),
                                'dependency'        => array(
                                    'element' => 'link_type',
                                    'value'   => 'image',
                                )
                            ), 
							
                            array(
								'type'              => 'vc_link',
								'heading'           => esc_html__( 'Link', 'ut_shortcodes' ),
								'param_name'        => 'link',
								'group'             => 'General',
                                'dependency'  => array(
                                    'element' => 'link_type',
                                    'value'   => 'custom',
                                )
						  	),
                            
							array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Hide Image Title on Mouse Over (Browser Tooltip)?', 'ut_shortcodes' ),
                                'param_name'        => 'hide_image_title',
                                'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'yes, please!', 'ut_shortcodes' ) => 'yes',                                    
									esc_html__( 'no, thanks!', 'ut_shortcodes' ) => 'no'
                                ),
                    
                            ),						
							
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Activate Hover Effect?', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'The hover effect can contain the image caption or a "plus sign".', 'ut_shortcodes' ),
                                'param_name'        => 'hover',
                                'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'no',
                                    esc_html__( 'yes', 'ut_shortcodes' ) => 'yes',                                    
                                ),
                    
                            ),
                    
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Hover Effect Content', 'ut_shortcodes' ),
                                'param_name'        => 'caption_content',
                                'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'Caption Text', 'ut_shortcodes' ) => 'caption',
                                    esc_html__( 'Plus Sign', 'ut_shortcodes' ) => 'plus',
                                    esc_html__( 'Custom Text', 'ut_shortcodes' ) => 'custom',                                    
                                ),
                                'dependency'        => array(
                                    'element' => 'hover',
                                    'value'   => 'yes',
                                )
                            ),
                            
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Custom Text', 'ut_shortcodes' ),
                                'param_name'        => 'custom_caption',
                                'group'             => 'General',
                                'dependency'        => array(
                                    'element' => 'caption_content',
                                    'value'   => 'custom',
                                )
                            ),
                    
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Show Image Caption Below?', 'ut_shortcodes' ),
                                'param_name'        => 'caption_below',
                                'group'             => 'General',
                                'value' => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'no',
                                    esc_html__( 'yes', 'ut_shortcodes' ) => 'yes',                                     
                                ),
                            ),                    
                    
                            /* Animation Effect */
                            array(
                                'type'              => 'animation_style',
                                'heading'           => __( 'Animation Effect', 'ut_shortcodes' ),
                                'description'       => __( 'Select image animation effect.', 'ut_shortcodes' ),
                                'group'             => 'Animation',
                                'param_name'        => 'effect',
                                'settings' => array(
                                    'type' => array(
                                        'in',
                                        'out',
                                        'other',
                                    ),
                                )
                                
                            ),
                            
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Animation Duration', 'unitedthemes' ),
                                'description'       => esc_html__( 'Animation time in seconds  e.g. 1s', 'unitedthemes' ),
                                'param_name'        => 'animation_duration',
                                'group'             => 'Animation',
                            ), 
                             
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Animate Once?', 'unitedthemes' ),
                                'description'       => esc_html__( 'Animate only once when reaching the viewport, animate everytime when reaching the viewport or make the animation infinite? By default the animation executes everytime when the element becomes visible in viewport, means when leaving the viewport the animation will be reseted and starts again when reaching the viewport again. By setting this option to yes, the animation executes exactly once. By setting it to infinite, the animation loops all the time, no matter if the element is in viewport or not.', 'unitedthemes' ),
                                'param_name'        => 'animate_once',
                                'group'             => 'Animation',
                                'value'             => array(
                                    esc_html__( 'yes', 'unitedthemes' )      => 'yes',
                                    esc_html__( 'no' , 'unitedthemes' )      => 'no',
                                    esc_html__( 'infinite', 'unitedthemes' ) => 'infinite',
                                )
                            ),  
                            
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Animate Image on Tablet?', 'ut_shortcodes' ),
                                'param_name'        => 'animate_tablet',
                                'group'             => 'Animation',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'false',
                                    esc_html__( 'yes'  , 'ut_shortcodes' ) => 'true'
                                ),
                            ),
                            
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Animate Image on Mobile?', 'ut_shortcodes' ),
                                'param_name'        => 'animate_mobile',
                                'group'             => 'Animation',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'false',
                                    esc_html__( 'yes'  , 'ut_shortcodes' ) => 'true'
                                ),
                            ),                            
                            
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Delay Animation?', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Time in milliseconds until the image appears. e.g. 200', 'ut_shortcodes' ),
                                'param_name'        => 'delay',
                                'group'             => 'Animation',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'false',
                                    esc_html__( 'yes'  , 'ut_shortcodes' ) => 'true'                                                                        
                                )
                            ),
                            
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Delay Timer', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Time in milliseconds until the next image appears. e.g. 200', 'ut_shortcodes' ),
                                'param_name'        => 'delay_timer',
                                'group'             => 'Animation',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'dependency'        => array(
                                    'element' => 'delay',
                                    'value'   => 'true',
                                )
                            ),                            
                            
                            // Caption Colors
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Caption Text Transform', 'ut_shortcodes' ),
                                'param_name'        => 'caption_transform',
                                'group'             => 'Caption Settings',
                                'value'             => array(
                                    esc_html__( 'Select Text Transform' , 'ut_shortcodes' ) => '',
                                    esc_html__( 'capitalize' , 'ut_shortcodes' ) => 'capitalize',
                                    esc_html__( 'uppercase', 'ut_shortcodes' ) => 'uppercase',
                                    esc_html__( 'lowercase', 'ut_shortcodes' ) => 'lowercase'                                    
                                ),
                                'dependency'        => array(
                                    'element' => 'hover',
                                    'value'   => 'yes',
                                )
                            ),
                    		array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Font Weight', 'ut_shortcodes' ),
								'param_name'        => 'caption_font_weight',
								'group'             => 'Caption Settings',
                                'value'             => array(
                                    esc_html__( 'Select Font Weight' , 'ut_shortcodes' ) => '',
                                    esc_html__( 'normal' , 'ut_shortcodes' )             => 'normal',
                                    esc_html__( 'bold' , 'ut_shortcodes' )               => 'bold',
									esc_html__( '100' , 'ut_shortcodes' )                => '100',
                                    esc_html__( '200' , 'ut_shortcodes' )                => '200',
                                    esc_html__( '300' , 'ut_shortcodes' )                => '300',
                                    esc_html__( '400' , 'ut_shortcodes' )                => '400',
                                    esc_html__( '500' , 'ut_shortcodes' )                => '500',
                                    esc_html__( '600' , 'ut_shortcodes' )                => '600',
                                    esc_html__( '700' , 'ut_shortcodes' )                => '700',
                                    esc_html__( '800' , 'ut_shortcodes' )                => '800',
                                    esc_html__( '900' , 'ut_shortcodes' )                => '900',
                                    
                                ),
								'dependency'        => array(
                                    'element' => 'hover',
                                    'value'   => 'yes',
                                )
						  	),
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Caption Letter Spacing', 'ut_shortcodes' ),
								'param_name'        => 'caption_letter_spacing',
                                'group'             => 'Caption Settings',
                                'value'             => array(
                                    'default'   => ut_get_theme_options_font_setting( 'h3', 'letter-spacing', "0" ),
                                    'min'   	=> '-0.2',
                                    'max'   	=> '0.2',
                                    'step'  	=> '0.01',
                                    'unit'  	=> 'em'
                                ),
                                'dependency'        => array(
                                    'element' => 'hover',
                                    'value'   => 'yes',
                                )
								
						  	),
                    
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Caption Font Size', 'ut_shortcodes' ),
                                'description'       => esc_html__( '(optional) value in px or em, eg "20px"' , 'ut_shortcodes' ),
                                'param_name'        => 'caption_font_size',
                                'group'             => 'Caption Settings',
                                'dependency'        => array(
                                    'element' => 'hover',
                                    'value'   => 'yes',
                                )
                            ),
                    
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Caption Text Color', 'ut_shortcodes' ),
								'param_name'        => 'caption_color',
								'group'             => 'Caption Settings',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'dependency'        => array(
                                    'element' => 'hover',
                                    'value'   => 'yes',
                                )
						  	),
                    
                            array(
								'type'              => 'gradient_picker',
								'heading'           => esc_html__( 'Caption Background Color', 'ut_shortcodes' ),
								'param_name'        => 'caption_background',
								'group'             => 'Caption Settings',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'dependency'        => array(
                                    'element' => 'hover',
                                    'value'   => 'yes',
                                )
						  	),
                            
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Caption Below Font Weight', 'ut_shortcodes' ),
								'param_name'        => 'caption_below_font_weight',
								'group'             => 'Caption Settings',
                                'value'             => array(
                                    esc_html__( 'bold' , 'ut_shortcodes' )               => 'bold',
                                    esc_html__( 'normal' , 'ut_shortcodes' )             => 'normal',
									esc_html__( '100' , 'ut_shortcodes' )                => '100',
                                    esc_html__( '200' , 'ut_shortcodes' )                => '200',
                                    esc_html__( '300' , 'ut_shortcodes' )                => '300',
                                    esc_html__( '400' , 'ut_shortcodes' )                => '400',
                                    esc_html__( '500' , 'ut_shortcodes' )                => '500',
                                    esc_html__( '600' , 'ut_shortcodes' )                => '600',
                                    esc_html__( '700' , 'ut_shortcodes' )                => '700',
                                    esc_html__( '800' , 'ut_shortcodes' )                => '800',
                                    esc_html__( '900' , 'ut_shortcodes' )                => '900',
                                ),
                                'dependency'        => array(
                                    'element' => 'caption_below',
                                    'value'   => 'yes',
                                )
						  	),                    
                    
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Caption Below Text Transform', 'ut_shortcodes' ),
                                'param_name'        => 'caption_below_transform',
                                'group'             => 'Caption Settings',
                                'value'             => array(
                                    esc_html__( 'Select Text Transform' , 'ut_shortcodes' ) => '',
                                    esc_html__( 'capitalize' , 'ut_shortcodes' ) => 'capitalize',
                                    esc_html__( 'uppercase', 'ut_shortcodes' ) => 'uppercase',
                                    esc_html__( 'lowercase', 'ut_shortcodes' ) => 'lowercase'                                    
                                ),
                                'dependency'        => array(
                                    'element' => 'caption_below',
                                    'value'   => 'yes',
                                )
                            ),
                    
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Caption Below Letter Spacing', 'ut_shortcodes' ),
								'param_name'        => 'caption_below_letter_spacing',
                                'group'             => 'Caption Settings',
                                'value'             => array(
                                    'def'   => '0',
                                    'min'   => '-0.2',
                                    'max'   => '0.2',
                                    'step'  => '0.01',
                                    'unit'  => 'em'
                                ),
                                'dependency'        => array(
                                    'element' => 'caption_below',
                                    'value'   => 'yes',
                                )
								
						  	),
                    
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Caption Below Text Color', 'ut_shortcodes' ),
								'param_name'        => 'caption_below_color',
								'group'             => 'Caption Settings',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'dependency'        => array(
                                    'element' => 'caption_below',
                                    'value'   => 'yes',
                                )
						  	),
                    
                    
                    
                            /* css editor */
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'CSS Class', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'ut_shortcodes' ),
                                'param_name'        => 'class',
                                'group'             => 'General'
                            ),
                    
                            array(
                                'type'              => 'css_editor',
                                'param_name'        => 'css',
                                'group'             => esc_html__( 'Design Options', 'ut_shortcodes' ),
                            ) 
                            
                        )                        
                        
                    )
                
                ); /* end mapping */
                
            } 
        
        }
        
        function ut_create_shortcode( $atts, $content = NULL ) {
                        
            extract( shortcode_atts( array (
                'image'               => '',
                'image_opacity'       => '100',
                'image_border_radius' => '',
                'size'                => 'thumbnail',
                'align'               => 'left',
				'hide_image_title'	  => 'yes',
                'custom_width'		  => '',
				'custom_height'		  => '',
				'custom_crop'		  => 'on',
				
                // Caption
                'hover'               => '',
                'caption_content'     => 'caption',
                'custom_caption'      => '',
                'caption_below'       => '',
                
                // Caption and Hover Colors
                'caption_color'                => '',
                'caption_font_size'            => '',
                'caption_background'           => '',
                'caption_transform'            => '',
				'caption_font_weight'		   => '',	
                'caption_letter_spacing'       => '',
                'caption_below_font_weight'    => '',
                'caption_below_color'          => '',
                'caption_below_transform'      => '',
                'caption_below_letter_spacing' => '',
                
                // Animation
                'effect'              => '',
                'animate_once'        => 'yes',
                'animate_mobile'      => false,
                'animate_tablet'      => false,
                'delay'               => 'no',
                'delay_timer'         => '100',
                'animation_duration'  => '',
                'link_type'           => 'none',
				'lightbox_size'       => 'hd',
                'link'                => '',
                'class'               => '',
                'css'                 => ''
            ), $atts ) ); 
            
            /* get image */
            if( $size == 'custom' ) {

				$new_image = array();
				$thumbnail = wp_get_attachment_image_src( $image, $size );
				
				if( isset( $thumbnail[0] ) && strpos( $thumbnail[0], '.svg' ) !== false ) {
					
					$new_image[0] = $thumbnail[0];
					$new_image[1] = $custom_width;
					$new_image[2] = $custom_height;					
					
				} else {
				
					if( $custom_crop == 'on' ) {

						$new_image[0] = ut_resize( $thumbnail[0], $custom_width, $custom_height, true, true, true );
						$new_image[1] = $custom_width;
						$new_image[2] = $custom_height;

					} else {

						$new_image = ut_resize( $thumbnail[0], $custom_width, $custom_height, true, false, true );

					}
				
				}
				
				// assign new thumb
				$image_array = $new_image;

			} else {
				
				$image_array = wp_get_attachment_image_src( $image, $size );							
								
				// check for SVG
				if( isset( $image_array[0] ) && strpos( $image_array[0], '.svg' ) !== false ) {

					if( $size == 'thumbnail' ) {

						$image_array[1] = get_option('thumbnail_size_w');
						$image_array[2] = get_option('thumbnail_size_h');	

					}

					if( $size == 'medium' ) {

						$image_array[1] = get_option('medium_size_w');
						$image_array[2] = get_option('medium_size_h');

					}

					if( $size == 'large' || $size == 'full' ) {

						$image_array[1] = get_option('large_size_w');
						$image_array[2] = get_option('large_size_h');					

					}
					

				}
				
				
			}
			
            /* attachment meta */
            $attachment_meta = get_post( $image );
            
            /* image alt */
            $alt = get_post_meta( $image, '_wp_attachment_image_alt', true ) ? get_post_meta( $image, '_wp_attachment_image_alt', true ) : '';
            
            if( empty( $alt ) && !empty( $attachment_meta->post_excerpt ) ) {
                
                $alt = esc_attr( $attachment_meta->post_excerpt );
                
            }
            
            /* image title */
			$image_title = get_the_title( $image );
            $image_title = 'title="' . esc_attr( $image_title ) . '"';
            
			if( $hide_image_title == 'yes' ) {
				$image_title = '';
			}
			
            if( empty( $image_array ) ) {
                
                $image_array   = array();
                $image_array[] = ut_img_asset_url( 'replace-normal.jpg' );
                $image_array[] = "";
                $image_array[] = "";
                
            }
			
			
            /* class array */
            $classes = array("ut-image-gallery-image");
            $animation_classes = array();            
            
            /* extra element class */
            $classes[] = $class;
            
            /* attributes array */
            $attributes = array();
            
            /* fill animation classes */
            if( !empty( $effect ) && $effect != 'none' ) {
                
                $animation_classes[] = 'ut-animate-image';
                $animation_classes[] = 'animated';             
                            
                if( !$animate_tablet ) {
                    $animation_classes[]  = 'ut-no-animation-tablet';
                }
                
                if( !$animate_mobile ) {
                    $animation_classes[]  = 'ut-no-animation-mobile';
                }
                
                if( $animate_once == 'infinite' ) {
                    $animation_classes[]  = 'infinite';
                }
                
                $attributes['data-effect'] = esc_attr( $effect );
                $attributes['data-animateonce'] = esc_attr( $animate_once );
                $attributes['data-delay'] = $delay == 'true' ? esc_attr( $delay_timer ) : 0;
                
                if( !empty( $animation_duration ) ) {
                    $attributes['data-animation-duration'] = esc_attr( ut_add_timer_unit( $animation_duration, 's' ) );    
                }    
                
            }
            
            /* attributes string */
            $attributes = implode(' ', array_map(
                function ($v, $k) { return sprintf("%s=\"%s\"", $k, $v); },
                $attributes,
                array_keys( $attributes )
            ) ); 
            
            
            // inline custom css 
            $id = uniqid("ut_am_");
            $wrap_id = uniqid("ut_am_wrap_");
            
            $css_style = '';
            
            if( $image_opacity ) {
                $css_style .= '#' . $id . ' { opacity: ' . ( $image_opacity / 100 ) . '; }';   
            }
            
            if( $image_border_radius ) {
                $css_style .= '#' . $id . ' { -webkit-border-radius: ' . $image_border_radius . 'px; -moz-border-radius: ' . $image_border_radius . 'px; border-radius: ' . $image_border_radius . 'px; }';   
            }
            
            if( $caption_color ) {
                $css_style .= '#' . esc_attr( $wrap_id ) . ' .ut-image-gallery-item-caption-title h3 { color: '. $caption_color . '; }';
            }

            if( $caption_background && ut_is_gradient( $caption_background ) ) {
                
				$css_style .= ut_create_gradient_css( $caption_background, '#' . esc_attr( $wrap_id ) . ' .ut-image-gallery-item.ut-animation-done:hover .ut-image-gallery-image-caption', false, 'background' );
				
            } elseif( $caption_background ) {
				
				$css_style .= '#' . esc_attr( $wrap_id ) . ' .ut-image-gallery-item.ut-animation-done:hover .ut-image-gallery-image-caption { background: ' . $caption_background . '; }';
				
			}

            if( $caption_transform ) {
                $css_style .= '#' . esc_attr( $wrap_id ) . ' .ut-image-gallery-item-caption-title h3 { text-transform: '. $caption_transform . '; }';
            }
            
			if( $caption_font_weight ){
				$css_style .= '#' . esc_attr( $wrap_id ) . ' .ut-image-gallery-item-caption-title h3 { font-weight: '. $caption_font_weight . '; }';				
			}
			
            if( isset( $caption_letter_spacing ) && $caption_letter_spacing != '' ) {
				
				// fallback letter spacing
				if( $caption_letter_spacing >= 1 || $caption_letter_spacing <= -1 ) {
					$caption_letter_spacing = $caption_letter_spacing / 100;	
				}
				
                $css_style .= '#' . esc_attr( $wrap_id ) . ' .ut-image-gallery-item-caption-title h3 { letter-spacing: '. $caption_letter_spacing . 'em; }';
            }

            if( $caption_font_size ) {
                $css_style .= '#' . esc_attr( $wrap_id ) . ' .ut-image-gallery-item-caption-title h3 { font-size: '. $caption_font_size . '; }';
            }

            if( $caption_below_color ) {
                $css_style .= '#' . esc_attr( $wrap_id ) . ' .ut-gallery-slider-caption { color: '. $caption_below_color . '; }';
            }    

            if( $caption_below_transform ) {
                $css_style .= '#' . esc_attr( $wrap_id ) . ' .ut-gallery-slider-caption { text-transform: '. $caption_below_transform . '; }';
            }
            
            if( $caption_below_letter_spacing ) {
				
				// fallback letter spacing
				if( $caption_below_letter_spacing >= 1 || $caption_below_letter_spacing <= -1 ) {
					$caption_below_letter_spacing = $caption_below_letter_spacing / 100;	
				}				
				
                $css_style .= '#' . esc_attr( $wrap_id ) . ' .ut-gallery-slider-caption { letter-spacing: '. $caption_below_letter_spacing . 'em; }';
            }

            if( $caption_below_font_weight ) {
                $css_style .= '#' . esc_attr( $wrap_id ) . ' .ut-gallery-slider-caption { font-weight: '. $caption_below_font_weight . '; }';
            }
            
            /* start output */
            $output = '';
            
            // add css to output
            if( !empty( $css_style ) ) {
                $output .= ut_minify_inline_css( '<style type="text/css" scoped>' . $css_style . '</style>' );
            }
            
            $output .= '<div id="' . $wrap_id . '" class="' . implode( ' ', $classes ) . '" style="text-align:' . $align . ';">';
            
                $output .= '<div class="ut-animated-image-item ut-image-gallery-item ut-animation-done">';
            
                    if( $link_type == 'image' ) {

						// lightgallery zoom image
                        if( $lightbox_size == 'hd' ) {
                            
                            $lightgallery = wp_get_attachment_image_src( $image, 'ut-lightbox' );
                                
                        } elseif( $lightbox_size == 'full' ) {
                            
                            $lightgallery = wp_get_attachment_image_src( $image, 'full' );
                            
                        } elseif( $lightbox_size == 'full_hd' ) {
                            
                            $lightgallery = wp_get_attachment_image_src( $image, 'full' );
                            $lightgallery = ut_resize( $lightgallery[0], 1920, 1080, false, false, false );
                            
                        } elseif( $lightbox_size == 'wqhd' ) {    
                            
                            $lightgallery = wp_get_attachment_image_src( $image, 'full' );
                            $lightgallery = ut_resize( $lightgallery[0], 2560, 1440, false, false, false );
                            
                        } elseif( $lightbox_size == 'retina_4k' ) {    
                            
                            $lightgallery = wp_get_attachment_image_src( $image, 'full' );
                            $lightgallery = ut_resize( $lightgallery[0], 4096, 2304, false, false, false );
                            
                        } elseif( $lightbox_size == 'retina_5k' ) {    
                            
                            $lightgallery = wp_get_attachment_image_src( $image, 'full' );
                            $lightgallery = ut_resize( $lightgallery[0], 5120, 2880, false, false, false );
                            
                        }
						
                        // fallback image
                        if( empty( $lightgallery ) ) {

                            $lightgallery   = array();
                            $lightgallery[] = ut_img_asset_url( 'replace-normal.jpg' );
                            $lightgallery[] = "";
                            $lightgallery[] = "";

                        }

                        $mini = wp_get_attachment_image_src( $image, 'full' );
                        $mini = ut_resize( $mini[0], 200, 200, true, false, false );

                        // fallback image
                        if( empty( $mini ) ) {

                            $mini   = array();
                            $mini[] = ut_img_asset_url( 'replace-normal.jpg' );
                            $mini[] = "";
                            $mini[] = "";

                        }
						
                        /* set link */
                        $output .= '<a data-exthumbimage="' . esc_url( $mini[0] ) . '" href="' . esc_url( $lightgallery[0] ) . '" class="ut-vc-images-lightbox">';

                    }

                    if( $link_type == 'custom' && !empty( $link ) ) {

                        /* attract link settings */
                        $link = vc_build_link( $link );

                        /* set link attributes */
                        $link['target'] = empty( $link['target'] ) ? '_self' : $link['target'];
                        $link['url']    = empty( $link['url'] )    ? '#'     : $link['url'];
                        $rel            = empty( $link['rel'] )    ? ''      : 'rel="' . $link['rel'] . '"';

                        $output .= '<a target="' . esc_attr( $link['target'] ) . '" href="' . esc_url( $link['url'] ) . '" ' . $rel . '>';

                    }
                    
                    if( $link_type == 'none' ) {
                        
                        $output .= '<a class="ut-deactivated-link" href="#">';
                        
                    }            
            
                    $output .= '<img ' . $image_title . ' id="' . $id . '" style="" src="' . esc_url( $image_array[0] ) . '" width="' . esc_attr( ( $image_array[1] > 1 ? $image_array[1] : '' ) ) . '" height="' . esc_attr( ( $image_array[2] > 1 ? $image_array[2] : '' ) ) . '" alt="' . esc_attr( $alt ) . '"/>';

                    if( $hover == 'yes' ) {

                        $output .= '<div class="ut-image-gallery-image-caption">';

                            $output .= '<div class="ut-image-gallery-item-caption-title">';

                            if( !empty( $attachment_meta->post_excerpt ) && $caption_content == 'caption' ) {

                                $output .= '<h3>' . $attachment_meta->post_excerpt . '</h3>';

                            } elseif( $caption_content == 'custom' && !empty( $custom_caption ) ) {

                                $output .= '<h3>' . $custom_caption . '</h3>';

                            } else {

                                $output .= '<h3 class="ut-image-gallery-empty-title">+</h3>';

                            }

                             $output .= '</div>';

                        $output .= '</div>';                                

                    }
            
                    $output .= '</a>';                    
            
                    if( $caption_below == 'yes' && !empty( $attachment_meta->post_excerpt ) ) {

                        $output .= '<div class="ut-gallery-slider-caption">' . $attachment_meta->post_excerpt . '</div>';

                    }
            
                $output .= '</div>';
            
            $output .= '</div>';
                
            return '<div ' . $attributes . ' class="wpb_content_element ' . implode( ' ', $animation_classes ) . ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->shortcode, $atts ) . ' clearfix">' . $output . '</div>'; 
        
        }
            
    }

}

new UT_Animated_Image;

if ( class_exists( 'WPBakeryShortCode' ) ) {
    
    class WPBakeryShortCode_ut_animated_image extends WPBakeryShortCode {
        
        function __construct( $settings ) {
            
            parent::__construct( $settings );
            $this->jsScripts();
            
        }
    
        public function jsScripts() {
            
            wp_register_script( 'zoom', vc_asset_url( 'lib/bower/zoom/jquery.zoom.min.js' ), array(), WPB_VC_VERSION );
            wp_register_script( 'vc_image_zoom', vc_asset_url( 'lib/vc_image_zoom/vc_image_zoom.min.js' ), array(
                'jquery',
                'zoom',
            ), WPB_VC_VERSION, true );
            
        }
    
        public function singleParamHtmlHolder( $param, $value ) {
            
            $output = '';
    
            $param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
            $type = isset( $param['type'] ) ? $param['type'] : '';
            $class = isset( $param['class'] ) ? $param['class'] : '';
            
            if ( 'attach_image' === $param['type'] && 'image' === $param_name ) {
                $output .= '<input type="hidden" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '" />';
                $element_icon = $this->settings( 'icon' );
                $img = wpb_getImageBySize( array(
                    'attach_id' => (int) preg_replace( '/[^\d]/', '', $value ),
                    'thumb_size' => 'thumbnail',
                ) );
                $this->setSettings( 'logo', ( $img ? $img['thumbnail'] : '<img width="150" height="150" src="' . vc_asset_url( 'vc/blank.gif' ) . '" class="attachment-thumbnail vc_general vc_element-icon"  data-name="' . $param_name . '" alt="" title="" style="display: none;" />' ) . '<span class="no_image_image vc_element-icon' . ( ! empty( $element_icon ) ? ' ' . $element_icon : '' ) . ( $img && ! empty( $img['p_img_large'][0] ) ? ' image-exists' : '' ) . '" /><a href="#" class="column_edit_trigger' . ( $img && ! empty( $img['p_img_large'][0] ) ? ' image-exists' : '' ) . '">' . __( 'Add image', 'js_composer' ) . '</a>' );
                $output .= $this->outputTitleTrue( $this->settings['name'] );
            } elseif ( ! empty( $param['holder'] ) ) {
                if ( 'input' === $param['holder'] ) {
                    $output .= '<' . $param['holder'] . ' readonly="true" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '">';
                } elseif ( in_array( $param['holder'], array( 'img', 'iframe' ) ) ) {
                    $output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" src="' . $value . '">';
                } elseif ( 'hidden' !== $param['holder'] ) {
                    $output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">' . $value . '</' . $param['holder'] . '>';
                }
            }
    
            if ( ! empty( $param['admin_label'] ) && true === $param['admin_label'] ) {
                $output .= '<span class="vc_admin_label admin_label_' . $param['param_name'] . ( empty( $value ) ? ' hidden-label' : '' ) . '"><label>' . $param['heading'] . '</label>: ' . $value . '</span>';
            }
    
            return $output;
        }
    
        public function getImageSquareSize( $img_id, $img_size ) {
            if ( preg_match_all( '/(\d+)x(\d+)/', $img_size, $sizes ) ) {
                $exact_size = array(
                    'width' => isset( $sizes[1][0] ) ? $sizes[1][0] : '0',
                    'height' => isset( $sizes[2][0] ) ? $sizes[2][0] : '0',
                );
            } else {
                $image_downsize = image_downsize( $img_id, $img_size );
                $exact_size = array(
                    'width' => $image_downsize[1],
                    'height' => $image_downsize[2],
                );
            }
            $exact_size_int_w = (int) $exact_size['width'];
            $exact_size_int_h = (int) $exact_size['height'];
            if ( isset( $exact_size['width'] ) && $exact_size_int_w !== $exact_size_int_h ) {
                $img_size = $exact_size_int_w > $exact_size_int_h
                    ? $exact_size['height'] . 'x' . $exact_size['height']
                    : $exact_size['width'] . 'x' . $exact_size['width'];
            }
    
            return $img_size;
        }
    
        protected function outputTitle( $title ) {
            return '';
        }
    
        protected function outputTitleTrue( $title ) {
            return '<h4 class="wpb_element_title">' . $title . ' ' . $this->settings( 'logo' ) . '</h4>';
        }                
        
    }
    
}