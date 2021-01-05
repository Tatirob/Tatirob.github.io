<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'UT_Content_Block' ) ) {
	
    class UT_Content_Block {
        
        private $shortcode;
            
        function __construct() {
			
            /* shortcode base */
            $this->shortcode = 'ut_content_block';
            
            add_action( 'init', array( $this, 'ut_map_shortcode' ) );
            add_shortcode( $this->shortcode, array( $this, 'ut_create_shortcode' ) );	
            
		}
        
        function ut_map_shortcode( $atts, $content = NULL ) {
            
            if( function_exists( 'vc_map' ) ) {
                                
                vc_map(
                    array(
                        'name'            => esc_html__( 'Content Block', 'ut_shortcodes' ),
                        'base'            => $this->shortcode,
                        'category'        => 'Structual',
                        'class' 		  => 'ut-content-block',
						'class' 		  => 'vc_main-sortable-element',
						'is_container' 	  => true,
						'js_view' 		  => 'VcSectionView',
						'as_parent' => array(
							'only' => '', // no childs
						),
						'as_child' => array(
							'only' => '', // only root
						),
                        'params'          => array(
                            
							array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Select Content Block', 'ut_shortcodes' ),
								'param_name'        => 'id',
								'group'             => 'General',
                                'value'             => ut_get_content_blocks()
						  	),
                            
                        )                        
                        
                    )
                
                ); 
                
            } 
        
        }
        
        function ut_create_shortcode( $atts, $content = NULL ) {
            
            extract( shortcode_atts( array (
				'id'	=>	''	
            ), $atts ) ); 
            
            $output = '';
        

                
            return $output;
        
        }
            
    }

}

new UT_Content_Block;


class WPBakeryShortCode_UT_Content_Block extends WPBakeryShortCode {
	
	protected $predefined_atts = array(
		'el_class' => '',
	);

	public $nonDraggableClass = 'vc-non-draggable-row';

	/**
	 * @param $settings
	 */
	public function __construct( $settings ) {
		parent::__construct( $settings );
	}

	protected function content( $atts, $content = null ) {

		$prefix = '';
		return $prefix . $this->loadTemplate( $atts, $content );
		
	}

	/**
	 * This returs block controls
	 */
	public function getLayoutsControl() {
		global $vc_row_layouts;
		$controls_layout = '<span class="vc_row_layouts vc_control">';
		foreach ( $vc_row_layouts as $layout ) {
			$controls_layout .= '<a class="vc_control-set-column set_columns" data-cells="' . $layout['cells'] . '" data-cells-mask="' . $layout['mask'] . '" title="' . $layout['title'] . '"><i class="vc-composer-icon vc-c-icon-' . $layout['icon_class'] . '"></i></a> ';
		}
		$controls_layout .= '<br/><a class="vc_control-set-column set_columns custom_columns" data-cells="custom" data-cells-mask="custom" title="' . __( 'Custom layout', 'js_composer' ) . '">' . __( 'Custom', 'js_composer' ) . '</a> ';
		$controls_layout .= '</span>';

		return $controls_layout;
	}

	public function getColumnControls( $controls, $extended_css = '' ) {
		$output = '<div class="vc_controls vc_controls-row controls_row vc_clearfix">';
		$controls_end = '</div>';
		//Create columns
		$controls_layout = $this->getLayoutsControl();

		$controls_move = ' <a class="vc_control column_move vc_column-move" href="#" title="' . __( 'Drag row to reorder', 'js_composer' ) . '" data-vc-control="move"><i class="vc-composer-icon vc-c-icon-dragndrop"></i></a>';
		$moveAccess = vc_user_access()->part( 'dragndrop' )->checkStateAny( true, null )->get();
		if ( ! $moveAccess ) {
			$controls_move = '';
		}
		$controls_add = ' <a class="vc_control column_add vc_column-add" href="#" title="' . __( 'Add column', 'js_composer' ) . '" data-vc-control="add"><i class="vc-composer-icon vc-c-icon-add"></i></a>';
		$controls_delete = '<a class="vc_control column_delete vc_column-delete" href="#" title="' . __( 'Delete this row', 'js_composer' ) . '" data-vc-control="delete"><i class="vc-composer-icon vc-c-icon-delete_empty"></i></a>';
		$controls_edit = ' <a class="vc_control column_edit vc_column-edit" href="#" title="' . __( 'Edit this row', 'js_composer' ) . '" data-vc-control="edit"><i class="vc-composer-icon vc-c-icon-mode_edit"></i></a>';
		$controls_clone = ' <a class="vc_control column_clone vc_column-clone" href="#" title="' . __( 'Clone this row', 'js_composer' ) . '" data-vc-control="clone"><i class="vc-composer-icon vc-c-icon-content_copy"></i></a>';
		$controls_toggle = ' <a class="vc_control column_toggle vc_column-toggle" href="#" title="' . __( 'Toggle row', 'js_composer' ) . '" data-vc-control="toggle"><i class="vc-composer-icon vc-c-icon-arrow_drop_down"></i></a>';
		$editAccess = vc_user_access_check_shortcode_edit( $this->shortcode );
		$allAccess = vc_user_access_check_shortcode_all( $this->shortcode );

		if ( is_array( $controls ) && ! empty( $controls ) ) {
			foreach ( $controls as $control ) {
				$control_var = 'controls_' . $control;
				if ( ( $editAccess && 'edit' == $control ) || $allAccess ) {
					if ( isset( ${$control_var} ) ) {
						$output .= ${$control_var};
					}
				}
			}
			$output .= $controls_end;
		} elseif ( is_string( $controls ) ) {
			$control_var = 'controls_' . $controls;
			if ( ( $editAccess && 'edit' === $controls ) || $allAccess ) {
				if ( isset( ${$control_var} ) ) {
					$output .= ${$control_var} . $controls_end;
				}
			}
		} else {
			$row_edit_clone_delete = '<span class="vc_row_edit_clone_delete">';
			if ( $allAccess ) {
				$row_edit_clone_delete .= $controls_delete . $controls_clone . $controls_edit;
			} elseif ( $editAccess ) {
				$row_edit_clone_delete .= $controls_edit;
			}
			$row_edit_clone_delete .= $controls_toggle;
			$row_edit_clone_delete .= '</span>';

			if ( $allAccess ) {
				$output .= $controls_move . $controls_layout . $controls_add . $row_edit_clone_delete . $controls_end;
			} elseif ( $editAccess ) {
				$output .= $row_edit_clone_delete . $controls_end;
			} else {
				$output .= $row_edit_clone_delete . $controls_end;
			}
		}

		return $output;
	}

