<?php
class Controller_Admin_Settings extends Controller_Admin
{

	public function action_index()
	{
    $data = array();

    // List of languages in their native language
    Config::load('languages', true);
    $all_languages = Config::get('languages');

    // The languages list for availables languages
    $languages = array();

    // Search sub-folders in lang folder for availables languages
    foreach (glob(APPPATH.'lang/*', GLOB_ONLYDIR) as $language)
    {
        $iso = substr(strrchr($language, DIRECTORY_SEPARATOR), 1);
        $languages[$iso] = $all_languages[$iso];
    }

    // If there is only one language, no need to select another one
    if (count($languages) == 1)
    {
        $data['languages'] = null;
    }
    else
    {
        $data['languages'] = $languages;
    }

    // Load settings config file in one group
    Config::load('settings', true);

    // Use only this group in the view
    $settings = Config::get('settings');

    // First launch, so prepare defaults settings
    if ($this->no_settings)
    {
      $settings = array('private_site' => true,
                        'tvshow_poster' => 'banner',
                        'manage_music' => true,
                        'manage_movies' => true,
                        'manage_sets' => true,
                        'manage_tvshows' => true,
                        'last_albums' => 5,
                        'last_movies' => 5,
                        'last_episodes' => 5,
                        'last_tvshows' => 6,
                      );
    }

    $data['settings'] = $settings;

		if (Input::method() == 'POST')
		{
      // If language is selected
      if (Input::post('language'))
      {
        // If we have selected another langage than the default langage
        if (Config::get('language') != Input::post('language'))
        {
          $filename = APPPATH.'config/config.php';

          // Load the config file
          $handle = fopen($filename, 'r');
          $contents = fread($handle, filesize($filename));
          fclose($handle);

          // Change the language for the application in configuration file
          $contents = str_replace("'language' => '".Config::get('language')."',", "'language' => '".Input::post('language')."',", $contents);

          // Save the config file
          $handle = fopen($filename, 'w');
          fwrite($handle, $contents);
          fclose($handle);

          // Change the language for the application
          Config::set('language', Input::post('language'));
        }

      }

      $settings['private_site'] = (Input::post('private_site') == '1');
      $settings['tvshow_poster'] = Input::post('tvshow_poster');

      $settings['manage_music'] = (Input::post('manage_music') == '1');
      $settings['manage_movies'] = (Input::post('manage_movies') == '1');
      $settings['manage_sets'] = (Input::post('manage_sets') == '1');
      if (!$settings['manage_movies']) $settings['manage_sets'] = false;
      $settings['manage_tvshows'] = (Input::post('manage_tvshows') == '1');

      // Control user entries is zero
      $settings['last_albums'] = (Input::post('last_albums') > 0) ? (int) Input::post('last_albums') : 5;
      $settings['last_movies'] = (Input::post('last_movies') > 0) ? (int) Input::post('last_movies') : 5;
      $settings['last_episodes'] = (Input::post('last_episodes') > 0) ? (int) Input::post('last_episodes') : 5;
      $settings['last_tvshows'] = (Input::post('last_tvshows') > 0) ? (int) Input::post('last_tvshows') : 6;

      // Force number of TV Shows to a multile of 3
      $settings['last_tvshows'] = (($settings['last_tvshows'] % 3)) ? (3 * ($settings['last_tvshows'] % 3)) : $settings['last_tvshows'];


      // save the updated config group 'settings'
      Config::save('settings', $settings);

      Session::set_flash('success', Lang::get('success.settings_updated'));

      Response::redirect('admin');

    }

    $data['page_title'] = Lang::get('title.settings');
		$this->template->title = $data['page_title'];
		$this->template->content = View::forge('admin/setting/index', $data);
	}

}
