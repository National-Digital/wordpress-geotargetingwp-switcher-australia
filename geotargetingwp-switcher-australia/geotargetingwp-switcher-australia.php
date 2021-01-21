<?php
/*
 * @wordpress-plugin
 * Plugin Name:       GeoTargetingWP Widget for Australia
 * Plugin URI:        https://github.com/National-Digital/wordpress-geotargetingwp-switcher-australia
 * Description:       Display a dropdown to let users change Australian state/territory
 * Version:           1.0.0
 * Author:            National Digital
 * Author URI:        mailto:support@nationaldigital.com.au
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

use GeotWP\Record\GeotRecord;

/**
 * Class GeotSwitcher
 * will create a dropdown widget for changing cities
 */
class GeotSwitcher {
	/**
	 * GeotSwitcher constructor.
	 */
	public function __construct() {
		// check main plugin exists
		If ( ! class_exists( 'Geot' ) ) {
			return;
		}

		// required files and assets
		$this->includes();
		$this->assets();

		//register widget
		add_action( 'widgets_init', [ $this, 'register_widget' ] );
		// capture value
		add_filter( 'geot/cancel_query', [ $this, 'set_custom_data' ] );
	}

	/**
	 * Files includes
	 */
	private function includes() {
		require_once 'class-geot-dropdown-widget.php';
	}

	/**
	 * Enqueue assets
	 */
	private function assets() {
		wp_enqueue_script( 'geotargetingwp-switcher-australia', plugin_dir_url( __FILE__ ) . 'switcher.js', [ 'jquery', 'geot-js' ], '1.0', true );
	}

	/**
	 * Register widget into WP
	 */
	function register_widget() {
		register_widget( 'GeotS_Widget' );
	}

	/**
	 * Check if switcher cookie exists and modify data
	 */
	function set_custom_data() {

		// if no cookie or not a valid state continue with request to API
		if ( empty( $_COOKIE['geot_switcher'] ) || ! in_array( $_COOKIE['geot_switcher'], array_keys( GeotSwitcher::cities() ) ) ) {
			return false;
		}

		$state = $_COOKIE['geot_switcher'];

		// on this example we hardcoded the country but you can make it conditional based on your state
		$data = [
			'country'     => 'Australia',
			'country_iso' => 'AU',
			'state'       => $state,
			'state_iso'   =>  GeotSwitcher::cities()[ $states ]
		];
		// return formatted object to the plugin
		return $this->formatter($data);
	}

	/**
	 * Valid states used in dropdown
	 * @return array of states and ISO codes
	 */
	public static function states() {
		return [
			'New South Wales' => 'AU-NSW',
			'Queensland' => 'AU-QLD',
			'South Australia' => 'AU-SA',
			'Tasmania' => 'AU-TAS ',
			'Victoria' => 'AU-VIC',
			'Western Australia' => 'AU-WA',
			'Australian Capital Territory' => 'AU-ACT',
			'Northern Territory' => 'AU-NT',
		];
	}

	private function formatter( $data ) {

		$state           = new \stdClass;
		$state->names    = [ $data['state'] ];
		$state->iso_code = $data['state_iso'];

		$country           = new \stdClass;
		$country->names    = [ $data['country'] ];
		$country->iso_code = $data['country_iso'];

		$continent        = new \stdClass;
		$continent->names = '';

		$city        = new \stdClass;
		$city->names = [ $data['city'] ];
		$city->zip   = $data['zip'];

		$geolocation                  = new \stdClass();
		$geolocation->accuracy_radius = '';
		$geolocation->longitude       = '';
		$geolocation->latitude        = '';
		$geolocation->time_zone       = '';

		return (object) [
			'country'     => $country,
			'city'        => $city,
			'state'       => $state,
			'continent'   => $continent,
			'geolocation' => $geolocation,
		];
	}
}
add_action( 'wp_enqueue_scripts', function () {
	new GeotSwitcher();
});