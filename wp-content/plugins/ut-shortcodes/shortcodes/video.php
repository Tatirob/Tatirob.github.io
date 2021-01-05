<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'UT_Video_Shortcode' ) ) {
	
    class UT_Video_Shortcode {
        
        private $shortcode;
            
        function __construct() {
			
            // shortcode base
            $this->shortcode = 'ut_video_player';
            
            add_action( 'init', array( $this, 'ut_map_shortcode' ) );
            add_shortcode( $this->shortcode, array( $this, 'ut_create_shortcode' ) );	
            
            // ajax requests
            add_action( 'wp_ajax_nopriv_unite_get_video_player', array( $this, 'get_video_player' ) );
            add_action( 'wp_ajax_unite_get_video_player', array( $this, 'get_video_player' ) );            
            
		}
        
        /**
         * Render Video Player.
         *
         * @since    1.0.0
         */    
        public function get_video_player() {
        
            // get video to check
            $video = esc_url( $_POST['video'] );
            
            // needed variables 
            $embed_code = NULL;
            
            // check if youtube has been used 
            preg_match('~(?:http|https|)(?::\/\/|)(?:www.|)(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/ytscreeningroom\?v=|\/feeds\/api\/videos\/|\/user\S*[^\w\-\s]|\S*[^\w\-\s]))([\w\-]{11})[a-z0-9;:@#?&%=+\/\$_.-]*~i', trim($video) , $matches );        
                
            if( !empty( $matches[1] ) ) {
                $embed_code = '<iframe height="315" width="560" src="//www.youtube.com/embed/'.trim($matches[1]).'?wmode=transparent&vq=hd720&autoplay=1" wmode="Opaque" allowfullscreen="" frameborder="0"></iframe>';          
            }
            
            // no video found so far, try to create a player 
            if( empty($embed_code) ) {
                
                $video_embed = wp_oembed_get(trim($video));
                if( !empty($video_embed) ) {
                    $embed_code = $video_embed;            
                }
                
            }
            
            // still no video found , let's try to apply a shortcode
            if( empty( $embed_code ) ) {
                $embed_code = do_shortcode(stripslashes($video));
            
            }
             
            echo $embed_code;
            
            die(1);
        
        }
        
        function ut_map_shortcode( $atts, $content = NULL ) {
            
            if( function_exists( 'vc_map' ) ) {
                                
                vc_map(
                    array(
                        'name'            => esc_html__( 'Video Player', 'ut_shortcodes' ),
						'description' 	  => esc_html__( 'An ajax powered video player for a faster website loading and with some eyecandy features.', 'ut_shortcodes' ),
                        'base'            => $this->shortcode,
                        // 'icon'            => 'fa fa-play ut-vc-module-icon',
                        'icon'            => UT_SHORTCODES_URL . '/admin/img/vc_icons/video-player.png',
                        'category'        => 'Media',
                        'class'           => 'ut-vc-icon-module ut-media-module',
                        'content_element' => true,
                        'params'          => array(
                            
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Video URL', 'ut_shortcodes' ),
                                'description'       => esc_html__( '(required) Only the video URL eg "http://vimeo.com/62375781" or "https://youtu.be/TXQT1JKCQPo".', 'ut_shortcodes' ),
                                'param_name'        => 'url',
                                'group'             => 'General',
                                'admin_label'       => true,
                            ),
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Video Caption', 'ut_shortcodes' ),
                                'description'       => esc_html__( '(optional) Will be displayed next to the video play icon or below.', 'ut_shortcodes' ),
                                'param_name'        => 'caption',
                                'group'             => 'General',
                                'class'             => 'ut-textarea-mid-size',
                            ),
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Video Caption Position', 'ut_shortcodes' ),
								'param_name'        => 'caption_position',
								'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'default (next to playicon)' , 'ut_shortcodes' ) => 'default',
                                    esc_html__( 'bottom (below playicon)' , 'ut_shortcodes' ) => 'bottom',
                                ),                               
						  	),
                            
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Video Caption Spacing', 'ut_shortcodes' ),
								'param_name'        => 'caption_spacing',
                                'group'             => 'General',
                                'value'             => array(
                                    'default'   => '20',
                                    'min'       => '10',
                                    'max'       => '60',
                                    'step'      => '1',
                                    'unit'      => 'px'
                                ),                                
						  	),
                            
                            array(
								'type'              => 'attach_image',
								'heading'           => esc_html__( 'Poster Image', 'ut_shortcodes' ),
                                'description'       => esc_html__( '(required) A poster image will be displayed until the user decides to play the video. This saves bandwidth and increases the page speed! If you use Youtube or Vimeo this field is optional since the script will use the broadcaster thumbnail as fallback.', 'ut_shortcodes' ),
								'param_name'        => 'poster',
								'group'             => 'General',
						  	),
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Max. Width', 'ut_shortcodes' ),
								'param_name'        => 'maxwidth',
                                'group'             => 'General',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    'default' => '100',
                                    'min'     => '50',
                                    'max'     => '100',
                                    'step'    => '1',
                                    'unit'    => '%'
                                ),
						  	),
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Alignment', 'ut_shortcodes' ),
								'param_name'        => 'align',
								'group'             => 'General',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    esc_html__( 'none', 'ut_shortcodes' )   => '',
                                    esc_html__( 'left'  , 'ut_shortcodes' ) => 'left',
                                    esc_html__( 'center', 'ut_shortcodes' ) => 'center',
                                    esc_html__( 'right' , 'ut_shortcodes' ) => 'right',
                                ),
						  	),
                            
                            // font settings
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Video Caption Font Weight', 'ut_shortcodes' ),
								'param_name'        => 'caption_font_weight',
								'group'             => 'Font Settings',
                                'value'             => array(
                                    esc_html__( 'bold' , 'ut_shortcodes' )   => 'bold',
                                    esc_html__( 'normal' , 'ut_shortcodes' ) => 'normal',
									esc_html__( '100' , 'ut_shortcodes' )    => '100',
                                    esc_html__( '200' , 'ut_shortcodes' )    => '200',
                                    esc_html__( '300' , 'ut_shortcodes' )    => '300',
                                    esc_html__( '400' , 'ut_shortcodes' )    => '400',
                                    esc_html__( '500' , 'ut_shortcodes' )    => '500',
                                    esc_html__( '600' , 'ut_shortcodes' )    => '600',
                                    esc_html__( '700' , 'ut_shortcodes' )    => '700',
                                    esc_html__( '800' , 'ut_shortcodes' )    => '800',
                                    esc_html__( '900' , 'ut_shortcodes' )    => '900', 
                                ),                               
						  	),
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Video Caption Letter Spacing', 'ut_shortcodes' ),
								'param_name'        => 'caption_letter_spacing',
                                'group'             => 'Font Settings',
                                'value'             => array(
                                    'default'   => '0',
                                    'min'       => '-0.2',
                                    'max'       => '0.2',
                                    'step'      => '0.01',
                                    'unit'      => 'em'
                                ),								
						  	),
                            
                            // box settings
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Show Video Box Shadow?', 'ut_shortcodes' ),
								'param_name'        => 'video_shadow',
								'group'             => 'Box Settings',
                                'value'             => array(
                                    esc_html__( 'no, thanks!', 'ut_shortcodes' )   => 'off',
                                    esc_html__( 'yes, please!', 'ut_shortcodes' )   => 'on'                                    
                                ),
						  	),    
                            
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Video Box Shadow Style', 'ut_shortcodes' ),
								'param_name'        => 'video_shadow_style',
								'group'             => 'Box Settings',
                                'value'             => array(
                                    esc_html__( 'Default', 'ut_shortcodes' )   => '',
                                    esc_html__( 'Box Shadow Style 1', 'ut_shortcodes' )   => 'ut-box-shadow-1',
                                    esc_html__( 'Box Shadow Style 2', 'ut_shortcodes' )   => 'ut-box-shadow-2',
                                    esc_html__( 'Box Shadow Style 3', 'ut_shortcodes' )   => 'ut-box-shadow-3',
                                    esc_html__( 'Box Shadow Style 4', 'ut_shortcodes' )   => 'ut-box-shadow-4',
                                    esc_html__( 'Box Shadow Style 5', 'ut_shortcodes' )   => 'ut-box-shadow-5',
                                    esc_html__( 'Box Shadow Style 6', 'ut_shortcodes' )   => 'ut-box-shadow-6',
                                    esc_html__( 'Box Shadow Style 7', 'ut_shortcodes' )   => 'ut-box-shadow-7',
                                    esc_html__( 'Box Shadow Style 8', 'ut_shortcodes' )   => 'ut-box-shadow-8'
                                ),
                                'dependency'        => array(
                                    'element' => 'video_shadow',
                                    'value'   => 'on',
                                ),
						  	),
                            
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Shadow Color', 'ut_shortcodes' ),
								'param_name'        => 'shadow_color',
								'group'             => 'Box Settings',
                                'dependency'        => array(
                                    'element' => 'video_shadow',
                                    'value'   => 'on',
                                ),
						  	),
                            
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Shadow Blur Radius', 'ut_shortcodes' ),
								'param_name'        => 'shadow_blur_radius',
                                'group'             => 'Box Settings',
                                'value'             => array(
                                    'default'   => '20',
                                    'min'       => '1',
                                    'max'       => '40',
                                    'step'      => '1',
                                    'unit'      => 'px'
                                ),
                                'dependency'        => array(
                                    'element' => 'video_shadow',
                                    'value'   => 'on',
                                ),
						  	),
                            
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Show Video Border?', 'ut_shortcodes' ),
								'param_name'        => 'video_border',
								'group'             => 'Box Settings',
                                'value'             => array(
                                    esc_html__( 'no, thanks!', 'ut_shortcodes' ) => 'off',
                                    esc_html__( 'yes, please!', 'ut_shortcodes' ) => 'on'                                    
                                ),
						  	),
                            
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Border Color', 'ut_shortcodes' ),
								'param_name'        => 'border_color',
								'group'             => 'Box Settings',
                                'dependency'        => array(
                                    'element' => 'video_border',
                                    'value'   => 'on',
                                ),
						  	),
							
							array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Width', 'ut_shortcodes' ),
                                'param_name'        => 'border_width',
                                'group'             => 'Box Settings',
                                'value'             => array(
                                    'default'  	=> '1',
									'min'   	=> '1',
                                    'max'  	 	=> '10',
                                    'step'  	=> '1',
                                    'unit'  	=> ''
                                ),
								'dependency'        => array(
                                    'element' => 'video_border',
                                    'value'   => 'on',
                                ),
						  	),
							
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Video Box Padding', 'ut_shortcodes' ),
								'param_name'        => 'video_padding',
                                'group'             => 'Box Settings',
                                'value'             => array(
                                    'default'   => '20',
                                    'min'       => '0',
                                    'max'       => '40',
                                    'step'      => '1',
                                    'unit'      => 'px'
                                ),
                                'dependency'        => array(
                                    'element' => 'video_border',
                                    'value'   => 'on',
                                ),
						  	),
                        
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Video Caption Text Color', 'ut_shortcodes' ),
								'param_name'        => 'caption_color',
								'group'             => 'Box Settings',                                
						  	),
                            array(
								'type'              => 'gradient_picker',
								'heading'           => esc_html__( 'Overlay Color', 'ut_shortcodes' ),
								'param_name'        => 'overlay_color',
								'group'             => 'Box Settings',                                
						  	),
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Video Play Icon Style', 'ut_shortcodes' ),
								'param_name'        => 'play_icon_style',
								'group'             => 'Box Settings',
                                'value'             => array(
                                    esc_html__( 'line', 'ut_shortcodes' ) => 'line',
                                    esc_html__( 'solid', 'ut_shortcodes' ) => 'solid'                                    
                                ),
						  	),
							array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Video Play Icon Size', 'ut_shortcodes' ),
								'param_name'        => 'play_icon_size',
								'group'             => 'Box Settings',
                                'value'             => array(
                                    esc_html__( 'normal', 'ut_shortcodes' ) => '',
                                    esc_html__( 'large', 'ut_shortcodes' )  => 'large'                                    
                                ),
						  	),
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Video Play Icon Color', 'ut_shortcodes' ),
								'param_name'        => 'play_color',
								'group'             => 'Box Settings',                                
						  	),
                            array(
								'type'              => 'gradient_picker',
								'heading'           => esc_html__( 'Video Play Icon Background Color', 'ut_shortcodes' ),
								'param_name'        => 'play_bg_color',
								'group'             => 'Box Settings',                                
						  	),
                            
                            
                            // Pulse Effect
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Add Pulse Effect to Play Icon?', 'ut_shortcodes' ),
								'param_name'        => 'icon_pulsate',
								'group'             => 'Pulse Effect',
                                'value'             => array(
                                    esc_html__( 'no, thanks!', 'ut_shortcodes' ) => 'off',
                                    esc_html__( 'yes, please!', 'ut_shortcodes' ) => 'on'                                    
                                ),
						  	),                            
                            
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Use Quick Options?', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Deactivate the Quick Options in order to be able to style each pulse individually.', 'ut_shortcodes' ),
								'param_name'        => 'icon_pulsate_quick_options',
								'group'             => 'Pulse Effect',
                                'value'             => array(
                                    esc_html__( 'yes, please!', 'ut_shortcodes' ) => 'on',                                    
                                    esc_html__( 'no, thanks!', 'ut_shortcodes' ) => 'off'                                                                        
                                ),
                                'dependency'        => array(
                                    'element' => 'icon_pulsate',
                                    'value'   => 'on'
                                ),
						  	),  
                            
                            // Simple Mode
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Pulse Line Default Color', 'ut_shortcodes' ),
								'param_name'        => 'pulse_color',
								'group'             => 'Pulse Effect',
                                'dependency'        => array(
                                    'element' => 'icon_pulsate',
                                    'value'   => 'on',
                                ),
						  	),
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Pulse Line Width', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Drag the handle to set the border width.', 'ut_shortcodes' ),
								'param_name'        => 'pulse_style_width',
                                'group'             => 'Pulse Effect',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    'default'   => '1',
                                    'min'       => '1',
                                    'max'       => '10',
                                    'step'      => '1',
                                    'unit'      => 'px'
                                ),
                                'dependency'        => array(
                                    'element' => 'icon_pulsate_quick_options',
                                    'value'   => 'on',
                                ),
						  	),
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Pulse Line Style', 'ut_shortcodes' ),
                                'description'       => esc_html__( '"Double" requires a line width of 3px.', 'ut_shortcodes' ),
								'param_name'        => 'pulse_style',
								'group'             => 'Pulse Effect',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    esc_html__( 'solid' , 'ut_shortcodes' ) => 'solid',
                                    esc_html__( 'dotted', 'ut_shortcodes' ) => 'dotted',
                                    esc_html__( 'dashed', 'ut_shortcodes' ) => 'dashed',
                                    esc_html__( 'double', 'ut_shortcodes' ) => 'double'
                                ),
                                'dependency'        => array(
                                    'element' => 'icon_pulsate_quick_options',
                                    'value'   => 'on',
                                ),
						  	),
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Pulse Amount', 'ut_shortcodes' ),
								'param_name'        => 'icon_pulsate_amount_simple',
                                'group'             => 'Pulse Effect',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    'default'   => '1',
                                    'min'       => '1',
                                    'max'       => '4',
                                    'step'      => '1',
                                    'unit'      => ''
                                ),
                                'dependency'        => array(
                                    'element' => 'icon_pulsate_quick_options',
                                    'value'   => 'on'
                                ),
						  	),
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Pulse Amount', 'ut_shortcodes' ),
								'param_name'        => 'icon_pulsate_amount_advanced',
                                'group'             => 'Pulse Effect',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    'default'   => '1',
                                    'min'       => '1',
                                    'max'       => '4',
                                    'step'      => '1',
                                    'unit'      => ''
                                ),
                                'dependency'        => array(
                                    'element' => 'icon_pulsate_quick_options',
                                    'value'   => 'off'
                                ),
						  	),
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Pulse Intensity', 'ut_shortcodes' ),
								'param_name'        => 'icon_pulsate_intensity',
                                'group'             => 'Pulse Effect',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    'default'   => '25',
                                    'min'       => '15',
                                    'max'       => '50',
                                    'step'      => '5',
                                    'unit'      => ''
                                ),
                                'dependency'        => array(
                                    'element' => 'icon_pulsate',
                                    'value'   => 'on'
                                ),
						  	),
                            
                            // Pulse 1
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Pulse Line 1 Color', 'ut_shortcodes' ),
								'param_name'        => 'pulse_color_1',
								'group'             => 'Pulse Effect',
                                'dependency'        => array(
                                    'element' => 'icon_pulsate_amount_advanced',
                                    'value'   => array("1","2","3","4"),
                                ),
						  	),
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Pulse Line 1 Width', 'ut_shortcodes' ),
								'param_name'        => 'pulse_style_width_1',
                                'group'             => 'Pulse Effect',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    'default'   => '1',
                                    'min'       => '1',
                                    'max'       => '10',
                                    'step'      => '1',
                                    'unit'      => 'px'
                                ),
                                'dependency'        => array(
                                    'element' => 'icon_pulsate_amount_advanced',
                                    'value'   => array("1","2","3","4"),
                                ),
						  	),
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Pulse Line 1 Style', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Style "double" requires at least a line width of 3px.', 'ut_shortcodes' ),
								'param_name'        => 'pulse_style_1',
								'group'             => 'Pulse Effect',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    esc_html__( 'solid' , 'ut_shortcodes' ) => 'solid',
                                    esc_html__( 'dotted', 'ut_shortcodes' ) => 'dotted',
                                    esc_html__( 'dashed', 'ut_shortcodes' ) => 'dashed',
                                    esc_html__( 'double', 'ut_shortcodes' ) => 'double'
                                ),
                                'dependency'        => array(
                                    'element' => 'icon_pulsate_amount_advanced',
                                    'value'   => array("1","2","3","4"),
                                ),
						  	),
                            
                            // Pulse 2
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Pulse Line 2 Color', 'ut_shortcodes' ),
								'param_name'        => 'pulse_color_2',
								'group'             => 'Pulse Effect',
                                'dependency'        => array(
                                    'element' => 'icon_pulsate_amount_advanced',
                                    'value'   => array("2","3","4"),
                                ),
						  	),
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Pulse Line 2 Width', 'ut_shortcodes' ),
								'param_name'        => 'pulse_style_width_2',
                                'group'             => 'Pulse Effect',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    'default'   => '1',
                                    'min'       => '1',
                                    'max'       => '10',
                                    'step'      => '1',
                                    'unit'      => 'px'
                                ),
                                'dependency'        => array(
                                    'element' => 'icon_pulsate_amount_advanced',
                                    'value'   => array("2","3","4"),
                                ),
						  	),
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Pulse Line 2 Style', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Style "double" requires at least a line width of 3px.', 'ut_shortcodes' ),
								'param_name'        => 'pulse_style_2',
								'group'             => 'Pulse Effect',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    esc_html__( 'solid' , 'ut_shortcodes' ) => 'solid',
                                    esc_html__( 'dotted', 'ut_shortcodes' ) => 'dotted',
                                    esc_html__( 'dashed', 'ut_shortcodes' ) => 'dashed',
                                    esc_html__( 'double', 'ut_shortcodes' ) => 'double'
                                ),
                                'dependency'        => array(
                                    'element' => 'icon_pulsate_amount_advanced',
                                    'value'   => array("2","3","4"),
                                ),
						  	),
                            
                            // Pulse 3
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Pulse Line 3 Color', 'ut_shortcodes' ),
								'param_name'        => 'pulse_color_3',
								'group'             => 'Pulse Effect',
                                'dependency'        => array(
                                    'element' => 'icon_pulsate_amount_advanced',
                                    'value'   => array("3","4"),
                                ),
						  	),
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Pulse Line 3 Width', 'ut_shortcodes' ),
								'param_name'        => 'pulse_style_width_3',
                                'group'             => 'Pulse Effect',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    'default'   => '1',
                                    'min'       => '1',
                                    'max'       => '10',
                                    'step'      => '1',
                                    'unit'      => 'px'
                                ),
                                'dependency'        => array(
                                    'element' => 'icon_pulsate_amount_advanced',
                                    'value'   => array("3","4"),
                                ),
						  	),
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Pulse Line 3 Style', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Style "double" requires at least a line width of 3px.', 'ut_shortcodes' ),
								'param_name'        => 'pulse_style_3',
								'group'             => 'Pulse Effect',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    esc_html__( 'solid' , 'ut_shortcodes' ) => 'solid',
                                    esc_html__( 'dotted', 'ut_shortcodes' ) => 'dotted',
                                    esc_html__( 'dashed', 'ut_shortcodes' ) => 'dashed',
                                    esc_html__( 'double', 'ut_shortcodes' ) => 'double'
                                ),
                                'dependency'        => array(
                                    'element' => 'icon_pulsate_amount_advanced',
                                    'value'   => array("3","4"),
                                ),
						  	),
                            
                            // Pulse 4
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Pulse Line 4 Color', 'ut_shortcodes' ),
								'param_name'        => 'pulse_color_4',
								'group'             => 'Pulse Effect',
                                'dependency'        => array(
                                    'element' => 'icon_pulsate_amount_advanced',
                                    'value'   => array("4"),
                                ),
						  	),
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Pulse Line 4 Width', 'ut_shortcodes' ),
								'param_name'        => 'pulse_style_width_4',
                                'group'             => 'Pulse Effect',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    'default'   => '1',
                                    'min'       => '1',
                                    'max'       => '10',
                                    'step'      => '1',
                                    'unit'      => 'px'
                                ),
                                'dependency'        => array(
                                    'element' => 'icon_pulsate_amount_advanced',
                                    'value'   => array("4"),
                                ),
						  	),
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Pulse Line 4 Style', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Style "double" requires at least a line width of 3px.', 'ut_shortcodes' ),
								'param_name'        => 'pulse_style_4',
								'group'             => 'Pulse Effect',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    esc_html__( 'solid' , 'ut_shortcodes' ) => 'solid',
                                    esc_html__( 'dotted', 'ut_shortcodes' ) => 'dotted',
                                    esc_html__( 'dashed', 'ut_shortcodes' ) => 'dashed',
                                    esc_html__( 'double', 'ut_shortcodes' ) => 'double'
                                ),
                                'dependency'        => array(
                                    'element' => 'icon_pulsate_amount_advanced',
                                    'value'   => array("4"),
                                ),
						  	),
                                                        
                            // CSS
                            array(
								'type'              => 'textfield',
								'heading'           => esc_html__( 'Class', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'ut_shortcodes' ),
								'param_name'        => 'class',
								'group'             => 'General'
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
        
        function create_keyframes( $keyframe_name, $scale ) {
            
            ob_start(); ?>
            
            @keyframes <?php echo $keyframe_name; ?> {
    
                0% {
                    -webkit-transform: scale(1);
                        -moz-transofrom: scale(1);
                            transform: scale(1);
                }
                50% {
                    opacity: 0.7;
                }
                75% {
                    opacity: 0.1;
                }
                100% {
                    opacity: 0;
                    -webkit-transform: scale(<?php echo $scale/10; ?>);
                        -moz-transofrom: scale(<?php echo $scale/10; ?>);
                            transform: scale(<?php echo $scale/10; ?>);
                }

            }

            <?php
            
            return ob_get_clean();
            
        }
        
        
        function ut_create_shortcode( $atts, $content = NULL ) {
            
            extract( shortcode_atts( array (
                'url'                   => '',
                'caption'               => '',
                'caption_position'      => 'default',
                'caption_spacing'       => '20',
                'caption_font_weight'   => 'bold',
                'caption_letter_spacing'=> '',
                'caption_color'         => '',
                'overlay_color'         => '',
                'poster'                => '',
                'maxwidth'              => '',
                'align'                 => '',
                'video_border'          => 'off',
                'video_shadow'          => '',
                'video_shadow_style'    => '',
                'shadow_color'          => '',
                'shadow_blur_radius'    => '',
                'video_padding'         => '20',
                'border_color'          => '',
				'border_width'			=> '',
                'play_color'            => '',
                'play_bg_color'         => '',
                'play_icon_style'       => 'line',
				'play_icon_size'		=> '',
                
                // icon pulsate
                'icon_pulsate'                => 'off',
                'icon_pulsate_quick_options'  => 'on',
                'icon_pulsate_amount_simple'  => '1',
                'icon_pulsate_amount_advanced'=> '1',
                'icon_pulsate_intensity'      => '25',
                
                // simple mode colors
                'pulse_color'           => '#FFF',
                'pulse_style_width'     => '',
                'pulse_style'           => 'solid',                
                
                // icon 1 pulsate
                'pulse_color_1'         => '',
                'pulse_style_width_1'   => '',
                'pulse_style_1'         => 'solid',
                
                // icon 2 pulsate
                'pulse_color_2'         => '',
                'pulse_style_width_2'   => '',
                'pulse_style_2'         => 'solid',
                
                // icon 3 pulsate
                'pulse_color_3'         => '',
                'pulse_style_width_3'   => '',
                'pulse_style_3'         => 'solid',
                
                // icon 4 pulsate
                'pulse_color_4'         => '',
                'pulse_style_width_4'   => '',
                'pulse_style_4'         => 'solid',
                
                'class'                 => '' ,
                'css'                   => '',
                
            ), $atts ) ); 
            
            // extract url as fallback
            $url = ut_extract_url_from_string( $url );
            
            // we have no url, leave here 
            if( empty( $url ) ) {
                return esc_html__( 'No Video URL found!', 'ut_shortcodes' );
            } 
                        
            // settings
            $css_style = '';
            $image_icon = false;
            $classes = array();
            $classes[] = $class;
            
            $classes2 = array(); // module
            $classes3 = array(); // caption
            
            // video border
            if( $video_border == 'on' ) {
                $classes3[] = 'ut-video-module-border';
            }
            
            // video shadow
            if( $video_shadow == 'on' && empty( $video_shadow_style ) ) {
                $classes3[] = 'ut-video-module-shadow';
            }
            
            // video shadow extra style
            if( $video_shadow_style ) {
                $classes3[] = $video_shadow_style;
            }
            
            
			// icon size
			if( $play_icon_size == 'large' ) {
				$classes3[] = 'ut-video-module-play-icon-large';
			}
			
            // video align
            if( $align ) {
                $classes[] = 'ut-shortcode-video-wrap-' . $align ;
            } 
            
            // get poster image
            $poster = wp_get_attachment_image_src( $poster, 'full' );
            $poster = !empty( $poster[0] ) ? '<img src="' . esc_url( $poster[0] ) . '">' : ''; 
            
            // poster is empty - use fallback ( currently only for youtube and vimeo )
            if( empty( $poster ) ) {
                
                // youtube
                if( ut_video_is_youtube( $url ) ) {
                
                    preg_match('~(?:http|https|)(?::\/\/|)(?:www.|)(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/ytscreeningroom\?v=|\/feeds\/api\/videos\/|\/user\S*[^\w\-\s]|\S*[^\w\-\s]))([\w\-]{11})[a-z0-9;:@#?&%=+\/\$_.-]*~i', trim( $url ) , $matches );

                    if( !empty( $matches[1] ) ) {

                        $poster = '<img src="//i.ytimg.com/vi/' . $matches[1] . '/maxresdefault.jpg" alt="United Themes Shortcode Plugin">';

                    }
                
                }                   
                    
                // vimeo
                if( ut_video_is_vimeo( $url ) ) {
                    
                    $vimeo_id = extract_vimeo_id( $url );
                    
                    if( $vimeo_id ) {
                    
                        $data = file_get_contents("http://vimeo.com/api/v2/video/$vimeo_id.json");
                        $data = json_decode( $data );
                        
                        if( !empty( $data[0]->thumbnail_large ) ) {
                        
                            $poster = '<img src="' . esc_url( $data[0]->thumbnail_large ) . '">';
                            
                        }
                    
                    }                    
                    
                }                
                
            }
            
            // still empty, let's use a final fallback
            if( empty( $poster ) ) {
                $poster = '<img src="' . UT_SHORTCODES_URL . '/img/placeholder/video-poster.jpg" alt="United Themes Shortcode Plugin">';
            }
                        
            // start output
            $output = '';
                        
            // set unique ID for this video
            $id       = uniqid("ut_vs_");
            $keyframe = uniqid("ut-video-play-border-animation-");
            
            // custom CSS
            if( !empty( $maxwidth ) ) {
                // custom max width
                $css_style .= '#' . $id . '.ut-shortcode-video-wrap .ut-video-module { max-width: ' . $maxwidth . '% ; }';
            }
            
            if( $video_border == 'on' && !empty( $border_color ) ) {
                // border color
                $css_style .= '#' . $id . '.ut-shortcode-video-wrap .ut-video-module-border { border-color: ' . $border_color . '; }';    
            }
            
            if( $video_border == 'on' && !empty( $video_padding ) ) {
                // border spacing
                $css_style .= '#' . $id . '.ut-shortcode-video-wrap .ut-video-module-caption { padding: ' . $video_padding . 'px; }';    
            }
            
			if( $video_border == 'on' && !empty( $border_width ) ) {
				// border width
                $css_style .= '#' . $id . '.ut-shortcode-video-wrap .ut-video-module-caption { border-width: ' . $border_width . 'px; }';
			}
						
            if( $video_shadow == 'on' && !empty( $shadow_color ) ) {
                
                // shadow color
                $css_style .= '#' . $id . '.ut-shortcode-video-wrap .ut-video-module-shadow { color: ' . $shadow_color . '; }';
                
                if( !empty( $video_shadow_style ) ) {
                    
                    $css_style .= ut_box_shadow( $video_shadow_style, $shadow_color );
                    
                }
                
            }
            
            if( $video_shadow == 'on' && !empty( $shadow_blur_radius ) && empty( $video_shadow_style ) ) {
                
                $css_style .= '#' . $id . '.ut-shortcode-video-wrap .ut-video-module-shadow { -webkit-box-shadow: 0 0 ' . $shadow_blur_radius . 'px; box-shadow: 0 0 ' . $shadow_blur_radius . 'px; }';    
                
            }
            
            if( ut_is_gradient( $overlay_color ) ) {
                
                $css_style .= ut_create_gradient_css( $overlay_color, '#' . $id . '.ut-shortcode-video-wrap .ut-video-module-caption .ut-load-video::before', false, 'background' );

            } elseif( $overlay_color ) {

                $css_style .= '#' . $id . '.ut-shortcode-video-wrap .ut-video-module-caption .ut-load-video::before { background: ' . $overlay_color . '; }';

            }
            
            if( $caption_font_weight ) {
                $css_style .= '#' . $id . '.ut-shortcode-video-wrap .ut-video-module-caption-text span { font-weight: ' . $caption_font_weight . '; }';
            }
            
            if( $caption_letter_spacing ) {
                $css_style .= '#' . $id . '.ut-shortcode-video-wrap .ut-video-module-caption-text span { letter-spacing: ' . $caption_letter_spacing . 'em; }';
            }
            
            if( $caption_color ) {
                $css_style .= '#' . $id . '.ut-shortcode-video-wrap .ut-video-module-caption-text span { color: ' . $caption_color . '; }';                
            }
            
            if( $play_color ) {
                $css_style .= '#' . $id . '.ut-shortcode-video-wrap .ut-video-module-caption-text i { color: ' . $play_color . '; }';                
            }
            
			
			if( ut_is_gradient( $play_bg_color ) ) {
                
                $css_style .= ut_create_gradient_css( $play_bg_color, '#' . $id . '.ut-shortcode-video-wrap .ut-video-module-play-icon', false, 'background' );

            } elseif( $play_bg_color ) {

                $css_style .= '#' . $id . '.ut-shortcode-video-wrap .ut-video-module-play-icon { background: ' . $play_bg_color . '; }';

            }
						
            if( $caption_spacing && !empty( $caption ) ) {
                
                if( $caption_position == 'default' ) {
                    
                    $css_style .= '#' . $id . '.ut-shortcode-video-wrap .ut-video-module-play-icon { margin-right: ' . $caption_spacing . 'px; }';
                    
                } else {
                    
                    $css_style .= '#' . $id . '.ut-shortcode-video-wrap .ut-video-module-play-icon { margin-bottom: ' . $caption_spacing . 'px; }';
                    
                }
                
            }
            
            // Pulse CSS
            if( $icon_pulsate == 'on' ) {
                
                $css_style .= $this->create_keyframes( $keyframe, $icon_pulsate_intensity );
                
                if( $icon_pulsate_quick_options == 'on'  ) {

                    $pulsate_amount = $icon_pulsate_amount_simple;
					
					$pulse_style_width = !empty( $pulse_style_width ) ? $pulse_style_width :  1;
					
                    $css_style .= '#' . $id . ' .ut-video-play-animation-1 { border: ' . $pulse_style_width . 'px ' . $pulse_style . ' ' . $pulse_color . ' }';
                    $css_style .= '#' . $id . ' .ut-video-play-animation-2 { border: ' . $pulse_style_width . 'px ' . $pulse_style . ' ' . $pulse_color . ' }';
                    $css_style .= '#' . $id . ' .ut-video-play-animation-3 { border: ' . $pulse_style_width . 'px ' . $pulse_style . ' ' . $pulse_color . ' }';
                    $css_style .= '#' . $id . ' .ut-video-play-animation-4 { border: ' . $pulse_style_width . 'px ' . $pulse_style . ' ' . $pulse_color . ' }';
					
					for( $x = 1; $x <= $pulsate_amount; $x++ ) {
						
						$css_style .= '#' . $id . ' .ut-video-play-animation-' . $x . '.ut-video-play-animation-on {
                            -webkit-animation: 3s ease-in-out ' . ( 0.75 * $x ) . 's infinite normal none running ' . $keyframe . ';
                                -moz-animation: 3s ease-in-out ' . ( 0.75 * $x ) . 's infinite normal none running ' . $keyframe . ';
                                        animation: 3s ease-in-out ' . ( 0.75 * $x ) . 's infinite normal none running ' . $keyframe . ';   
                        }';						
						
					}
					
                } else {

                    $pulsate_amount = $icon_pulsate_amount_advanced;

                    for( $x = 1; $x <= $pulsate_amount; $x++ ) {
                        
                        $current_pulse_color = ${"pulse_color_" . $x} ? ${"pulse_color_" . $x} : $pulse_color;
                        
                        if( $current_pulse_color ) {
                            $css_style .= '#' . $id . ' .ut-video-play-animation-' . $x . ' { border-color: ' . $current_pulse_color . '; }';
                        }

                        if( ${"pulse_style_width_" . $x} ) {
                            $css_style .= '#' . $id . ' .ut-video-play-animation-' . $x . ' { border-width: ' . ${"pulse_style_width_" . $x} . 'px; }';
                        }

                        if( ${"pulse_style_" . $x} ) {
                            $css_style .= '#' . $id . ' .ut-video-play-animation-' . $x . ' { border-style: ' . ${"pulse_style_" . $x} . '; }';
                        }
                        
                        $css_style .= '#' . $id . ' .ut-video-play-animation-' . $x . '.ut-video-play-animation-on {
                            -webkit-animation: 3s ease-in-out ' . ( 0.75 * $x ) . 's infinite normal none running ' . $keyframe . ';
                                -moz-animation: 3s ease-in-out ' . ( 0.75 * $x ) . 's infinite normal none running ' . $keyframe . ';
                                        animation: 3s ease-in-out ' . ( 0.75 * $x ) . 's infinite normal none running ' . $keyframe . ';   
                        }';

                    }

                }
            
            }                
                
            if( !empty( $css_style ) ) {
            
                $output .= ut_minify_inline_css( '<style type="text/css" scoped>' . $css_style . '</style>' ); 
            
            }            
            
            $output .= '<div id="' . $id . '" class="ut-shortcode-video-wrap ' . esc_attr( implode( " ", $classes ) ) . ' clearfix">';
            
                $output .= '<div class="ut-video-module ' . esc_attr( implode( " ", $classes2 ) ) . '">';
            
                    $output .= '<div class="ut-video-module-caption ' . esc_attr( implode( " ", $classes3 ) ) . '">';

                        $output .= '<a class="ut-load-video" data-video="' . trim( $url ) . '" href="#">';
                            
                            $output .= $poster;
                        
                            $output .= '<div class="ut-video-module-caption-text">';

                                $output .= '<div class="ut-video-module-inner-caption-text ' . ( empty( $caption ) ? 'ut-video-module-empty-caption' : '' ) . ' ut-video-module-caption-position-' . $caption_position . '">'; 

                                    $output .= '<div class="ut-video-module-play-icon">';

                                        if( $icon_pulsate == 'on' ) {

                                            for( $x = 1; $x <= $pulsate_amount; $x++ ) {

                                                $output .= '<div class="ut-video-play-animation-' . $x . ' ut-video-play-animation-on"></div>';

                                            }

                                        }

                                        if( $play_icon_style == 'line' ) {

                                            $output .= '<i class="Bklyn-Core-Right-6" aria-hidden="true"></i>';      

                                        } else {

                                            $output .= '<i class="fa fa-play" aria-hidden="true"></i>'; 

                                        }

                                    $output .= '</div>';

                                    if( !empty( $caption ) ) {

                                        $output .= '<span>' . $caption . '</span>';

                                    }    

                                $output .= '</div>';

                            $output .= '</div>';
            
                        $output .= '</a>';
            
                        $output .= '</div>';
                    
                    $output .= '<div class="ut-video-module-loading">';
                        
                        $output .= '<div class="sk-fading-circle">';        
                            
                            for ($x = 1; $x <= 12; $x++) {
                                
                                $output .= '<div class="sk-circle'.$x.' sk-circle"></div>';
                                
                            }
                        
                        $output .= '</div>';
                    
                    $output .= '</div>';
            
                $output .= '</div>';
            
            $output .= '</div>';
            
            /* return player */
            if( defined( 'WPB_VC_VERSION' ) ) { 
                
                return '<div class="wpb_content_element ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->shortcode, $atts ) . '">' . $output . '</div>'; 
            
            }
           
            return $output;
        
        }
            
    }

}

new UT_Video_Shortcode;