<?php

class Setup
{

  public static function create_table_users()
  {
    // database connection
    \DBUtil::set_connection('default');

    // check if table exists
    if (\DBUtil::table_exists('users'))
    {
      \DBUtil::drop_table('users');
    }

    \DBUtil::create_table(
        'users',
        array(
            'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
            'username' => array('constraint' => 50, 'type' => 'varchar'),
            'password' => array('constraint' => 255, 'type' => 'varchar'),
            'group' => array('constraint' => 11, 'type' => 'varchar', 'default' => '1'),
            'email' => array('constraint' => 50, 'type' => 'varchar'),
            'last_login' => array('constraint' => 25, 'type' => 'varchar'),
            'login_hash' => array('constraint' => 255, 'type' => 'varchar'),
            'profile_fields' => array('type' => 'text'),
            'created_at' => array('constraint' => 11, 'type' => 'varchar', 'default' => '0'),
            'updated_at' => array('constraint' => 11, 'type' => 'varchar', 'default' => '0'),
        ),
        array('id'),
        false,
        'MyISAM',
        'utf8_unicode_ci'
    );

    \DBUtil::create_index('users', array('username', 'email'), 'unique');
    \Auth::create_user('admin', 'password', Lang::get('setup.admin_email'), 100);
  }

  public static function create_table_certifications()
  {
    // database connection
    \DBUtil::set_connection('default');

    // check if table exists
    if (\DBUtil::table_exists('certifications'))
    {
      \DBUtil::drop_table('certifications');
    }

    \DBUtil::create_table(
        'certifications',
        array(
            'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
            'name' => array('constraint' => 60,'type' => 'varchar'),
            'rating' => array('constraint' => 4, 'type' => 'int'),
        ),
        array('id'),
        false,
        'MyISAM',
        'utf8_unicode_ci'
    );

    $mpaas = array();

    // add some certifications for french language or others
    if (\Config::get('language') == 'fr')
    {
      $mpaas[] = 'Tout public';
      $mpaas[] = '- de 10 ans';
      $mpaas[] = '- de 12 ans';
      $mpaas[] = '- de 16 ans';
      $mpaas[] = '- de 18 ans';
      $mpaas[] = 'Non classé';
    }
    else
    {
      $mpaas[] = 'Rated G';
      $mpaas[] = 'Rated PG';
      $mpaas[] = 'Rated PG-13';
      $mpaas[] = 'Rated R';
      $mpaas[] = 'Rated NC-17';
      $mpaas[] = 'Rated U';
    }

    $cpt = 0;
    foreach($mpaas as $mpaa)
    {
      $certification = new Model_Certification();
      $certification->name = $mpaa;
      $certification->rating = ++$cpt;
      $certification->save();
    }
  }

  public static function create_table_music_server_paths()
  {
    // database connection
    \DBUtil::set_connection('music');

    // check if table exists
    if (\DBUtil::table_exists('tamplan_server_paths'))
    {
      \DBUtil::drop_table('tamplan_server_paths');
    }

    \DBUtil::create_table(
        'tamplan_server_paths',
        array(
            'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
            'client_path' => array('type' => 'text'),
            'server_path' => array('type' => 'text'),
        ),
        array('id'),
        false,
        'MyISAM',
        'utf8_unicode_ci'
    );
  }

  public static function create_table_video_server_paths()
  {
    // database connection
    \DBUtil::set_connection('video');

    // check if table exists
    if (\DBUtil::table_exists('tamplan_server_paths'))
    {
      \DBUtil::drop_table('tamplan_server_paths');
    }

    \DBUtil::create_table(
        'tamplan_server_paths',
        array(
            'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
            'idPath' => array('constraint' => 11, 'type' => 'int'),
            'server_path' => array('type' => 'text'),
        ),
        array('id'),
        false,
        'MyISAM',
        'utf8_unicode_ci'
    );
  }

}
