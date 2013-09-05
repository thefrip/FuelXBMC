<?php
use Orm\Model;

class Model_Movie extends Model
{
  protected static $_connection = 'video';
  protected static $_table_name = 'movie';
  protected static $_primary_key = array('idMovie');

	protected static $_properties = array(
		'idMovie',
		'idFile',
		'c00',      // local title
		'c01',      // Movie Plot overview
		'c02',      // Movie Plot Outline
		'c03',      // Movie Tagline tagline
		'c04',      // rating votes
		'c05',      // rating
		'c06',      // writers : name / name (for display)
		'c07',      // year
		'c08',      // thumbnails url : fake xml
		'c09',      // imdb id
		'c10',      // title formatted for sorting
		'c11',      // runtime (in seconds)
		'c12',      // mpaa certification
		'c13',      // imdb top 250 rankng
		'c14',      // genres : name / name (for display)
		'c15',      // directors : name / name (for display)
		'c16',      // origianal title
		'c17',      // unknown
		'c18',      // studios : name / name (for display)
		'c19',      // trailer url
		'c20',      // fanarts url : fake xml
		'c21',      // countries : name / name (for display)
		'c22',      // unknown
		'c23',      // foreign key for path table
		'idFile',   // foreign key for files table
		'idSet',    // foreign key for sets table
	);

