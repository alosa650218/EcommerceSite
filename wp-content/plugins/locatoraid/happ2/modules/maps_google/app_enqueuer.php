<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Maps_Google_App_Enqueuer_HC_MVC extends _HC_MVC
{
	public function after_init( $return )
	{
		$return
			->run('register-script', 'gmaps', 'happ2/modules/maps_google/assets/js/gmaps.js' )
			;

		$app_settings = $this->make('/app/lib/settings');
		$api_key = $app_settings->run('get', 'maps_google:api_key');
		if( $api_key == 'none' ){
			$api_key = '';
		}

		$params = array(
			'api_key'	=> $api_key,
			);

		$return
			->run('localize-script', 'gmaps', $params )
			;

		$return
			->run('enqueue-script', 'gmaps' )
			;

		return $return;
	}
}