<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Edit_View_Layout_LC_HC_MVC extends _HC_MVC
{
	public function header( $model )
	{
		$return = HCM::__('Edit Location');
		return $return;
	}

	public function menubar( $model )
	{
		$menubar = $this->make('/html/view/container');

	// LIST
		$menubar->add(
			'list',
			$this->make('/html/view/link')
				->to('/locations')
				->add( $this->make('/html/view/icon')->icon('arrow-left') )
				->add( HCM::__('Locations') )
			);

		return $menubar;
	}

	public function render( $content, $model )
	{
		$menubar = $this->run('menubar', $model);
		$header = $this->run('header', $model);

		$out = $this->make('/layout/view/content-header-menubar')
			->set_content( $content )
			->set_header( $header )
			->set_menubar( $menubar )
			;

		return $out;
	}
}