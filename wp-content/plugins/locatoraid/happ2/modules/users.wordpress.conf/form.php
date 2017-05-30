<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Users_WordPress_Conf_Form_HC_MVC extends _HC_Form
{
	public function _init()
	{
		$wp_users_model = $this->make('/users.wordpress/model/user');
		$wp_always_admin = $wp_users_model->run('wp-always-admin');

		$can_edit = FALSE;
		$user = $this->make('/auth/model/user')->get();
		if( $user->is_always_admin() ){
			$can_edit = TRUE;
		}

		$options = array(
			'none'	=> HCM::__('No Access'),
			// 'user'	=> HCM::__('User'),
			'admin'	=> HCM::__('Admin')
			);

		$wp_roles = new WP_Roles();
		$wordpress_roles = $wp_roles->get_names();

		foreach( $wordpress_roles as $role_value => $role_name ){
			$default = 1;
			$this_field_pname = 'wordpress_users:role_' . $role_value;

			if( in_array($role_value, $wp_always_admin) ){
				$this
					->set_input( $this_field_pname,
						$this->make('/form/view/label')
							->set_label( $role_name )
						)
					;
			}
			else {
				$this
					->set_input( $this_field_pname,
						$this->make('/form/view/radio')
							->set_inline()
							->set_options( $options )
							->set_label( $role_name )
						)
					;
			}
		}

		if( ! $can_edit ){
			$this
				->set_readonly()
				;
		}

		return $this;
	}
}