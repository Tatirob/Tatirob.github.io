<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'UT_Testimonial_Rotator' ) ) {
	
    class UT_Testimonial_Rotator {
		
		private $shortcode;
		private $add_script;
		
		function __construct() {
			
			$this->shortcode = 'ut_testimonial_rotator';
			
			add_action( 'init', array( $this, 'ut_map_shortcode' ) );
			add_shortcode( $this->shortcode, array( $this, 'ut_create_shortcode' ) );
			
			add_action( 'init', array( $this, 'register_scripts' ) );
            add_action( 'wp_footer', array( $this, 'enqueue_scripts' ) );  			
			
		}		
				
		function ut_map_shortcode( $atts, $content = NULL ) {
						
			vc_map(
				array(
					'name'                  => esc_html__( 'Testimonial Rotator', 'ut_shortcodes' ),
					'description'     		=> esc_html__( 'Please this element inside a fullwidth row.', 'ut_shortcodes' ),
					'base'                  => $this->shortcode,
					'icon'                  => UT_SHORTCODES_URL . '/admin/img/vc_icons/quote-rotator.png',
					'category'              => 'Community',
					'class'                 => 'ut-vc-icon-module ut-community-module', 
					'as_parent'             => array( 'only' => 'ut_single_quote' ),
					'is_container'          => true,
					'params'                => array(

						array(
							'type'          => 'dropdown',
							'heading'       => esc_html__( 'Autoplay Slider?', 'ut_shortcodes' ),
							'param_name'    => 'autoplay',
							'group'         => 'Slider Settings',
							'value'         => array(
								esc_html__( 'no', 'ut_shortcodes' ) => 'false',
								esc_html__( 'yes'  , 'ut_shortcodes' ) => 'true'
							),
						),
						array(
							'type'          => 'textfield',
							'heading'       => esc_html__( 'Autoplay Timeout', 'ut_shortcodes' ),
							'description'   => esc_html__( 'Autoplay interval timeout in milliseconds. Default: 5000' , 'ut_shortcodes' ),
							'param_name'    => 'autoplay_timeout',
							'group'         => 'Slider Settings',
							'dependency'    => array(
								'element' => 'autoplay',
								'value'   => array( 'true' ),
							)

						),
						array(
							'type'          => 'dropdown',
							'heading'       => esc_html__( 'Loop Slider?', 'ut_shortcodes' ),
							'param_name'    => 'loop',
							'group'         => 'Slider Settings',
							'value'         => array(
								esc_html__( 'yes'  , 'ut_shortcodes' ) => 'true',
								esc_html__( 'no', 'ut_shortcodes' ) => 'false'
							),
							'dependency'    => array(
								'element' => 'type',
								'value'   => array( 'single' ),
							) 
						),
						array(
							'type'          => 'dropdown',
							'heading'       => esc_html__( 'Show Next / Prev Navigation?', 'ut_shortcodes' ),
							'param_name'    => 'nav',
							'group'         => 'Slider Settings',
							'value'         => array(
								esc_html__( 'yes'  , 'ut_shortcodes' ) => 'true',
								esc_html__( 'no', 'ut_shortcodes' ) => 'false'
							),
						),
						array(
							'type'              => 'dropdown',
							'heading'           => esc_html__( 'Animation Effect', 'ut_shortcodes' ),
							'param_name'        => 'effect',
							'group'             => 'Slider Settings',
							'value'         => array(
								esc_html__( 'Slide'  , 'ut_shortcodes' ) => 'slide',
								esc_html__( 'Fade', 'ut_shortcodes' ) => 'fade'
							),                                
						),
						array(
							'type'              => 'textfield',
							'heading'           => esc_html__( 'CSS Class', 'ut_shortcodes' ),
							'description'       => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'ut_shortcodes' ),
							'param_name'        => 'class',
							'group'             => 'Slider Settings'
						),                            

						// css editor
						array(
							'type'              => 'css_editor',
							'param_name'        => 'css',
							'group'             => esc_html__( 'Design Options', 'ut_shortcodes' ),
						)                            

					),
					'js_view'         => 'VcColumnView'                        

				)

			);
			
		}
		
		
		function ut_create_shortcode( $atts, $content = NULL ) {
            
            /* enqueue scripts */
            $this->add_script = true;
            
            extract( shortcode_atts( array (
                'nav'               => 'true',
                'effect'            => '',       
                'animate_once'      => 'no',
                'animate_mobile'    => false,
                'animate_tablet'    => false,
                'delay'             => 'false',
                'delay_timer'       => '100',
                'animation_duration'=> '',   
                'css'               => '',                
                'class'             => ''
            ), $atts ) ); 
            
            /* class array */
            $classes    = array();
            $attributes = array();
            
            /* extra element class */
            $classes[] = $class;
            
            /* animation effect */
            $dataeffect = NULL;
            
            if( !empty( $effect ) ) {
                
                $attributes['data-effect']      = esc_attr( $effect );
                $attributes['data-animateonce'] = esc_attr( $animate_once );
                $attributes['data-delay'] = $delay == 'true' ? esc_attr( $delay_timer ) : 0;
                
                if( !empty( $animation_duration ) ) {
                    $attributes['data-animation-duration'] = esc_attr( ut_add_timer_unit( $animation_duration, 's' ) );    
                }
                
                $classes[]  = 'ut-animate-element';
                $classes[]  = 'animated';
                
                if( !$animate_tablet ) {
                    $classes[]  = 'ut-no-animation-tablet';
                }
                
                if( !$animate_mobile ) {
                    $classes[]  = 'ut-no-animation-mobile';
                }
                
                if( $animate_once == 'infinite' ) {
                    $classes[]  = 'infinite';
                }
                
            }     
            
            /* attributes string */
            $attributes = implode(' ', array_map(
                function ($v, $k) { return sprintf("%s=\"%s\"", $k, $v); },
                $attributes,
                array_keys( $attributes )
            ) ); 
            
            /* set unique ID for this rotator */
            $id = uniqid("qtSlider_");
            $outer_id = uniqid("qtSliderOuter");
            
            /* start output */
            $output = '';            
            
            /* attach script */
            $output .= $this->ut_create_inline_script( $id, $atts, substr_count( $content, '[ut_single_quote') );
            
            /* attach css */
            // $output .= ut_minify_inline_css( $this->ut_create_inline_css( $outer_id, $atts ) );
            
            $output .= '<div ' . $attributes . ' class="ut-bkly-testimonial-rotator ' . implode( ' ', $classes ) . '">';
            
                $output .= '<div id="' . esc_attr( $id ) . '" class="owl-carousel owl-theme">';
                    
                    $output .= do_shortcode( $content );
                         
                $output .= '</div>';
                
                if( $nav == 'true' ) {
                        
                    // $output .= '<a href="#" data-for="' . esc_attr( $id ) . '" class="ut-prev-gallery-slide"><i class="Bklyn-Core-Left-2"></i></a>';
                    // $output .= '<a href="#" data-for="' . esc_attr( $id ) . '" class="ut-next-gallery-slide"><i class="Bklyn-Core-Right-2"></i></a>';                 
                
                }
            
            $output .= '</div>';
            
            if( defined( 'WPB_VC_VERSION' ) ) { 
                
                return '<div id="'. esc_attr( $outer_id ).'" class="wpb_content_element ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->shortcode, $atts ) . '">' . $output . '</div>'; 
            
            }
                
            return $output;
        
		}
		
		
		function ut_create_inline_script( $id, $atts, $slides ) {
            
            /* no custom js for search excerpts */
            if( is_search() ) {
                return;
            }
            
            $loop = $slides > 1 ? 'true' : 'false';
            
            extract( shortcode_atts( array (
                'effect'           => 'slide',
                'autoplay'         => 'false',
                'autoplay_timeout' => 5000,
                'loop'             => $loop,
            ), $atts ) );
            
            ob_start();
            
            ?>
            
            <script type="text/javascript">
                
                (function($){
                                            
                    $(document).ready(function(){
                        
                        var $<?php echo esc_attr( $id ); ?> =  $("#<?php echo esc_attr( $id ); ?>");
                        
                        $<?php echo esc_attr( $id ); ?>.owlCarousel({
                            items:3,
                            smartSpeed: 600,
                            lazyLoad: true,
							center: true,
                            <?php if( $effect == 'fade' ) : ?>
                            animateIn: "fadeIn",
                            animateOut: "fadeOut",
                            <?php endif; ?>
                            autoplay: <?php echo $autoplay; ?>,
                            autoplayTimeout: <?php echo $autoplay_timeout; ?>,
							autoplayHoverPause: true, 
                            loop:<?php echo $loop; ?>,
                            nav: false,
                            dots: true,
							responsiveClass: true,
							responsive: {
                                0: {
                                    items: 1
                                },
                                768: {
                                    items: 1
                                },
                                1025: {
                                    items: 3
                                }
                            },
							onRefresh: function () {
								
								$<?php echo esc_attr( $id ); ?>.find('div.owl-item').height('');								
							
							},
							onRefreshed: function () {
								
								$<?php echo esc_attr( $id ); ?>.find('div.owl-item').height( $<?php echo esc_attr( $id ); ?>.height() );								
								
							}
																	
                        });
						
					
						$(document).on('click', '#<?php echo esc_attr( $id ); ?> .owl-item.active:not(.center)', function(event) {
							
							if( $(this).prev().hasClass("center") ) {
								
								$<?php echo esc_attr( $id ); ?>.trigger('next.owl.carousel');
							   
							} else {
								
								$<?php echo esc_attr( $id ); ?>.trigger('prev.owl.carousel'); 
								
							}
							
						});
					
                    });
                        
                })(jQuery);
                    
            </script>
            
            <?php
            
            return ob_get_clean();
        
        }
		
		function register_scripts() {
            
            $min = NULL;
        
            if( !WP_DEBUG ){
                $min = '.min';
            } 
            
            wp_register_script( 
                'ut-owl-carousel', 
                plugins_url('../js/plugins/owlsider/js/owl.carousel' . $min . '.js', __FILE__), 
                array('jquery'), 
                '2.0.0', 
                true
            );
            
            wp_register_style(
                'ut-owl-carousel', 
                plugins_url('../js/plugins/owlsider/css/owl.carousel' . $min . '.css', __FILE__), 
                array(), 
                '2.0.0' 
            );
            
            wp_register_style(
                'ut-owl-carousel-theme', 
                plugins_url('../js/plugins/owlsider/css/owl.theme.default' . $min . '.css', __FILE__), 
                array(), 
                '2.0.0' 
            );
            
            
        
        }
		
		function enqueue_scripts() {
            
            if( !$this->add_script ) {
                return;
            }
            
            wp_print_scripts('ut-owl-carousel');
            wp_print_styles('ut-owl-carousel');
            wp_print_styles('ut-owl-carousel-theme');
        
        }		
		
	}
	
}

new UT_Testimonial_Rotator();


if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    
    class WPBakeryShortCode_ut_testimonial_rotator extends WPBakeryShortCodesContainer {
    
    }
    
}