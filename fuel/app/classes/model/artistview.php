<?php

class Model_ArtistView extends Orm\Model
{
    protected static $_connection = 'music';
    protected static $_table_name = 'artistview';
    protected static $_primary_key = array('idArtist');
    protected static $_properties = array(
        'idArtist', // both validation & typing observers will ignore the PK
        'strArtist' => array(
            'data_type' => 'text',
        ),
        'strFormed' => array(
            'data_type' => 'text',
        ),
        'strBiography' => array(
            'data_type' => 'text',
        ),
        'strImage' => array(
            'data_type' => 'text',
        ),
        'strFanart' => array(
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
    public static function read($artist_id, $for_view = FALSE)
    {
      return self::_manage_results(self::find($artist_id), $for_view);
    }

  /**
   * Cherche les artistes dont le nom commence par le nom fourni
   * Retourne un tableau représentant la ou les personnalité(s) trouvée(s) ou NULL si non trouvé
   *
   * @access public
   * @param string
   * @param integer
   * @param integer
   * @return array
   */
  public static function read_all_by_name($name, $limit = NULL, $offset = NULL)
  {
    // On décompose le nom en tableau de mots
    $words = explode(' ', $name);

    // Un seul mot alors on recherche
    if (count($words) == 1)
    {
      $where = ' WHERE LOWER(`artist`.`strArtist`) LIKE "%'.$name.'%"';
    }
    else
    {
      // Sinon on fait (WHERE `artist`.`strArtist` LIKE %mot1%) AND (WHERE `artist`.`strArtist` LIKE %mot2%) AND ...
      $where = ' WHERE ';
      foreach($words as $word)
      {
        $where .= '(LOWER(`artist`.`strArtist`) LIKE "%'.$word.'%") AND ';
      }
      // On supprime le dernier ' AND '
      $where = substr($where, 0, -5);
    }

    // On recherche...
    $query = 'SELECT * FROM `artist`'.$where.' ORDER BY `artist`.`strArtist` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins un artiste est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en artiste
				$people[] = self::_manage_results($result);
      }
    }
    else
    {
      $people = NULL;
    }

    // On retourne les artistes trouvés ou NULL
    return $people;
  }

  /**
   * Retourne le nombre total d'artistes dont le nom commence par le nom fourni
   *
   * @access public
   * @param string
   * @return integer
   */
  public static function count_all_by_name($name)
  {
    // On décompose le nom en tableau de mots
    $words = explode(' ', $name);

    // Un seul mot alors on recherche
    if (count($words) == 1)
    {
      $where = ' WHERE LOWER(`artist`.`strArtist`) LIKE "%'.$name.'%"';
    }
    else
    {
      // Sinon on fait (WHERE `artist`.`strArtist` LIKE %mot1%) AND (WHERE `artist`.`strArtist` LIKE %mot2%) AND ...
      $where = ' WHERE ';
      foreach($words as $word)
      {
        $where .= '(LOWER(`artist`.`strArtist`) LIKE "%'.$word.'%") AND ';
      }
      // On supprime le dernier ' AND '
      $where = substr($where, 0, -5);
    }

    // On recherche...
    $query = 'SELECT `artist`.idArtist FROM `artist`'.$where.' ORDER BY `artist`.`strArtist` ASC';

    return count(\DB::query($query)->execute(static::$_connection));
  }

  /**
   * Retourne toutes les artistes ayant au moins un album
   *
   * @access public
   * @return array
   */
  public static function read_all($limit = NULL, $offset = NULL)
  {
		$query = 'SELECT DISTINCT `artistview`.`idArtist`, `artistview`.* FROM artistview JOIN `album_artist` ON (`album_artist`.`idArtist` = `artistview`.`idArtist`) ORDER BY strArtist ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset and ($offset > 1)) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins un artiste est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en artiste
				$artists[] = self::_manage_results($result);
      }
    }
    else
    {
      $artists = NULL;
    }

    // On retourne les artistns trouvées ou NULL
    return $artists;
  }

  /**
   * Compte tous les artistes ayant au moins un album, ignore les artistes des compilations
   *
   * @access public
   * @return array
   */
  public static function count_all()
  {
		$query = 'SELECT COUNT(DISTINCT `artistview`.`idArtist`) as total FROM artistview JOIN `album_artist` ON (`album_artist`.`idArtist` = `artistview`.`idArtist`)';

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    return $results[0]->total;
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
    private static function _manage_results($result, $for_view = false)
    {
      $artist = new stdClass();
      $artist->id = $result->idArtist;
      $artist->name = $result->strArtist;

      $media_images = Model_MusicArt::get_for_artist($artist->id);

      $artist->thumb = $media_images->thumb;

      if ($for_view)
      {
        $artist->fanart = $media_images->fanart;
//        $artistinfo = Model_ArtistViewinfo::read($artist->id);

        $artist->images = Scrapers_Music_Universal::manage_images($result);

//echo '<pre>'.print_r($artist, true).'</pre>'; die();

      if (isset($result->strBiography))
          $artist->biography = $result->strBiography;
      else
          $artist->biography = Lang::get('media.no_biography');

      if (isset($result->strFormed))
          $artist->formed = $result->strFormed;
      else
          $artist->formed = Lang::get('media.no_formed');
      }

      return $artist;
    }

    public static function find_source($movie_id)
    {
      $source = new stdClass();

      $source->scraper_class = 'Scrapers_Music_Universal';

      return $source;
    }
}
