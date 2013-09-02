<?php

class Controller_Sets extends Controller_Base
{

    /**
     * Show the sets list
     *
     * @access  public
     * @return  Response
     */
    public function action_index()
    {
      $config = array(
        'pagination_url' => \Uri::create('sets/page'),
        'total_items' => Model_SetView::count(),
        'per_page' => 5,
        'uri_segment' => 3,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('sets-list', $config);

      $data['sets'] = Model_SetView::read_all($pagination->per_page, $pagination->offset);
      $data['sets_type'] = 'all_sets';
      $data['page_title'] = Lang::get('title.list_sets');
      $data['pagination'] = $pagination->render();

      $this->template->title = $data['page_title'];
      $this->template->content = View::forge('set/index', $data);
    }

    /**
     * Contrôle présence $_POST et conversion vers une url
     */
    public function action_pre_search()
    {
      // Si pas de titre alors on affiche la liste des sagas de films
      if (Input::post('query') == '') Response::redirect('sets');

      Response::redirect('sets/search/'.urlencode(Input::post('query')));
    }

    /**
     * Recherche de sagas de films par leur titre
     */
    public function action_search($query)
    {
      $title = urldecode($query);

      $config = array(
        'pagination_url' => \Uri::create('sets/search/'.$query.'/page'),
        'total_items' => Model_Set::count_all_by_title($title),
        'per_page' => 8,
        'uri_segment' => 5,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('sets-search-list', $config);

      $data['sets'] = Model_SetView::read_all_by_title($title, $pagination->per_page, $pagination->offset);
      $data['sets_type'] = 'search_sets';
      $data['page_title'] = sprintf(Lang::get('title.list_sets_search'), $title);
      $data['pagination'] = $pagination->render();

      // Contenu de la page à retourner directement si c'est une requête en ajax
      // ou dans le template dans le cas contraire
      $content = View::forge('set/index', $data);

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
     * Show the page for a movies set
     *
     * @access  public
     * @return  Response
     */
    public function action_view($set_id = 0, $set_title = '')
    {
      $set = Model_SetView::read($set_id, true);

      $config = array(
        'pagination_url' => \Uri::create('set/'.$set_id.'-'.$set_title.'/movies/page'),
        'total_items' => count($set->movies),
        'per_page' => 5,
        'uri_segment' => 5,
        'num_links' => 3,
        'show_first' => true,
        'show_last' => true,
      );

      $pagination = Pagination::forge('movies-set-list', $config);

      $data['movies'] = Model_MovieView::read_all_by_set($set_id, $pagination->per_page, $pagination->offset);
      $data['movies_type'] = 'movies_set';
      $data['page_title'] = $set->title;
      $data['pagination'] = $pagination->render();

      $data['set'] = $set;

      $this->template->title = $set->title;
      $this->template->content = View::forge('movie/index', $data);
    }

}