	public function contentAdmin( $atts, $content = null ) {
		$width = $el_class = '';
		$atts = shortcode_atts( $this->predefined_atts, $atts );

		$output = '';

		$column_controls = $this->getColumnControls( $this->settings( 'controls' ) );

		for ( $i = 0; $i < count( $width ); $i ++ ) {
			$output .= '<div data-element_type="' . $this->settings['base'] . '" class="ut-content-block ' . $this->cssAdminClass() . '">';
			$output .= str_replace( '%column_size%', 1, $column_controls );
			$output .= '<div class="wpb_element_wrapper">';
			$output .= '<div class="vc_row vc_row-fluid wpb_row_container vc_container_for_children">';
			if ( '' === $content && ! empty( $this->settings['default_content_in_template'] ) ) {
				$output .= do_shortcode( shortcode_unautop( $this->settings['default_content_in_template'] ) );
			} else {
				$output .= do_shortcode( shortcode_unautop( $content ) );

			}
			$output .= '</div>';
			if ( isset( $this->settings['params'] ) ) {
				$inner = '';
				foreach ( $this->settings['params'] as $param ) {
					if ( ! isset( $param['param_name'] ) ) {
						continue;
					}
					$param_value = isset( $atts[ $param['param_name'] ] ) ? $atts[ $param['param_name'] ] : '';
					if ( is_array( $param_value ) ) {
						// Get first element from the array
						reset( $param_value );
						$first_key = key( $param_value );
						$param_value = $param_value[ $first_key ];
					}
					$inner .= $this->singleParamHtmlHolder( $param, $param_value );
				}
				$output .= $inner;
			}
			$output .= '</div>';
			$output .= '</div>';
		}

		return $output;
	}

	public function cssAdminClass() {
		$sortable = ( vc_user_access_check_shortcode_all( $this->shortcode ) ? ' wpb_sortable' : ' ' . $this->nonDraggableClass );

		return 'wpb_' . $this->settings['base'] . $sortable . '' . ( ! empty( $this->settings['class'] ) ? ' ' . $this->settings['class'] : '' );
	}

	/**
	 * @deprecated - due to it is not used anywhere? 4.5
	 * @typo Bock - Block
	 * @return string
	 */
	public function customAdminBockParams() {
		// _deprecated_function( 'WPBakeryShortCode_VC_Row::customAdminBockParams', '4.5 (will be removed in 4.10)' );

		return '';
	}

	/**
	 * @deprecated 4.5
	 *
	 * @param string $bg_image
	 * @param string $bg_color
	 * @param string $bg_image_repeat
	 * @param string $font_color
	 * @param string $padding
	 * @param string $margin_bottom
	 *
	 * @return string
	 */
	public function buildStyle( $bg_image = '', $bg_color = '', $bg_image_repeat = '', $font_color = '', $padding = '', $margin_bottom = '' ) {
		// _deprecated_function( 'WPBakeryShortCode_VC_Row::buildStyle', '4.5 (will be removed in 4.10)' );

		$has_image = false;
		$style = '';
		if ( (int) $bg_image > 0 && false !== ( $image_url = wp_get_attachment_url( $bg_image ) ) ) {
			$has_image = true;
			$style .= 'background-image: url(' . $image_url . ');';
		}
		if ( ! empty( $bg_color ) ) {
			$style .= vc_get_css_color( 'background-color', $bg_color );
		}
		if ( ! empty( $bg_image_repeat ) && $has_image ) {
			if ( 'cover' === $bg_image_repeat ) {
				$style .= 'background-repeat:no-repeat;background-size: cover;';
			} elseif ( 'contain' === $bg_image_repeat ) {
				$style .= 'background-repeat:no-repeat;background-size: contain;';
			} elseif ( 'no-repeat' === $bg_image_repeat ) {
				$style .= 'background-repeat: no-repeat;';
			}
		}
		if ( ! empty( $font_color ) ) {
			$style .= vc_get_css_color( 'color', $font_color );
		}
		if ( '' !== $padding ) {
			$style .= 'padding: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $padding ) ? $padding : $padding . 'px' ) . ';';
		}
		if ( '' !== $margin_bottom ) {
			$style .= 'margin-bottom: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $margin_bottom ) ? $margin_bottom : $margin_bottom . 'px' ) . ';';
		}

		return empty( $style ) ? '' : ' style="' . esc_attr( $style ) . '"';
	}
	
}
