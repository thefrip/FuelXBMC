<?php

class Model_MovieView extends Orm\Model
{
  protected static $_connection = 'video';
  protected static $_table_name = 'movieview';
  protected static $_primary_key = array('idMovie');
  protected static $_properties = array(
    'idMovie',
		'c00',          // local title
		'c01',          // Movie Plot overview
		'c02',          // Movie Plot Outline
		'c03',          // Movie Tagline tagline
		'c04',          // rating votes
		'c05',          // rating
		'c07',          // year
		'c08',          // thumbnails url : fake xml
		'c09',          // imdb id
		'c10',          // title formatted for sorting
		'c11',          // runtime (in seconds)
		'c12',          // mpaa certification
		'c13',          // imdb top 250 rankng
		'c16',          // origianal title
		'c17',
		'c19',          // trailer url
		'c20',          // fanarts url : fake xml
		'c22',
		'c23',          // foreign key for path table
    'strFileName',
    'idSet',
    'strSet',
    'strPath',
    'playCount',
    'lastPlayed',
  );

	/**
	 * Searches for a single row in the database.
	 *
	 * @param string $id The primary key of the record to search for.
	 *
	 * @return mixed An object representing the db row, or FALSE.
	 */
    public static function find_source($movie_id)
    {
      $query = 'SELECT `movieview`.`strPath` as path FROM `movieview` WHERE `movieview`.`idMovie` = '.$movie_id;

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
    public static function read($movie_id, $for_view = FALSE)
    {
      return self::_manage_results(self::find($movie_id), $for_view);
    }

  /**
   * Cherche un film dont le titre commence par le titre fourni
   * Retourne un tableau représentant le ou les film(s) trouvé(s) ou NULL si non trouvé
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
      $where = ' WHERE LOWER(`movieview`.`c00`) LIKE "%'.$title.'%"';
    }
    else
    {
      // Sinon on fait (WHERE `movieview`.`c00` LIKE %mot1%) AND (WHERE `movieview`.`c00` LIKE %mot2%) AND ...
      $where = ' WHERE ';
      foreach($words as $word)
      {
        $where .= '(LOWER(`movieview`.`c00`) LIKE "%'.$word.'%") AND ';
      }
      // On supprime le dernier ' AND '
      $where = substr($where, 0, -5);
    }

    // On recherche...
    $query = 'SELECT * FROM `movieview`'.$where.' ORDER BY `movieview`.`c00` ASC';

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
				$movies[] = self::_manage_results($result);
      }
    }
    else
    {
      $movies = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $movies;
  }

  /**
   * Retourne le nombre total de films dont le titre commence par le titre fourni
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
      $where = ' WHERE LOWER(`movieview`.`c00`) LIKE "%'.$title.'%"';
    }
    else
    {
      // Sinon on fait (WHERE `movieview`.`c00` LIKE %mot1%) AND (WHERE `movieview`.`c00` LIKE %mot2%) AND ...
      $where = ' WHERE ';
      foreach($words as $word)
      {
        $where .= '(LOWER(`movieview`.`c00`) LIKE "%'.$word.'%") AND ';
      }
      // On supprime le dernier ' AND '
      $where = substr($where, 0, -5);
    }

    // On recherche...
    $query = 'SELECT `movieview`.idMovie FROM `movieview`'.$where.' ORDER BY `movieview`.`c00` ASC';

    return count(\DB::query($query)->execute(static::$_connection));
  }

  /**
   * Retourne tous les films
   *
   * @access public
   * @return array
   */
  public static function read_all($limit = NULL, $offset = NULL)
  {
    $query = 'SELECT * FROM `movieview` ORDER BY `movieview`.`c00` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset and ($offset > 1)) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    $movies = array();

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en film
				$movies[] = self::_manage_results($result);
      }
    }
    else
    {
      $movies = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $movies;
  }

