<?php

class Scrapers_Video_Themoviedborg
{

  protected static $_connection = 'video';
  protected static $_table_name = 'movieview';
  protected static $_primary_key = 'idMovie';
  protected static $_fields = 'c08, c20';

  private static $_api_key = '57983e31fb435df4df77afb854740ea9';

  // The language's user
  private static $_lang = '';

  private static $_api_url = 'http://api.themoviedb.org/2.1/';
  private static $_site_url = 'http://www.themoviedb.org/';

  public static function _init()
  {
    $lang = Config::get('language');
    self::setLang($lang);
  }

  public static function setLang($iso)
  {
    self::$_lang = $iso;
  }

  /**
   * Récupère le lien vers un film à partir de son identifiant sur le site
   * distant
   *
   * @access public
   * @param object
   * @return string
   */
  public static function get_external_link($data)
  {
    // Utilisation de la classe Xbmc
    $response = Xbmc::download(self::$_api_url.'Movie.imdbLookup/'.self::$_lang.'/json/'.self::$_api_key.'/'.$data->c09);
    $response = json_decode($response);

    return $response[0]->url.'?language='.self::$_lang;
  }

  /**
   * Get url for original size image for a movie
   *
   * @access public
   * @param string the url
   * @param int the primary key of the media
   * @param string poster or fanart
   * @return string url for original size image
   */
  public static function get_image_link($url = '', $media_id = 0, $type = '')
  {
    // We search for all images for this movie
    $query = 'SELECT '.static::$_fields.' FROM '.static::$_table_name.' WHERE '.static::$_primary_key.' = '.$media_id;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // List all posters and fanarts for this movie with preview and original sizes
    $all_images = self::manage_images($results[0]);

    // type is poster or fanart, we search for posters or fanarts
    $image_type = $type.'s';

    // We have the key of the selected poster or fanart in preview size
    $key = array_search($url, $all_images->$image_type->previews);

    // And we have the original size too
    return $all_images->$image_type->originals[$key];
  }

  /**
   * Traite et retourne les images d'un film à partir d'un obejt
   * issu de la base 'video'
   *
   * @access public
   * @param object
   * return object
   */
  public static function manage_images($movie = null)
  {
    $images = new stdClass();
    $images->posters = self::_manage_posters($movie->c08);
    $images->fanarts = self::_manage_fanarts($movie->c20);

//echo '<pre>'.print_r($images, true).'</pre>'; die();

    return $images;
  }

  /**
   * Traite et retourne les affiches d'un film à partir du champ
   * issu de la base 'video'
   *
   * @access private
   * @param string
   * return array
   */
  private static function _manage_posters($partial_xml = null)
  {
    // Tiny class with previews and originals arrays as properties
    $posters = new stdClass();
    $posters->previews = array();
    $posters->originals = array();

    if ($partial_xml)
    {
      $xml = simplexml_load_string('<root>'.$partial_xml.'</root>');

      $cpt = 0;
      $previews = array();
      $originals = array();
      foreach ($xml->thumb as $thumb)
      {
        $attributes = $thumb->attributes();

        // Each image have two urls, one for a preview size and one for an original size
        $previews[$cpt] = (string) $attributes['preview'];
        $originals[$cpt] = (string) $thumb[0];
        ++$cpt;
      }
      $posters->previews = $previews;
      $posters->originals = $originals;
    }

    return $posters;
  }

  /**
   * Traite et retourne les fonds d'écran d'un film à partir du champ
   * issu de la base 'video'
   *
   * @access private
   * @param string
   * @return array
   */
  private static function _manage_fanarts($partial_xml = null)
  {
    // Tiny class with previews and originals arrays as properties
    $fanarts = new stdClass();
    $fanarts->previews = array();
    $fanarts->originals = array();

    if ($partial_xml)
    {
      $xml = simplexml_load_string('<root>'.$partial_xml.'</root>');

      $cpt = 0;
      $previews = array();
      $originals = array();
      foreach ($xml->fanart->thumb as $thumb)
      {
        $attributes = $thumb->attributes();

        // Each image have two urls, one for a preview size and one for an original size
        $previews[$cpt] = (string) $attributes['preview'];
        $originals[$cpt] = (string) $thumb[0];
        ++$cpt;
      }
      $fanarts->previews = $previews;
      $fanarts->originals = $originals;
    }

    return $fanarts;
  }

}
