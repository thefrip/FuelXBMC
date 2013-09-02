<?php

class Controller_Movies extends Controller_Base
{
    /**
     * Show the movies list
     *
     * @access  public
     * @return  Response
     */
    public function action_index()
    {
      $config = array(
        'pagination_url' => \Uri::create('movies/page'),
        'total_items' => Model_MovieView::count(),
        'per_page' => 5,
        'uri_segment' => 3,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('movies-list', $config);

      $data['movies'] = Model_MovieView::read_all($pagination->per_page, $pagination->offset);
      $data['movies_type'] = 'all_movies';
      $data['page_title'] = Lang::get('title.list_movies');
      $data['pagination'] = $pagination->render();

      // Contenu de la page à retourner directement si c'est une requête en ajax
      // ou dans le template dans le cas contraire
      $content = View::forge('movie/index', $data);

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
      // Si pas de titre alors on affiche la liste des films
      if (Input::post('query') == '') Response::redirect('movies');

      Response::redirect('movies/search/'.urlencode(Input::post('query')));
    }

    /**
     * Recherche de films par leur titre
     */
    public function action_search($query)
    {
      $title = html_entity_decode(urldecode($query));

      $config = array(
        'pagination_url' => \Uri::create('movies/search/'.$query.'/page'),
        'total_items' => Model_MovieView::count_all_by_title($title),
        'per_page' => 6,
        'uri_segment' => 5,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('movies-search-list', $config);

      $data['movies'] = Model_MovieView::read_all_by_title($title, $pagination->per_page, $pagination->offset);
      $data['movies_type'] = 'search_movies';

      // Cas de la première page de résultats sans page sélectionnée
      if (!isset($pagination->per_page))
      {
        $data['page_title'] = sprintf(Lang::get('title.list_movies_search'), $title);
      }

      $data['pagination'] = $pagination->render();

      // Contenu de la page à retourner directement si c'est une requête en ajax
      // ou dans le template dans le cas contraire
      $content = View::forge('movie/index', $data);

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
     * Show the movies list wich match a year
     *
     * @access  public
     * @return  Response
     */
    public function action_year($year = 0)
    {
      $config = array(
        'pagination_url' => \Uri::create('movies/year/'.$year.'/page'),
        'total_items' => Model_MovieView::count_by_year($year),
        'per_page' => 5,
        'uri_segment' => 5,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('movies-year-list', $config);

      $data['movies'] = Model_MovieView::read_all_by_year($year, $pagination->per_page, $pagination->offset);
      $data['movies_type'] = 'all_movies';
      $data['page_title'] = sprintf(Lang::get('title.list_movies_year'), $year);
      $data['pagination'] = $pagination->render();

      $this->template->title = $data['page_title'];
      $this->template->content = View::forge('movie/index', $data);
    }

    /**
     * Show the movies list wich match a genre
     *
     * @access  public
     * @return  Response
     */
    public function action_genre($genre_id = 0, $genre_name = '')
    {
      $genre = Model_VideoGenre::find($genre_id);

      $config = array(
        'pagination_url' => \Uri::create('movies/genre/'.$genre_id.'-'.$genre_name.'/page'),
        'total_items' => Model_MovieView::count_by_genre($genre_id),
        'per_page' => 5,
        'uri_segment' => 5,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('movies-genre-list', $config);

      $data['movies'] = Model_MovieView::read_all_by_genre($genre_id, $pagination->per_page, $pagination->offset);
      $data['movies_type'] = 'all_movies';
      $data['page_title'] = sprintf( Lang::get('title.list_movies_genre'), $genre->strGenre);
      $data['pagination'] = $pagination->render();

      $this->template->title = $data['page_title'];
      $this->template->content = View::forge('movie/index', $data);
    }

    /**
     * Show the movies list wich match a studio
     *
     * @access  public
     * @return  Response
     */
    public function action_studio($studio_id = 0, $studio_name = '')
    {
      $studio = Model_Studio::find($studio_id);

      $config = array(
        'pagination_url' => \Uri::create('movies/studio/'.$studio_id.'-'.$studio_name.'/page'),
        'total_items' => Model_MovieView::count_by_studio($studio_id),
        'per_page' => 5,
        'uri_segment' => 5,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('movies-studio-list', $config);

      $data['movies'] = Model_MovieView::read_all_by_studio($studio_id, $pagination->per_page, $pagination->offset);
      $data['movies_type'] = 'all_movies';
      $data['page_title'] = sprintf( Lang::get('title.list_movies_studio'), $studio->strStudio);
      $data['pagination'] = $pagination->render();

      $this->template->title = $data['page_title'];
      $this->template->content = View::forge('movie/index', $data);
    }

    /**
     * Show the movies list wich match a country
     *
     * @access  public
     * @return  Response
     */
    public function action_country($country_id = 0, $country_name = '')
    {
      $country = Model_Country::find($country_id);

      $config = array(
        'pagination_url' => \Uri::create('movies/country/'.$country_id.'-'.$country_name.'/page'),
        'total_items' => Model_MovieView::count_by_country($country_id),
        'per_page' => 5,
        'uri_segment' => 5,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('movies-country-list', $config);

      $data['movies'] = Model_MovieView::read_all_by_country($country_id, $pagination->per_page, $pagination->offset);
      $data['movies_type'] = 'all_movies';
      $data['page_title'] = sprintf(Lang::get('title.list_movies_country'), $country->strCountry);
      $data['pagination'] = $pagination->render();

      $this->template->title = $data['page_title'];
      $this->template->content = View::forge('movie/index', $data);
    }

    /**
     * Show the movies list written by a person
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
          'pagination_url' => \Uri::create('movies/written/'.$person->id.'-'.Inflector::friendly_title($person->name, '-').'/page'),
          'total_items' => Model_MovieView::count_by_writer($person_id),
          'per_page' => 5,
          'uri_segment' => 5,
          'num_links' => 3,
          'show_first' => true,
          'show_last' => true,
        );

        $pagination_movies_written = Pagination::forge('movies-written-list', $config);

        $data['movies'] = Model_MovieView::read_all_by_writer($person_id, $pagination_movies_written->per_page, $pagination_movies_written->offset);
        $data['movies_type'] = 'movies_written';
        $data['page_title'] = '';
        $data['pagination'] = $pagination_movies_written->render();

        echo View::forge('movie/index', $data);
      }
      die();
    }

    /**
     * Show the movies list directed by a person
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
          'pagination_url' => \Uri::create('movies/directed/'.$person->id.'-'.Inflector::friendly_title($person->name, '-').'/page'),
          'total_items' => Model_MovieView::count_by_director($person_id),
          'per_page' => 5,
          'uri_segment' => 5,
          'num_links' => 3,
          'show_first' => true,
          'show_last' => true,
        );

        $pagination_movies_directed = Pagination::forge('movies-directed-list', $config);

        $data['movies'] = Model_MovieView::read_all_by_director($person_id, $pagination_movies_directed->per_page, $pagination_movies_directed->offset);
        $data['movies_type'] = 'movies_directed';
        $data['page_title'] = '';
        $data['pagination'] = $pagination_movies_directed->render();

        echo View::forge('movie/index', $data);
      }
      die();
    }

    /**
     * Show the movies list played by a person
     *
     * @access  public
     * @return  Response
     */
    public function action_played($person_id = 0, $person_name = '')
    {
      if (Input::is_ajax())
      {
        $person = Model_Actor::read($person_id);

        $config = array(
          'pagination_url' => \Uri::create('movies/played/'.$person->id.'-'.Inflector::friendly_title($person->name, '-').'/page'),
          'total_items' => Model_MovieView::count_by_actor($person_id),
          'per_page' => 5,
          'uri_segment' => 5,
          'num_links' => 3,
          'show_first' => true,
          'show_last' => true,
        );

        $pagination_movies_played = Pagination::forge('movies-played-list', $config);

        $data['movies'] = Model_MovieView::read_all_by_actor($person_id, $pagination_movies_played->per_page, $pagination_movies_played->offset);
        $data['movies_type'] = 'movies_played';
        $data['page_title'] = '';
        $data['pagination'] = $pagination_movies_played->render();

        echo View::forge('movie/index', $data);
      }
      die();
    }

    /**
     * Show the page for a movie
     *
     * @access  public
     * @return  Response
     */
    public function action_view($movie_id = 0, $title = '')
    {
      $movie = Model_MovieView::read($movie_id, true);

      $data['movie'] = $movie;

      if ($movie->original_title == $movie->local_title) unset($movie->original_title);
      $original_title = (isset($movie->original_title)) ? '('.$movie->original_title.')' : '';

      $data['original_title'] = $original_title;

      $this->template->title = $movie->local_title;
      $this->template->content = View::forge('movie/view', $data);
    }

}
