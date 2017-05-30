<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Users_Wordpress_Users_View_HC_MVC extends _HC_MVC
{
	public function after_header( $return )
	{
		$return['wp_role'] = HCM::__('WordPress Role');
		return $return;
	}

	public function after_row( $return, $args )
	{
		$e = array_shift( $args );

		$p = $this->make('/users/presenter');
		$p->set_data( $e );

		$wp_roles = $e['_wp_userdata']['roles'];
		$wp_roles_view = join(', ', $wp_roles);
		$return['wp_role'] = $wp_roles_view;

		return $return;
	}
}