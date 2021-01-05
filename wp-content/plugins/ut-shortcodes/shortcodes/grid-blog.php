<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'UT_Grid_Blog' ) ) {
	
    class UT_Grid_Blog {
        
        private $shortcode;
            
        function __construct() {
			
            /* shortcode base */
            $this->shortcode = 'ut_grid_blog';
            
            add_action( 'init', array( $this, 'ut_map_shortcode' ) );
            add_shortcode( $this->shortcode, array( $this, 'ut_create_shortcode' ) );	
            
		}
        
        function ut_map_shortcode( $atts, $content = NULL ) {
            
            if( function_exists( 'vc_map' ) ) {
                                
                vc_map(
                    array(
                        'name'            => esc_html__( 'Latest Blog Posts', 'ut_shortcodes' ),
						'description'     => esc_html__( 'A grid blog containing the latest posts or posts of a chosen category.', 'ut_shortcodes' ),
                        'base'            => $this->shortcode,
                        // 'icon'            => 'fa fa-th ut-vc-module-icon',
                        'icon'            => UT_SHORTCODES_URL . '/admin/img/vc_icons/blog-excerpt.png',
                        'category'        => 'Community',
                        'class'           => 'ut-vc-icon-module ut-community-module', 
                        'content_element' => true,
                        'params'          => array(
                            
                            array(
                                'type'        => 'textfield',
                                'heading'     => esc_html__( 'Posts to Display', 'ut_shortcodes' ),
                                'description' => esc_html__( 'Enter desired amount of posts to display. Default 3.', 'ut_shortcodes' ),
                                'param_name'  => 'numberposts',
                                'group'       => 'General'
                            ),
                    
                            array(
                                'type'        => 'textfield',
                                'heading'     => esc_html__( 'Excerpt Length', 'ut_shortcodes' ),
                                'description' => esc_html__( '', 'ut_shortcodes' ),
                                'param_name'  => 'excerpt',
                                'group'       => 'General'
                            ),
							
							array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Hide Categories', 'ut_shortcodes' ),
                                'param_name'        => 'hide_meta_categories',
								'edit_field_class'  => 'vc_col-sm-6',
                                'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'no, thanks!', 'ut_shortcodes' ) => '',
                                    esc_html__( 'yes, please!'  , 'ut_shortcodes' ) => 'on'
                                ),
                            ),
							
							array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Hide Author and Comments', 'ut_shortcodes' ),
                                'param_name'        => 'hide_meta_footer',
								'edit_field_class'  => 'vc_col-sm-6',
                                'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'no, thanks!', 'ut_shortcodes' ) => '',
                                    esc_html__( 'yes, please!'  , 'ut_shortcodes' ) => 'on'
                                ),
                            ),
							
							array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Hide Date', 'ut_shortcodes' ),
                                'param_name'        => 'hide_date',
								'edit_field_class'  => 'vc_col-sm-6',
                                'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'no, thanks!', 'ut_shortcodes' ) => '',
                                    esc_html__( 'yes, please!'  , 'ut_shortcodes' ) => 'on'
                                ),
                            ),
							
							array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Disable Box Shadow', 'ut_shortcodes' ),
                                'param_name'        => 'disable_shadow',
								'edit_field_class'  => 'vc_col-sm-6',
                                'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'no, thanks!', 'ut_shortcodes' ) => '',
                                    esc_html__( 'yes, please!'  , 'ut_shortcodes' ) => 'on'
                                ),
                            ),
							
							array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Disable Box Border', 'ut_shortcodes' ),
                                'param_name'        => 'disable_border',
								'edit_field_class'  => 'vc_col-sm-6',
                                'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'no, thanks!', 'ut_shortcodes' ) => '',
                                    esc_html__( 'yes, please!'  , 'ut_shortcodes' ) => 'on'
                                ),
                            ),
							
							array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Disable Post Excerpt Content Padding', 'ut_shortcodes' ),
								'description' 		=> esc_html__( 'Once Border and Shadow are deactivated, the post excerpt still has its default spacing. With the help of this option, you can remove this spacing.', 'ut_shortcodes' ),
                                'param_name'        => 'disable_content_padding',
                                'group'             => 'General',
                                'value'             => array(
                                    esc_html__( 'no, thanks!', 'ut_shortcodes' ) => '',
                                    esc_html__( 'yes, please!'  , 'ut_shortcodes' ) => 'on'
                                ),
                            ),
							
							// Query Settings
                            array(
                                'type'        => 'textfield',
                                'heading'     => esc_html__( 'Show Posts by Category ID', 'ut_shortcodes' ),
                                'description' => esc_html__( 'Enter category ID, separate multiple category IDs with comma.', 'ut_shortcodes' ),
                                'param_name'  => 'cat',
                                'group'       => 'Query Settings'
                            ),        
                            array(
                                'type'        => 'textfield',
                                'heading'     => esc_html__( 'Show Posts by Category Name', 'ut_shortcodes' ),
                                'description' => esc_html__( 'Enter category slug, separate multiple category slugs with comma.', 'ut_shortcodes' ),
                                'param_name'  => 'category_name',
                                'group'       => 'Query Settings'
                            ),
														
							// Button Settings
							array(
                                'type'        => 'textfield',
                                'heading'     => esc_html__( 'Button Text leading to Main Blog.', 'ut_shortcodes' ),
                                'param_name'  => 'button_text',
                                'group'       => 'Blog Button'
                            ),
							
							array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Add Icon?', 'unitedthemes' ),
                                'param_name'        => 'button_add_icon',
                                'group'             => 'Blog Button',
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'no',
                                    esc_html__( 'yes', 'ut_shortcodes' ) => 'yes'                                    
                                )                                
                            ),
                            
                            array(
                                'type'          => 'dropdown',
                                'heading'       => esc_html__( 'Icon library', 'ut_shortcodes' ),
                                'description'   => esc_html__( 'Select icon library.', 'ut_shortcodes' ),
                                'param_name'    => 'button_icon_type', 
                                'group'         => 'Blog Button', 
                                'value'         => array(
                                    esc_html__( 'Brooklyn Icons', 'ut_shortcodes' ) => 'bklynicons',
                                    esc_html__( 'Font Awesome', 'ut_shortcodes' ) => 'fontawesome',                                    
                                ),
                                'dependency'        => array(
                                    'element' => 'button_add_icon',
                                    'value'   => 'yes',
                                ),                               
                            ),
                            
                            array(
								'type'              => 'iconpicker',
                                'heading'           => esc_html__( 'Choose Icon', 'ut_shortcodes' ),
                                'param_name'        => 'button_icon',
                                'group'             => 'Blog Button',                                
                                'dependency' => array(
                                    'element'   => 'button_icon_type',
                                    'value'     => 'fontawesome',
                                ),
                            ),
                            
                            array(
								'type'              => 'iconpicker',
                                'heading'           => esc_html__( 'Choose Icon', 'ut_shortcodes' ),
                                'param_name'        => 'button_icon_bklyn',
                                'group'             => 'Blog Button',
                                'settings' => array(
                                    'emptyIcon'     => true,
                                    'type'          => 'bklynicons',
                                ),
                                'dependency' => array(
                                    'element'   => 'button_icon_type',
                                    'value'     => 'bklynicons',
                                ),
                                                                
                            ),
                            
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Icon Alignment', 'ut_shortcodes' ),
								'param_name'        => 'button_icon_align',
								'group'             => 'Blog Button',
                                'value'             => array(
                                    esc_html__( 'left'  , 'ut_shortcodes' ) => 'left',
                                    esc_html__( 'right' , 'ut_shortcodes' ) => 'right',
                                ),
                                'dependency'        => array(
                                    'element' => 'button_add_icon',
                                    'value'   => 'yes',
                                ),
						  	),
							
							/* Button Colors */
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Button Effect', 'unitedthemes' ),
                                'param_name'        => 'button_effect',
                                'group'             => 'Blog Button Colors',
                                'value'             => array(
                                    esc_html__( 'No Effect', 'ut_shortcodes' ) => 'none',
                                    esc_html__( 'Aylen', 'ut_shortcodes' ) => 'aylen',
                                    esc_html__( 'Winona', 'ut_shortcodes' ) => 'winona'
                                )                                
                            ),                            
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Text Color', 'ut_shortcodes' ),
								'param_name'        => 'button_text_color',
								'group'             => 'Blog Button Colors'
						  	),
                            array(
								'type'              => 'gradient_picker',
								'heading'           => esc_html__( 'Background Color', 'ut_shortcodes' ),
								'param_name'        => 'button_background',
								'group'             => 'Blog Button Colors'
						  	),
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Text Hover Color', 'ut_shortcodes' ),
								'param_name'        => 'button_text_color_hover',
								'group'             => 'Blog Button Colors'
						  	),
                            array(
								'type'              => 'gradient_picker',
								'heading'           => esc_html__( 'Background Hover Color', 'ut_shortcodes' ),
								'param_name'        => 'button_background_hover',
								'group'             => 'Blog Button Colors'
						  	),
                            array(
								'type'              => 'gradient_picker',
								'heading'           => esc_html__( 'Second Background Hover Color for Button Effect', 'ut_shortcodes' ),
								'param_name'        => 'button_background_hover_2',
								'group'             => 'Blog Button Colors',
                                'dependency'        => array(
                                    'element' => 'button_effect',
                                    'value'   => array('aylen'),
                                ),
						  	),
							
							/* Button Design */
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Button Size', 'ut_shortcodes' ),
								'param_name'        => 'button_size',
								'group'             => 'Blog Button Design',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    esc_html__( 'Choose Button Size', 'ut_shortcodes' ) => '',
                                    esc_html__( 'mini'   , 'ut_shortcodes' ) => 'bklyn-btn-mini',
                                    esc_html__( 'small'  , 'ut_shortcodes' ) => 'bklyn-btn-small',
                                    esc_html__( 'normal' , 'ut_shortcodes' ) => 'bklyn-btn-normal',
                                    esc_html__( 'large'  , 'ut_shortcodes' ) => 'bklyn-btn-large',
                                ),
						  	),
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Button Alignment', 'ut_shortcodes' ),
								'description'       => esc_html__( 'If this button belongs to a button group, the alignment is controlled by the button group setting.', 'ut_shortcodes' ),
                                'param_name'        => 'button_align',
								'group'             => 'Blog Button Design',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    esc_html__( 'center', 'ut_shortcodes' ) => 'bklyn-btn-center',
                                    esc_html__( 'left'  , 'ut_shortcodes' ) => 'bklyn-btn-left',
                                    esc_html__( 'right' , 'ut_shortcodes' ) => 'bklyn-btn-right',
                                ),
						  	),
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Make Button Fluid?', 'unitedthemes' ),
                                'param_name'        => 'button_fluid',
                                'group'             => 'Blog Button Design',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'no',
                                    esc_html__( 'yes', 'ut_shortcodes' ) => 'yes'                                    
                                )                                
                            ),
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Activate Button Shadow?', 'unitedthemes' ),
                                'param_name'        => 'button_hover_shadow',
                                'group'             => 'Blog Button Design',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    esc_html__( 'no, thanks!', 'ut_shortcodes' ) => 'no',
                                    esc_html__( 'yes, display shadow on mouse over!', 'ut_shortcodes' ) => 'yes',
									esc_html__( 'yes, display shadow by default and on mouse over!', 'ut_shortcodes' ) => 'yes_default'
                                )                                
                            ), 
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Radius', 'ut_shortcodes' ),
								'description'       => esc_html__( 'Border Radius is not supported if you are using gradient border colors.', 'unitedthemes' ),
                                'param_name'        => 'button_border_radius',
                                'group'             => 'Blog Button Design',
                                'value'             => array(
                                    'min'   => '0',
                                    'max'   => '50',
                                    'step'  => '1',
                                    'unit'  => 'px'
                                ),								
						  	), 
                            
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Activate Button Border?', 'unitedthemes' ),
                                'param_name'        => 'button_custom_border',
                                'group'             => 'Blog Button Design',
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'no',
                                    esc_html__( 'yes', 'ut_shortcodes' ) => 'yes'                                    
                                )                                
                            ),                            
                            array(
								'type'              => 'gradient_picker',
								'heading'           => esc_html__( 'Border Color', 'ut_shortcodes' ),
								'param_name'        => 'button_border_color',
								'group'             => 'Blog Button Design',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'dependency'        => array(
                                    'element' => 'button_custom_border',
                                    'value'   => 'yes',
                                ),
						  	),
                            array(
								'type'              => 'gradient_picker',
								'heading'           => esc_html__( 'Border Hover Color', 'ut_shortcodes' ),
								'param_name'        => 'button_border_color_hover',
								'group'             => 'Blog Button Design',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'dependency'        => array(
                                    'element' => 'button_custom_border',
                                    'value'   => 'yes',
                                ),
						  	),
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Border Style', 'ut_shortcodes' ),
								'description'       => esc_html__( 'Border Style is not supported if you are using gradient border colors.', 'unitedthemes' ),
								'param_name'        => 'button_border_style',
								'group'             => 'Blog Button Design',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    esc_html__( 'solid' , 'ut_shortcodes' ) => 'solid',
                                    esc_html__( 'dotted', 'ut_shortcodes' ) => 'dotted',
                                    esc_html__( 'dashed', 'ut_shortcodes' ) => 'dashed',
                                    esc_html__( 'double', 'ut_shortcodes' ) => 'double'
                                ),
                                'dependency'        => array(
                                    'element' => 'button_custom_border',
                                    'value'   => 'yes',
                                ),
						  	),
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Border Width', 'ut_shortcodes' ),
								'param_name'        => 'button_border_width',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    'min'   => '0',
                                    'max'   => '50',
                                    'step'  => '1',
                                    'unit'  => 'px'
                                ),
								'group'             => 'Blog Button Design',
                                'dependency'        => array(
                                    'element' => 'button_custom_border',
                                    'value'   => 'yes',
                                ),
						  	),
							
							array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Font Family', 'ut_shortcodes' ),
								'param_name'        => 'font_family',
								'group'             => 'Blog Button Font',
                                'value'             => array(
                                    
                                    esc_html__( 'Sans Serif (default)' , 'ut_shortcodes' ) => '',
                                    esc_html__( 'Body Font (inherit)' , 'ut_shortcodes' ) => 'inherit',
                                    
                                ),
						  	),
                    
                            /* Font Settings */
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Font Weight', 'ut_shortcodes' ),
								'param_name'        => 'font_weight',
								'group'             => 'Blog Button Font',
                                'value'             => array(
                                    esc_html__( 'Select Font Weight' , 'ut_shortcodes' ) => '',
                                    esc_html__( 'normal' , 'ut_shortcodes' )             => 'normal',
                                    esc_html__( 'bold' , 'ut_shortcodes' )               => 'bold'
                                ),
						  	),
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Text Transform', 'ut_shortcodes' ),
								'param_name'        => 'text_transform',
								'group'             => 'Blog Button Font',
                                'value'             => array(
                                    esc_html__( 'Select Text Transform' , 'ut_shortcodes' ) => '',
                                    esc_html__( 'capitalize' , 'ut_shortcodes' ) => 'capitalize',
                                    esc_html__( 'uppercase', 'ut_shortcodes' ) => 'uppercase',
                                    esc_html__( 'lowercase', 'ut_shortcodes' ) => 'lowercase'                                    
                                ),
						  	),
                            array(
								'type'              => 'range_slider',
								'heading'           => esc_html__( 'Letter Spacing', 'ut_shortcodes' ),
								'param_name'        => 'letter_spacing',
                                'group'             => 'Blog Button Font',
                                'value'             => array(
                                    'default'   => '0',
                                    'min'       => '-10',
                                    'max'       => '10',
                                    'step'      => '1',
                                    'unit'      => 'px'
                                ),								
						  	),
                                                        
                            /* CSS Editor */
                            array(
                                'type'              => 'ut_css_editor',
                                'heading'           => esc_html__( 'Button Spacing', 'ut_shortcodes' ),
                                'param_name'        => 'spacing',
                                'group'             => 'Button Spacing',
                            )
                            
                            
                            
                        )                        
                        
                    )
                
                ); /* end mapping */
                
            } 
        
        }
        
        function ut_create_shortcode( $atts, $content = NULL ) {
            
            $class = '';
            
            extract( shortcode_atts( array (
                
				'numberposts'     		=> '3',
                'excerpt'         		=> '40',
				'hide_meta_categories'	=> '',
				'hide_meta_footer'		=> '',
				'hide_date'				=> '',
				'disable_shadow'		=> '',
				'disable_border'		=> '',
				'disable_content_padding' => '',
                'cat'             		=> '',
                'category_name'   		=> '',                
                'button_text'      		=> '',
                'class'           		=> '',
				
				// Blog Button
				'button_align'          => 'bklyn-btn-center',
                'button_size'           => 'bklyn-btn-normal',
                'button_fluid'          => '',
                'button_hover_shadow'   => 'no',
                
                /* icons */
                'button_add_icon'           => '',
                'button_icon_type'          => 'bklynicons',
                'button_icon'               => '',
                'button_icon_bklyn'         => '',
                'button_icon_align'         => 'left',
				
				/* color settings */
                'button_effect'             => 'none',
                'button_text_color'         => '',
                'button_text_color_hover'   => '',
                'button_background'         => '',
                'button_background_hover'   => '',
                'button_background_hover_2' => '',
                
                /* border settings */
                'button_custom_border'      => '',
                'button_border_color'       => '',
                'button_border_color_hover' => '',
                'button_border_style'       => '',
                'button_border_width'       => '',
                'button_border_radius'      => '',
                
                /* font */
                'font_family'               => '',
                'font_weight'               => '',
                'letter_spacing'            => '',
                'text_transform'            => '',
                
                /* css */
                'spacing'                   => '',
				
            ), $atts ) ); 
            
			// avoid error with early hooks
			if( !function_exists("unite_get_excerpt_by_id") ) {
				return;
			}			
			
            $args = array(
                'post_type'      => 'post',
                'cat'            => $cat,
                'category_name'  => $category_name,
                'posts_per_page' => $numberposts,    
            );
			
			$id = uniqid("ut_blog_grid_");
			
			$css_style = '<style type="text/css" scoped>';
			
				if( $hide_date ) {
					$css_style .= '#' . esc_attr( $id ) . ' .date-format { display:none; }';	
				}
			
				if( $disable_shadow ) {
					$css_style .= '#' . esc_attr( $id ) . ' .ut-blog-grid-article-inner { -webkit-box-shadow:none; -moz-box-shadow:none; box-shadow:none; }';
				}
			
				if( $disable_border ) {
					$css_style .= '#' . esc_attr( $id ) . ' .ut-blog-grid-article-inner { border:none; }';
				}
				
				if( $disable_content_padding ) {
					$css_style .= '#' . esc_attr( $id ) . ' .ut-blog-grid-content-wrap { padding:40px 0 0 0; }';
				}
			
			$css_style .= '</style>';
			
			
			ob_start();			
            
			$hide_meta_categories = $hide_meta_categories == "on";
			$hide_meta_footer = $hide_meta_footer == "on";
			
			if( defined('UT_THEME_VERSION') ) {
				
				echo $css_style;
				
				echo '<div id="' . esc_attr( $id ) . '" class="ut-blog-grid-module blog">';
				
					echo '<div class="ut-blog-grid clearfix ' . $class . '">';

						// initiate query
						$blog_query = new WP_Query( $args );

						// start loop
						if ( $blog_query->have_posts() ) : 

							global $post;
                            global $is_grid_blog;
                
                            $is_grid_blog = true;

							while ( $blog_query->have_posts() ) : $blog_query->the_post();

								setup_postdata( $post );

								if( get_post_format() ) {

									include( get_template_directory() . '/partials/blog-grid/content-' . get_post_format() . '.php' );

								} else {

									include( get_template_directory() . '/partials/blog-grid/content.php' );

								}

							endwhile; 

						endif;        

						// restore original post data
						wp_reset_postdata();

					echo '</div>';
				
				echo '</div>';
				
				if( $button_text ) {
					
					// set blog link
					$blog_id = get_option('page_for_posts');  
					$atts['button_plain_link'] = get_permalink( $blog_id );
					
					// Blog Button
					$blog_button = new UT_BTN();
					echo $blog_button->ut_create_shortcode( $atts );
					
				}
            
			} else {
				
				echo esc_html__( 'Brooklyn Theme not active!', 'ut_shortcodes' );
				
			}
				
            return ob_get_clean();
        
        }
            
    }

}

new UT_Grid_Blog;