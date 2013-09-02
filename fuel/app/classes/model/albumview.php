<?php

class Model_AlbumView extends Orm\Model
{
    protected static $_connection = 'music';
    protected static $_table_name = 'albumview';
    protected static $_primary_key = array('idAlbum');
    protected static $_properties = array(
        'idAlbum', // both validation & typing observers will ignore the PK
        'strArtists' => array(
            'data_type' => 'text',
        ),
        'idArtist' => array(
            'data_type' => 'int',
        ),
        'strAlbum' => array(
            'data_type' => 'text',
        ),
        'iYear' => array(
            'data_type' => 'int',
        ),
        'idGenre' => array(
            'data_type' => 'int',
        ),
        'strGenre' => array(
            'data_type' => 'text',
        ),
        'strThumb' => array(
            'data_type' => 'text',
        ),
    );

	/**
	 * Searches for a single row in the database.
	 *
	 * @param string $id The primary key of the record to search for.
	 *
	 * @return mixed An object representing the db row, or FALSE.
	 */
    public static function read($album_id, $for_view = FALSE)
    {
      $query = 'SELECT * FROM `albumview` JOIN `album_artist` ON (`album_artist`.`idAlbum` = `albumview`.`idAlbum`) WHERE `album_artist`.`idAlbum` = '.$album_id.' LIMIT 1';

      $results = \DB::query($query)
                      ->as_object()
                      ->execute(static::$_connection);

      // Si un album est dans la base de données
      if (count($results) == 1)
      {
        // On traite le résultat pour le convertir en album
        $album = self::_manage_results($results[0], $for_view);
      }
      else
      {
        $album = NULL;
      }

      // On retourne l'album trouvé ou NULL
      return $album;
    }

