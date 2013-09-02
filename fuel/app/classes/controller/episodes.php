<?php

class Controller_Episodes extends Controller_Base
{
    /**
     * Show the page for an episode from a TV show
     *
     * @access  public
     * @return  Response
     */
    public function action_view($episode_id = 0, $title = '')
    {
      $episode = Model_EpisodeView::read($episode_id, true);

      $data['episode'] = $episode;

      $episode_number = str_replace('%s', sprintf("%02s", $episode->season_number), Lang::get('media.number_format'));
      $episode_number = str_replace('%e', sprintf("%02s", $episode->episode_number), $episode_number);

      $data['page_title'] = $episode_number.$episode->title;

      $this->template->title = $data['page_title'];
      $this->template->content = View::forge('episode/view', $data);
    }

    /**
     * Show the episodes list written by a person
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
          'pagination_url' => \Uri::create('episodes/written/'.$person->id.'-'.Inflector::friendly_title($person->name, '-').'/page'),
          'total_items' => Model_EpisodeView::count_by_writer($person_id),
          'per_page' => 6,
          'uri_segment' => 5,
          'num_links' => 3,
          'show_first' => true,
          'show_last' => true,
        );

        $pagination_episodes_written = Pagination::forge('episodes-written-list', $config);

        $data['episodes'] = Model_EpisodeView::read_all_by_writer($person_id, $pagination_episodes_written->per_page, $pagination_episodes_written->offset);
        $data['episodes_type'] = 'episodes_written';
        $data['pagination'] = $pagination_episodes_written->render();

        echo View::forge('episode/index', $data);
      }
      die();
    }

    /**
     * Show the episodes list directed by a person
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
          'pagination_url' => \Uri::create('episodes/directed/'.$person->id.'-'.Inflector::friendly_title($person->name, '-').'/page'),
          'total_items' => Model_EpisodeView::count_by_director($person_id),
          'per_page' => 6,
          'uri_segment' => 5,
          'num_links' => 3,
          'show_first' => true,
          'show_last' => true,
        );

        $pagination_episodes_directed = Pagination::forge('episodes-directed-list', $config);

        $data['episodes'] = Model_EpisodeView::read_all_by_director($person_id, $pagination_episodes_directed->per_page, $pagination_episodes_directed->offset);
        $data['episodes_type'] = 'episodes_directed';
        $data['pagination'] = $pagination_episodes_directed->render();

        echo View::forge('episode/index', $data);
      }
      die();
    }

    /**
     * Show the episodes list played by a person
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
          'pagination_url' => \Uri::create('episodes/played/'.$person->id.'-'.Inflector::friendly_title($person->name, '-').'/page'),
          'total_items' => Model_EpisodeView::count_by_actor($person_id),
          'per_page' => 6,
          'uri_segment' => 5,
          'num_links' => 3,
          'show_first' => true,
          'show_last' => true,
        );

        $pagination_episodes_played = Pagination::forge('episodes-played-list', $config);

        $data['episodes'] = Model_EpisodeView::read_all_by_actor($person_id, $pagination_episodes_played->per_page, $pagination_episodes_played->offset);
        $data['episodes_type'] = 'episodes_played';
        $data['pagination'] = $pagination_episodes_played->render();

        echo View::forge('episode/index', $data);
      }
      die();
    }

}
