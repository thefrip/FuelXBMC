<?php

class Model_Country extends Orm\Model
{
    protected static $_connection = 'video';
    protected static $_table_name = 'country';
    protected static $_primary_key = array('idCountry');
    protected static $_properties = array(
        'idCountry', // both validation & typing observers will ignore the PK
        'strCountry' => array(
            'data_type' => 'text',
        ),
    );

    protected static $_conditions = array(
        'order_by' => array('strCountry' => 'asc'),
    );

  /**
   * Récupère les pays d'un film dont on précise l'identifiant
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
      $results = \DB::select('country.idCountry', 'country.strCountry')
                      ->from('countrylinkmovie')
                      ->join('country')->on('country.idCountry', '=', 'countrylinkmovie.idCountry')
                      ->where('countrylinkmovie.idMovie', $idMovie)
                      ->execute(static::$_connection);
    }
    else
    {
      $results = \DB::select('country.idCountry', 'country.strCountry')
                      ->from('countrylinkmovie')
                      ->join('country')->on('country.idCountry', '=', 'countrylinkmovie.idCountry')
                      ->where('countrylinkmovie.idMovie', $idMovie)
                      ->limit($limit)
                      ->execute(static::$_connection);
    }

    // Mise en forme des résultats
    $countries = array();

    foreach ($results as $item)
    {
        $country = new stdClass();
        $country->id = $item['idCountry'];
        $country->name = $item['strCountry'];
        $countries[] = $country;
    }

    return $countries;
  }

  /**
   * Supprime un pays pour un film dont on précise l'identifiant
   *
   * @access public
   * @param integer
   * @param integer
   * @return void
   */
  public static function remove_for_movie($country_id, $movie_id)
  {
    \DB::delete('countrylinkmovie')
        ->where('idCountry', '=', $country_id)
        ->where('idMovie', '=', $movie_id)
        ->execute(static::$_connection);
  }

  /**
   * Fixe les pays d'un film dont on précise l'identifiant
   *
   * @access public
   * @param integer
   * @param integer
   * @return void
   */
  public static function set_for_movie($country_id, $movie_id)
  {
    $data = array('idCountry' => $country_id, 'idMovie' => $movie_id);

    \DB::insert('countrylinkmovie')
        ->set($data)
        ->execute(static::$_connection);
  }

}
