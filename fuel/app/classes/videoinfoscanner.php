<?php

class VideoInfoScanner
{

  private static $_sources = array();

  public static function _init()
  {
    // Sources de la base de données 'xbmc'
    $sources = Model_VideoServerPath::read_all();

    static::setSources($sources);
  }

  public static function setSources($sources)
  {
    static::$_sources = $sources;
  }

  public static function getSources()
  {
    return static::$_sources;
  }

  /**
   * Trouve la source relative à un chemin
   *
   * @access public
   * @param string
   * @param string
   * @return object
   */
  public static function find_source($path)
  {
    $the_source = null;

    foreach(static::$_sources as $source)
    {
      if (strrpos($path, $source->client_path) !== FALSE)
      {
        $the_source = $source;
        break;
      }
    }
    return $the_source;
  }

  /**
   * Retourne les informations relatives à un dossier
   *
   * @access public
   * @param string
   * @param string
   * @return object
   */
  function get_source($path)
  {
    $the_source = new stdClass();

    foreach($this->sources as $source)
    {
      if (strrpos($path, $source->strPath) !== FALSE)
      {
        $the_source = $source;
        break;
      }
    }

    return $the_source;
  }

}
