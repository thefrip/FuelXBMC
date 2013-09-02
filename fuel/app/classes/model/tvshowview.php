<?php

class Model_TvshowView extends Orm\Model
{
  protected static $_connection = 'video';
  protected static $_table_name = 'tvshowview';
  protected static $_primary_key = array('idShow');
  protected static $_properties = array(
    'idShow',
    'c00',          // local title
    'c01',          // plot summary
    'c03',          // votes
    'c04',          // rating
    'c05',          // first aired (use only year)
    'c06',          // thumbnails url : fake xml
    'c11',          // fanarts url : fake xml
    'c12',          // thetvdb id (when use the corresponding scraper)
    'c13',          // mpaa certification
    'strPath',
    'totalSeasons',
    'totalCount',
  );

	/**
	 * Searches for a single row in the database.
	 *
	 * @param string $id The primary key of the record to search for.
	 *
	 * @return mixed An object representing the db row, or FALSE.
	 */
    public static function find_source($tvshow_id)
    {
      $query = 'SELECT `tvshowview`.`strPath` as path FROM `tvshowview` WHERE `tvshowview`.`idShow` = '.$tvshow_id;

      $results = \DB::query($query)
                      ->as_object()
                      ->execute(static::$_connection);

      return VideoInfoScanner::find_source($results[0]->path);
    }

	/**
	 * Searches for a single row in the database.
	 *
	 * @param string $id The primary key of the record to search for.
	 *
	 * @return mixed An object representing the db row, or FALSE.
	 */
    public static function read($tvshow_id, $for_view = FALSE)
    {
      $query = 'SELECT `tvshowview`.*, MAX(CAST(`episodeview`.`c12` AS UNSIGNED)) AS last_season, MIN(CAST(`episodeview`.`c12` AS UNSIGNED)) AS first_season FROM `tvshowview` JOIN `episodeview` ON (`episodeview`.`idShow` = `tvshowview`.`idShow`) WHERE `tvshowview`.`idShow` = '.$tvshow_id;

      $results = \DB::query($query)
                      ->as_object()
                      ->execute(static::$_connection);

      // Si une série TV est dans la base de données
      if (count($results) == 1)
      {
        // On traite le résultat pour le convertir en série TV
        $tvshow = self::_manage_results($results[0], $for_view);
      }
      else
      {
        $tvshow = NULL;
      }

      // On retourne la série TV trouvée ou NULL
      return $tvshow;

      return self::_manage_results(self::find($tvshow_id), $for_view);
    }

