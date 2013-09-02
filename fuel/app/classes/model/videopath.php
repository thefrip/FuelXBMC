<?php

class Model_VideoPath extends Orm\Model
{
    protected static $_connection = 'video';
    protected static $_table_name = 'path';
    protected static $_primary_key = array('idPath');
    protected static $_properties = array(
        'idPath',
        'strPath' => array(
            'data_type' => 'text',
        ),
        'strContent' => array(
            'data_type' => 'text',
        ),
        'strScraper' => array(
            'data_type' => 'text',
        ),
        'scanRecursive' => array(
            'data_type' => 'int(11)',
        ),
        'useFolderNames' => array(
            'data_type' => 'tinyint(1)',
        ),
        'strSettings' => array(
            'data_type' => 'text',
        ),
        'noUpdate' => array(
            'data_type' => 'tinyint(1)',
        ),
        'exclude' => array(
            'data_type' => 'tinyint(1)',
        ),
    );

    static function get_server_paths()
    {
      // On recherche...
      $query = 'SELECT `path`.idPath, `path`.strPath FROM `path` WHERE `path`.`strScraper` != \'\'';

      $results = \DB::query($query)
                      ->as_object()
                      ->execute(static::$_connection);

      $paths = array();

      // Si au moins un résultat est dans la base de données
      if (count($results) > 0)
      {
        foreach($results as $result)
        {
          // On traite chaque résultat pour les convertir en objet
          $path = new stdClass();

          $path->idPath = $result->idPath;
          $path->server_path = $result->strPath;

          $paths[] = $path;
        }
      }

      return $paths;
    }

}
