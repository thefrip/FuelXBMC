<?php

class Model_VideoArt extends Orm\Model
{
    protected static $_connection = 'video';
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
     * Cherche la photo d'une personne dont on précise l'identifiant
     *
     * @access public
     * @param integer
     * @return array
     */
    public static function get_for_person($person_id)
    {
      // On recherche...
      $query = 'SELECT url FROM `art` WHERE `art`.`media_id` = '.$person_id.' AND `art`.`media_type` = \'actor\'';

      $results = \DB::query($query)
                      ->as_object()
                      ->execute(static::$_connection);

      $url = '';
      // Si au moins une photo est dans la base de données
      if (count($results) > 0)
      {
        $url = $results[0]->url;
      }

      $photo = Xbmc::get_person_photo($url);

      return $photo;
    }

    /**
     * Cherche les images d'un film dont on précise l'identifiant
     *
     * @access public
     * @param integer
     * @return array
     */
    public static function get_for_movie($movie_id)
    {
      // On recherche...
      $query = 'SELECT type, url FROM `art` WHERE `art`.`media_id` = '.$movie_id.' AND `art`.`media_type` = \'movie\'';

      $results = \DB::query($query)
                      ->as_object()
                      ->execute(static::$_connection);

      $images = new stdClass();

      $url_poster = '';
      $url_fanart = '';

      // Si au moins une photo est dans la base de données
      if (count($results) > 0)
      {
        foreach ($results as $result)
        {
          if ($result->type == 'poster') $url_poster = $result->url;
          if ($result->type == 'fanart') $url_fanart = $result->url;
        }

      }

      $images->poster = Xbmc::get_movie_poster($url_poster);
      $images->fanart = Xbmc::get_movie_fanart($url_fanart);

      return $images;
    }

    /**
     * Cherche les images d'une série TV dont on précise l'identifiant
     *
     * @access public
     * @param integer
     * @return array
     */
    public static function get_for_tvshow($tvshow_id)
    {
      // On recherche...
      $query = 'SELECT type, url FROM `art` WHERE `art`.`media_id` = '.$tvshow_id.' AND `art`.`media_type` = \'tvshow\'';

      $results = \DB::query($query)
                      ->as_object()
                      ->execute(static::$_connection);

      $images = new stdClass();

      $url_banner = '';
      $url_poster = '';
      $url_fanart = '';

      // Si au moins une photo est dans la base de données
      if (count($results) > 0)
      {
        foreach ($results as $result)
        {
          if ($result->type == 'banner') $url_banner = $result->url;
          if ($result->type == 'poster') $url_poster = $result->url;
          if ($result->type == 'fanart') $url_fanart = $result->url;
        }

      }

      $images->banner = Xbmc::get_tvshow_banner($url_banner);
      $images->poster = Xbmc::get_tvshow_poster($url_poster);
      $images->fanart = Xbmc::get_tvshow_fanart($url_fanart);

      return $images;
    }

    /**
     * Cherche l'image des saisons d'une série TV dont on précise l'identifiant
     *
     * Retourne l'image de TOUTES les saisons que l'on dipose des épisodes ou pas
     *
     * @access public
     * @param integer
     * @return array
     */
    public static function get_for_seasons($tvshow_id)
    {
      // On recherche...
      $query = 'SELECT type, url, `seasons`.`season` FROM `art` JOIN `seasons` ON (`art`.`media_id` = `seasons`.`idSeason`) WHERE `seasons`.`idShow` = '.$tvshow_id.' AND `art`.`media_type` = \'season\'';

      $results = \DB::query($query)
                      ->as_object()
                      ->execute(static::$_connection);

      $images = array();

      // Si au moins une image est dans la base de données
      if (count($results) > 0)
      {
        foreach ($results as $result)
        {
          // $result->season vaut -1 pour toutes les saisons
          // puis 0 pour hors-saison, puis 1 pour la saison 1...
          $images[$result->season] = Xbmc::get_tvshow_poster($result->url);
        }
      }

      return $images;
    }

    /**
     * Cherche la photo d'une episodene dont on précise l'identifiant
     *
     * @access public
     * @param integer
     * @return array
     */
    public static function get_for_episode($episode_id)
    {
      // On recherche...
      $query = 'SELECT url FROM `art` WHERE `art`.`media_id` = '.$episode_id.' AND `art`.`media_type` = \'episode\'';

      $results = \DB::query($query)
                      ->as_object()
                      ->execute(static::$_connection);

      $url = '';
      // Si au moins une photo est dans la base de données
      if (count($results) > 0)
      {
        $url = $results[0]->url;
      }

      $poster = Xbmc::get_episode_poster($url);

      return $poster;
    }

}
