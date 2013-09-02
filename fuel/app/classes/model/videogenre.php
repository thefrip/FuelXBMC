<?php

class Model_VideoGenre extends Orm\Model
{
    protected static $_connection = 'video';
    protected static $_table_name = 'genre';
    protected static $_primary_key = array('idGenre');
    protected static $_properties = array(
        'idGenre', // both validation & typing observers will ignore the PK
        'strGenre' => array(
            'data_type' => 'text',
        ),
    );

    protected static $_conditions = array(
        'order_by' => array('strGenre' => 'ASC'),
    );

  /**
   * Récupère les genres d'un film dont on précise l'identifiant
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
      $results = \DB::select('genre.idGenre', 'genre.strGenre')
                      ->from('genrelinkmovie')
                      ->join('genre')->on('genre.idGenre', '=', 'genrelinkmovie.idGenre')
                      ->where('genrelinkmovie.idMovie', $idMovie)
                      ->execute(static::$_connection);
    }
    else
    {
      $results = \DB::select('genre.idGenre', 'genre.strGenre')
                      ->from('genrelinkmovie')
                      ->join('genre')->on('genre.idGenre', '=', 'genrelinkmovie.idGenre')
                      ->where('genrelinkmovie.idMovie', $idMovie)
                      ->limit($limit)
                      ->execute(static::$_connection);
    }

    // Mise en forme des résultats
    $genres = array();

    foreach ($results as $item)
    {
        $genre = new stdClass();
        $genre->id = $item['idGenre'];
        $genre->name = $item['strGenre'];
        $genres[] = $genre;
    }

    return $genres;
  }

  /**
   * Récupère les genres d'une série TV dont on précise l'identifiant
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
      $results = \DB::select('genre.idGenre', 'genre.strGenre')
                      ->from('genrelinktvshow')
                      ->join('genre')->on('genre.idGenre', '=', 'genrelinktvshow.idGenre')
                      ->where('genrelinktvshow.idShow', $idShow)
                      ->execute(static::$_connection);
    }
    else
    {
      $results = \DB::select('genre.idGenre', 'genre.strGenre')
                      ->from('genrelinktvshow')
                      ->join('genre')->on('genre.idGenre', '=', 'genrelinktvshow.idGenre')
                      ->where('genrelinktvshow.idShow', $idShow)
                      ->limit($limit)
                      ->execute(static::$_connection);
    }

    // Mise en forme des résultats
    $genres = array();

    foreach ($results as $item)
    {
        $genre = new stdClass();
        $genre->id = $item['idGenre'];
        $genre->name = $item['strGenre'];
        $genres[] = $genre;
    }

    return $genres;
  }

  /**
   * Supprime un genre pour un film dont on précise l'identifiant
   *
   * @access public
   * @param integer
   * @param integer
   * @return void
   */
  public static function remove_for_movie($genre_id, $movie_id)
  {
    \DB::delete('genrelinkmovie')
        ->where('idGenre', '=', $genre_id)
        ->where('idMovie', '=', $movie_id)
        ->execute(static::$_connection);
  }

  /**
   * Supprime un genre pour une série TV dont on précise l'identifiant
   *
   * @access public
   * @param integer
   * @param integer
   * @return void
   */
  public static function remove_for_tvshow($genre_id, $tvshow_id)
  {
    \DB::delete('genrelinktvshow')
        ->where('idGenre', '=', $genre_id)
        ->where('idShow', '=', $tvshow_id)
        ->execute(static::$_connection);
  }

  /**
   * Fixe les genres d'un film dont on précise l'identifiant
   *
   * @access public
   * @param integer
   * @param integer
   * @return void
   */
  public static function set_for_movie($genre_id, $movie_id)
  {
    $data = array('idGenre' => $genre_id, 'idMovie' => $movie_id);

    \DB::insert('genrelinkmovie')
        ->set($data)
        ->execute(static::$_connection);
  }

  /**
   * Fixe les genres d'une série TV dont on précise l'identifiant
   *
   * @access public
   * @param integer
   * @param integer
   * @return void
   */
  public static function set_for_tvshow($genre_id, $tvshow_id)
  {
    $data = array('idGenre' => $genre_id, 'idShow' => $tvshow_id);

    \DB::insert('genrelinktvshow')
        ->set($data)
        ->execute(static::$_connection);
  }

}
