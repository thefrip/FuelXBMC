<?php

class Controller_Albums extends Controller_Base
{

    /**
     * Show the albums list
     *
     * @access  public
     * @return  Response
     */
    public function action_index()
    {
      $config = array(
        'pagination_url' => \Uri::create('albums/page'),
        'total_items' => Model_AlbumView::count(),
        'per_page' => 5,
        'uri_segment' => 3,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('albums-list', $config);

      $data['albums'] = Model_AlbumView::read_all($pagination->per_page, $pagination->offset);
      $data['albums_type'] = 'all_albums';
      $data['page_title'] = Lang::get('title.list_albums');
      $data['pagination'] = $pagination->render();

//echo '<pre>'.print_r($data['albums'], true).'</pre>'; die();

      // Contenu de la page à retourner directement si c'est une requête en ajax
      // ou dans le template dans le cas contraire
      $content = View::forge('album/index', $data);

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
      if (Input::post('query') == '') Response::redirect('albums');

      Response::redirect('albums/search/'.urlencode(Input::post('query')));
    }

    /**
     * Recherche de films par leur titre
     */
    public function action_search($query)
    {
      $title = html_entity_decode(urldecode($query));

      $config = array(
        'pagination_url' => \Uri::create('albums/search/'.$query.'/page'),
        'total_items' => Model_AlbumView::count_all_by_title($title),
        'per_page' => 6,
        'uri_segment' => 5,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('albums-search-list', $config);

      $data['albums'] = Model_AlbumView::read_all_by_title($title, $pagination->per_page, $pagination->offset);
      $data['albums_type'] = 'search_albums';

      // Cas de la première page de résultats sans page sélectionnée
      if (!isset($pagination->per_page))
      {
        $data['page_title'] = sprintf(Lang::get('title.list_albums_search'), $title);
      }

      $data['pagination'] = $pagination->render();

      // Contenu de la page à retourner directement si c'est une requête en ajax
      // ou dans le template dans le cas contraire
      $content = View::forge('album/index', $data);

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
     * Show the albums list wich match a year
     *
     * @access  public
     * @return  Response
     */
    public function action_year($year = 0)
    {
      $config = array(
        'pagination_url' => \Uri::create('albums/year/'.$year.'/page'),
        'total_items' => Model_AlbumView::count_by_year($year),
        'per_page' => 5,
        'uri_segment' => 5,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('albums-year-list', $config);

      $data['albums'] = Model_AlbumView::read_all_by_year($year, $pagination->per_page, $pagination->offset);
      $data['albums_type'] = 'all_albums';
      $data['page_title'] = sprintf(Lang::get('title.list_albums_year'), $year);
      $data['pagination'] = $pagination->render();

      $this->template->title = $data['page_title'];
      $this->template->content = View::forge('album/index', $data);
    }

    /**
     * Show the albums list wich match a genre
     *
     * @access  public
     * @return  Response
     */
    public function action_genre($genre_id = 0, $genre_name = '')
    {
      $genre = Model_MusicGenre::find($genre_id);

      $config = array(
        'pagination_url' => \Uri::create('albums/genre/'.$genre_id.'-'.$genre_name.'/page'),
        'total_items' => Model_AlbumView::count_by_genre($genre_id),
        'per_page' => 5,
        'uri_segment' => 5,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('albums-genre-list', $config);

      $data['albums'] = Model_AlbumView::read_all_by_genre($genre_id, $pagination->per_page, $pagination->offset);
      $data['albums_type'] = 'all_albums';
      $data['page_title'] = sprintf( Lang::get('title.list_albums_genre'), $genre->strGenre);
      $data['pagination'] = $pagination->render();

      $this->template->title = $data['page_title'];
      $this->template->content = View::forge('album/index', $data);
    }

    /**
     * Show the albums list played by a artist
     *
     * @access  public
     * @return  Response
     */
    public function action_played($artist_id = 0, $artist_name = '')
    {
      if (Input::is_ajax())
      {
        $config = array(
          'pagination_url' => \Uri::create('albums/played/'.$artist_id.'-'.$artist_name.'/page'),
          'total_items' => Model_AlbumView::count_by_artist($artist_id),
          'per_page' => 5,
          'uri_segment' => 5,
          'num_links' => 3,
          'show_first' => true,
          'show_last' => true,
        );

        $pagination_albums_played = Pagination::forge('albums-played-list', $config);

        $data['albums'] = Model_AlbumView::read_all_by_artist($artist_id, $pagination_albums_played->per_page, $pagination_albums_played->offset);
        $data['albums_type'] = 'albums_played';
        $data['page_title'] = '';
        $data['pagination'] = $pagination_albums_played->render();

        echo View::forge('album/index', $data);
      }
      die();
    }

    /**
     * Show the page for a album
     *
     * @access  public
     * @return  Response
     */
    public function action_view($album_id = 0, $title = '')
    {
      $album = Model_AlbumView::read($album_id, true);

      $data['album'] = $album;

      $this->template->title = $album->title;
      $this->template->content = View::forge('album/view', $data);
    }

}
