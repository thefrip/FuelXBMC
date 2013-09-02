<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.6
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

class Model_User extends Orm\Model
{
	/**
	 * @var  string  connection to use
	 */
	protected static $_connection = null;

	/**
	 * @var  string  table name to overwrite assumption
	 */
	protected static $_table_name;

	/**
	 * @var array	model properties
	 */
	protected static $_properties = array(
		'id',
		'username'        => array(
			'label'		  => 'label.name',
			'default' 	  => 0,
			'null'		  => false,
			'validation'  => array('required', 'max_length' => array(255))
		),
		'email'           => array(
			'label'		  => 'label.email',
			'default' 	  => 0,
			'null'		  => false,
			'validation'  => array('required', 'valid_email')
		),
		'group'        => array(
			'label'		  => 'label.group',
			'default' 	  => 0,
			'null'		  => false,
			'form'  	  => array('type' => 'select'),
			'validation'  => array('required', 'is_numeric')
		),
		'password'        => array(
			'label'		  => 'label.password',
			'default' 	  => 0,
			'null'		  => false,
			'form'  	  => array('type' => 'password'),
			'validation'  => array('required', 'min_length' => array(8))
		),
		'last_login'	  => array(
			'form'  	  => array('type' => false),
		),
		'login_hash'	  => array(
			'form'  	  => array('type' => false),
		),
		'created_at'      => array(
			'default' 	  => 0,
			'null'		  => false,
			'form'  	  => array('type' => false),
		),
		'updated_at'      => array(
			'default' 	  => 0,
			'null'		  => false,
			'form'  	  => array('type' => false),
		),
	);

	/**
	 * init the class
	 */
   	public static function _init()
	{
		// auth config
		\Config::load('simpleauth', true);

		// set the connection this model should use
		static::$_connection = \Config::get('simpleauth.db_connection');

		// set the models table name
		static::$_table_name = \Config::get('simpleauth.table_name', 'users');

		// Language file for models
		\Lang::load('label', true);
	}

	/**
	 * before_insert observer event method
	 */
	public function _event_before_insert()
	{
		// assign the user id that lasted updated this record
		$this->user_id = ($this->user_id = \Auth::get_user_id()) ? $this->user_id[1] : 0;
	}

	/**
	 * before_update observer event method
	 */
	public function _event_before_update()
	{
		$this->_event_before_insert();
	}
}
