<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Api_LC_HC_MVC extends _HC_Rest_Api
{
	public function custom_get_notgeocodedcount( $args = array() )
	{
		$model = $this->_prepare_get_many( $args );
		$model
			->where( 'latitude', 'IN', array(NULL,0) )
			->where( 'longitude', 'IN', array(NULL,0) )
			;
		$return = $model
			->count()
			;

		return $this->make('/http/view/response')
			->set_status_code('200')
			->set_view( $return )
			;
	}

	public function custom_get_notgeocoded( $args = array() )
	{
		$model = $this->_prepare_get_many( $args );
		$model
			->where( 'latitude', 'IN', array(NULL,0) )
			->where( 'longitude', 'IN', array(NULL,0) )
			;

		$entries = $model
			->fetch_many()
			;
		$return = array();
		foreach( $entries as $e ){
			$e = $e->run('to-array');
			$return[] = $e;
		}

		$return = json_encode( $return );
		return $this->make('/http/view/response')
			->set_status_code('200')
			->set_view( $return )
			;
	}
}