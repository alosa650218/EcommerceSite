<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Acl_Manager_HC_MVC extends _HC_MVC
{
	protected $user = NULL;

	const WHO_USER		= 'user';
	const WHO_GROUP		= 'group';

	const TYPE_OBJECT	= 'object';
	const TYPE_TABLE	= 'table';
	const TYPE_GLOBAL	= 'global';

	public function _init()
	{
		$user = $this->make('/auth/model/user')
			->get()
			;
		$this->set_user( $user );
		return $this;
	}

	public function user()
	{
		return $this->user;
	}

	public function set_user( $user )
	{
		$this->user = $user;
		return $this;
	}

	public function groups()
	{
		$return = array(
			'root'		=> pow(2, 0),
			'everyone'	=> pow(2, 1),
			);
		return $return;
	}

	/* 
	$what is like location@edit
	*/
	public function can( $what, $on = array() )
	{
		$return = FALSE;

		$which = $this->run( 'which', $what );
		$return = $this->is_valid($on, $which);

		return $return;
	}

	public function which( $what )
	{
		$return = array();
		return $return;
	}

	public function get_all_actions( $table = NULL )
	{
		$return = array();
		return $return;
	}

	public function after_link_check( $return, $args )
	{
		$slug = array_shift( $return );
		$params = array_shift( $return );

		$return = $this->run('link-check', $slug, $params);
		if( ! $return ){
			$return = array('', array());
		}
		return $return;
	}

	public function link_check( $slug, $params = array() )
	{
		$return = array( $slug, $params );
		return $return;
	}

// this compares an object array to a set of conditions, compatible with those have for SQL queries
	public function is_valid( $e, $conditions = array() )
	{
		$return = FALSE;
		$allowed_compares = array('=', '<>', '>=', '<=', '>', '<', 'IN', 'NOTIN', 'LIKE');

		foreach( $conditions as $c ){
			list( $key, $compare, $with ) = $c;
			$compare = trim( $compare );
			$compare = strtoupper( $compare );

			if( ! in_array($compare, $allowed_compares) ){
				echo "COMPARING BY '$compare' IS NOT ALLOWED!<br>";
				$return = FALSE;
				break;
			}

			if( ! array_key_exists($key, $e) ){
				$return = FALSE;
				break;
			}

			$what = $e[$key];
			if( is_array($what) && array_key_exists('id', $what) ){
				$what = $what['id'];
			}

			switch( $compare ){
				case '=':
					$return = ( $what == $with ) ? TRUE : FALSE;
					break;
				case '<>':
					$return = ( $what != $with ) ? TRUE : FALSE;
					break;
				case '>=':
					$return = ( $what >= $with ) ? TRUE : FALSE;
					break;
				case '<=':
					$return = ( $what <= $with ) ? TRUE : FALSE;
					break;
				case '>':
					$return = ( $what > $with ) ? TRUE : FALSE;
					break;
				case '<':
					$return = ( $what < $with ) ? TRUE : FALSE;
					break;
				case 'IN':
					$return = in_array($what, $with) ? TRUE : FALSE;
					break;
				case 'NOT IN':
				case 'NOTIN':
					$return = (! in_array($what, $with)) ? TRUE : FALSE;
					break;
				case 'LIKE':
					$return = (strpos($with, $what) !== FALSE) ? TRUE : FALSE;
					break;
			}
		}

		return $return;
	}
}