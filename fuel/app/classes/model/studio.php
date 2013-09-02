<?php

class Model_Studio extends Orm\Model
{
    protected static $_connection = 'video';
    protected static $_table_name = 'studio';
    protected static $_primary_key = array('idStudio');
    protected static $_properties = array(
        'idStudio',
        'strStudio',
    );

    protected static $_conditions = array(
        'order_by' => array('strStudio' => 'ASC'),
    );

  /**
   * Récupère les studios d'un film dont on précise l'identifiant
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
      $results = \DB::select('studio.idStudio', 'studio.strStudio')
                      ->from('studiolinkmovie')
                      ->join('studio')->on('studio.idStudio', '=', 'studiolinkmovie.idStudio')
                      ->where('studiolinkmovie.idMovie', $idMovie)
                      ->execute(static::$_connection);
    }
    else
    {
      $results = \DB::select('studio.idStudio', 'studio.strStudio')
                      ->from('studiolinkmovie')
                      ->join('studio')->on('studio.idStudio', '=', 'studiolinkmovie.idStudio')
                      ->where('studiolinkmovie.idMovie', $idMovie)
                      ->limit($limit)
                      ->execute(static::$_connection);
    }

    // Mise en forme des résultats
    $studios = array();

    foreach ($results as $item)
    {
        $studio = new stdClass();
        $studio->id = $item['idStudio'];
        $studio->name = $item['strStudio'];
        $studios[] = $studio;
    }

    return $studios;
  }

  /**
   * Récupère les studios d'une série Tv dont on précise l'identifiant
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
      $results = \DB::select('studio.idStudio', 'studio.strStudio')
                      ->from('studiolinktvshow')
                      ->join('studio')->on('studio.idStudio', '=', 'studiolinktvshow.idStudio')
                      ->where('studiolinktvshow.idShow', $idShow)
                      ->execute(static::$_connection);
    }
    else
    {
      $results = \DB::select('studio.idStudio', 'studio.strStudio')
                      ->from('studiolinktvshow')
                      ->join('studio')->on('studio.idStudio', '=', 'studiolinktvshow.idStudio')
                      ->where('studiolinktvshow.idShow', $idShow)
                      ->limit($limit)
                      ->execute(static::$_connection);
    }

    // Mise en forme des résultats
    $studios = array();

    foreach ($results as $item)
    {
        $studio = new stdClass();
        $studio->id = $item['idStudio'];
        $studio->name = $item['strStudio'];
        $studios[] = $studio;
    }

    return $studios;
  }

  /**
   * Supprime un studio pour un film dont on précise l'identifiant
   *
   * @access public
   * @param integer
   * @param integer
   * @return void
   */
  public static function remove_for_movie($studio_id, $movie_id)
  {
    \DB::delete('studiolinkmovie')
        ->where('idStudio', '=', $studio_id)
        ->where('idMovie', '=', $movie_id)
        ->execute(static::$_connection);
  }

  /**
   * Supprime un studio pour une série TV dont on précise l'identifiant
   *
   * @access public
   * @param integer
   * @param integer
   * @return void
   */
  public static function remove_for_tvshow($studio_id, $tvshow_id)
  {
    \DB::delete('studiolinktvshow')
        ->where('idStudio', '=', $studio_id)
        ->where('idShow', '=', $tvshow_id)
        ->execute(static::$_connection);
  }

  /**
   * Fixe les studios d'un film dont on précise l'identifiant
   *
   * @access public
   * @param integer
   * @param integer
   * @return void
   */
  public static function set_for_movie($studio_id, $movie_id)
  {
    $data = array('idStudio' => $studio_id, 'idMovie' => $movie_id);

    \DB::insert('studiolinkmovie')
        ->set($data)
        ->execute(static::$_connection);
  }

  /**
   * Fixe les studios d'une série TV dont on précise l'identifiant
   *
   * @access public
   * @param integer
   * @param integer
   * @return void
   */
  public static function set_for_tvshow($studio_id, $tvshow_id)
  {
    $data = array('idStudio' => $studio_id, 'idShow' => $tvshow_id);

    \DB::insert('studiolinktvshow')
        ->set($data)
        ->execute(static::$_connection);
  }

}
