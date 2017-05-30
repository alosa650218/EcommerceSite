<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Maps_Google_Conf_Form_HC_MVC extends _HC_Form
{
	public function _init()
	{
		$this
			->set_input( 'maps_google:api_key',
				$this->make('/form/view/text')
					->set_label( HCM::__('Google Maps API Key') . '<br>' . HCM::__('Or enter "none" to skip it') )
					->add_attr('size', 48)
					->add_validator( $this->make('/validate/required') )
				)
			;

		return $this;
	}

	public function render_input( $input_name ){
		switch( $input_name ){
			case 'maps_google:api_key':
				$return = $this->make('/html/view/list-div');

				$app_settings = $this->make('/app/lib/settings');
				$api_key = $app_settings->run('get', 'maps_google:api_key');

				if( ! strlen($api_key) ){
					$help = $this->make('/html/view/element')->tag('div')
						->add(
							$this->make('/html/view/list-div')
								->add(
									HCM::__('Usage of the Google Maps APIs now requires an API key which you can get from the Google Maps developers website.')
									)
								->add(
									'<a href="https://console.developers.google.com/flows/enableapi?apiid=maps_backend&keyType=CLIENT_SIDE&reusekey=true" target="_blank">' .
									HCM::__('Get Google Maps API key') .
									'</a>'
									)
							)
						->add_attr('class', 'hc-p3')
						->add_attr('class', 'hc-mb2')
						->add_attr('class', 'hc-border')
						->add_attr('class', 'hc-border-olive')
						->add_attr('class', 'hc-rounded')
						->add_attr('class', 'hc-fs4')
						;

					$return
						->add( $help )
						;
				}

				$return
					->add( parent::render_input($input_name) )
					;
				break;

			default:
				$return = parent::render_input( $input_name );
		}

		return $return;
	}
}