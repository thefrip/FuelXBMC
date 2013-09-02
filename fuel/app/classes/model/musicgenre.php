<?php

class Model_MusicGenre extends Orm\Model
{
    protected static $_connection = 'music';
    protected static $_table_name = 'genre';
    protected static $_primary_key = array('idGenre');
    protected static $_properties = array(
        'idGenre', // both validation & typing observers will ignore the PK
        'strGenre' => array(
            'data_type' => 'text',
        ),
    );

    protected static $_conditions = array(
        'order_by' => array('strGenre' => 'asc'),
    );

  /**
   * Récupère les genres d'un album dont on précise l'identifiant
   *
   * @access public
   * @param integer
   * @return array
   */
  public static function get_for_album($idAlbum)
  {
    $results = \DB::select('genre.idGenre', 'genre.strGenre')
                    ->from('album_genre')
                    ->join('genre')->on('genre.idGenre', '=', 'album_genre.idGenre')
                    ->where('album_genre.idAlbum', $idAlbum)
                    ->execute(static::$_connection);

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

}
