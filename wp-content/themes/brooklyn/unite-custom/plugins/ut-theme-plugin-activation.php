<?php if ( ! defined( 'ABSPATH' ) ) exit;

/** 
 * Plugin Requirements for this theme
 *
 * @return    void
 *
 * @access    private
 * @since     1.0.0
 * @version   1.0.0
 */
if ( ! function_exists( '_ut_register_required_plugins' ) ) : 

    function _ut_register_required_plugins() {
    
        $plugins = array(
			
            array(
				'name'     				=> 'Twitter by United Themes',
				'slug'     				=> 'ut-twitter',
				'source'   				=> THEME_WEB_ROOT . '/unite-custom/plugins/lib/ut-twitter.zip', 
				'required' 				=> true, 
				'version' 				=> '3.1.1', 
			),
			
			array(
				'name'     				=> 'Shortcodes by United Themes',
				'slug'     				=> 'ut-shortcodes',
				'source'   				=> THEME_WEB_ROOT . '/unite-custom/plugins/lib/ut-shortcodes.zip', 
				'required' 				=> true, 
				'version' 				=> '4.7.3.2',
			),
            
			array(
				'name'     				=> 'Portfolio Management by United Themes',
				'slug'     				=> 'ut-portfolio',
				'source'   				=> THEME_WEB_ROOT . '/unite-custom/plugins/lib/ut-portfolio.zip', 
				'required' 				=> true, 
				'version' 				=> '4.4.3',
			),
			
			array(
				'name'     				=> 'Pricing Tables by United Themes',
				'slug'     				=> 'ut-pricing',
				'source'   				=> THEME_WEB_ROOT . '/unite-custom/plugins/lib/ut-pricing.zip', 
				'required' 				=> true, 
				'version' 				=> '3.2', 
			),
            
            array(
				'name'     				=> 'Brooklyn Page Builder (Visual Composer)',
				'slug'     				=> 'js_composer',
				'source'   				=> THEME_WEB_ROOT . '/unite-custom/plugins/lib/js_composer.zip', 
				'required' 				=> true, 
				'version' 				=> '5.6',
			),
			
			array(
				'name'     				=> 'Revolution Slider',
				'slug'     				=> 'revslider',
				'source'   				=> THEME_WEB_ROOT . '/unite-custom/plugins/lib/revslider.zip', 
				'version' 				=> '5.4.8.1',
			),
            
            array(
                'name'      			=> 'Contact Form 7',
                'slug'      			=> 'contact-form-7',
                'required'  			=> false,
				'version' 				=> '5.0.4', 
            ),
			
			array(
                'name'      			=> 'MailChimp for WordPress',
                'slug'      			=> 'mailchimp-for-wp',
                'required'  			=> false,
				'version' 				=> '4.2.4', 
            ),
            
        );
         
        $config = array(
            
            'default_path' 		=> '',                         	/* Default absolute path to pre-packaged plugins */
            'menu'         		=> 'install-required-plugins', 	/* Menu slug */
            'has_notices'      	=> true,                       	/* Show admin notices or not */
            'is_automatic'    	=> true,						/* Automatically activate plugins after installation or not */	
			'dismissable'  	    => true,						/* If false, a user cannot dismiss the nag message. */
			'dismiss_msg'		=> 'Test',
           
        );
        
        tgmpa( $plugins, $config );
    
    }
    
    add_action( 'tgmpa_register', '_ut_register_required_plugins' );
    
endif;