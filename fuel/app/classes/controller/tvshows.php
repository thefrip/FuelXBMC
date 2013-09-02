<?php

class Controller_Tvshows extends Controller_Base
{

    /**
     * Show the tvshows list
     *
     * @access  public
     * @return  Response
     */
    public function action_index()
    {
      $config = array(
        'pagination_url' => \Uri::create('tvshows/page'),
        'total_items' => Model_TvshowView::count(),
        'per_page' => 6,
        'uri_segment' => 3,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('tvshows-list', $config);

      $data['tvshows'] = Model_TvshowView::read_all($pagination->per_page, $pagination->offset);
      $data['tvshows_type'] = 'all_tvshows';
      $data['page_title'] = Lang::get('title.list_tvshows');
      $data['pagination'] = $pagination->render();

//echo '<pre>'.print_r($data['tvshows'], true).'</pre>'; die();

      // Contenu de la page à retourner directement si c'est une requête en ajax
      // ou dans le template dans le cas contraire
      // Utilisation d'un template adapté selon le type d'affiche désirée (banner ou poster)
      $content = View::forge('tvshow/index', $data);

      if (Input::is_ajax())
      {
        echo $content;
        die();
      }
      else
      {
        $this->template->title = $data['page_title'];
        $this->template->content = $content;
      }
    }

    /**
     * Contrôle présence $_POST et conversion vers une url
     */
    public function action_pre_search()
    {
      // Si pas de titre alors on affiche la liste des séries TV
      if (Input::post('query') == '') Response::redirect('tvshows');

      Response::redirect('tvshows/search/'.urlencode(Input::post('query')));
    }

    /**
     * Recherche de séries TV par leur titre
     */
    public function action_search($query)
    {
      $title = html_entity_decode(urldecode($query));

      $config = array(
        'pagination_url' => \Uri::create('tvshows/search/'.$query.'/page'),
        'total_items' => Model_TvshowView::count_all_by_title($title),
        'per_page' => 6,
        'uri_segment' => 5,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('tvshows-search-list', $config);

      $data['tvshows'] = Model_TvshowView::read_all_by_title($title, $pagination->per_page, $pagination->offset);
      $data['tvshows_type'] = 'search_tvshows';

      // Cas de la première page de résultats sans page sélectionnée
      if (!isset($pagination->per_page))
      {
        $data['page_title'] = sprintf(Lang::get('title.list_tvshows_search'), $title);
      }

      $data['pagination'] = $pagination->render();

      // Contenu de la page à retourner directement si c'est une requête en ajax
      // ou dans le template dans le cas contraire
      $content = View::forge('tvshow/index', $data);

      if (Input::is_ajax())
      {
        echo $content;
        die();
      }
      else
      {
        $this->template->title = $data['page_title'];
        $this->template->content = $content;
      }
    }

    /**
     * Show the tvshows list wich match a year
     *
     * @access  public
     * @return  Response
     */
    public function action_year($year = 0)
    {
      $config = array(
        'pagination_url' => \Uri::create('tvshows/year/'.$year.'/page'),
        'total_items' => Model_TvshowView::count_by_year($year),
        'per_page' => 6,
        'uri_segment' => 5,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('tvshows-year-list', $config);

      $data['tvshows'] = Model_TvshowView::read_all_by_year($year, $pagination->per_page, $pagination->offset);
      $data['tvshows_type'] = 'all_tvshows';
      $data['page_title'] = sprintf(Lang::get('title.list_tvshows_year'), $year);
      $data['pagination'] = $pagination->render();

      $this->template->title = $data['page_title'];
      $this->template->content = View::forge('tvshow/index', $data);
    }

    /**
     * Show the tvshows list wich match a genre
     *
     * @access  public
     * @return  Response
     */
    public function action_genre($genre_id = 0, $genre_name = '')
    {
      $genre = Model_VideoGenre::find($genre_id);

      $config = array(
        'pagination_url' => \Uri::create('tvshows/genre/'.$genre_id.'-'.$genre_name.'/page'),
        'total_items' => Model_TvshowView::count_by_genre($genre_id),
        'per_page' => 6,
        'uri_segment' => 5,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('tvshows-genre-list', $config);

      $data['tvshows'] = Model_TvshowView::read_all_by_genre($genre_id, $pagination->per_page, $pagination->offset);
      $data['tvshows_type'] = 'all_tvshows';
      $data['page_title'] = sprintf( Lang::get('title.list_tvshows_genre'), $genre->strGenre);
      $data['pagination'] = $pagination->render();

      $this->template->title = $data['page_title'];
      $this->template->content = View::forge('tvshow/index', $data);
    }

    /**
     * Show the tvshows list wich match a studio
     *
     * @access  public
     * @return  Response
     */
    public function action_studio($studio_id = 0, $studio_name = '')
    {
      $studio = Model_Studio::find($studio_id);

      $config = array(
        'pagination_url' => \Uri::create('tvshows/studio/'.$studio_id.'-'.$studio_name.'/page'),
        'total_items' => Model_TvshowView::count_by_studio($studio_id),
        'per_page' => 6,
        'uri_segment' => 5,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('tvshows-studio-list', $config);

      $data['tvshows'] = Model_TvshowView::read_all_by_studio($studio_id, $pagination->per_page, $pagination->offset);
      $data['tvshows_type'] = 'all_tvshows';
      $data['page_title'] = sprintf( Lang::get('title.list_tvshows_studio'), $studio->strStudio);
      $data['pagination'] = $pagination->render();

      $this->template->title = $data['page_title'];
      $this->template->content = View::forge('tvshow/index', $data);
    }

    /**
     * Show the tvshows list written by a person
     *
     * @access  public
     * @return  Response
     */
    public function action_written($person_id = 0, $person_name = '')
    {
      if (Input::is_ajax() or true)
      {
        $person = Model_Writer::read($person_id);

        $config = array(
          'pagination_url' => \Uri::create('tvshows/written/'.$person->id.'-'.Inflector::friendly_title($person->name, '-').'/page'),
          'total_items' => Model_TvshowView::count_by_writer($person_id),
          'per_page' => 6,
          'uri_segment' => 5,
          'num_links' => 3,
          'show_first' => true,
          'show_last' => true,
        );

        $pagination_tvshows_written = Pagination::forge('tvshows-written-list', $config);

        $data['tvshows'] = Model_TvshowView::read_all_by_writer($person_id, $pagination_tvshows_written->per_page, $pagination_tvshows_written->offset);
        $data['tvshows_type'] = 'tvshows_written';
        $data['page_title'] = '';
        $data['pagination'] = $pagination_tvshows_written->render();

        echo View::forge('tvshow/index', $data);
      }
      die();
    }

    /**
     * Show the tvshows list directed by a person
     *
     * @access  public
     * @return  Response
     */
    public function action_directed($person_id = 0, $person_name = '')
    {
      if (Input::is_ajax() or true)
      {
        $person = Model_Director::read($person_id);

        $config = array(
          'pagination_url' => \Uri::create('tvshows/directed/'.$person->id.'-'.Inflector::friendly_title($person->name, '-').'/page'),
          'total_items' => Model_TvshowView::count_by_director($person_id),
          'per_page' => 6,
          'uri_segment' => 5,
          'num_links' => 3,
          'show_first' => true,
          'show_last' => true,
        );

        $pagination_tvshows_directed = Pagination::forge('tvshows-directed-list', $config);

        $data['tvshows'] = Model_TvshowView::read_all_by_director($person_id, $pagination_tvshows_directed->per_page, $pagination_tvshows_directed->offset);
        $data['tvshows_type'] = 'tvshows_directed';
        $data['page_title'] = '';
        $data['pagination'] = $pagination_tvshows_directed->render();

        echo View::forge('tvshow/index', $data);
      }
      die();
    }

    /**
     * Show the tvshows list played by a person
     *
     * @access  public
     * @return  Response
     */
    public function action_played($person_id = 0, $person_name = '')
    {
      if (Input::is_ajax() or true)
      {
        $person = Model_Actor::read($person_id);

        $config = array(
          'pagination_url' => \Uri::create('tvshows/played/'.$person->id.'-'.Inflector::friendly_title($person->name, '-').'/page'),
          'total_items' => Model_TvshowView::count_by_actor($person_id),
          'per_page' => 6,
          'uri_segment' => 5,
          'num_links' => 3,
          'show_first' => true,
          'show_last' => true,
        );

        $pagination_tvshows_played = Pagination::forge('tvshows-played-list', $config);

        $data['tvshows'] = Model_TvshowView::read_all_by_actor($person_id, $pagination_tvshows_played->per_page, $pagination_tvshows_played->offset);
        $data['tvshows_type'] = 'tvshows_played';
        $data['page_title'] = '';
        $data['pagination'] = $pagination_tvshows_played->render();

        echo View::forge('tvshow/index', $data);
      }
      die();
    }

    /**
     * Show the page for a tvshow
     *
     * @access  public
     * @return  Response
     */
    public function action_view($tvshow_id = 0, $title = '')
    {
      $tvshow = Model_TvshowView::read($tvshow_id, true);

      $data['tvshow'] = $tvshow;

//echo '<pre>'.print_r($tvshow, true).'</pre>'; die();

      $seasons = array();
      foreach($tvshow->seasons as $key => $value)
      {
        if ($key != -1)
        {
        $season = new stdClass();
        $season->id = $key;
        $seasons[] = $season;

        $config = array(
          'pagination_url' => \Uri::create('tvshow/'.$tvshow->id.'-'.Inflector::friendly_title($tvshow->local_title, '-').'/season/'.$season->id.'/page'),
          'total_items' => Model_EpisodeView::count_all_by_tvshow($tvshow->id, $season->id),
          'per_page' => 5,
          'uri_segment' => 6,
          'num_links' => 3,
          'show_first' => true,
          'show_last' => true,
        );

        $pagination = Pagination::forge('episodes-'.$season->id.'-list', $config);

        $sub_view['episodes'] = Model_EpisodeView::read_all_by_tvshow($tvshow->id, $season->id, $pagination->per_page, $pagination->offset);

        $sub_view['episodes_type'] = 'season_episodes';
        $sub_view['page_title'] = '';
        $sub_view['pagination'] = $pagination->render();
        $data['episodes_season'.$season->id] = View::forge('episode/index', $sub_view);
        }
      }

      $data['seasons'] = $seasons;

//echo '<pre>'.print_r($tvshow->seasons, true).'</pre>'; die();

      $this->template->title = $tvshow->local_title;
      $this->template->content = View::forge('tvshow/view', $data);
    }

    /**
     * Show the episodes list for a season of a tvshow
     *
     * @access  public
     * @return  Response
     */
    public function action_season($tvshow_id = 0, $tvshow_title = '', $season_id)
    {
      if (Input::is_ajax() or true)
      {
        $config = array(
          'pagination_url' => \Uri::create('tvshow/'.$tvshow_id.'-'.Inflector::friendly_title($tvshow_title, '-').'/season/'.$season_id.'/page'),
          'total_items' => Model_EpisodeView::count_all_by_tvshow($tvshow_id, $season_id),
          'per_page' => 5,
          'uri_segment' => 6,
          'num_links' => 3,
          'show_first' => true,
          'show_last' => true,
        );

        $pagination = Pagination::forge('episodes-'.$season_id.'-list', $config);

        $data['episodes'] = Model_EpisodeView::read_all_by_tvshow($tvshow_id, $season_id, $pagination->per_page, $pagination->offset);
        $data['episodes_type'] = 'season_episodes';
        $data['page_title'] = '';
        $data['pagination'] = $pagination->render();

        echo View::forge('episode/index', $data);
      }
      die();
    }

}
