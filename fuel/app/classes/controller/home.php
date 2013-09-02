<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.6
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * The Home Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Home extends Controller_Base
{

    /**
     * The basic welcome message
     *
     * @access  public
     * @return  Response
     */
    public function action_index()
    {
        if (!$this->no_settings)
        {
          $this->data['last_albums'] = '';
          $this->data['last_movies'] = '';
          $this->data['last_tvshows'] = '';
          $this->data['last_episodes'] = '';

          if (Config::get('settings.manage_music'))
          {
            $sub_view = array();
            $sub_view['albums'] = Model_AlbumView::read_last(Config::get('settings.last_albums'));
            $sub_view['albums_type'] = 'last_albums';
            $sub_view['pagination'] = '';
            $sub_view['page_title'] = '';

            $this->data['last_albums'] = View::forge('album/index', $sub_view);
          }

          if (Config::get('settings.manage_movies'))
          {
            $sub_view = array();
            $sub_view['movies'] = Model_MovieView::read_last(Config::get('settings.last_movies'));
            $sub_view['movies_type'] = 'last_movies';
            $sub_view['pagination'] = '';
            $sub_view['page_title'] = '';

            $this->data['last_movies'] = View::forge('movie/index', $sub_view);
          }


          if (Config::get('settings.manage_tvshows'))
          {
            $sub_view = array();
            $sub_view['tvshows'] = Model_TvshowView::read_last(Config::get('settings.last_tvshows'));
            $sub_view['tvshows_type'] = 'last_tvshows';
            $sub_view['pagination'] = '';
            $sub_view['page_title'] = '';

            $this->data['last_tvshows'] = View::forge('tvshow/index', $sub_view);

            $sub_view = array();
            $sub_view['episodes'] = Model_EpisodeView::read_last(Config::get('settings.last_episodes'));
            $sub_view['episodes_type'] = 'last_episodes';
            $sub_view['pagination'] = '';
            $sub_view['page_title'] = '';

            $this->data['last_episodes'] = View::forge('episode/index', $sub_view);
          }

          $this->data['page_title'] = Lang::get('title.home');
          $this->template->title = $this->data['page_title'];
          $this->template->content = View::forge('home', $this->data);
        }
        else
        {
          // Load the language file 'setup' to guide the user to set the application
          \Lang::load('setup', true);

          // On first login in redirect to admin dashboard
          Session::set('destination', 'admin');

          $this->data['page_title'] = Lang::get('setup.welcome');
          $this->template->title = $this->data['page_title'];
          $this->template->content = View::forge('setup/settings', $this->data);
        }
    }

    /**
     * The 404 action for the application.
     *
     * @access  public
     * @return  Response
     */
    public function action_404()
    {
      $this->data['error404'] = true;
      $this->data['title'] = 'Error';
      $this->template->title = $this->data['title'];
      $this->template->content = View::forge('partials/404', $this->data);
    }

}
