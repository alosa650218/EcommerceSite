<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_View_Map_Template_LC_HC_MVC extends _HC_MVC
{
	public function render()
	{
		$out = array();

		$app_settings = $this->make('/app/lib/settings');
		$p = $this->make('/locations/presenter');
		$fields = $p->run('fields-labels');

		$out[] = '<div class="hc-black hc-bg-lightsilver hc-p2 hc-rounded">';

		$out[] = '<div class="hc-bold lpr-location-name">{{=name}}</div>';
		$out[] = '<div class="hc-italic lpr-location-address">{{=address}}</div>';

		if( array_key_exists('distance', $fields) ){
			$out[] = '{{distance}}';

			$this_field_pname = 'front_map:' . 'distance'  . ':' . 'w_label';
			$this_field_w_label = $app_settings->run('get', $this_field_pname);

			$out[] = '<div class="hc-bold lpr-location-distance">';

			if( $this_field_w_label ){
				$flabel = $fields['distance'];
				if( strlen($flabel) ){
					$label_class = 'hc-inline-block hc-mr1 lpr-location-label';
					$out[] = '<div class="' . $label_class . '">' . $flabel . '</div>';
				}
			}

			$out[] = '{{=distance}}';
			$out[] = '</div>';

			$out[] = '{{/distance}}';
		}

		$skip_if = array('name', 'address', 'distance');

		foreach( $fields as $fn => $flabel ){
			if( in_array($fn, $skip_if) ){
				continue;
			}

			$this_field_pname = 'front_map:' . $fn  . ':' . 'show';
			$this_field_show = $app_settings->run('get', $this_field_pname);
			if( ! $this_field_show ){
				continue;
			}

			$this_field_pname = 'front_map:' . $fn  . ':' . 'w_label';
			$this_field_w_label = $app_settings->run('get', $this_field_pname);

			$class = array();
			$class[] = 'lpr-location-' . $fn;
			$class = join(' ', $class);

			$this_one_view = '';
			if( $this_field_w_label ){
				$label_class = 'hc-inline-block hc-mr1 lpr-location-label';
				$this_one_view .= '<div class="' . $label_class . '">' . $flabel . '</div>';
			}
			$this_one_view .= '{{=' . $fn . '}}';

			if( ! in_array($fn, $skip_if) ){
				$out[] = '{{' . $fn . '}}';
			}
			$out[] = '<div class="' . $class . '">' . $this_one_view . '</div>';
			if( ! in_array($fn, $skip_if) ){
				$out[] = '{{/' . $fn . '}}';
			}
		}

		$out[] = '</div>';

		$out = join("\n", $out);

		return $out;
	}
}