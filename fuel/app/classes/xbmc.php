<?php

class Xbmc
{

  const DEFAULT_IMAGES = 'assets/images/gui/';
  const THUMBNAILS = 'assets/images/Thumbnails/';

  private static $_default_images_url;
  private static $_thumbnails_dir;
  private static $_thumbnails_url;

  public static function _init()
  {
    self::$_default_images_url = Config::get('base_url').self::DEFAULT_IMAGES;
    self::$_thumbnails_dir = DOCROOT.self::THUMBNAILS;
    self::$_thumbnails_url = Config::get('base_url').self::THUMBNAILS;
  }

  /**
   * Retourne media dont on précise le type pour générer une miniature dont la taille
   * dépend du type
   *
   * @access private
   * @param string
   * @param string
   * @return object
   */
  private static function _get_media_art($url = '', $type = '')
  {
    $art = new stdClass();

    // Valeur par défaut
    $art->url = self::$_default_images_url.'default_'.$type.'.jpg';

    if ($url != '')
    {
      // Calcul du hashage du fichier
      $thumbnail = self::_get_hash($url);

      // Emplacement présumé du fichier pour xbmc
      $art->filename = self::$_thumbnails_dir.substr($thumbnail, 0, 1).'/'.$thumbnail.'.jpg';

      // Fichier absent sur le disque ?
      if (!file_exists($art->filename))
      {
        // on le télécharge
        Xbmc::download($url, $art->filename);
      }
      $art->url = self::$_thumbnails_url.substr($thumbnail, 0, 1).'/'.$thumbnail.'.jpg';;
    }

    return $art;
  }

  /**
   * Clear strings before database insertion
   *
   * @access public
   * @param string
   * @return string
   */
  public static function clean($string = '')
  {
    $string = strip_tags($string);

    if (get_magic_quotes_gpc())
    {
      $string = stripslashes($string);
    }

    return $string;
  }

  /**
   * Retourne la pochette d'un album
   *
   * @access public
   * @param string
   * @return object
   */
  public static function get_album_thumb($url)
  {
    return self::_get_media_art($url, 'album');
  }

  /**
   * Retourne l'affiche d'un artiste
   *
   * @access public
   * @param string
   * @return object
   */
  public static function get_artist_thumb($url)
  {
    return self::_get_media_art($url, 'artist_thumb');
  }

  /**
   * Retourne le fond d'écran d'un artiste
   *
   * @access public
   * @param string
   * @return object
   */
  public static function get_artist_fanart($url)
  {
    return self::_get_media_art($url, 'artist_fanart');
  }

  /**
   * Retourne l'affiche d'un film
   *
   * @access public
   * @param string
   * @return object
   */
  public static function get_movie_poster($url)
  {
    return self::_get_media_art($url, 'poster');
  }

  /**
   * Retourne le fond d'écran d'un film
   *
   * @access public
   * @param string
   * @return object
   */
  public static function get_movie_fanart($url)
  {
    return self::_get_media_art($url, 'video_fanart');
  }

  /**
   * Retourne l'affiche d'une série TV
   *
   * @access public
   * @param string
   * @return object
   */
  public static function get_tvshow_poster($url)
  {
    return self::_get_media_art($url, 'poster');
  }

  /**
   * Retourne la bannière d'une série TV
   *
   * @access public
   * @param string
   * @return object
   */
  public static function get_tvshow_banner($url)
  {
    return self::_get_media_art($url, 'banner');
  }

  /**
   * Retourne le fond d'écran d'une série TV
   *
   * @param string
   * @return object
   */
  public static function get_tvshow_fanart($url)
  {
    return self::_get_media_art($url, 'video_fanart');
  }

  /**
   * Retourne l'image d'un épisode d'une série tv pour un fichier
   *
   * @access public
   * @param string
   * @return object
   */
  public static function get_episode_poster($url)
  {
    return self::_get_media_art($url, 'episode');
  }

  /**
   * Retourne la photo d'une personne
   *
   * @access public
   * @param string
   * @return object
   */
  public static function get_person_photo($url)
  {
    return self::_get_media_art($url, 'photo');
  }

  /**
   * Calcule le hash pour un fichier selon l'algorithme utilisé par XBMC
   *
   * @access private
   * @param string
   * @return string
   */
  private static function _get_hash($text = '')
  {
    $chars = strtolower($text);
    $crc = 0xffffffff;

    for ($ptr = 0; $ptr < strlen($chars); $ptr++)
    {
      $chr = ord($chars[$ptr]);
      $crc ^= $chr << 24;

      for ((int) $i = 0; $i < 8; $i++)
      {
        if ($crc & 0x80000000)
        {
          $crc = ($crc << 1) ^ 0x04C11DB7;
        }
        else
        {
          $crc <<= 1;
        }
      }
    }

    // Système d'exploitation en 64 bits ?
    if (strpos(php_uname('m'), '_64') !== false)
    {

			//Formatting the output in a 8 character hex
			if ($crc>=0)
			{
				$hash = sprintf("%16s",sprintf("%x",sprintf("%u",$crc)));
			}
			else
			{
				$source = sprintf('%b', $crc);

				$hash = "";
				while ($source <> "")
				{
					$digit = substr($source, -4);
					$hash = dechex(bindec($digit)) . $hash;
					$source = substr($source, 0, -4);
				}
			}
			$hash = substr($hash, 8);
    }
    else
    {
			//Formatting the output in a 8 character hex
			if ($crc>=0)
			{
				$hash = sprintf("%08s",sprintf("%x",sprintf("%u",$crc)));
			}
			else
			{
				$source = sprintf('%b', $crc);

				$hash = "";
				while ($source <> "")
				{
					$digit = substr($source, -4);
					$hash = dechex(bindec($digit)) . $hash;
					$source = substr($source, 0, -4);
				}
			}
    }

    return $hash;
  }

  /**
   * Télécharge et stocke un fichier distant en se faisant passer pour un navigateur
   * Retourne le contenu de l'url pointée si on ne spécifie pas de nom de fichier
   *
   * @access public
   * @param string
   * @param string
   * @return void or string
   */
  public static function download($url, $filename = NULL)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, \Input::user_agent());

    $result = curl_exec($ch);
    $headers = curl_getinfo($ch);

    $error_number = curl_errno($ch);
    $error_message = curl_error($ch);

    curl_close($ch);

    // Téléchargement si nom de fichier spécifié sinon retour du contenu pointé
    if (isset($filename))
    {
      $f = fopen($filename,'wb');
      fwrite($f, $result, strlen($result));
      fclose($f);
    }
    else
    {
      return $result;
    }
  }

}
