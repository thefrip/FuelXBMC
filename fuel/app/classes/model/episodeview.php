<?php

class Model_EpisodeView extends Orm\Model
{
    protected static $_connection = 'video';
    protected static $_table_name = 'episodeview';
    protected static $_primary_key = array('idEpisode');

/*
    protected static $_properties = array(
        'idCountry', // both validation & typing observers will ignore the PK
        'strCountry' => array(
            'data_type' => 'text',
        ),
    );
*/

  /**
   * Retourne un épisode de série TV dont on précide l'identifiant
   *
   * @access public
   * @param integer
   * @return array
   */
  public static function read($episode_id, $for_view = FALSE)
  {
    return self::_manage_results(self::find($episode_id), $for_view);
  }

  /**
   * Retourne 'max' dernieres épisodes de série TV
   *
   * @access public
   * @param integer
   * @return array
   */
  public static function read_last($max = 5)
  {
    $query = 'SELECT * FROM `episodeview` ORDER BY `episodeview`.`IdEpisode` DESC LIMIT '.$max;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins un épisode de série TV est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en épisode de série TV
				$episodes[] = self::_manage_results($result);
      }
    }
    else
    {
      $episodes = NULL;
    }

    // On retourne les épisodes de série TV trouvés ou NULL
    return $episodes;
  }

  /**
   * Récupère les épisodes d'une saison d'une série TV dont on précise l'identifiant
   *
   * Limite les résultats à $limit si différent précisé
   *
   * @access public
   * @param integer
   * @param integer
   * @param integer
   * @param integer
   * @return array
   */
  public static function read_all_by_tvshow($tvshow_id = 0, $season_id = 0, $limit = NULL, $offset = NULL)
  {
    $query = 'SELECT * FROM (`episodeview`) WHERE `episodeview`.`idShow` = '.$tvshow_id.' AND `episodeview`.`c12` = '.$season_id.' ORDER BY CAST(c13 AS UNSIGNED) ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins un épisode est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en épisode de série TV
				$episodes[] = self::_manage_results($result);
      }
    }
    else
    {
      $episodes = NULL;
    }

    // On retourne les épisodes de série TV trouvés ou NULL
    return $episodes;
  }

  /**
   * Retourne le nombre total d'épisodes d'une saison d'une série TV
   *
   * @access public
   * @param integer
   * @param integer
   * @return integer
   */
  public static function count_all_by_tvshow($tvshow_id, $season_id)
  {
    return count(DB::select('idEpisode')->from('episodeview')
                                      ->where('idShow', $tvshow_id)
                                      ->where('c12', $season_id)
                                      ->execute(static::$_connection));
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
    $query = 'SELECT * FROM `episodeview` JOIN `writerlinkepisode` ON (`writerlinkepisode`.`idEpisode` = `episodeview`.`idEpisode`) WHERE `writerlinkepisode`.`idWriter` = '.$idWriter.' ORDER BY CAST(c13 AS UNSIGNED) ASC';

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
				$episodes[] = self::_manage_results($result);
      }
    }
    else
    {
      $episodes = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $episodes;
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
    return count(DB::select('idEpisode')->from('writerlinkepisode')
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
    $query = 'SELECT * FROM `episodeview` JOIN `directorlinkepisode` ON (`directorlinkepisode`.`idEpisode` = `episodeview`.`idEpisode`) WHERE `directorlinkepisode`.`idDirector` = '.$idDirector.' ORDER BY CAST(c13 AS UNSIGNED) ASC';

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
				$episodes[] = self::_manage_results($result);
      }
    }
    else
    {
      $episodes = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $episodes;
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
    return count(DB::select('idEpisode')->from('directorlinkepisode')
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
    $query = 'SELECT * FROM `episodeview` JOIN `actorlinkepisode` ON (`actorlinkepisode`.`idEpisode` = `episodeview`.`idEpisode`) WHERE `actorlinkepisode`.`idActor` = '.$idActor.' ORDER BY CAST(c13 AS UNSIGNED) ASC';

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
				$episodes[] = self::_manage_results($result);
      }
    }
    else
    {
      $episodes = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $episodes;
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
    return count(DB::select('idEpisode')->from('actorlinkepisode')
                                      ->where('idActor', $idActor)
                                      ->execute(static::$_connection));
  }

  /**
   * Traite un résultat d'une requête dans la base de données pour le convertir en épisode de série TV
   *
   * @access public
   * @param object
   * @return object
   */
    private static function _manage_results($result, $for_view = FALSE)
    {
      $episode = new stdClass();

      // Identifiant
      $episode->id = $result->idEpisode;

      // Chemin et scraper correspondant
      $episode->path = $result->strPath;
      $episode->source = VideoInfoScanner::find_source($episode->path);

      // Fichier
      $episode->file_id = $result->idFile;
      $episode->filename = $result->strFileName;

      // Episode vu ou pas
      $episode->seen = !is_null($result->lastPlayed);

      // Données diverses
      $episode->local_title = $result->c00;

      if ($result->c01 != '')
          $episode->overview = $result->c01;
      else
          $episode->overview = Lang::get('media.no_overview');

      if ($result->c03 != '')
          $episode->rating = $result->c03;
      else
          $episode->rating = Lang::get('media.no_rating');

      if ($result->mpaa != '')
          $episode->mpaa = $result->mpaa;
      else
          $episode->mpaa = Lang::get('media.no_mpaa');

      if ($result->c05 != '')
      {
        $episode->first_aired = date(Lang::get('media.first_aired_format'), strtotime($result->c05));
      }
      else
      {
        $episode->first_aired = Lang::get('media.no_first_aired');
      }

      if ($result->c09 != '')
          $episode->runtime = gmstrftime(Lang::get('media.runtime_format'), $result->c09);
      else
          $episode->runtime = Lang::get('media.no_runtime');

      $episode->poster_url = $result->c06;
      $episode->tvshow_id = $result->idShow;
      $episode->tvshow_name = $result->strTitle;
      $episode->season_number = $result->c12;
      $episode->episode_number = $result->c13;

      $episode->poster = Model_VideoArt::get_for_episode($episode->id);

  		$episode->writers = array();
  		$episode->directors = array();
  		$episode->actors = array();

  		$episode->writers = Model_Writer::get_for_episode($episode->id);
  		$episode->directors = Model_Director::get_for_episode($episode->id);

      // Consulation de la page détaillée d'un épisode ?
      if ($for_view)
      {
        $episode->actors = Model_Actor::get_for_episode($episode->id);
      }

      // On retourne l'épisode traité
      return $episode;
    }

}
