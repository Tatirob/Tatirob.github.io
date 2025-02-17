<?php
/**
 * Auto Update notification Class File.
 * @author flippercode
 * @package Updates
 * @version 1.0.0
 */

/**
 * Auto Update notification Class.
 * @author flippercode
 * @package Posts
 * @version 1.0.0
 */
class WTHP_Auto_Update
{
	/**
	 * Plugin's current version
	 * @var string
	 */
	public $wsq_current_version = '1.0.0';

	/**
	 * Plugin's remote path
	 * @var string
	 */
	public $wsq_remote_path = 'https://www.cooltimeline.com';

	/**
	 * Plugin's Slug
	 * @var string
	 */
	public $wsq_slug;

	/**
	 * Initialize a new instance of the Auto-Update class.
	 */
	function __construct() {

		$folder_data = explode( '/', dirname( plugin_basename( __FILE__ ) ) );
		$folder_name = sanitize_title( $folder_data[0] );
		$this->wsq_plugin_file = $folder_name.'/'.$folder_name.'.php';
	
		$this->wsq_slug = $folder_name;

		// Check for plugin updates.
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_updates' ),10,1 );

		// Set the response.
		add_filter( 'plugins_api', array( $this, 'get_updates_info' ), 10, 3 );
	}

	/**
	 * Add our plugin to the filter transient.
	 * @param  object $transient Transient Object.
	 * @return object            Transient Object.
	 */
	public function check_updates($transient) {

		if ( empty( $transient->checked ) ) {
			return $transient;
		}
		$plugin_data = get_plugin_data( ABSPATH . 'wp-content/plugins/'.$this->wsq_plugin_file );
		$this->wsq_current_version = $plugin_data['Version'];
		// Check and Get remote version.
		$response = wp_remote_post( $this->wsq_remote_path.'/wunpupdates', array( 'body' => array( 'action' => 'version', 'plugin' => $this->wsq_slug ) ) );
		if ( ! is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) === 200 ) {
			$new_updates = unserialize( $response['body'] );
			$new_updates = $this->append_auth_parameter($new_updates);
			// If update is available, set the transient.
			if ( version_compare( $this->wsq_current_version, $new_updates->new_version, '<' ) ) {
				$transient->response[ $this->wsq_plugin_file ] = $new_updates;
			}
		}
		return $transient;
	}

	/**
	 * Get Plugins Update Information
	 * @param  bool   $false  Get Info or Not.
	 * @param  string $action Action Type.
	 * @param  array  $arg    Arguments.
	 * @return array         New Updates Information.
	 */
	public function get_updates_info($false, $action, $arg) {

		if ('plugin_information' == $action &&  $arg->slug === $this->wsq_slug  ) {
			$args = (array) $arg;
			$options = array_merge( $args,array( 'action' => $action, 'plugin' => $this->wsq_slug ) );
			$response = wp_remote_post( $this->wsq_remote_path.'/wunpupdates', array( 'body' => $options ) );
			if ( ! is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) === 200 ) {
			if (is_array($response['body'])) {
				$info = unserialize( $response['body'] );
				$info = $this->append_auth_parameter($info);
				return $info;
				}
			}
			return false;
		}
		return false;
	}

	function append_auth_parameter($info) {
		$username = get_option($this->wsq_slug.'-uname');
		$purchase_key = get_option($this->wsq_slug.'-pkey');
		
		if( !empty($username) and !empty($purchase_key) ) {
			$auth_url = add_query_arg( array(
				'euname' => $username,
				'epkey' => $purchase_key,
			), $info->package );
		} else {
			$auth_url = $info->package; 
		}

		$auth_url = apply_filters('wupp_auth_url',$auth_url, $this->wsq_slug);
		$info->download_link = $auth_url;
		$info->package = $auth_url;
		return $info;
	}
}
new WTHP_Auto_Update();

