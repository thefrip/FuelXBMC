<?php

class Controller_People extends Controller_Base
{
    /**
     * Show the people list
     *
     * @access  public
     * @return  Response
     */
    public function action_index()
    {
      $config = array(
        'pagination_url' => \Uri::create('people/page'),
        'total_items' => Model_Actor::count(),
        'per_page' => 8,
        'uri_segment' => 3,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('people-list', $config);

      $data['people'] = Model_Actor::read_all($pagination->per_page, $pagination->offset);
      $data['people_type'] = 'all_people';
      $data['page_title'] = Lang::get('title.list_people');
      $data['pagination'] = $pagination->render();

      $this->template->title = $data['page_title'];
      $this->template->content = View::forge('person/index', $data);
    }

    /**
     * Contrôle présence $_POST et conversion vers une url
     */
    public function action_pre_search()
    {
      // Si pas de titre alors on affiche la liste des personnalités
      if (Input::post('query') == '') Response::redirect('people');

      Response::redirect('people/search/'.urlencode(Input::post('query')));
    }

    /**
     * Recherche de personnalités par leur nom
     */
    public function action_search($query)
    {
      $name = html_entity_decode(urldecode($query));

      $config = array(
        'pagination_url' => \Uri::create('people/search/'.$query.'/page'),
        'total_items' => Model_Actor::count_all_by_name($name),
        'per_page' => 8,
        'uri_segment' => 5,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('people-search-list', $config);

      $data['people'] = Model_Actor::read_all_by_name($name, $pagination->per_page, $pagination->offset);
      $data['people_type'] = 'search_people';

      // Cas de la première page de résultats sans page sélectionnée
      if (!isset($pagination->per_page))
      {
        $data['page_title'] = sprintf(Lang::get('title.list_people_search'), $name);
      }

      $data['pagination'] = $pagination->render();

      // Contenu de la page à retourner directement si c'est une requête en ajax
      // ou dans le template dans le cas contraire
      $content = View::forge('person/index', $data);

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
     * Show the page for a person
     *
     * @access  public
     * @return  Response
     */
    public function action_view($person_id = 0, $name = '')
    {
      $person = Model_Actor::read($person_id, true);

      $data['person'] = $person;

      // Default value to hide tab pane
      $total_movies_written = 0;
      $total_movies_directed = 0;
      $total_movies_played = 0;
      $total_tvshows_played = 0;
      $total_episodes_written = 0;
      $total_episodes_directed = 0;
      $total_episodes_played = 0;

      if (Config::get('settings.manage_movies'))
      {
        // Give first page of movies list written by this person
        $total_movies_written = Model_MovieView::count_by_writer($person_id);
        if ($total_movies_written > 0)
        {
          $config = array(
            'pagination_url' => \Uri::create('movies/written/'.$person->id.'-'.Inflector::friendly_title($person->name, '-').'/page'),
            'total_items' => $total_movies_written,
            'per_page' => 5,
            'uri_segment' => 5,
            'num_links' => 3,
            'show_first' => true,
            'show_last' => true,
          );

          $pagination = Pagination::forge('movies-written-list', $config);

          $sub_view['movies'] = Model_MovieView::read_all_by_writer($person_id, 5);
          $sub_view['movies_type'] = 'movies_written';
          $sub_view['page_title'] = '';
          $sub_view['pagination'] = $pagination->render();

          $data['movies_written'] = View::forge('movie/index', $sub_view);
        }

        // Give first page of movies list directed by this person
        $total_movies_directed = Model_MovieView::count_by_director($person_id);
        $data['total_movies_directed'] = $total_movies_directed;
        if ($total_movies_directed > 0)
        {
          $config = array(
            'pagination_url' => \Uri::create('movies/directed/'.$person->id.'-'.Inflector::friendly_title($person->name, '-').'/page'),
            'total_items' => $total_movies_directed,
            'per_page' => 5,
            'uri_segment' => 5,
            'num_links' => 3,
            'show_first' => true,
            'show_last' => true,
          );

          $pagination = Pagination::forge('movies-directed-list', $config);

          $sub_view['movies'] = Model_MovieView::read_all_by_director($person_id, 5);
          $sub_view['movies_type'] = 'movies_directed';
          $sub_view['page_title'] = '';
          $sub_view['pagination'] = $pagination->render();

          $data['movies_directed'] = View::forge('movie/index', $sub_view);
        }

        // Give first page of movies list played by this person
        $total_movies_played = Model_MovieView::count_by_actor($person_id);
        if ($total_movies_played > 0)
        {
          $config = array(
            'pagination_url' => \Uri::create('movies/played/'.$person->id.'-'.Inflector::friendly_title($person->name, '-').'/page'),
            'total_items' => $total_movies_played,
            'per_page' => 5,
            'uri_segment' => 5,
            'num_links' => 3,
            'show_first' => true,
            'show_last' => true,
          );

          $pagination = Pagination::forge('movies-played-list', $config);

          $sub_view['movies'] = Model_MovieView::read_all_by_actor($person_id, 5);
          $sub_view['movies_type'] = 'movies_played';
          $sub_view['page_title'] = '';
          $sub_view['pagination'] = $pagination->render();

          $data['movies_played'] = View::forge('movie/index', $sub_view);
        }
      }

      if (Config::get('settings.manage_tvshows'))
      {
        // Give first page of tvshows list played by this person
        $total_tvshows_played = Model_TvshowView::count_by_actor($person_id);
        if ($total_tvshows_played > 0)
        {
          $config = array(
            'pagination_url' => \Uri::create('tvshows/played/'.$person->id.'-'.Inflector::friendly_title($person->name, '-').'/page'),
            'total_items' => $total_tvshows_played,
            'per_page' => 5,
            'uri_segment' => 5,
            'num_links' => 3,
            'show_first' => true,
            'show_last' => true,
          );

          $pagination = Pagination::forge('tvshows-played-list', $config);

          $sub_view['tvshows'] = Model_TvshowView::read_all_by_actor($person_id, 5);
          $sub_view['tvshows_type'] = 'tvshows_played';
          $sub_view['page_title'] = '';
          $sub_view['pagination'] = $pagination->render();

          $data['tvshows_played'] = View::forge('tvshow/index', $sub_view);
        }

        // Give first page of episodes list written by this person
        $total_episodes_written = Model_EpisodeView::count_by_writer($person_id);
        if ($total_episodes_written > 0)
        {
          $config = array(
            'pagination_url' => \Uri::create('episodes/written/'.$person->id.'-'.Inflector::friendly_title($person->name, '-').'/page'),
            'total_items' => $total_episodes_written,
            'per_page' => 5,
            'uri_segment' => 5,
            'num_links' => 3,
            'show_first' => true,
            'show_last' => true,
          );

          $pagination = Pagination::forge('episodes-written-list', $config);

          $sub_view['episodes'] = Model_EpisodeView::read_all_by_writer($person_id, 5);
          $sub_view['episodes_type'] = 'episodes_written';
          $sub_view['page_title'] = '';
          $sub_view['pagination'] = $pagination->render();

          $data['episodes_written'] = View::forge('episode/index', $sub_view);
        }

        // Give first page of episodes list directed by this person
        $total_episodes_directed = Model_EpisodeView::count_by_director($person_id);
        if ($total_episodes_directed > 0)
        {
          $config = array(
            'pagination_url' => \Uri::create('episodes/directed/'.$person->id.'-'.Inflector::friendly_title($person->name, '-').'/page'),
            'total_items' => $total_episodes_directed,
            'per_page' => 5,
            'uri_segment' => 5,
            'num_links' => 3,
            'show_first' => true,
            'show_last' => true,
          );

          $pagination = Pagination::forge('episodes-directed-list', $config);

          $sub_view['episodes'] = Model_EpisodeView::read_all_by_director($person_id, 5);
          $sub_view['episodes_type'] = 'episodes_directed';
          $sub_view['page_title'] = '';
          $sub_view['pagination'] = $pagination->render();

          $data['episodes_directed'] = View::forge('episode/index', $sub_view);
        }

        // Give first page of episodes list played by this person
        $total_episodes_played = Model_EpisodeView::count_by_actor($person_id);
        if ($total_episodes_played > 0)
        {
          $config = array(
            'pagination_url' => \Uri::create('episodes/played/'.$person->id.'-'.Inflector::friendly_title($person->name, '-').'/page'),
            'total_items' => $total_episodes_played,
            'per_page' => 5,
            'uri_segment' => 5,
            'num_links' => 3,
            'show_first' => true,
            'show_last' => true,
          );

          $pagination = Pagination::forge('episodes-played-list', $config);

          $sub_view['episodes'] = Model_EpisodeView::read_all_by_actor($person_id, 5);
          $sub_view['episodes_type'] = 'episodes_played';
          $sub_view['page_title'] = '';
          $sub_view['pagination'] = $pagination->render();

          $data['episodes_played'] = View::forge('episode/index', $sub_view);
        }
      }

      $total_movies_written = Model_MovieView::count_by_writer($person_id);
      $data['total_movies_written'] = $total_movies_written;
      $data['total_movies_directed'] = $total_movies_directed;
      $data['total_movies_played'] = $total_movies_played;
      $data['total_tvshows_played'] = $total_tvshows_played;
      $data['total_episodes_written'] = $total_episodes_written;
      $data['total_episodes_directed'] = $total_episodes_directed;
      $data['total_episodes_played'] = $total_episodes_played;

      $this->template->title = $person->name;
      $this->template->content = View::forge('person/view', $data);
    }

}