  /**
   * Retourne 'max' derniers films
   *
   * @access public
   * @param integer
   * @return array
   */
  public static function read_last($max = 5)
  {
    $query = 'SELECT * FROM `movieview` ORDER BY `movieview`.`IdMovie` DESC LIMIT '.$max;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    $movies = array();

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en film
				$movies[] = self::_manage_results($result);
      }
    }
    else
    {
      $movies = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $movies;
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
    $query = 'SELECT * FROM `movieview` JOIN `writerlinkmovie` ON (`writerlinkmovie`.`idMovie` = `movieview`.`idMovie`) WHERE `writerlinkmovie`.`idWriter` = '.$idWriter.' ORDER BY `movieview`.`c00` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    $movies = array();

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en film
				$movies[] = self::_manage_results($result);
      }
    }
    else
    {
      $movies = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $movies;
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
    return count(DB::select('idMovie')->from('writerlinkmovie')
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
    $query = 'SELECT * FROM `movieview` JOIN `directorlinkmovie` ON (`directorlinkmovie`.`idMovie` = `movieview`.`idMovie`) WHERE `directorlinkmovie`.`idDirector` = '.$idDirector.' ORDER BY `movieview`.`c00` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    $movies = array();

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en film
				$movies[] = self::_manage_results($result);
      }
    }
    else
    {
      $movies = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $movies;
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
    return count(DB::select('idMovie')->from('directorlinkmovie')
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
    $query = 'SELECT * FROM `movieview` JOIN `actorlinkmovie` ON (`actorlinkmovie`.`idMovie` = `movieview`.`idMovie`) WHERE `actorlinkmovie`.`idActor` = '.$idActor.' ORDER BY `movieview`.`c00` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    $movies = array();

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en film
				$movies[] = self::_manage_results($result);
      }
    }
    else
    {
      $movies = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $movies;
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
    return count(DB::select('idMovie')->from('actorlinkmovie')
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
    $query = 'SELECT * FROM `movieview` WHERE `movieview`.`c07` = '.$year.' ORDER BY `movieview`.`c00` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    $movies = array();

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en film
				$movies[] = self::_manage_results($result);
      }
    }
    else
    {
      $movies = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $movies;
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
    return count(DB::select('idMovie')->from('movieview')
                                      ->where('c07', $year)
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
    $query = 'SELECT * FROM `movieview` JOIN `genrelinkmovie` ON (`genrelinkmovie`.`idMovie` = `movieview`.`idMovie`) WHERE `genrelinkmovie`.`idGenre` = '.$idGenre.' ORDER BY `movieview`.`c00` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    $movies = array();

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en film
				$movies[] = self::_manage_results($result);
      }
    }
    else
    {
      $movies = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $movies;
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
    return count(DB::select('idMovie')->from('genrelinkmovie')
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
    $query = 'SELECT * FROM `movieview` JOIN `studiolinkmovie` ON (`studiolinkmovie`.`idMovie` = `movieview`.`idMovie`) WHERE `studiolinkmovie`.`idStudio` = '.$idStudio.' ORDER BY `movieview`.`c00` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    $movies = array();

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en film
				$movies[] = self::_manage_results($result);
      }
    }
    else
    {
      $movies = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $movies;
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
    return count(DB::select('idMovie')->from('studiolinkmovie')
                                      ->where('idStudio', $idStudio)
                                      ->execute(static::$_connection));
  }

  /**
   * Retourne tous les films dont on précise l'identifiant du pays
   *
   * @access public
   * @param integer
   * @return array
   */
  public static function read_all_by_country($idCountry, $limit = NULL, $offset = NULL)
  {
    $query = 'SELECT * FROM `movieview` JOIN `countrylinkmovie` ON (`countrylinkmovie`.`idMovie` = `movieview`.`idMovie`) WHERE `countrylinkmovie`.`idCountry` = '.$idCountry.' ORDER BY `movieview`.`c00` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    $movies = array();

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en film
				$movies[] = self::_manage_results($result);
      }
    }
    else
    {
      $movies = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $movies;
  }

  /**
   * Retourne le total des films dont on précise l'identifiant du pays
   *
   * @access public
   * @param integer
   * @return integer
   */
  public static function count_by_country($idCountry)
  {
    return count(DB::select('idMovie')->from('countrylinkmovie')
                                      ->where('idCountry', $idCountry)
                                      ->execute(static::$_connection));
  }

  /**
   * Retourne tous les films dont on précise l'identifiant d'une saga de films
   *
   * @access public
   * @param integer
   * @return array
   */
  public static function read_all_by_set($idSet, $limit = NULL, $offset = NULL)
  {
    $query = 'SELECT * FROM `movieview` WHERE `movieview`.`idSet` = '.$idSet.' ORDER BY `movieview`.`c09` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    $movies = array();

    // Si au moins un film est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en film
				$movies[] = self::_manage_results($result);
      }
    }
    else
    {
      $movies = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $movies;
  }

  /**
   * Retourne le total des films dont on précise l'identifiant de la saga de films
   *
   * @access public
   * @param integer
   * @return integer
   */
  public static function count_by_set($idSet)
  {
    return count(DB::select('idMovie')->from('setlinkmovie')
                                      ->where('idSet', $idSet)
                                      ->execute(static::$_connection));
  }

  /**
   * Traite un résultat d'une requête dans la base de données pour le convertir en film
   *
   * Complète les informations du film si on en affiche la page détaillée
   *
   * @access public
   * @param object
   * @param bool
   * @return object
   */
    private static function _manage_results($result, $for_view = FALSE)
    {
      $movie = new stdClass();

      // Identifiant
      $movie->id = $result->idMovie;

      // Chemin et scraper correspondant
      $movie->path = $result->strPath;
      $movie->source = VideoInfoScanner::find_source($movie->path);

      $movie->filename = $result->strFileName;

      $media_images = Model_VideoArt::get_for_movie($movie->id);

      $movie->poster = $media_images->poster;

      // Données diverses avec mise en forme
      $movie->local_title = $result->c00;

      if ($result->c16 != '')
          $movie->original_title = $result->c16;
      else
          $movie->original_title = $movie->local_title;

      $movie->year = (int) $result->c07;

      $runtime = new stdClass();
      $runtime->value = 0;
      $runtime->display = '';

      // Gestion de la durée qui est secondes !
      if ($result->c11 != '')
      {
        $runtime->value = (int) $result->c11 / 60;

        // Si le format est vide, on affiche la durée en minutes
        if (Lang::get('media.runtime_format') == '')
        {
          $display = $runtime->value.'m';
        }
        else
        {
          $display = gmstrftime(Lang::get('media.runtime_format'), $result->c11);
        }

        // enfin on s'occupe de 'h' et 'm'
        $display = str_replace('h', Lang::get('media.runtime_format_hour'), $display);
        $display = str_replace('m', Lang::get('media.runtime_format_minute'), $display);
        $runtime->display = $display;
      }
      else
      {
        $runtime->value = 0;
        $runtime->display = Lang::get('media.no_runtime');
      }
      $movie->runtime = $runtime;

      if ($result->c01 != '')
          $movie->overview = $result->c01;
      else
          $movie->overview = Lang::get('media.no_overview');

      if ($result->c05 != '')
          $movie->rating = $result->c05;
      else
          $movie->rating = Lang::get('media.no_rating');

      if ($result->c04 != '')
          $movie->votes = $result->c04;
      else
          $movie->votes = Lang::get('media.no_vote');

      $movie->mpaa = $result->c12;

      // Consulation de la page détaillée d'un film ?
      if ($for_view)
      {

        $movie->fanart = $media_images->fanart;

        // La classe du scraper existe ?
        if (class_exists($scraper = $movie->source->scraper_class))
        {
          $movie->external_link = $scraper::get_external_link($result);

          // Prepare images list to change them if user is an admin
          if (Auth::member(100))
          {
            $movie->images = $scraper::manage_images($result);
          }
        }

//echo '<pre>'.print_r($movie->images, true).'</pre>'; die();

        // Film vu ou pas
        $movie->seen = !is_null($result->lastPlayed);

        if ($result->c03 != '')
            $movie->tagline = $result->c03;
        else
            $movie->tagline = Lang::get('media.no_tagline');

        $movie->writers = Model_Writer::get_for_movie($movie->id);
        $movie->directors = Model_Director::get_for_movie($movie->id);
        $movie->actors = Model_Actor::get_for_movie($movie->id);
        $movie->genres = Model_VideoGenre::get_for_movie($movie->id);
        $movie->studios = Model_Studio::get_for_movie($movie->id);
        $movie->countries = Model_Country::get_for_movie($movie->id);

        // Saga à laquelle est rattaché ce film, pas de saga par défaut
        $set = new stdClass();
        $set->id = 0;
        $set->name = '';
        $set->order = ($result->c10 != '') ? $result->c10 : 0;

        // Si le film fait partie d'une saga
        if ($result->idSet and $result->strSet)
        {
          $set->id = $result->idSet;
          $set->name = $result->strSet;
        }

        // On rattache la saga de films à ce film qu'elle existe ou pas
        $movie->set = $set;

//echo '<pre>'.print_r($movie, true).'</pre>'; die();
      }
      else
      {
        // Données limitées en nombre sur les autres vues que la vue détaillée d'un film
        $movie->writers = Model_Writer::get_for_movie($movie->id, 2);
        $movie->directors = Model_Director::get_for_movie($movie->id, 2);
        $movie->actors = Model_Actor::get_for_movie($movie->id, 2);
        $movie->genres = Model_VideoGenre::get_for_movie($movie->id, 2);
        $movie->studios = Model_Studio::get_for_movie($movie->id, 2);
        $movie->countries = Model_Country::get_for_movie($movie->id, 2);
      }

      return $movie;
    }
}
