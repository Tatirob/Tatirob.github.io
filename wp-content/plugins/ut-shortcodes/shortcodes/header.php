<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'UT_Header' ) ) {
	
    class UT_Header {
        
        private $shortcode;
            
        function __construct() {
			
            /* shortcode base */
            $this->shortcode = 'ut_header';
            
            add_action( 'init', array( $this, 'ut_map_shortcode' ) );
            add_shortcode( $this->shortcode, array( $this, 'ut_create_shortcode' ) );	
            
		}
        
        function ut_map_shortcode( $atts, $content = NULL ) {
            
            if( function_exists( 'vc_map' ) ) {
                                
                vc_map(
                    array(
                        'name'            => esc_html__( 'Section Title (Page Title) Module', 'ut_shortcodes' ), 
                        'description'     => esc_html__( 'A section or page titles typically forms the first element inside a section or page.', 'ut_shortcodes' ),
						'base'            => $this->shortcode,
                        // 'icon'            => 'fa fa-header ut-vc-module-icon',
                        'icon'            => UT_SHORTCODES_URL . '/admin/img/vc_icons/header.png',
                        'category'        => 'Structual',                        
                        'class'           => 'ut-vc-icon-module ut-structual-module',
                        'content_element' => true,
                        'params'          => array(
                            array(
								'type'              => 'dropdown',
								'class'             => 'ut-select-header-style',
								'heading'           => esc_html__( 'Style', 'ut_shortcodes' ),
								'param_name'        => 'style',
								'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'Select Style', 'ut_shortcodes' ) => '',
                                    esc_html__( 'Default (Theme Options)', 'ut_shortcodes' ) => 'global',
                                    esc_html__( 'Style One'   , 'ut_shortcodes' ) => 'pt-style-1',
                                    esc_html__( 'Style Two'   , 'ut_shortcodes' ) => 'pt-style-2',
                                    esc_html__( 'Style Three' , 'ut_shortcodes' ) => 'pt-style-3',
                                    esc_html__( 'Style Four'  , 'ut_shortcodes' ) => 'pt-style-4',
                                    esc_html__( 'Style Five'  , 'ut_shortcodes' ) => 'pt-style-5',
                                    esc_html__( 'Style Six'   , 'ut_shortcodes' ) => 'pt-style-6',
                                    esc_html__( 'Style Seven' , 'ut_shortcodes' ) => 'pt-style-7',

                                ),
						  	),
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Alignment', 'ut_shortcodes' ),
								'param_name'        => 'align',
							    'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'Select Alignment', 'ut_shortcodes' ) => '',
                                    esc_html__( 'Default (Theme Options)', 'ut_shortcodes' ) => 'global',
                                    esc_html__( 'center', 'ut_shortcodes' ) => 'center',
                                    esc_html__( 'left'  , 'ut_shortcodes' ) => 'left',
                                    esc_html__( 'right'  , 'ut_shortcodes' ) => 'right',
                                ),
                            ),
                    
                            array(
								'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Title Source', 'ut_shortcodes' ),
                                'param_name'        => 'title_source',
                                'admin_label'       => true,
                                'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'Custom Title', 'ut_shortcodes' ) => 'custom',
                                    esc_html__( 'Current Page Title', 'ut_shortcodes' ) => 'page',
                                )
                            ),
                    
                            array(
                                'type'              => 'textarea',
                                'heading'           => esc_html__( 'Title', 'ut_shortcodes' ),
                                'param_name'        => 'title',
                                'admin_label'       => true,
                                'group'             => 'General',
                                'class'             => 'ut-textarea-mid-size',
                                'dependency' => array(
                                    'element' => 'title_source',
                                    'value'   => array( 'custom' ),
                                ),
                            ),
                            
							array(
								'type'              => 'checkbox',
								'heading'           => esc_html__( 'Hide Linebreaks on Tablet', 'ut_shortcodes' ),
								'param_name'        => 'title_linebreak_tablet',
							    'group'             => 'General',
								'edit_field_class'  => 'vc_col-sm-6',
								'value' 			=> array('yes, please!' => 'on' ),
								'std'				=> ''
                            ),
							
							array(
								'type'              => 'checkbox',
								'heading'           => esc_html__( 'Hide Linebreaks on Mobile', 'ut_shortcodes' ),
								'param_name'        => 'title_linebreak_mobile',
							    'group'             => 'General',
								'edit_field_class'  => 'vc_col-sm-6',
								'value' 			=> array('yes, please!' => 'on' ),
								'std'				=> 'on',
								'save_always' 		=> true
                            ),
							
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Title Spacing', 'ut_shortcodes' ),
								'param_name'        => 'spacing',
							    'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'Default (Theme Options)', 'ut_shortcodes' ) => 'global',
                                    esc_html__( 'Custom Spacing', 'ut_shortcodes' ) => 'custom',                                    
                                ),
                            ),
                            
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Title Spacing Bottom', 'ut_shortcodes' ),
								'param_name'        => 'spacing_bottom',
                                'group'             => 'General',
                                'value'             => array(
                                    'def'   => '20',
                                    'min'   => '0',
                                    'max'   => '200',
                                    'step'  => '1',
                                    'unit'  => 'px'
                                ),
					           	'dependency' => array(
                                    'element' => 'spacing',
                                    'value'   => array( 'custom' ),
                                ),		
                    
                    
						  	),
                    
                            // Extra Settings Style 1
                            
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Title Style', 'ut_shortcodes' ),
								'param_name'        => 'title_style',
                                'dependency' => array(
                                    'element' => 'style',
                                    'value'   => array( 'pt-style-1' ),
                                ),
								'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'Decoration Line as Linetrough' , 'ut_shortcodes' ) => 'section',
                                    esc_html__( 'Decoration Line above Title'   , 'ut_shortcodes' ) => 'parallax',
                                ),
						  	),
                            
                            array(
                                'type'              => 'textarea',
                                'heading'           => esc_html__( 'Lead Text', 'ut_shortcodes' ),
                                'admin_label'       => true,
                                'param_name'        => 'content',
                                'group'             => 'General'
                            ),
                            
							array(
								'type'              => 'checkbox',
								'heading'           => esc_html__( 'Hide Linebreaks on Tablet', 'ut_shortcodes' ),
								'param_name'        => 'lead_linebreak_tablet',
							    'group'             => 'General',
								'edit_field_class'  => 'vc_col-sm-6',
								'value' 			=> array('yes, please!' => 'on' ),
								'std'				=> ''
                            ),
							
							array(
								'type'              => 'checkbox',
								'heading'           => esc_html__( 'Hide Linebreaks on Mobile', 'ut_shortcodes' ),
								'param_name'        => 'lead_linebreak_mobile',
							    'group'             => 'General',
								'edit_field_class'  => 'vc_col-sm-6',
								'value' 			=> array('yes, please!' => 'on' ),
								'std'				=> 'on',
								'save_always' 		=> true
                            ),							
							
							array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Lead Text Width', 'ut_shortcodes' ),
								'param_name'        => 'lead_width',
                                'group'             => 'General',
                                'value'             => array(
                                    'def'   => '100',
                                    'min'   => '0',
                                    'max'   => '100',
                                    'step'  => '1',
                                    'unit'  => '%'
                                ),
						  	),
							
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Show Icon before lead text?', 'ut_shortcodes' ),
                                'param_name'        => 'lead_icon',
                                'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'false',
                                    esc_html__( 'yes'  , 'ut_shortcodes' ) => 'true'
                                ),
                            ),
                            
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Color', 'ut_shortcodes' ),
								'param_name'        => 'lead_icon_color',
								'group'             => 'General',
                                'dependency' => array(
                                    'element'   => 'lead_icon',
                                    'value'     => 'true',
                                ),
						  	),
                            
                            array(
                                'type'          => 'dropdown',
                                'heading'       => esc_html__( 'Icon library', 'ut_shortcodes' ),
                                'description'   => esc_html__( 'Select icon library.', 'ut_shortcodes' ),
                                'param_name'    => 'lead_icon_type', 
                                'group'         => 'General', 
                                'value'         => array(
                                    esc_html__( 'Font Awesome', 'ut_shortcodes' ) => 'fontawesome',
                                    esc_html__( 'Brooklyn Icons', 'ut_shortcodes' ) => 'bklynicons',
                                ),
                                'dependency' => array(
                                    'element'   => 'lead_icon',
                                    'value'     => 'true',
                                ),
                                                              
                            ), 
                            array(
								'type'              => 'iconpicker',
                                'heading'           => esc_html__( 'Choose Icon', 'ut_shortcodes' ),
                                'param_name'        => 'lead_icon_fontawesome',
                                'group'             => 'General',
                                'dependency' => array(
                                    'element'   => 'lead_icon_type',
                                    'value'     => 'fontawesome',
                                ),
                            ),
                            array(
								'type'              => 'iconpicker',
                                'heading'           => esc_html__( 'Choose Icon', 'ut_shortcodes' ),
                                'param_name'        => 'lead_icon_bklyn',
                                'group'             => 'General',
                                'settings' => array(
                                    'emptyIcon'     => true,
                                    'type'          => 'bklynicons',
                                ),
                                'dependency' => array(
                                    'element'   => 'lead_icon_type',
                                    'value'     => 'bklynicons',
                                ),
                                                                
                            ),
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Lead Text Margin Top', 'ut_shortcodes' ),
                                'description'       => esc_html__( '(optional) - value in px , default: 0px', 'ut_shortcodes' ),
                                'param_name'        => 'lead_margin_top',
                                'group'             => 'General'
                            ),
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Lead Text Margin Left', 'ut_shortcodes' ),
                                'description'       => esc_html__( '(optional) - value in px , default: 0px', 'ut_shortcodes' ),
                                'param_name'        => 'lead_margin_left',
                                'group'             => 'General'
                            ),
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Lead Text Margin Right', 'ut_shortcodes' ),
                                'description'       => esc_html__( '(optional) - value in px , default: 0px', 'ut_shortcodes' ),
                                'param_name'        => 'lead_margin_right',
                                'group'             => 'General'
                            ),
                            
                            // Extra Settings Style 2
                           
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Decoration Line Height', 'ut_shortcodes' ),
                                'description'       => esc_html__( '(optional) - value in px , default: 1px', 'ut_shortcodes' ),
                                'param_name'        => 'style_2_height',
                                'dependency' => array(
                                    'element' => 'style',
                                    'value'   => array( 'pt-style-2' ),
                                ),
                                'group'             => 'General'
                            ),
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Decoration Line Width', 'ut_shortcodes' ),
                                'description'       => esc_html__( '(optional) - value in % , default: 15%', 'ut_shortcodes' ),
                                'param_name'        => 'style_2_width',
                                'dependency' => array(
                                    'element' => 'style',
                                    'value'   => array( 'pt-style-2' ),
                                ),
                                'group'             => 'General'
                            ),
                            
                            // Extra Settings Style 4                    
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Decoration Line Width', 'ut_shortcodes' ),
                                'description'       => esc_html__( '(optional) - value in px , default: 6px', 'ut_shortcodes' ),
                                'param_name'        => 'style_4_width',
                                'dependency' => array(
                                    'element' => 'style',
                                    'value'   => array( 'pt-style-4' ),
                                ),
                                'group'             => 'General'
                            ),
                    
                    		// Title Font Settings
							array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Choose Font Source', 'ut_shortcodes' ),
                                'param_name'        => 'title_font_source',
                                'group'             => 'Title Font',
                                'value'             => array(
                                    esc_html__( 'Theme Default', 'ut_shortcodes' )  => 'default',
                                    esc_html__( 'Web Safe Fonts', 'ut_shortcodes' ) => 'websafe',
                                    esc_html__( 'Google Font', 'ut_shortcodes' )    => 'google',
									esc_html__( 'Custom Font', 'ut_shortcodes' ) => 'custom',
                                ),                                                                
                            ),
                            array(
                                'type'              => 'google_fonts',
                                'param_name'        => 'title_google_fonts',
                                'value'             => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
                                'group'             => 'Title Font',
                                'settings'          => array(
                                    'fields' => array(
                                        'font_family_description' => __( 'Select font family.', 'ut_shortcodes' ),
                                        'font_style_description'  => __( 'Select font styling.', 'ut_shortcodes' ),
                                    ),
                                ),
                                'dependency'        => array(
                                    'element'           => 'title_font_source',
                                    'value'             => 'google',
                                ),
                            ),
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Websafe Fonts', 'ut_shortcodes' ),
                                'param_name'        => 'title_websafe_fonts',
                                'group'             => 'Title Font',
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
                                    'element'           => 'title_font_source',
                                    'value'             => 'websafe',
                                ),
                                
                            ),
							array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Custom Fonts', 'ut_shortcodes' ),
                                'param_name'        => 'title_custom_fonts',
                                'group'             => 'Title Font',
                                'value'             => ut_get_custom_fonts(),
                                'dependency'        => array(
                                    'element'           => 'title_font_source',
                                    'value'             => 'custom',
                                ),
                                
                            ),							
							array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Title Font Size', 'ut_shortcodes' ),
								'param_name'        => 'font_size',
                                'group'             => 'Title Font',
                                'value'             => array(
                                    'default'   => ut_get_theme_options_font_setting( 'section_title', 'font-size', "40" ),
                                    'min'       => '8',
                                    'max'       => '300',
                                    'step'      => '1',
                                    'unit'      => 'px'
                                ),
								
						  	),                    
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Title Font Weight', 'ut_shortcodes' ),
								'param_name'        => 'font_weight',
                                'group'             => 'Title Font',
                                'value'             => array(
                                    esc_html__( 'Select Font Weight' , 'ut_shortcodes' ) => '',
                                    esc_html__( 'Default (Theme Options)', 'ut_shortcodes' ) => 'global',
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
                                    'element'           => 'title_font_source',
                                    'value'             => array('websafe','theme'),
                                ),
						  	),
							array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Line height', 'ut_shortcodes' ),
								'param_name'        => 'line_height',
                                'group'             => 'Title Font',
                                'value'             => array(
                                    'default'   => ut_get_theme_options_font_setting( 'section_title', 'line-height', "50" ),
                                    'min'       => '0',
                                    'max'       => '400',
                                    'step'      => '1',
                                    'unit'      => 'px'
                                ),
								
						  	),                    
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Title Letter Spacing', 'ut_shortcodes' ),
								'param_name'        => 'title_letter_spacing',
                                'group'             => 'Title Font',
                                'value'             => array(
                                    'def'   => ut_get_theme_options_font_setting( 'section_title', 'letter-spacing', "0" ),
                                    'min'   => '-0.2',
                                    'max'   => '0.2',
                                    'step'  => '0.01',
                                    'unit'  => 'em'
                                ),
						  	),
							
                            // Colors
                            
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Accent Color', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Depending the on the chosen style the accent color can be a background or decoration line.', 'ut_shortcodes' ),
								'param_name'        => 'accent',
                                'dependency' => array(
                                    'element' => 'style',
                                    'value'   => array( 'pt-style-1', 'pt-style-2', 'pt-style-3', 'pt-style-4', 'pt-style-5', 'pt-style-6' ),
                                ),
								'group'             => 'Colors'
						  	),
                            array(
								'type'              => 'gradient_picker',
								'heading'           => esc_html__( 'Title Color', 'ut_shortcodes' ),
								'param_name'        => 'title_color',
								'group'             => 'Colors'
						  	),
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Title Accent Color', 'ut_shortcodes' ),
                                'description'       => sprintf( esc_html__( '(optional) - use: %s inside your title text to apply this color.', 'ut_shortcodes' ), '<xmp class="ut-code-usage"><ins>Word</ins></xmp>' ),
								'param_name'        => 'title_accent_color',
                                'edit_field_class'  => 'vc_col-sm-6',
								'group'             => 'Colors'
						  	),
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Title Accent Background Color', 'ut_shortcodes' ),
                                'description'       => sprintf( esc_html__( '(optional) - use: %s inside your title text to apply this color.', 'ut_shortcodes' ), '<xmp class="ut-code-usage"><ins>Word</ins></xmp>' ),
								'param_name'        => 'title_accent_color_bg',
                                'edit_field_class'  => 'vc_col-sm-6',
								'group'             => 'Colors'
						  	),
                            array(
								'type'              => 'gradient_picker',
								'heading'           => esc_html__( 'Lead Color', 'ut_shortcodes' ),
								'param_name'        => 'lead_color',
								'group'             => 'Colors'
						  	),
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Lead Accent Color', 'ut_shortcodes' ),
                                'description'       => sprintf( esc_html__( '(optional) - use: %s inside your lead text to apply this color.', 'ut_shortcodes' ), '<xmp class="ut-code-usage"><ins>Word</ins></xmp>' ),
								'param_name'        => 'lead_accent_color',
                                'edit_field_class'  => 'vc_col-sm-6',
								'group'             => 'Colors'
						  	),
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Lead Accent Background Color', 'ut_shortcodes' ),
                                'description'       => sprintf( esc_html__( '(optional) - use: %s inside your lead text to apply this color.', 'ut_shortcodes' ), '<xmp class="ut-code-usage"><ins>Word</ins></xmp>' ),
								'param_name'        => 'lead_accent_color_bg',
                                'edit_field_class'  => 'vc_col-sm-6',    
								'group'             => 'Colors'
						  	),
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Lead Accent Font Weight', 'ut_shortcodes' ),
								'param_name'        => 'lead_accent_font_weight',
								'group'             => 'Colors',
                                'value'             => array(
                                    esc_html__( 'Select Font Weight' , 'ut_shortcodes' ) => '',
                                    esc_html__( 'normal' , 'ut_shortcodes' )             => 'normal',
                                    esc_html__( 'bold' , 'ut_shortcodes' )               => 'bold'
                                ),
						  	),
                            
                            // Animation        
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
                                    esc_html__( 'yes', 'unitedthemes' ) => 'yes',
                                    esc_html__( 'no' , 'unitedthemes' ) => 'no',
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
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'false',
                                    esc_html__( 'yes'  , 'ut_shortcodes' ) => 'true'                                                                        
                                )
                            ),
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Delay Timer (Title)', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Time in milliseconds until the next image appears. e.g. 200', 'ut_shortcodes' ),
                                'param_name'        => 'delay_timer',
                                'group'             => 'Animation',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'dependency'        => array(
                                    'element' => 'delay',
                                    'value'   => 'true',
                                )
                            ),
                        
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Delay Timer (Lead)', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Time in milliseconds until the next image appears. e.g. 400', 'ut_shortcodes' ),
                                'param_name'        => 'delay_timer_lead',
                                'group'             => 'Animation',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'dependency'        => array(
                                    'element' => 'delay',
                                    'value'   => 'true',
                                )
                            ),
                    
                            // CSS
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
                'title_source'            => 'custom',
                'title'                   => '',
				'title_font_source'		  => 'theme',
				'title_google_fonts'	  => '',
				'title_websafe_fonts' 	  => '',
				'title_custom_fonts'	  => '',
				'title_linebreak_tablet'  => '',
				'title_linebreak_mobile'  => 'on',	
				'font_size'               => '',
                'font_weight'             => '',
                'line_height'             => '',
                'title_letter_spacing'    => '',
                'style'                   => 'global',
                'align'                   => 'global',
                'spacing'                 => 'global',
                'spacing_bottom'          => '20',
                'accent'                  => '',
                'color'                   => '',
                'title_color'             => '',
                'title_accent_color'      => '',
                'title_accent_color_bg'   => '',
				'lead_width'			  => '',
                'lead_color'              => '',
                'lead_accent_color'       => '',
                'lead_accent_font_weight' => '',
                'lead_margin_top'         => '',
                'lead_margin_left'        => '',
                'lead_margin_right'       => '',
                'lead_icon'               => 'false',
                'lead_icon_color'         => '',
                'lead_icon_type'          => 'fontawesome',
                'lead_icon_fontawesome'   => '',
                'lead_icon_bklyn'         => '',
				'lead_linebreak_tablet'   => '',
				'lead_linebreak_mobile'   => 'on',
                'title_style'             => 'section',
                'style_2_height'          => '',
                'style_2_width'           => '',
                'style_4_width'           => '',
                'effect'                  => '',     
                'animate_once'            => 'yes',
                'animate_mobile'          => false,
                'animate_tablet'          => false,
                'delay'                   => 'false',
                'delay_timer'             => '100',
                'delay_timer_lead'        => '200',
                'animation_duration'      => '',
                'css'                     => ''                               
            ), $atts ) ); 
            
            $classes   = array();
            $attributes= array(); 
            
            // align
            if( $align == 'global' && function_exists('ot_get_option') ) {
                $align = ot_get_option( 'ut_global_headline_align', 'center' );                
            }
            
            // style 
            if( $style == 'global' && function_exists('ot_get_option') ) {
                $style = ot_get_option('ut_global_headline_style', 'pt-style-1' );                
            }
            
			// hide linebreak
			if( $title_linebreak_mobile ) {
				$classes[] = 'ut-no-title-linebreak-mobile';
			}
				
			if( $title_linebreak_tablet ) {	
				$classes[] = 'ut-no-title-linebreak-tablet';
			}
			
			if( $lead_linebreak_mobile ) {
				$classes[] = 'ut-no-lead-linebreak-mobile';
			}
				
			if( $lead_linebreak_tablet ) {	
				$classes[] = 'ut-no-lead-linebreak-tablet';
			}
			
			
            /* add class */
            $classes[] = $style;
            $classes[] = 'header-' . $align;
            
            /* style extra */
            $height = !empty( $style_2_height ) && $style == 'pt-style-2' ? $style_2_height : '';
            $width  = !empty( $style_2_width )  && $style == 'pt-style-2' ? $style_2_width  : '';
            $width  = !empty( $style_4_width )  && $style == 'pt-style-4' ? $style_4_width  : '';
            
            $accent = empty( $accent ) && $style == 'pt-style-2' && !empty( $title_color ) ? $title_color : $accent;
            $accent = empty( $accent ) && $style == 'pt-style-4' && !empty( $title_color ) ? $title_color : $accent;
            $accent = empty( $accent ) && $style == 'pt-style-6' && !empty( $title_color ) ? $title_color : $accent;
            
			$ut_font_css = false;
            
            /* initialize google font */
            if( $title_font_source && $title_font_source == 'google' ) {
                
                 $ut_google_font     = new UT_VC_Google_Fonts( $atts, 'title_google_fonts', $this->shortcode );
                 $ut_font_css        = $ut_google_font->get_google_fonts_css_styles();
                        
            }
            
            $ut_font_css = is_array( $ut_font_css ) ? implode( '', $ut_font_css ) : $ut_font_css;
			
            
            /* unique header ID */
            $id = uniqid("ut_header_");
            
            $css_style = '<style type="text/css" scoped>';
                
                if( $title_color && ut_is_gradient( $title_color ) ) {
                    
                    $classes[] = 'header-with-gradient';
                    $css_style.= ut_create_gradient_css( $title_color, '#' . $id . ' h2 span', false, 'background' );
                    
                    
                } elseif( $title_color ) {
                    
                    $css_style.= '#' . $id . ' h2 span { color:' . $title_color . '; }';
                    
                }
			
				if( $ut_font_css ) {
					$css_style.= '#' . $id . ' h2.section-title  { ' . $ut_font_css . ' }';
				}
				
				if( $title_font_source && $title_font_source == 'websafe' ) {
					$css_style .= '#' . $id . ' h2.section-title  { font-family: ' . get_websafe_font_css_family( $title_websafe_fonts ) . '; }';                
				}
				// title_custom_fonts
				if( $title_font_source && $title_font_source == 'custom' && $title_custom_fonts ) {
					
					$font_family = get_term($title_custom_fonts,'unite_custom_fonts');
					
					if( isset( $font_family->name ) )
					$css_style .= '#' . $id . ' h2.section-title  { font-family: "' . $font_family->name . '"; }';                
					
				}
			
                // fallback value since the new slider only return numerics
                if( $font_size && ( strpos( $font_size, 'px') !== false || strpos( $font_size, 'em') !== false || strpos( $font_size, '%') !== false ) ) {
                    
                    $css_style.= '#' . $id . ' h2.' . $title_style . '-title { font-size: ' . $font_size . '; }';
                    
                } elseif( $font_size ) {
                    
                    $css_style.= '#' . $id . ' h2.' . $title_style . '-title { font-size: ' . $font_size . 'px; }';
                    
                }
            
                if( $title_letter_spacing ) {
					
					// fallback letter spacing
					if( $title_letter_spacing >= 1 || $title_letter_spacing <= -1 ) {
						$title_letter_spacing = $title_letter_spacing / 100;	
					}					
					
                    $css_style.= '#' . $id . ' h2 { letter-spacing: ' . $title_letter_spacing . 'em !important; }';
					
                }            
            
                if( $line_height ) {
                    
					$css_style.= '@media (min-width: 768px) { #' . $id . ' h2 { line-height: ' . $line_height . 'px !important; } }';
					$classes[] = 'element-with-custom-line-height';
					
                }
                
				if( function_exists("ut_check_theme_options_line_height") && ut_check_theme_options_line_height("ut_global_headline_font_type") ) {
					$classes[] = 'element-with-custom-line-height';
				}
			
                if( $font_weight && $font_weight != 'global' ) {
                    $css_style.= '#' . $id . ' h2.section-title { font-weight:' . $font_weight . '; }';
                }
            	
				if( $lead_width ) {
					
                    $css_style.= '@media (min-width: 1025px) { #' . $id . ' .lead { width:' . $lead_width . '%;';
					
					if( $align == 'center' ) {
						$css_style.= 'margin: 0 auto;';	
					}
					
					if( $align == 'right' ) {
						$css_style.= 'margin: 0 0 0 auto;';	
					}
					
					$css_style.= '} }';
					
                }
						
				if( $lead_color && ut_is_gradient( $lead_color ) ) {
					
					$classes[] = 'header-with-gradient-lead';
                    $css_style.= ut_create_gradient_css( $lead_color, '#' . $id . ' .lead' , false, 'background' );
					
				} elseif( $lead_color ) {
                    
					$css_style.= '#' . $id . ' .lead { color:' . $lead_color . '; }';
                    $css_style.= '#' . $id . ' .lead p { color:' . $lead_color . '; }';
					
                }                                
                
                if( $lead_icon == 'true' ) {
                    
                    if( $lead_icon_type == 'bklynicons' && !empty( $lead_icon_bklyn ) ) {

                        $css_style.= '#' . $id . ' .lead.ut-lead-has-icon::before { font-family: "icon54com" !important; speak: none; font-style: normal; font-weight: normal; font-variant: normal; text-transform: none; line-height: 1; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }';

                    }

                    if( $lead_icon_type == 'fontawesome' && !empty( $lead_icon_fontawesome ) ) {

                        $css_style.= '#' . $id . ' .lead.ut-lead-has-icon::before { display: inline-block; font: normal normal normal 14px/1 FontAwesome; font-size: inherit; text-rendering: auto; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }';

                    }

                }            
            
                if( $lead_icon_color ) {
                    $css_style.= '#' . $id . ' .lead.ut-lead-has-icon::before { color:' . $lead_icon_color . '; }';
                }
            
                if( $lead_accent_color ) {
                    $css_style.= '#' . $id . ' .lead ins { color:' . $lead_accent_color . '; }';
                }
            
                if( $title_accent_color ) {
                    $css_style.= '#' . $id . ' .section-title ins { color:' . $title_accent_color . '; }';
                }
            
                if( $title_accent_color_bg ) {
                    $css_style.= '#' . $id . ' .section-title ins { background:' . $title_accent_color_bg . '; }';
                }
                
                if( $lead_accent_font_weight ) {
                    $css_style.= '#' . $id . ' .lead ins { font-weight:' . $lead_accent_font_weight . '; }';
                }                 
                
                if( $lead_margin_top ) {
                    $css_style.= '#' . $id . ' .lead { margin-top:' . $lead_margin_top . '; }';    
                }
            
                if( $lead_margin_left ) {
                    $css_style.= '#' . $id . ' .lead { margin-left:' . $lead_margin_left . '; }';    
                }
                
                if( $lead_margin_right ) {
                    $css_style.= '#' . $id . ' .lead { margin-right:' . $lead_margin_right . '; }';    
                }
                                
                $css_style .= $this->create_section_headline_style( '#' . $id, $style, $accent, $height, $width );
                
                /* spacing bottom */
                if( $spacing == 'custom' && $spacing_bottom != '' ) {
                     $css_style.= '#' . $id . ' .section-title { margin-bottom:' . $spacing_bottom . 'px; }';               
                }
            
            $css_style.= '</style>';            
            
            // animation effect
            $dataeffect = NULL;
            $animation_classes = array();
            
            if( !empty( $effect ) && $effect != 'none' ) {
                
                $attributes['data-effect']      = esc_attr( $effect );
                $attributes['data-animateonce'] = esc_attr( $animate_once );
                
                $attributes['data-delay'] = $delay == 'true' ? esc_attr( $delay_timer ) : 0;
                    
                if( !empty( $animation_duration ) ) {
                    $attributes['data-animation-duration'] = esc_attr( ut_add_timer_unit( $animation_duration, 's' ) );    
                }                
                
                $animation_classes[]  = 'ut-animate-element';
                $animation_classes[]  = 'animated';
                
                if( !$animate_tablet ) {
                    $animation_classes[]  = 'ut-no-animation-tablet';
                }
                
                if( !$animate_mobile ) {
                    $animation_classes[]  = 'ut-no-animation-mobile';
                }
                
                if( $animate_once == 'infinite' ) {
                    $animation_classes[]  = 'infinite';
                }
                                
            }
            
            // attributes string
            $animation_attributes = implode(' ', array_map(
                function ($v, $k) { return sprintf("%s=\"%s\"", $k, $v); },
                $attributes,
                array_keys( $attributes )
            ) );
            
            // add extra delay for lead
            $attributes['data-delay'] = $delay == 'true' ? esc_attr( $delay_timer_lead ) : 0;
            
            $animation_attributes_lead = implode(' ', array_map(
                function ($v, $k) { return sprintf("%s=\"%s\"", $k, $v); },
                $attributes,
                array_keys( $attributes )
            ) );
            
            // start output
            $output = '';
            
            // attach CSS
            $output .= ut_minify_inline_css( $css_style );            
            
			// can contain double classes
			$classes = array_unique( $classes );
			
            $output .= '<header id="' . $id . '" class="section-header ' . implode( ' ', $classes ) . '">';
                
                if( $title_source == 'page' ) {
                    $title = get_the_title();
                }
            
                if( !empty( $title ) ) {
                    
                    if( $style == 'pt-style-1' ) {
                        
                        $output .= '<h2 ' . $animation_attributes . ' class="bklyn-divider-styles bklyn-divider-style-1 ' . $title_style . '-title ' . implode( " ", $animation_classes ) . '"><span>' . ut_nl2br_special( $title ) . '</span></h2>';
                        
                    } else {
                        
                        $output .= '<h2 ' . $animation_attributes . ' class="' . $title_style . '-title ' . implode( " ", $animation_classes ) . '"><span>' . ut_nl2br_special( $title ) . '</span></h2>';
                        
                    }
                
                }
            
                if( !empty( $content ) ) {
                    
                    if( $lead_icon == 'true' ) {
                        
                        $animation_classes[] = 'ut-lead-has-icon';
                        
                        if( $lead_icon_type == 'bklynicons' && !empty( $lead_icon_bklyn ) ) {
                            
                            $animation_classes[] = $lead_icon_bklyn;

                        }

                        if( $lead_icon_type == 'fontawesome' && !empty( $lead_icon_fontawesome ) ) {
                            
                            $lead_icon_fontawesome = str_replace('fa fa','fa', $lead_icon_fontawesome);
                            $animation_classes[] = $lead_icon_fontawesome;

                        }
                        
                    }
                    
                    $output .= '<div ' . $animation_attributes_lead . ' class="lead ' . implode( " ", $animation_classes ) . '">';
                        
                        $output .= do_shortcode( wpautop( $content, true ) );                        
                    
                    $output .= '</div>'; 
                
                }
                
            $output .= '</header>';            
            
            if( defined( 'WPB_VC_VERSION' ) ) { 
                
                return '<div class="wpb_content_element ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->shortcode, $atts ) . '">' . $output . '</div>'; 
            
            }
                            
            return $output;
        
        }
        
        function create_section_headline_style( $div = '',  $style = 'pt-style-1' , $color = '' , $height = '' , $width = '' ) {
        
            if( empty( $color ) && $style != 'pt-style-4' && $style != 'pt-style-2' ) {
                
                // nothing to do here, let's leave
                return;
                
            }
            
            switch ( $style ) {
                
                case 'pt-style-1':
                    return;
                break;
                
                case 'pt-style-2':
                    
                    return '
                        ' . $div . '.pt-style-2 .page-title:after, 
                        ' . $div . '.pt-style-2 .parallax-title:after, 
                        ' . $div . '.pt-style-2 .section-title:after {
                            background-color: ' . $color . ' !important;
                            height: ' . $height .';
                            width: ' . $width . ';
                        }
                    ';
                    
                break;
                
                case 'pt-style-3':
                    
                    return '
                        ' . $div . '.pt-style-3 .page-title span, 
                        ' . $div . '.pt-style-3 .parallax-title span, 
                        ' . $div . '.pt-style-3 .section-title span { 
                            background:' . $color . ' !important;            
                            -webkit-box-shadow:0 0 0 3px' . $color . ' !important; 
                            -moz-box-shadow:0 0 0 3px' . $color . ' !important; 
                            box-shadow:0 0 0 3px' . $color . ' !important; 
                        }
                    ';                
                    
                break;
                
                case 'pt-style-4':
                    
                    return '
                        ' . $div . '.pt-style-4 .page-title span, 
                        ' . $div . '.pt-style-4 .parallax-title span, 
                        ' . $div . '.pt-style-4 .section-title span {
                            border-color:' . $color . ';
                            border-width:' . $width . ';
                        }
                    ';
                    
                break;
                
                case 'pt-style-5':
                    
                    return '
                        ' . $div . '.pt-style-5 .page-title span, 
                        ' . $div . '.pt-style-5 .parallax-title span, 
                        ' . $div . '.pt-style-5 .section-title span {
                            background:' . $color . ';            
                            -webkit-box-shadow:0 0 0 3px' . $color . '; 
                            -moz-box-shadow:0 0 0 3px' . $color . '; 
                            box-shadow:0 0 0 3px' . $color . '; 
                        }
                    ';
                    
                break;
                
                
                case 'pt-style-6':
                    
                    return '
                        ' . $div .'.pt-style-6 .page-title:after, 
                        ' . $div .'.pt-style-6 .parallax-title:after, 
                        ' . $div .'.pt-style-6 .section-title:after {
                            border-bottom: 1px dotted ' . $color . ';
                        }
                    ';
                
                break;
                
                
            }
            
        }
            
    }

}

new UT_Header;