  /**
   * Cherche un album dont le titre commence par le titre fourni
   * Retourne un tableau représentant le ou les albulm(s) trouvé(s) ou NULL si non trouvé
   *
   * @access public
   * @param string
   * @param integer
   * @param integer
   * @return array
   */
  public static function read_all_by_title($title, $limit = NULL, $offset = NULL)
  {
    // On décompose le titre en tableau de mots
    $words = explode(' ', $title);

    // Un seul mot alors on recherche
    if (count($words) == 1)
    {
      $where = ' WHERE LOWER(`albumview`.`strAlbum`) LIKE "%'.$title.'%"';
    }
    else
    {
      // Sinon on fait (WHERE `albumview`.`strAlbum` LIKE %mot1%) AND (WHERE `albumview`.`strAlbum` LIKE %mot2%) AND ...
      $where = ' WHERE ';
      foreach($words as $word)
      {
        $where .= '(LOWER(`albumview`.`strAlbum`) LIKE "%'.$word.'%") AND ';
      }
      // On supprime le dernier ' AND '
      $where = substr($where, 0, -5);
    }

    // On recherche...
    $query = 'SELECT * FROM `albumview` JOIN `album_artist` ON (`album_artist`.`idAlbum` = `albumview`.`idAlbum`) '.$where.' ORDER BY `albumview`.`strAlbum` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins un album est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en album
				$albums[] = self::_manage_results($result);
      }
    }
    else
    {
      $albums = NULL;
    }

    // On retourne les albums trouvés ou NULL
    return $albums;
  }

  /**
   * Retourne le nombre total de albums dont le titre commence par le titre fourni
   *
   * @access public
   * @param string
   * @return integer
   */
  public static function count_all_by_title($title)
  {
    // On décompose le titre en tableau de mots
    $words = explode(' ', $title);

    // Un seul mot alors on recherche
    if (count($words) == 1)
    {
      $where = ' WHERE LOWER(`albumview`.`strAlbum`) LIKE "%'.$title.'%"';
    }
    else
    {
      // Sinon on fait (WHERE `albumview`.`strAlbum` LIKE %mot1%) AND (WHERE `albumview`.`strAlbum` LIKE %mot2%) AND ...
      $where = ' WHERE ';
      foreach($words as $word)
      {
        $where .= '(LOWER(`albumview`.`strAlbum`) LIKE "%'.$word.'%") AND ';
      }
      // On supprime le dernier ' AND '
      $where = substr($where, 0, -5);
    }

    // On recherche...
    $query = 'SELECT `albumview`.idAlbum FROM `albumview`'.$where.' ORDER BY `albumview`.`strAlbum` ASC';

    return count(\DB::query($query)->execute(static::$_connection));
  }

  /**
   * Traite un résultat d'une requête dans la base de données pour le convertir en album
   *
   * Complète les informations du album si on en affiche la page détaillée
   *
   * @access public
   * @param object
   * @param bool
   * @return object
   */
    private static function _manage_results($result, $for_view = true)
    {
      $album = new stdClass();

      // Identifiant
      $album->id = $result->idAlbum;

      $album->thumb = Model_MusicArt::get_for_album($album->id);

      // Données diverses avec mise en forme
      $album->title = $result->strAlbum;

      if ($result->iYear != 0)
          $album->year = $result->iYear;
      else
          $album->year = Lang::get('media.no_year');

      if ($result->strReview != '')
          $album->review = $result->strReview;
      else
          $album->review = Lang::get('media.no_review');

      $album->genres = Model_MusicGenre::get_for_album($album->id);

      $artist = new stdClass();
      $artist->id = $result->idArtist;
      $artist->name = $result->strArtists;

      $album->artist = $artist;

      // Consulation de la page détaillée d'un album ?
      if ($for_view)
      {
        $album->songs = Model_SongView::get_for_album($album->id);
      }

      // On retourne l'album traité
      return $album;
    }

  /**
   * Retourne toutes les albums
   *
   * @access public
   * @return array
   */
  public static function read_all($limit = NULL, $offset = NULL)
  {
		$query = 'SELECT * FROM albumview JOIN `album_artist` ON (`album_artist`.`idAlbum` = `albumview`.`idAlbum`) ORDER BY albumview.strAlbum ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset and ($offset > 1)) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins un album est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en bum
				$albums[] = self::_manage_results($result);
      }
    }
    else
    {
      $albums = NULL;
    }

    // On retourne les albums trouvées ou NULL
    return $albums;
  }

  /**
   * Retourne 'max' derniers albums
   *
   * @access public
   * @param integer
   * @return array
   */
  public static function read_last($max = 5)
  {
    $query = 'SELECT * FROM `albumview` JOIN `album_artist` ON (`album_artist`.`idAlbum` = `albumview`.`idAlbum`) ORDER BY `albumview`.`IdAlbum` DESC LIMIT '.$max;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    $albums = array();

    // Si au moins un album est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en album
				$albums[] = self::_manage_results($result);
      }
    }
    else
    {
      $albums = NULL;
    }

    // On retourne les albums trouvés ou NULL
    return $albums;
  }

  /**
   * Retourne tous les albums dont on précise l'année
   *
   * @access public
   * @param integer
   * @return array
   */
  public static function read_all_by_year($year, $limit = NULL, $offset = NULL)
  {
    $query = 'SELECT * FROM `albumview` JOIN `album_artist` ON (`album_artist`.`idAlbum` = `albumview`.`idAlbum`) WHERE `albumview`.`iYear` = '.$year.' ORDER BY `albumview`.`strAlbum` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    $albums = array();

    // Si au moins un album est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en album
				$albums[] = self::_manage_results($result);
      }
    }
    else
    {
      $albums = NULL;
    }

    // On retourne les albums trouvés ou NULL
    return $albums;
  }

  /**
   * Retourne le total des albums dont on précise l'année
   *
   * @access public
   * @param integer
   * @return integer
   */
  public static function count_by_year($year)
  {
    return count(DB::select('idAlbum')->from('albumview')
                                      ->where('iYear', $year)
                                      ->execute(static::$_connection));
  }

  /**
   * Retourne tous les albums dont on précise l'identifiant du genre
   *
   * @access public
   * @param integer
   * @return array
   */
  public static function read_all_by_genre($idGenre, $limit = NULL, $offset = NULL)
  {
    $query = 'SELECT * FROM `albumview` JOIN `album_artist` ON (`album_artist`.`idAlbum` = `albumview`.`idAlbum`) JOIN `album_genre` ON (`album_artist`.`idAlbum` = `album_genre`.`idAlbum`) WHERE `album_genre`.`idGenre` = '.$idGenre.' ORDER BY `albumview`.`strAlbum` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    $albums = array();

    // Si au moins un album est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en album
				$albums[] = self::_manage_results($result);
      }
    }
    else
    {
      $albums = NULL;
    }

    // On retourne les albums trouvés ou NULL
    return $albums;
  }

  /**
   * Retourne le total des albums dont on précise l'identifiant du genre
   *
   * @access public
   * @param integer
   * @return integer
   */
  public static function count_by_genre($idGenre)
  {
    return count(DB::select('idAlbum')->from('album_genre')
                                      ->where('idGenre', $idGenre)
                                      ->execute(static::$_connection));
  }

  /**
   * Retourne tous les albums pour lesquels la personne dont on précise
   * l'identifiant est acteur
   *
   * @access public
   * @param integer
   * @return array
   */
  public static function read_all_by_artist($idArtist, $limit = NULL, $offset = NULL)
  {
    $query = 'SELECT * FROM `albumview` JOIN `album_artist` ON (`album_artist`.`idAlbum` = `albumview`.`idAlbum`) WHERE `album_artist`.`idArtist` = '.$idArtist.' ORDER BY `albumview`.`strAlbum` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    $albums = array();

    // Si au moins un album est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en album
				$albums[] = self::_manage_results($result);
      }
    }
    else
    {
      $albums = NULL;
    }

    // On retourne les albums trouvés ou NULL
    return $albums;
  }

  /**
   * Compte tous les albums pour lesquels la personne dont on précise
   * l'identifiant est acteur
   *
   * @access public
   * @param integer
   * @return integer
   */
  public static function count_by_artist($idArtist)
  {
    return count(DB::select('idAlbum')->from('album_artist')
                                      ->where('idArtist', $idArtist)
                                      ->execute(static::$_connection));
  }

}
