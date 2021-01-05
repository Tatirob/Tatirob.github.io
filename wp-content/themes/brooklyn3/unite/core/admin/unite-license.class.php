<?php if (!defined('ABSPATH')) {
    exit; // exit if accessed directly
}

class UT_Licensing {
    
    /**
     * Code to Verify
     */

    public $purchase_code;
    
    
    /**
     * Slug
     * @var string
     */
    protected $key = 'unite-manage-license';   
    
    
    /**
     * Update Server
     * @var string
     */
    
    protected $server = 'https://licensing.unitedthemes.com/';
    
    
    /**
     * Theme URL
     * @var string
     */
    
    private $theme_url = 'https://themeforest.net/item/brooklyn-responsive-multipurpose-wordpress-theme/6221179/';
    
    
    /**
     * Home Title
     * @var string
     */
    protected $title = '';
    
    
    /**
     * Instantiates the class
     */

    function __construct() {
        
        $this->title = esc_html__( 'Licensing', 'unite-admin' );
        
        // Actions
        add_action( 'admin_menu', array( $this, 'add_menu_item' ) );
        
    }
    
    
    /**
     * Initiate our hooks
     *
     * @since     1.0.0
     * @version   1.0.0
     */
     
    public function hooks() {
        
        
        
        
        
    }
    
    
    /**
     * Add to menu
     *
     * @since     4.9.2
     * @version   1.0.0
     */
    
    public function add_menu_item() {
        
        $this->options_page = add_submenu_page(
            'unite-welcome-page', 
            $this->title, 
            $this->title, 
            'manage_options', 
            $this->key, 
            array( $this , 'admin_page_display' ) 
        );
        
    }
    
    
    /**
     * Admin page markup
     * @since    1.0
     * @version  1.0.0
     */
    
    public function admin_page_display() { 
        
        ?>
        
            <div id="ut-admin-wrap" class="clearfix">

                <div id="ut-ui-admin-header">
                    
                    <div class="grid-10 medium-grid-15 tablet-grid-20 hide-on-mobile grid-parent">

                        <div class="ut-admin-branding">
                            <a href="http://www.unitedthemes.com" target="_blank"><img src="<?php echo THEME_WEB_ROOT; ?>/unite-custom/admin/assets/img/icons/bklyn-logo-white.svg" alt="UnitedThemes"><span class="version-number">Version <?php echo UT_THEME_VERSION; ?></span></a>
                        </div>

                    </div>                                                

                    <div class="grid-90 medium-grid-85 tablet-grid-80 mobile-grid-100 grid-parent">

                        <div class="ut-admin-header-title">

                            <?php $theme = wp_get_theme(); ?>

                            <h1><?php esc_html_e( 'Manage License.', 'unite-admin' ); ?></h1>

                        </div>

                    </div>

                </div>
                
                <div class="ut-option-holder grid-100 medium-grid-100 tablet-grid-100 mobile-grid-100">
                    
                    <form method="post" action="options.php"> 
             
                        <?php wp_nonce_field('update-options'); ?>                    
                        <?php settings_fields('ut-license-data-group'); ?>      
                        
                        <input class="regular-text code" type="text" name="unite-code" value="<?php echo get_option('unite-code'); ?>">

                        <input type="hidden" name="action" value="update" />
                        <input type="hidden" name="page_options" value="ut-license-data" />

                        <p class="submit"><input id="submit" type="submit" class="button button-primary" value="<?php _e('Save Changes', 'unite') ?>" /></p>                            
                    
                
                    <?php $this->retrieve_update_info(); ?>
                        
                    </form>
                    
                </div>    
                    
                
            </div>    

        <?php     
    
    
    }
    
    
    /**
     * Get Update Info
     * @since    1.0
     * @version  1.0.0
     */
    
    public function retrieve_update_info() {
        
        // build request
        $code = get_option('unite-code', '');
        
        // start request
        $request = wp_remote_get( add_query_arg( array(
            
            'purchase_code' => $code,
            'domain'        => 'test.com',
            'admin_email'   => 'test@test.com',
            'revoke_domain' => '0'
            
        ), $this->server ));
                
        // failed to connect
        if( is_wp_error( $request ) ) {
            
            
            
        }
        
        
        if( wp_remote_retrieve_response_code( $request ) == 200 ) {
            
            ut_print( wp_remote_retrieve_body( $request ) );            
            
        }
        
        
        // set transient
        
        
        
        
        
    }
    
    
    /*
     * Get Server URL based on purpose
     */
    
	public function get_url( $purpose ){
		
		
		switch( $purpose ){
			
            case 'updates':
				
                $url = '';
				break;
                
			case 'verify':
				
                $url = 'verify-purchase';
				break;
            
			default:
				return false;
                
		}
				
		return $url;
        
	}
    
    
    
    
}

new UT_Licensing();



class UT_Licensing_Check extends UT_Licensing {
    
    
}
