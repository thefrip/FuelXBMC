<?php

class Model_MusicArt extends Orm\Model
{
    protected static $_connection = 'music';
    protected static $_table_name = 'art';
    protected static $_primary_key = array('art_id');
    protected static $_properties = array(
        'art_id',
        'media_id' => array(
            'data_type' => 'intt',
        ),
        'media_type' => array(
            'data_type' => 'text',
        ),
        'type' => array(
            'data_type' => 'text',
        ),
        'url' => array(
            'data_type' => 'text',
        ),
    );

    /**
     * Sélectionne une référence à une image dont on précise les informations
     *
     * @access public
     * @param integer identifiant du media (pk de artist ou album)
     * @param string artist ou album
     * @param string thumb ou fanart
     * @return integer
     */
    public static function get_id($media_id = null, $media_type = null, $type = null)
    {
      $query = 'SELECT art_id FROM `art` WHERE `art`.`media_id` = '.$media_id.' AND `art`.`media_type` = \''.$media_type.'\' AND `art`.`type` = \''.$type.'\'';
      $results = \DB::query($query)->as_object()->execute(static::$_connection);

      return $results[0]->art_id;
    }

    /**
     * Cherche les photos d'un artiste dont on précise l'identifiant
     *
     * @access public
     * @param integer
     * @return array
     */
    public static function get_for_artist($artist_id)
    {
      // On recherche...
      $query = 'SELECT type, url FROM `art` WHERE `art`.`media_id` = '.$artist_id.' AND `art`.`media_type` = \'artist\'';

      $results = \DB::query($query)
                      ->as_object()
                      ->execute(static::$_connection);

      $images = new stdClass();

      $url_thumb = '';
      $url_fanart = '';

      // Si au moins une photo est dans la base de données
      if (count($results) > 0)
      {
        foreach ($results as $result)
        {
          if ($result->type == 'thumb') $url_thumb = $result->url;
          if ($result->type == 'fanart') $url_fanart = $result->url;
        }

      }

      $images->thumb = Xbmc::get_artist_thumb($url_thumb);
      $images->fanart = Xbmc::get_artist_fanart($url_fanart);

      return $images;
    }

    /**
     * Cherche la pochette d'un album dont on précise l'identifiant
     *
     * @access public
     * @param integer
     * @return array
     */
    public static function get_for_album($album_id)
    {
      // On recherche...
      $query = 'SELECT type, url FROM `art` WHERE `art`.`media_id` = '.$album_id.' AND `art`.`media_type` = \'album\'';

      $results = \DB::query($query)
                      ->as_object()
                      ->execute(static::$_connection);

      $url = '';
      // Si au moins une photo est dans la base de données
      if (count($results) > 0)
      {
        $url = $results[0]->url;
      }

      $thumb = Xbmc::get_album_thumb($url);

      return $thumb;
    }

}
