<?php

class Model_SetView extends Orm\Model
{
    protected static $_connection = 'video';
    protected static $_table_name = 'sets';
    protected static $_primary_key = array('idSet');
    protected static $_properties = array(
        'idSet',
        'strSet',
    );

	/**
	 * Searches for a single row in the database.
	 *
	 * @param string $id The primary key of the record to search for.
	 *
	 * @return mixed An object representing the db row, or FALSE.
	 */
    public static function read($set_id)
    {
      return self::_manage_results(self::find($set_id));
    }

  /**
   * Cherche les sagas dont le nom commence par le titre fourni
   * Retourne un tableau représentant la ou les saga(s) trouvée(s) ou NULL si non trouvée
   *
   * @access public
   * @param string
   * @param integer
   * @param integer
   * @return array
   */
  public static function read_all_by_title($title, $limit = NULL, $offset = NULL)
  {
    // On décompose le nom en tableau de mots
    $words = explode(' ', $title);

    // Un seul mot alors on recherche
    if (count($words) == 1)
    {
      $where = ' WHERE `sets`.`strSet` LIKE "%'.$title.'%"';
    }
    else
    {
      // Sinon on fait (WHERE `sets`.`strSet` LIKE %mot1%) AND (WHERE `sets`.`strSet` LIKE %mot2%) AND ...
      $where = ' WHERE ';
      foreach($words as $word)
      {
        $where .= '(`sets`.`strSet` LIKE "%'.$word.'%") AND ';
      }
      // On supprime le dernier ' AND '
      $where = substr($where, 0, -5);
    }

    // On recherche...
    $query = 'SELECT * FROM `sets`'.$where.' ORDER BY `sets`.`strSet` ASC';

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
				$sets[] = self::_manage_results($result);
      }
    }
    else
    {
      $sets = NULL;
    }

    // On retourne les films trouvés ou NULL
    return $sets;
  }

  /**
   * Retourne le nombre total de personnalités dont le nom commence par le nom fourni
   *
   * @access public
   * @param string
   * @return integer
   */
  public static function count_all_by_title($title)
  {
    // On décompose le nom en tableau de mots
    $words = explode(' ', $title);

    // Un seul mot alors on recherche
    if (count($words) == 1)
    {
      $where = ' WHERE `sets`.`strSet` LIKE "%'.$title.'%"';
    }
    else
    {
      // Sinon on fait (WHERE `sets`.`strSet` LIKE %mot1%) AND (WHERE `sets`.`strSet` LIKE %mot2%) AND ...
      $where = ' WHERE ';
      foreach($words as $word)
      {
        $where .= '(`sets`.`strSet` LIKE "%'.$word.'%") AND ';
      }
      // On supprime le dernier ' AND '
      $where = substr($where, 0, -5);
    }

    // On recherche...
    $query = 'SELECT `sets`.idSet FROM `sets`'.$where.' ORDER BY `sets`.`strSet` ASC';

    return count(\DB::query($query)->execute(static::$_connection));
  }

  /**
   * Retourne toutes les sagas de films
   *
   * @access public
   * @return array
   */
  public static function read_all($limit = NULL, $offset = NULL)
  {
    $query = 'SELECT * FROM `sets` ORDER BY `sets`.`strSet` ASC';

    if ($limit) $query .= ' LIMIT '.$limit;
    if ($offset and ($offset > 1)) $query .= ' OFFSET '.$offset;

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins une saga de films est dans la base de données
    if (count($results) > 0)
    {
      foreach($results as $result)
      {
				// On traite chaque résultat pour les convertir en saga de films
				$sets[] = self::_manage_results($result);
      }
    }
    else
    {
      $sets = NULL;
    }

    // On retourne les sagas de films trouvées ou NULL
    return $sets;
  }

  /**
   * Traite un résultat d'une requête dans la base de données pour le convertir en saga de films
   *
   * Complète les informations de la saga de films si on en affiche la page détaillée
   *
   * @access public
   * @param object
   * @param bool
   * @return object
   */
    private static function _manage_results($result)
    {
      $set = new stdClass();
      $set->id = $result->idSet;
      $set->title = $result->strSet;

      $movies = Model_MovieView::read_all_by_set($set->id);
      $set->poster = $movies[0]->poster;
      $set->movies = $movies;

      return $set;
    }

}