  /**
   * Cherche les séries TV dont le titre commence par le titre fourni
   * Retourne un tableau représentant la ou les série(s) TV trouvé(s) ou NULL si non trouvé
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
      $where = ' WHERE LOWER(`tvshowview`.`c00`) LIKE "%'.$title.'%"';
    }
    else
    {
      // Sinon on fait (WHERE `tvshowview`.`c00` LIKE %mot1%) AND (WHERE `tvshowview`.`c00` LIKE %mot2%) AND ...
      $where = ' WHERE ';
      foreach($words as $word)
      {
        $where .= '(LOWER(`tvshowview`.`c00`) LIKE "%'.$word.'%") AND ';
      }
      // On supprime le dernier ' AND '
      $where = substr($where, 0, -5);
    }

    // On recherche...
    $query = 'SELECT * FROM `tvshowview`'.$where.' ORDER BY `tvshowview`.`c00` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en série TV
				$tvshows[] = self::_manage_results($result);
      }
    }
    else
    {
      $tvshows = NULL;
    }

    // On retourne les séries TV trouvées ou NULL
    return $tvshows;
  }

  /**
   * Retourne le nombre total de séries TV dont le titre commence par le titre fourni
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
      $where = ' WHERE LOWER(`tvshowview`.`c00`) LIKE "%'.$title.'%"';
    }
    else
    {
      // Sinon on fait (WHERE `tvshowview`.`c00` LIKE %mot1%) AND (WHERE `tvshowview`.`c00` LIKE %mot2%) AND ...
      $where = ' WHERE ';
      foreach($words as $word)
      {
        $where .= '(LOWER(`tvshowview`.`c00`) LIKE "%'.$word.'%") AND ';
      }
      // On supprime le dernier ' AND '
      $where = substr($where, 0, -5);
    }

    // On recherche...
    $query = 'SELECT `tvshowview`.idShow FROM `tvshowview`'.$where.' ORDER BY `tvshowview`.`c00` ASC';

    return count(\DB::query($query)->execute(static::$_connection));
  }

  /**
   * Retourne tous les séries TV
   *
   * @access public
   * @return array
   */
  public static function read_all($limit = NULL, $offset = NULL)
  {
    $query = 'SELECT * FROM `tvshowview` ORDER BY `tvshowview`.`c00` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset and ($offset > 1)) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en film
				$tvshows[] = self::_manage_results($result);
      }
    }
    else
    {
      $tvshows = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $tvshows;
  }

  /**
   * Retourne 'max' dernières séries TV
   *
   * @access public
   * @param integer
   * @return array
   */
  public static function read_last($max = 6)
  {
    $query = 'SELECT * FROM `tvshowview` ORDER BY `tvshowview`.`IdShow` DESC LIMIT '.$max;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins une série TV est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en série TV
				$tvshows[] = self::_manage_results($result);
      }
    }
    else
    {
      $tvshows = NULL;
    }

    // On retourne les série TV trouvées ou NULL
    return $tvshows;
  }

  /**
   * Retourne tous les films pour lesquels la personne dont on précise
   * l'identifiant est scénariste
   *
   * @access public
   * @param integer
   * @return array
   */
  public static function read_all_by_writer($idWriter, $limit = NULL, $offset = NULL)
  {
    $query = 'SELECT * FROM `tvshowview` JOIN `writerlinktvshow` ON (`writerlinktvshow`.`idShow` = `tvshowview`.`idShow`) WHERE `writerlinktvshow`.`idWriter` = '.$idWriter.' ORDER BY `tvshowview`.`c00` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en film
				$tvshows[] = self::_manage_results($result);
      }
    }
    else
    {
      $tvshows = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $tvshows;
  }

  /**
   * Compte tous les films pour lesquels la personne dont on précise
   * l'identifiant est scénariste
   *
   * @access public
   * @param integer
   * @return integer
   */
  public static function count_by_writer($idWriter)
  {
    return count(DB::select('idShow')->from('writerlinktvshow')
                                      ->where('idWriter', $idWriter)
                                      ->execute(static::$_connection));
  }

  /**
   * Retourne tous les films pour lesquels la personne dont on précise
   * l'identifiant est réalisateur
   *
   * @access public
   * @param integer
   * @return array
   */
  public static function read_all_by_director($idDirector, $limit = NULL, $offset = NULL)
  {
    $query = 'SELECT * FROM `tvshowview` JOIN `directorlinktvshow` ON (`directorlinktvshow`.`idShow` = `tvshowview`.`idShow`) WHERE `directorlinktvshow`.`idDirector` = '.$idDirector.' ORDER BY `tvshowview`.`c00` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en film
				$tvshows[] = self::_manage_results($result);
      }
    }
    else
    {
      $tvshows = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $tvshows;
  }

  /**
   * Compte tous les films pour lesquels la personne dont on précise
   * l'identifiant est réalisateur
   *
   * @access public
   * @param integer
   * @return integer
   */
  public static function count_by_director($idDirector)
  {
    return count(DB::select('idShow')->from('directorlinktvshow')
                                      ->where('idDirector', $idDirector)
                                      ->execute(static::$_connection));
  }

  /**
   * Retourne tous les films pour lesquels la personne dont on précise
   * l'identifiant est acteur
   *
   * @access public
   * @param integer
   * @return array
   */
  public static function read_all_by_actor($idActor, $limit = NULL, $offset = NULL)
  {
    $query = 'SELECT * FROM `tvshowview` JOIN `actorlinktvshow` ON (`actorlinktvshow`.`idShow` = `tvshowview`.`idShow`) WHERE `actorlinktvshow`.`idActor` = '.$idActor.' ORDER BY `tvshowview`.`c00` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en film
				$tvshows[] = self::_manage_results($result);
      }
    }
    else
    {
      $tvshows = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $tvshows;
  }

  /**
   * Compte tous les films pour lesquels la personne dont on précise
   * l'identifiant est acteur
   *
   * @access public
   * @param integer
   * @return integer
   */
  public static function count_by_actor($idActor)
  {
    return count(DB::select('idShow')->from('actorlinktvshow')
                                      ->where('idActor', $idActor)
                                      ->execute(static::$_connection));
  }

  /**
   * Retourne tous les films dont on précise l'année
   *
   * @access public
   * @param integer
   * @return array
   */
  public static function read_all_by_year($year, $limit = NULL, $offset = NULL)
  {
    $query = 'SELECT * FROM `tvshowview` WHERE `tvshowview`.`c05` LIKE \'%'.$year.'%\' ORDER BY `tvshowview`.`c00` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en film
				$tvshows[] = self::_manage_results($result);
      }
    }
    else
    {
      $tvshows = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $tvshows;
  }

  /**
   * Retourne le total des films dont on précise l'année
   *
   * @access public
   * @param integer
   * @return integer
   */
  public static function count_by_year($year)
  {
    return count(DB::select('idShow')->from('tvshowview')
                                      ->where('c05', 'like', '%'.$year.'%')
                                      ->execute(static::$_connection));
  }

  /**
   * Retourne tous les films dont on précise l'identifiant du genre
   *
   * @access public
   * @param integer
   * @return array
   */
  public static function read_all_by_genre($idGenre, $limit = NULL, $offset = NULL)
  {
    $query = 'SELECT * FROM `tvshowview` JOIN `genrelinktvshow` ON (`genrelinktvshow`.`idShow` = `tvshowview`.`idShow`) WHERE `genrelinktvshow`.`idGenre` = '.$idGenre.' ORDER BY `tvshowview`.`c00` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en film
				$tvshows[] = self::_manage_results($result);
      }
    }
    else
    {
      $tvshows = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $tvshows;
  }

  /**
   * Retourne le total des films dont on précise l'identifiant du genre
   *
   * @access public
   * @param integer
   * @return integer
   */
  public static function count_by_genre($idGenre)
  {
    return count(DB::select('idShow')->from('genrelinktvshow')
                                      ->where('idGenre', $idGenre)
                                      ->execute(static::$_connection));
  }

  /**
   * Retourne tous les films dont on précise l'identifiant du studio
   *
   * @access public
   * @param integer
   * @return array
   */
  public static function read_all_by_studio($idStudio, $limit = NULL, $offset = NULL)
  {
    $query = 'SELECT * FROM `tvshowview` JOIN `studiolinktvshow` ON (`studiolinktvshow`.`idShow` = `tvshowview`.`idShow`) WHERE `studiolinktvshow`.`idStudio` = '.$idStudio.' ORDER BY `tvshowview`.`c00` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en film
				$tvshows[] = self::_manage_results($result);
      }
    }
    else
    {
      $tvshows = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $tvshows;
  }

  /**
   * Retourne le total des films dont on précise l'identifiant du studio
   *
   * @access public
   * @param integer
   * @return integer
   */
  public static function count_by_studio($idStudio)
  {
    return count(DB::select('idShow')->from('studiolinktvshow')
                                      ->where('idStudio', $idStudio)
                                      ->execute(static::$_connection));
  }

  /**
   * Traite un résultat d'une requête dans la base de données pour le convertir en série TV
   *
   * Complète les informations de la série TV si on en affiche la page détaillée
   *
   * @access public
   * @param object
   * @param bool
   * @return object
   */
    private static function _manage_results($result, $for_view = FALSE)
    {
      $tvshow = new stdClass();

      // Identifiant
      $tvshow->id = $result->idShow;

      // Chemin et scraper correspondant
      $tvshow->path = $result->strPath;
      $tvshow->source = VideoInfoScanner::find_source($tvshow->path);

      $tvshow->local_title = $result->c00;

      $tvshow->year = (int) date('Y', strtotime($result->c05));

      // Données diverses
      if ($result->c01 != '')
          $tvshow->overview = $result->c01;
      else
          $tvshow->overview = Lang::get('media.no_overview');

      if ($result->c04 != '')
          $tvshow->rating = $result->c04;
      else
          $tvshow->rating = Lang::get('media.no_rating');

      if ($result->c03 != '')
          $tvshow->votes = $result->c04;
      else
          $tvshow->votes = Lang::get('media.no_vote');

      $tvshow->mpaa = $result->c13;

      $tvshow->total_seasons = $result->totalSeasons;
      $tvshow->total_episodes = $result->totalCount;

//echo '<pre>'.print_r($result, true).'</pre>'; die();

      $media_images = Model_VideoArt::get_for_tvshow($tvshow->id);
      $tvshow->banner = $media_images->banner;
      $tvshow->poster = $media_images->poster;

      // Données complètes ?
      if ($for_view)
      {
        $tvshow->fanart = $media_images->fanart;

        // La classe du scraper existe ?
        if (class_exists($scraper = $tvshow->source->scraper_class))
        {
          $tvshow->external_link = $scraper::get_external_link($result);

          // Prepare images list to change them if user is an admin
          if (Auth::member(100))
          {
            $tvshow->images = $scraper::manage_images($result);
          }
        }

        // Images sur les saisons de cette série tv
        $images = Model_VideoArt::get_for_seasons($tvshow->id);

        // Si il y a une saisons hors-saison et que l'on ne dispose pas des épisodes
        if (isset($images[0]) and $result->first_season != 0)
        {
          unset($images[0]);
        }

        // Si il y a plus de saisons que de saisons pour les épisodes dont on dispose
        if (isset($images[$result->last_season+1]))
        {
          unset($images[$result->last_season+1]);
        }

        // Images des saisons dont on dispose des épisodes
        $tvshow->seasons = $images;

        $tvshow->actors = Model_Actor::get_for_tvshow($tvshow->id);
        $tvshow->genres = Model_VideoGenre::get_for_tvshow($tvshow->id);
        $tvshow->studios = Model_Studio::get_for_tvshow($tvshow->id);
      }
      else
      {
        // Données limitées en nombre sur les autres vues que la vue détaillée d'une série TV
        $tvshow->actors = Model_Actor::get_for_tvshow($tvshow->id, 2);
        $tvshow->genres = Model_VideoGenre::get_for_tvshow($tvshow->id, 2);
        $tvshow->studios = Model_Studio::get_for_tvshow($tvshow->id);
      }

      // On retourne la série TV traitée
      return $tvshow;
    }
}
