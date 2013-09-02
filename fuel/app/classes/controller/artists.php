<?php

class Controller_Artists extends Controller_Base
{

    /**
     * Show the artists list
     *
     * @access  public
     * @return  Response
     */
    public function action_index()
    {
      $config = array(
        'pagination_url' => \Uri::create('artists/page'),
        'total_items' => Model_ArtistView::count_all(),
        'per_page' => 8,
        'uri_segment' => 3,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('artists-list', $config);

      $data['artists'] = Model_ArtistView::read_all($pagination->per_page, $pagination->offset);
      $data['page_title'] = Lang::get('title.list_artists');
      $data['pagination'] = $pagination->render();

      $this->template->title = $data['page_title'];
      $this->template->content = View::forge('artist/index', $data);
    }

    /**
     * Contrôle présence $_POST et conversion vers une url
     */
    public function action_pre_search()
    {
      // Si pas de titre alors on affiche la liste des artistnalités
      if (Input::post('query') == '') Response::redirect('artists');

      Response::redirect('artists/search/'.urlencode(Input::post('query')));
    }

    /**
     * Recherche de artistnalités par leur nom
     */
    public function action_search($query)
    {
      $name = html_entity_decode(urldecode($query));

      $config = array(
        'pagination_url' => \Uri::create('artists/search/'.$query.'/page'),
        'total_items' => Model_ArtistView::count_all_by_name($name),
        'per_page' => 8,
        'uri_segment' => 5,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('artists-search-list', $config);

      $data['artists'] = Model_ArtistView::read_all_by_name($name, $pagination->per_page, $pagination->offset);
      $data['artists_type'] = 'search_artists';

      // Cas de la première page de résultats sans page sélectionnée
      if (!isset($pagination->per_page))
      {
        $data['page_title'] = sprintf(Lang::get('title.list_artists_search'), $name);
      }

      $data['pagination'] = $pagination->render();

      // Contenu de la page à retourner directement si c'est une requête en ajax
      // ou dans le template dans le cas contraire
      $content = View::forge('artist/index', $data);

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
     * Show the page for an artist
     *
     * @access  public
     * @return  Response
     */
    public function action_view($artist_id = 0, $name = '')
    {
      $artist = Model_ArtistView::read($artist_id, true);

//    echo '<pre>'.print_r($artist, true).'</pre>'; die();

      $data['artist'] = $artist;

      // Give first page of albums list played by this artist
      $total_albums_played = Model_AlbumView::count_by_artist($artist_id);
      $data['total_albums_played'] = $total_albums_played;
      if ($total_albums_played > 0)
      {
        $config = array(
          'pagination_url' => \Uri::create('albums/played/'.$artist->id.'-'.Inflector::friendly_title($artist->name, '-').'/page'),
          'total_items' => $total_albums_played,
          'per_page' => 5,
          'uri_segment' => 5,
          'num_links' => 3,
          'show_first' => true,
          'show_last' => true,
        );

        $pagination = Pagination::forge('albums-played-list', $config);

        $sub_view['albums'] = Model_AlbumView::read_all_by_artist($artist_id, 5);
        $sub_view['albums_type'] = 'albums_played';
        $sub_view['page_title'] = '';
        $sub_view['pagination'] = $pagination->render();

        $data['albums_played'] = View::forge('album/index', $sub_view);
      }

      $this->template->title = $artist->name;
      $this->template->content = View::forge('artist/view', $data);
    }

}
