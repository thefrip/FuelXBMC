<?php

class Model_VideoServerPath extends Orm\Model
{
    protected static $_connection = 'video';
    protected static $_table_name = 'tamplan_server_paths';
    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id',
        'idPath',
        'server_path',
    );

    public static function read($id)
    {
        $query = 'SELECT * FROM `tamplan_server_paths` JOIN `path` ON (`path`.`idPath` = `tamplan_server_paths`.`idPath`) WHERE `tamplan_server_paths`.`id` = '.$id;

        $results = \DB::query($query)
                        ->as_object()
                        ->execute(static::$_connection);

      return self::_manage_results($results[0]);
    }

    public static function read_all()
    {
        $query = 'SELECT * FROM `tamplan_server_paths` JOIN `path` ON (`path`.`idPath` = `tamplan_server_paths`.`idPath`)';

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
            $paths[] = self::_manage_results($result);
          }
        }

        return $paths;
    }

    private static function _manage_results($result)
    {
        $path = $result;

        $path->idPath = $result->idPath;
        $path->client_path = $result->strPath;

        $scraper = str_replace('metadata.', '', $result->strScraper);
        $scraper = str_replace('.', '', $scraper);
        $path->scraper_class = 'Scrapers_Video_'.ucfirst($scraper);

        // Présence de paramètres ?
        if ($result->strSettings != '')
        {
          $xml = simplexml_load_string($result->strSettings);

          $settings = new stdClass();

          foreach($xml as $node)
          {
            $id = $node->attributes()->id;
            $v = (string) $node->attributes()->value;

            // Besoin de transformer des chaînes en booléens ?
            if (($v == 'true') || ($v == 'false'))
            {
              if ($v == 'true') $value = TRUE;
              if ($v == 'false') $value = FALSE;
            }
            else
                $value = $v;

            $settings->$id = $value;
          }
          $path->settings = $settings;
        }

        unset($path->strSettings);
        unset($path->strScraper);
        unset($path->strHash);
        unset($path->strPath);

        return $path;
    }

}
