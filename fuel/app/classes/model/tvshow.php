<?php
use Orm\Model;

class Model_Tvshow extends Model
{
  protected static $_connection = 'video';
  protected static $_table_name = 'tvshow';
  protected static $_primary_key = array('idShow');

	protected static $_properties = array(
		'idShow',
    'c00',          // local title
    'c01',          // plot summary
    'c02',          // status
    'c03',          // votes
    'c04',          // rating
    'c05',          // first aired (use only year)
    'c06',          // thumbnails url : fake xml
    'c07',          // unknown
    'c08',          // genres : name / name (for display)
    'c09',          // origianal title (not used here because always empty)
    'c10',          // episodes guide url : fake xml
    'c11',          // fanarts url : fake xml
    'c12',          // thetvdb id (when use the corresponding scraper)
    'c13',          // mpaa certification
    'c14',          // network (here mixed with studios : name / name (for display))
    'c15',          // title formated for sorting (not used here)
    'c16',          // not used
    'c17',          // not used
    'c18',          // not used
    'c19',          // not used
    'c20',          // unknown
	);

  public function manage_data($input)
  {

    // Load language file
		\Lang::load('media', true);

    $json = array();

    // useful to make urls via javascript
    $json['media_type'] = 'tvshows';

    // local title?
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

    // new year?
    if (isset($input['year']))
    {
      if ($this->c05 != $input['year'])
      {
        $this->c05 = (int) $input['year'];
        $json['year'] = $this->c05;
      }
    }

    // new certification? (0 => no mpaa certification)
    if ((int) $input['certification'] != '0')
    {
      if ($this->c13 != $input['certification'])
      {
        // search certification text
        $certification = Model_Certification::find_by_value((int) $input['certification']);
        $this->c13 = $certification->name;
        $json['certification'] = $this->c13;
      }
    }

    if (isset($input['genres']))
    {
      $selected_genres = array();
      foreach($input['genres'] as $genre)
      {
        $selected_genres[] = (int) $genre;
      }

      $actual_genres = array();
      foreach(Model_VideoGenre::get_for_tvshow($this->idShow) as $genre)
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
        Model_VideoGenre::remove_for_tvshow($value, $this->idShow);
      }

      // Add the added genres
      foreach($added_genres as $key => $value)
      {
        Model_VideoGenre::set_for_tvshow($value, $this->idShow);
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

        // list genres to display with this tvshow
        $this->c08 = implode(' / ', $genres);

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
      foreach(Model_Studio::get_for_tvshow($this->idShow) as $studio)
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
        Model_Studio::remove_for_tvshow($value, $this->idShow);
      }

      // Add the added studios
      foreach($added_studios as $key => $value)
      {
        Model_Studio::set_for_tvshow($value, $this->idShow);
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

        // list studios to display with this tvshow
        $this->c14 = implode(' / ', $studios);

        $json['studios'] = $selected_studios;
      }
    }

    return $json;
  }

}
