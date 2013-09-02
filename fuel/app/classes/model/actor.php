<?php

class Model_Actor extends Orm\Model
{
    protected static $_connection = 'video';
    protected static $_table_name = 'actors';
    protected static $_primary_key = array('idActor');
    protected static $_properties = array(
        'idActor', // both validation & typing observers will ignore the PK
        'strActor' => array(
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
    public static function read($person_id, $for_view = FALSE)
    {
      return self::_manage_results(self::find($person_id));
    }

  /**
   * Cherche les personnalités dont le nom commence par le nom fourni
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
      $where = ' WHERE LOWER(`actors`.`strActor`) LIKE "%'.$name.'%"';
    }
    else
    {
      // Sinon on fait (WHERE `actors`.`strActor` LIKE %mot1%) AND (WHERE `actors`.`strActor` LIKE %mot2%) AND ...
      $where = ' WHERE ';
      foreach($words as $word)
      {
        $where .= '(LOWER(`actors`.`strActor`) LIKE "%'.$word.'%") AND ';
      }
      // On supprime le dernier ' AND '
      $where = substr($where, 0, -5);
    }

    // On recherche...
    $query = 'SELECT * FROM `actors`'.$where.' ORDER BY `actors`.`strActor` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins une personnalité est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en personnalité
				$people[] = self::_manage_results($result);
      }
    }
    else
    {
      $people = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $people;
  }

  /**
   * Retourne le nombre total de personnalités dont le nom commence par le nom fourni
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
      $where = ' WHERE LOWER(`actors`.`strActor`) LIKE "%'.$name.'%"';
    }
    else
    {
      // Sinon on fait (WHERE `actors`.`strActor` LIKE %mot1%) AND (WHERE `actors`.`strActor` LIKE %mot2%) AND ...
      $where = ' WHERE ';
      foreach($words as $word)
      {
        $where .= '(LOWER(`actors`.`strActor`) LIKE "%'.$word.'%") AND ';
      }
      // On supprime le dernier ' AND '
      $where = substr($where, 0, -5);
    }

    // On recherche...
    $query = 'SELECT `actors`.idActor FROM `actors`'.$where.' ORDER BY `actors`.`strActor` ASC';

    return count(\DB::query($query)->execute(static::$_connection));
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
    private static function _manage_results($result)
    {
      $person = new stdClass();
      $person->id = $result->idActor;
      $person->name = $result->strActor;

      if (isset($result->strRole))
      {
        $person->role = ($result->strRole != '') ? $result->strRole : Lang::get('media.no_role');
      }

      $person->photo = Model_VideoArt::get_for_person($person->id);

      return $person;
    }

  /**
   * Retourne toutes les personnes
   *
   * @access public
   * @return array
   */
  public static function read_all($limit = NULL, $offset = NULL)
  {
    $query = 'SELECT * FROM `actors` ORDER BY `actors`.`strActor` ASC';

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
				// On traite chaque résultat pour les convertir en personne
				$people[] = self::_manage_results($result);
      }
    }
    else
    {
      $people = NULL;
    }

    // On retourne les personnes trouvées ou NULL
    return $people;
  }

  /**
   * Récupère les acteurs d'un film dont on précise l'identifiant
   *
   * Limite les résultats à $limit si différent précisé
   *
   * @access public
   * @param integer
   * @param integer
   * @return array
   */
  public static function get_for_movie($idMovie, $limit = 0)
  {
    // Pas de limite ?
    if ($limit == 0)
    {
      $results = \DB::select('actorlinkmovie.idActor', 'actors.strActor', 'actorlinkmovie.strRole', 'actorlinkmovie.iOrder')
                      ->from('actorlinkmovie')
                      ->join('actors')->on('actors.idActor', '=', 'actorlinkmovie.idActor')
                      ->where('actorlinkmovie.idMovie', $idMovie)
                      ->order_by('actorlinkmovie.iOrder','asc')
                      ->as_object()
                      ->execute(static::$_connection);
    }
    else
    {
      $results = \DB::select('actorlinkmovie.idActor', 'actors.strActor', 'actorlinkmovie.strRole', 'actorlinkmovie.iOrder')
                      ->from('actorlinkmovie')
                      ->join('actors')->on('actors.idActor', '=', 'actorlinkmovie.idActor')
                      ->where('actorlinkmovie.idMovie', $idMovie)
                      ->order_by('actorlinkmovie.iOrder','asc')
                      ->limit($limit)
                      ->as_object()
                      ->execute(static::$_connection);
    }

    // Mise en forme des résultats
    $actors = array();

    foreach ($results as $result)
    {
        $actor = self::_manage_results($result);
        $actors[] = $actor;
    }

    return $actors;
  }

  /**
   * Récupère les acteurs d'une série TV dont on précise l'identifiant
   *
   * Limite les résultats à $limit si différent précisé
   *
   * @access public
   * @param integer
   * @param integer
   * @return array
   */
  public static function get_for_tvshow($idShow, $limit = 0)
  {
    // Pas de limite ?
    if ($limit == 0)
    {
      $results = \DB::select('actorlinktvshow.idActor', 'actors.strActor', 'actorlinktvshow.strRole', 'actorlinktvshow.iOrder')
                      ->from('actorlinktvshow')
                      ->join('actors')->on('actors.idActor', '=', 'actorlinktvshow.idActor')
                      ->where('actorlinktvshow.idShow', $idShow)
                      ->order_by('actorlinktvshow.iOrder','asc')
                      ->as_object()
                      ->execute(static::$_connection);
    }
    else
    {
      $results = \DB::select('actorlinktvshow.idActor', 'actors.strActor', 'actorlinktvshow.strRole', 'actorlinktvshow.iOrder')
                      ->from('actorlinktvshow')
                      ->join('actors')->on('actors.idActor', '=', 'actorlinktvshow.idActor')
                      ->where('actorlinktvshow.idShow', $idShow)
                      ->order_by('actorlinktvshow.iOrder','asc')
                      ->limit($limit)
                      ->as_object()
                      ->execute(static::$_connection);
    }

    // Mise en forme des résultats
    $actors = array();

    foreach ($results as $result)
    {
        $actor = self::_manage_results($result);
        $actors[] = $actor;
    }

    return $actors;
  }

  /**
   * Récupère les acteurs d'une episode d'une série TV dont on précise l'identifiant
   *
   * @access public
   * @param integer
   * @param integer
   * @return array
   */
  public static function get_for_episode($idEpisode)
  {
    $results = \DB::select('actorlinkepisode.idActor', 'actors.strActor', 'actorlinkepisode.strRole', 'actorlinkepisode.iOrder')
                    ->from('actorlinkepisode')
                    ->join('actors')->on('actors.idActor', '=', 'actorlinkepisode.idActor')
                    ->where('actorlinkepisode.idEpisode', $idEpisode)
                    ->order_by('actorlinkepisode.iOrder','asc')
                    ->as_object()
                    ->execute(static::$_connection);

    // Mise en forme des résultats
    $actors = array();

    foreach ($results as $result)
    {
        $actor = self::_manage_results($result);
        $actors[] = $actor;
    }

    return $actors;
  }

}
