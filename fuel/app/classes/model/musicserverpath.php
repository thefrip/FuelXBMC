<?php

class Model_MusicServerPath extends Orm\Model
{
    protected static $_connection = 'music';
    protected static $_table_name = 'tamplan_server_paths';
    protected static $_primary_key = array('id');
    protected static $_properties = array(
		'id',
		'client_path'   => array(
			'label'		    => 'label.client_path',
			'default' 	  => 0,
			'null'		    => false,
			'validation'  => array('required', 'max_length' => array(512))
		),
		'server_path'   => array(
			'label'		    => 'label.server_path',
			'default' 	  => 0,
			'null'		    => false,
			'validation'  => array('required', 'max_length' => array(512))
		),
  );

}
