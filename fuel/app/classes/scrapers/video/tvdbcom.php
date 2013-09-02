<?php

class Scrapers_Video_Tvdbcom
{

  protected static $_connection = 'video';
  protected static $_table_name = 'tvshowview';
  protected static $_primary_key = 'idShow';
  protected static $_fields = 'c06, c11';

  private static $api_key = '7258DDA442492F57';

  // All available languages with this api, in case of changes see :
  // http://www.thetvdb.com/wiki/index.php/Multi_Language#Available_Languages
  private static $_languages = array('en' => 7, 'sv' => 8, 'no' => 9, 'da' => 10,
                                     'fi' => 11, 'nl' => 13, 'de' => 14, 'it' => 15,
                                     'es' => 16, 'fr' => 17, 'pl' => 18, 'hu' => 19,
                                     'el' => 20, 'tr' => 21, 'ru' => 22, 'he' => 24,
                                     'ja' => 25, 'pt' => 26, 'zh' => 27, 'cs' => 28,
                                     'sl' => 30, 'hr' => 31, 'ko' => 32,
                                     );

  // The language's user
  private static $_lang = '';

  private static $_api_url = 'http://www.thetvdb.com/api/';
  private static $_site_url = 'http://www.thetvdb.com/';
  private static $_image_url = 'http://thetvdb.com/';

  public static function _init()
  {
    $lang = Config::get('language');
    self::setLang($lang);
  }

  public static function setLang($iso)
  {
    self::$_lang = (array_key_exists($iso, self::$_languages)) ? self::$_languages[$iso] : 7;
  }

  /**
   * Récupère le lien vers une série TV à partir de son identifiant sur le site
   * distant
   *
   * @access public
   * @param object
   * @return string
   */
  public static function get_external_link($data)
  {
    return self::$_site_url.'?tab=series&id='.$data->c12.'&lid='.self::$_lang;
  }

  /**
   * Récupère le lien réel d'une image d'une série TV
   *
   * @access public
   * @param string
   * @param bool
   * @return string
   */
  public static function get_image_link_old($url = null, $fullsize = false)
  {
    // Change preview url ?
    if ($fullsize)
    {
      if (strpos($url, '/_cache/fanart/') !== false)
      {
        $url = str_replace('_cache/fanart', 'fanart', $url);
      }
    }

    return $url;
  }

  /**
   * Get url for original size image for a TV show
   *
   * @access public
   * @param string the url
   * @param int the primary key of the media
   * @param string banner, poster or fanart
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
   * Traite et retourne les images d'un film à partir d'un obejt
   * issu de la base 'video'
   *
   * @access public
   * @param object
   * return object
   */
  public static function manage_images($tvshow = null)
  {
    // images is a class with 2 arrays : banners ans posters
    $images = self::_manage_posters($tvshow->c06);
    $images->fanarts = self::_manage_fanarts($tvshow->c11);

    return $images;
  }

  /**
   * Traite et retourne les affiches d'une série TV à partir du champ
   * issu de la base 'video'
   *
   * @access private
   * @param string
   * return array
   */
  private static function _manage_posters($partial_xml = null)
  {
    $banners = null;
    $posters = null;

    if ($partial_xml)
    {
      $xml = simplexml_load_string('<root>'.$partial_xml.'</root>');

      $banners = array();
      $posters = array();

      foreach($xml->thumb as $thumb)
      {
        $attributes = $thumb->attributes();
        if ($attributes['aspect'] == 'banner')
        {
          $banners[] = str_replace('http://', \Uri::base(false).'media/', (string) $thumb[0]);
        }
        else
        {
          if (!isset($attributes['type']))
          {
            $posters[] = str_replace('http://', \Uri::base(false).'media/', (string) $thumb[0]);
          }
        }
      }

      // Temporary class to pass banners in right place
      $temp_banners = new stdClass();
      $temp_banners->previews = $banners;
      $temp_banners->originals = $banners;
      $banners = $temp_banners;

      // Temporary class to pass posters in right place
      $temp_posters = new stdClass();
      $temp_posters->previews = $posters;
      $temp_posters->originals = $posters;
      $posters = $temp_posters;
    }

    $images = new stdClass();
    $images->posters = $posters;
    $images->banners = $banners;

    return $images;
  }

  /**
   * Traite et retourne les fonds d'écran d'une série TV à partir du champ
   * issu de la base 'video'
   *
   * @access private
   * @param string
   * @return array
   */
  private static function _manage_fanarts($partial_xml)
  {
    // Tiny class with previews and originals arrays as properties
    $fanarts = new stdClass();
    $fanarts->previews = array();
    $fanarts->originals = array();

    $fanart = null;

    if ($partial_xml)
    {
      $xml = simplexml_load_string('<root>'.$partial_xml.'</root>');

      $cpt = 0;
      $previews = array();
      $originals = array();

      // Prefix url for images in this xml
      $attributes = $xml->fanart->attributes();
      $prefix_url = (string) $attributes['url'];

      foreach ($xml->fanart->thumb as $thumb)
      {
        $attributes = $thumb->attributes();

        // Anti hot link
        $url_preview = $prefix_url.(string) $attributes['preview'];
        $url_preview = str_replace('http://', \Uri::base(false).'media/', $url_preview);
        $url_original = $prefix_url.(string) $thumb[0];
        $url_original = str_replace('http://', \Uri::base(false).'media/', $url_original);

        // Each image have two urls, one for a preview size and one for an original size
        $previews[$cpt] = $url_preview;
        $originals[$cpt] = $url_original;
        ++$cpt;

      }
      $fanarts->previews = $previews;
      $fanarts->originals = $originals;
    }

    return $fanarts;
  }

}
