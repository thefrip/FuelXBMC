<?php

class Scrapers_Music_Universal
{

  protected static $_connection = 'music';
  protected static $_table_name = 'artistview';
  protected static $_primary_key = 'idArtist';
  protected static $_fields = 'strImage, strFanart';

  /**
   * Get url for original size image for an artist
   *
   * @access public
   * @param string the url
   * @param int the primary key of the media
   * @param string thumb or fanart
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

    $url = $all_images->$image_type->originals[$key];

    // Remove anti hot link tips for real url
    $url = str_replace(\Uri::base(false).'media/', 'http://', $url);

    // And we have the original size too
    return $url;
  }

  /**
   * Traite et retourne les images d'un artiste à partir d'un obejt
   * issu de la base 'video'
   *
   * @access public
   * @param object
   * return object
   */
  public static function manage_images($artist = null)
  {
    $images = new stdClass();
    $images->thumbs = self::_manage_thumbs($artist->strImage);
    $images->fanarts = self::_manage_fanarts($artist->strFanart);

    return $images;
  }

  /**
   * Traite et retourne les photographies d'un artiste à partir du champ
   * issu de la base 'audio'
   *
   * @access private
   * @param string
   * return array
   */
  private static function _manage_thumbs($partial_xml = null)
  {
    // Tiny class with previews and originals arrays as properties
    $thumbs = new stdClass();
    $thumbs->previews = array();
    $thumbs->originals = array();

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
      $thumbs->previews = $previews;
      $thumbs->originals = $originals;
    }

    return $thumbs;
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
