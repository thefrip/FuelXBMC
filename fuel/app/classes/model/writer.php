<?php

class Model_Writer extends Orm\Model
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
      $person->id = $result->idWriter;
      $person->name = $result->strActor;

      $person->photo = Xbmc::get_person_photo($person->name);

      return $person;
    }

  /**
   * Récupère tous les scénaristes d'un film dont on précise l'identifiant
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
      $results = \DB::select('writerlinkmovie.idWriter', 'actors.strActor')
                      ->from('writerlinkmovie')
                      ->join('actors')->on('actors.idActor', '=', 'writerlinkmovie.idWriter')
                      ->where('writerlinkmovie.idMovie', $idMovie)
                      ->as_object()
                      ->execute(static::$_connection);
    }
    else
    {
      $results = \DB::select('writerlinkmovie.idWriter', 'actors.strActor')
                      ->from('writerlinkmovie')
                      ->join('actors')->on('actors.idActor', '=', 'writerlinkmovie.idWriter')
                      ->where('writerlinkmovie.idMovie', $idMovie)
                      ->limit($limit)
                      ->as_object()
                      ->execute(static::$_connection);
    }

    // Mise en forme des résultats
    $writers = array();

    foreach ($results as $result)
    {
        $writers[] = self::_manage_results($result);
    }

    return $writers;
  }

  /**
   * Récupère tous les scénaristes d'un épisode de série TV dont on précise l'identifiant
   *
   * @access public
   * @param integer
   * @param integer
   * @return array
   */
  public static function get_for_episode($idEpisode)
  {
    $results = \DB::select('writerlinkepisode.idWriter', 'actors.strActor')
                    ->from('writerlinkepisode')
                    ->join('actors')->on('actors.idActor', '=', 'writerlinkepisode.idWriter')
                    ->where('writerlinkepisode.idEpisode', $idEpisode)
                    ->as_object()
                    ->execute(static::$_connection);

    // Mise en forme des résultats
    $writers = array();

    foreach ($results as $result)
    {
        $writers[] = self::_manage_results($result);
    }

    return $writers;
  }

}