  public function manage_data($input)
  {
    // Load language file
		\Lang::load('media', true);

    $json = array();

    // useful to make urls via javascript
    $json['media_type'] = 'movies';

    // new local title?
    if (isset($input['local-title']))
    {
      if ($this->c00 != $input['local-title'])
      {
        $this->c00 = Xbmc::clean($input['local-title']);
        $json['local_title'] = $this->c00;
      }
    }

    // new overview?
    if (isset($input['overview']))
    {
      if ($this->c01 != $input['overview'])
      {
        $this->c01 = Xbmc::clean($input['overview']);
        $json['overview'] = $this->c01;
      }
    }

    // new tagline?
    if (isset($input['tagline']))
    {
      if ($this->c03 != $input['tagline'])
      {
        $this->c03 = Xbmc::clean($input['tagline']);
        $json['tagline'] = $this->c03;
      }
    }

    // new year?
    if (isset($input['year']))
    {
      if ($this->c07 != $input['year'])
      {
        $this->c07 = (int) $input['year'];
        $json['year'] = $this->c07;
      }
    }

    // runtime
    if (isset($input['runtime-hour']))
    {
      $runtime = ((int) $input['runtime-hour'] * 60) + (int) $input['runtime-minute'];
    }
    else
    {
      $runtime = (int) $input['runtime-minute'];
    }
    // Convert duration in seconds
    $runtime_value = $runtime * 60;

    // new runtime?
    if ($this->c11 != $runtime_value)
    {
      $this->c11 = $runtime_value;

      $runtime = new stdClass();
      $runtime->value = $runtime_value;

      // Si le format est vide, on affiche la durée en minutes
      if (Lang::get('media.runtime_format') == '')
      {
        $display = $runtime->value.'m';
      }
      else
      {
        $display = gmstrftime(Lang::get('media.runtime_format'), $runtime->value);
      }
      // manage 'h' a,d 'm'
      $display = str_replace('h', Lang::get('media.runtime_format_hour'), $display);
      $display = str_replace('m', Lang::get('media.runtime_format_minute'), $display);
      $runtime->display = $display;

      $json['runtime'] = $runtime;
    }

    // new certification? (0 => no mpaa certification)
    if ((int) $input['certification'] != '0')
    {
      if ($this->c12 != $input['certification'])
      {
        // search certification text
        $certification = Model_Certification::find_by_value((int) $input['certification']);
        $this->c12 = $certification->name;
        $json['certification'] = $this->c12;
      }
    }

    // if we manage movies sets
    if (isset($input['set']))
    {
      // new set?
      if ($this->idSet != $input['set'])
      {
        $idSet = (int) $input['set'];
        // remove movie from set?
        if ((int) $input['set'] == 0)
        {
          $this->idSet = null;
          $json['set'] = '';
        }
        else
        {
          $this->idSet = $idSet;
          $set = Model_SetView::read($idSet);
          $json['set'] = sprintf(Lang::get('media.in_set'), Html::anchor('set/'.$set->id.'-'.Inflector::friendly_title($set->name, '-'), $set->name));
        }
      }
    }

    // genres (new or not)
    if (isset($input['genres']))
    {
      $selected_genres = array();
      foreach($input['genres'] as $genre)
      {
        $selected_genres[] = (int) $genre;
      }

      $actual_genres = array();
      foreach(Model_VideoGenre::get_for_movie($this->idMovie) as $genre)
      {
        $actual_genres[] = $genre->id;
      }

      asort($selected_genres);
      asort($actual_genres);

      $removed_genres = array_diff($actual_genres, $selected_genres);
      $added_genres = array_diff($selected_genres, $actual_genres);

      // Remove the removed genres
      foreach($removed_genres as $key => $value)
      {
        Model_VideoGenre::remove_for_movie($value, $this->idMovie);
      }

      // Add the added genres
      foreach($added_genres as $key => $value)
      {
        Model_VideoGenre::set_for_movie($value, $this->idMovie);
      }

      // if any change, update list genres for display and return genres list
      if ((count($removed_genres) > 0) or (count($added_genres) > 0))
      {
        $genres = array();
        foreach(Model_VideoGenre::find('all') as $genre)
        {
          if (in_array($genre->idGenre, $input['genres']))
          {
            $genres[] = $genre->strGenre;
          }
        }

        // list genres to display with this movie
        $this->c14 = implode(' / ', $genres);

        $json['genres'] = $selected_genres;
      }
    }

    // studios (new or not)
    if (isset($input['studios']))
    {
      $selected_studios = array();
      foreach($input['studios'] as $studio)
      {
        $selected_studios[] = (int) $studio;
      }

      $actual_studios = array();
      foreach(Model_Studio::get_for_movie($this->idMovie) as $studio)
      {
        $actual_studios[] = $studio->id;
      }

      asort($selected_studios);
      asort($actual_studios);

      $removed_studios = array_diff($actual_studios, $selected_studios);
      $added_studios = array_diff($selected_studios, $actual_studios);

      // Remove the removed studios
      foreach($removed_studios as $key => $value)
      {
        Model_Studio::remove_for_movie($value, $this->idMovie);
      }

      // Add the added studios
      foreach($added_studios as $key => $value)
      {
        Model_Studio::set_for_movie($value, $this->idMovie);
      }

      // if any change, update list studios for display and return studios list
      if ((count($removed_studios) > 0) or (count($added_studios) > 0))
      {
        $studios = array();
        foreach(Model_Studio::find('all') as $studio)
        {
          if (in_array($studio->idStudio, $input['studios']))
          {
            $studios[] = $studio->strStudio;
          }
        }

        // list studios to display with this movie
        $this->c18 = implode(' / ', $studios);

        $json['studios'] = $selected_studios;
      }
    }

    // countries (new or not)
    if (isset($input['countries']))
    {
      $selected_countries = array();
      foreach($input['countries'] as $country)
      {
        $selected_countries[] = (int) $country;
      }

      $actual_countries = array();
      foreach(Model_Country::get_for_movie($this->idMovie) as $country)
      {
        $actual_countries[] = $country->id;
      }

      asort($selected_countries);
      asort($actual_countries);

      $removed_countries = array_diff($actual_countries, $selected_countries);
      $added_countries = array_diff($selected_countries, $actual_countries);

      // Remove the removed countries
      foreach($removed_countries as $key => $value)
      {
        Model_Country::remove_for_movie($value, $this->idMovie);
      }

      // Add the added countries
      foreach($added_countries as $key => $value)
      {
        Model_Country::set_for_movie($value, $this->idMovie);
      }

      // if any change, update list countries for display and return countries list
      if ((count($removed_countries) > 0) or (count($added_countries) > 0))
      {
        $countries = array();
        foreach(Model_Country::find('all') as $country)
        {
          if (in_array($country->idCountry, $input['countries']))
          {
            $countries[] = $country->strCountry;
          }
        }

        // list countries to display with this movie
        $this->c21 = implode(' / ', $countries);

        $json['countries'] = $selected_countries;
      }
    }

    return $json;
  }

}
