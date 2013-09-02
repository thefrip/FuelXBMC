<?php

class Model_SongView extends Orm\Model
{
    protected static $_connection = 'music';
    protected static $_table_name = 'songview';
    protected static $_primary_key = array('idSong');

  /**
   * Cherche un album dont le titre commence par le titre fourni
   * Retourne un tableau représentant le ou les albulm(s) trouvé(s) ou NULL si non trouvé
   *
   * @access public
   * @param string
   * @param integer
   * @param integer
   * @return array
   */
  public static function get_for_album($album_id)
  {
    // On recherche...
    $query = 'SELECT * FROM `songview` WHERE `songview`.`idAlbum` = '.$album_id.' ORDER BY CAST(`songview`.`iTrack` AS UNSIGNED) ASC';

    $results = \DB::query($query)
                    ->as_object()
                    ->execute(static::$_connection);

    // Si au moins une chanson est dans la base de données
    if (count($results) > 0)
    {
      $i = 0;
      foreach($results as $result)
      {
        // Pour corriger les numéros de pistes incorrects sur certains albums
        $result->iTrack = ++$i;

				// On traite chaque résultat pour les convertir en chanson
				$songs[] = self::_manage_results($result);
      }
    }
    else
    {
      $songs = NULL;
    }

    // On retourne les chansons trouvées ou NULL
    return $songs;
  }

  /**
   * Traite un résultat d'une requête dans la base de données pour le convertir en chanson
   *
   * @access public
   * @param object
   * @return object
   */
    private static function _manage_results($result)
    {
      $song = new stdClass();

      // Données diverses avec mise en forme
      $song->number = sprintf('%02s', $result->iTrack);
      $song->title = $result->strTitle;

      // Gestion d'une durée supérieure à 1 heure
      if ($result->iDuration > 3600)
          $song->duration = date('h:i:s', $result->iDuration);
      else
          $song->duration = date('i:s', $result->iDuration);

      // On retourne la chanson traitée
      return $song;
    }

}
