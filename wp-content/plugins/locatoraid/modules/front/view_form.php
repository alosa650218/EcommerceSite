<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_View_Form_LC_HC_MVC extends _HC_MVC
{
	public function render( $params = array() )
	{
		$out = $this->make('/html/view/container');

		$form = $this->make('form');
		$search_form_id = 'hclc_search_form';

		$link = $this->make('/html/view/link')
			->to('search')
			->ajax()
			->href()
			;

		if( isset($params['start']) ){
			$form->set_value( 'search', $params['start'] );
		}

		$link_params = array(
			'search'	=> '_SEARCH_',
			'product'	=> '_PRODUCT_',
			'lat'		=> '_LAT_',
			'lng'		=> '_LNG_',
			);

		if( isset($params['limit']) ){
			$link_params['limit'] = $params['limit'];
		}
		if( isset($params['sort']) ){
			if( substr($params['sort'], -strlen('-reverse')) == '-reverse' ){
				$link_params['sort'] = array( substr($params['sort'], 0, -strlen('-reverse')), 0);
			}
			else {
				$link_params['sort'] = $params['sort'];
			}
		}

		reset( $params );
		foreach( $params as $k => $v ){
			if( ! (substr($k, 0, strlen('where-')) == 'where-') ){
				continue;
			}
			$k = substr( $k, strlen('where-') );
			$link_params[ $k ] = $v;
		}

		$link = $this->make('/html/view/link')
			->to('/search', $link_params )
			->ajax()
			->href()
			;

		$display_form = $this->make('/html/view/form')
			->add_attr('id', $search_form_id)
			->add_attr('action', $link )
			->set_form( $form )

			->add_attr('class', 'hc-mb2')
			;

		if( isset($params['start']) ){
			$display_form
				->add_attr('data-start', $params['start'])
				;
		}

		$form_view = $this->make('/html/view/list-div')
			->set_gutter(2)
			;

		$where_param = array();

		reset( $params );
		$take_where = array('where-country', 'where-zip', 'where-state', 'where-city');
		foreach( $params as $k => $v ){
			if( ! strlen($v) ){
				continue;
			}
			if( ! in_array($k, $take_where) ){
				continue;
			}

			$short_k = substr($k, strlen('where-'));
			$where_param[] = $short_k . ':' . $v;
		}

		if( $where_param ){
			$where_param = join(' ', $where_param);
			$display_form
				->add_attr('data-where', $where_param)
				;
		}

		$inputs = $form->inputs();
		foreach( $inputs as $k => $input ){
			// $display_form
				// ->add( $input )
				// ;
			$form_view
				->add( $input )
				;
		}

		$buttons = $this->make('/html/view/container');
		$buttons->add(
			$this->make('/html/view/element')->tag('input')
				->add_attr('type', 'submit')
				->add_attr('title', HCM::__('Search') )
				->add_attr('value', HCM::__('Search') )
				// ->add_attr('class', 'hc-theme-btn-submit', 'hc-theme-btn-primary')
				->add_attr('class', 'hc-block')
				->add_attr('style', 'margin:0;')
			);
		$form_view
			->add( $buttons )
			;

		$display_form
			->add( $form_view )
			;

		$out
			->add( $display_form )
			;

		return $out;
	}
}