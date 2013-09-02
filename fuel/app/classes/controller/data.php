<?php

class Controller_Data extends Controller_Rest
{
    // get all certifications list in json format, return a javascript file
    public function get_certifications()
    {
      // Load language file
      \Lang::load('media', true);

      $certifications[] = array('id' => 0,
                                'name' => \Lang::get('media.no_mpaa'),
                                );

      foreach(Model_Certification::find('all', array('order_by' => 'rating')) as $certification)
      {
        $certifications[] = array('id' => $certification->id,
                                  'name' => $certification->name,
                                  );
      }

      $json = json_encode($certifications);
      echo 'var json_all_certifications = JSON.parse(\''.$json.'\');';
    }

    // get all sets list in json format, return a javascript file
    public function get_sets()
    {
      // Load language file
      \Lang::load('media', true);

      $sets[] = array('id' => 0,
                      'name' => \Lang::get('media.no_set'),
                      );

      foreach(Model_SetView::find('all', array('order_by' => 'strSet')) as $set)
      {
        $sets[] = array('id' => $set->idSet,
                        'name' => $set->strSet,
                        );
      }

      $json = json_encode($sets);

      echo 'var json_all_sets = '.$json.';';
    }

    // get all contries list in json format, return a javascript file
    public function get_countries()
    {
      foreach(Model_Country::find('all') as $country)
      {
        $countries[] = array('id' => (int) $country->idCountry,
                             'name' => $country->strCountry,
                             'slug' => Inflector::friendly_title($country->strCountry, '-'),
                             );
      }

      $json = json_encode($countries);
      echo 'var json_all_countries = '.$json.';';
    }

    // get all genres for music list in json format, return a javascript file
    public function get_music_genres()
    {
      foreach(Model_MusicGenre::find('all') as $genre)
      {
        $genres[] = array('id' => (int) $genre->idGenre,
                          'name' => $genre->strGenre,
                          'slug' => Inflector::friendly_title($genre->strGenre, '-'),
                          );
      }

      $json = json_encode($genres);
      echo 'var json_all_genres = '.$json.';';
    }

    // get all genres for video list in json format, return a javascript file
    public function get_video_genres()
    {
      foreach(Model_VideoGenre::find('all') as $genre)
      {
        $genres[] = array('id' => (int) $genre->idGenre,
                          'name' => $genre->strGenre,
                          'slug' => Inflector::friendly_title($genre->strGenre, '-'),
                          );
      }

      $json = json_encode($genres);
      echo 'var json_all_genres = '.$json.';';
    }

    // get all studios for video list in json format, return a javascript file
    public function get_studios()
    {
      foreach(Model_Studio::find('all') as $studio)
      {
        $studios[] = array('id' => (int) $studio->idStudio,
                          'name' => $studio->strStudio,
                          'slug' => Inflector::friendly_title($studio->strStudio, '-'),
                          );
      }

      $json = json_encode($studios);
      echo 'var json_all_studios = '.$json.';';
    }

    // Change data of movie, tvshow, episode, artist or album
    public function post_infos($media_type_media_id = null)
    {

      // Security test to avoid change datas for every one
      // Only administrator can change datas
      if (!\Security::check_token(\Input::cookie('token')))
      {
        echo 'error';
        die();
      }

      // the type of media is movie, tvshow, episode, artist or album
      // the id is the unic identifier of this media
      list($media_type, $media_id) = explode('_', $media_type_media_id);

      // Get model for this media to access the media object
      if (class_exists($model = 'Model_'.ucfirst($media_type)))
      {
          $media = $model::find($media_id);

          $json = $media->manage_data(\Input::post());

          $media->save();
      }

      return $this->response($json);
    }
}
