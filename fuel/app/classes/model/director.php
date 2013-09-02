<?php

class Model_Director extends Orm\Model
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
      $person->id = $result->idDirector;
      $person->name = $result->strActor;

      $person->photo = Xbmc::get_person_photo($person->name);

      return $person;
    }

  /**
   * Récupère les réalisateurs d'un film dont on précise l'identifiant
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
      $results = \DB::select('directorlinkmovie.idDirector', 'actors.strActor')
                      ->from('directorlinkmovie')
                      ->join('actors')->on('actors.idActor', '=', 'directorlinkmovie.idDirector')
                      ->where('directorlinkmovie.idMovie', $idMovie)
                      ->as_object()
                      ->execute(static::$_connection);
    }
    else
    {
      $results = \DB::select('directorlinkmovie.idDirector', 'actors.strActor')
                      ->from('directorlinkmovie')
                      ->join('actors')->on('actors.idActor', '=', 'directorlinkmovie.idDirector')
                      ->where('directorlinkmovie.idMovie', $idMovie)
                      ->limit($limit)
                      ->as_object()
                      ->execute(static::$_connection);
    }

    // Mise en forme des résultats
    $directors = array();

    foreach ($results as $result)
    {
        $directors[] = self::_manage_results($result);
    }

    return $directors;
  }

  /**
   * Récupère les réalisateurs d'un épisode de série TV dont on précise l'identifiant
   *
   * @access public
   * @param integer
   * @param integer
   * @return array
   */
  public static function get_for_episode($idEpisode)
  {
    $results = \DB::select('directorlinkepisode.idDirector', 'actors.strActor')
                    ->from('directorlinkepisode')
                    ->join('actors')->on('actors.idActor', '=', 'directorlinkepisode.idDirector')
                    ->where('directorlinkepisode.idEpisode', $idEpisode)
                    ->as_object()
                    ->execute(static::$_connection);

    // Mise en forme des résultats
    $directors = array();

    foreach ($results as $result)
    {
        $directors[] = self::_manage_results($result);
    }

    return $directors;
  }

}
