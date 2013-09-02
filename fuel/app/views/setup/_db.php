<?php
/**
 * The development database settings. These get merged with the global settings.
 */

return array(
  'default' => array(
      'type'           => 'mysql',
      'connection'     => array(
          'hostname'       => 'HOST_IP',
          'port'           => '3306',
          'database'       => 'XBMC_DB',
          'username'       => 'USERNAME',
          'password'       => 'PASSWORD',
          'persistent'     => false,
          'compress'       => false,
      ),
      'identifier'   => '`',
      'table_prefix'   => '',
      'charset'        => 'utf8',
      'enable_cache'   => true,
      'profiling'      => false,
  ),

  'music' => array(
      'type'           => 'mysql',
      'connection'     => array(
          'hostname'       => 'HOST_IP',
          'port'           => '3306',
          'database'       => 'MUSIC_DB',
          'username'       => 'USERNAME',
          'password'       => 'PASSWORD',
          'persistent'     => false,
          'compress'       => false,
      ),
      'identifier'   => '`',
      'table_prefix'   => '',
      'charset'        => 'utf8',
      'enable_cache'   => true,
      'profiling'      => false,
  ),

  'video' => array(
      'type'           => 'mysql',
      'connection'     => array(
          'hostname'       => 'HOST_IP',
          'port'           => '3306',
          'database'       => 'VIDEO_DB',
          'username'       => 'USERNAME',
          'password'       => 'PASSWORD',
          'persistent'     => false,
          'compress'       => false,
      ),
      'identifier'   => '`',
      'table_prefix'   => '',
      'charset'        => 'utf8',
      'enable_cache'   => true,
      'profiling'      => false,
  ),

);
