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
class Controller_Setup extends Controller_Template
{
    public function action_index()
    {
      $this->data['symbolic_link'] = DOCROOT.'assets'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'Thumbnails';

      // Error counter to prevent the next step
      $errors = (file_exists($this->data['symbolic_link'])) ? 0 : 1;

      $writable_paths = array(APPPATH.'cache', APPPATH.'logs', APPPATH.'tmp', APPPATH.'config');

      $paths = array();
      foreach ($writable_paths as $path)
      {
        if (substr(sprintf('%o', fileperms($path)), -4) == '0777')
        {
          $paths[] = array('path' => $path, 'status' => 'correct');
        }
        else
        {
          $paths[] = array('path' => $path, 'status' => 'incorrect');
          ++$errors;
        }
      }

      $this->data['paths'] = $paths;
      $this->data['no_error'] = ($errors == 0);

      $this->data['page_title'] = Lang::get('setup.index_title');
      $this->template->title = $this->data['page_title'];
      $this->template->content = View::forge('setup/index', $this->data);
    }

    public function action_check_db()
    {
      if (Input::is_ajax())
      {
        $host_ip = Xbmc::clean(\Input::json('host_ip'));
        $root_username = 'root';
        $root_password = Xbmc::clean(\Input::json('password'));

        try
        {
          $mysqli = new mysqli($host_ip, $root_username, $root_password);
        }
        catch (PhpErrorException $e)
        {
          $json = array('success' => '0',
                        'message' => '<span class="label label-important">'.Lang::get('setup.incorrect').'</span>'
                        );
          $json = json_encode($json);

          header('Content-type: application/json');
          echo $json;

          die();
        }

        // Save datas to session
        $settings = array('host_ip' => $host_ip,
                          'root_username' => $root_username,
                          'root_password' => $root_password,
                        );

        Session::set('settings', $settings);

        $json = array('success' => '1',
                      'message' => '<span class="label label-success">'.Lang::get('setup.correct').'</span>'
                      );
        $json = json_encode($json);

        header('Content-type: application/json');
        echo $json;

        die();
      }
    }

    public function action_mysql1()
    {
      $settings = array('host_ip' => '127.0.0.1',
                        'root_password' => '',
                      );

      Session::set('settings', $settings);

      $this->data['settings'] = $settings;

      $this->data['page_title'] = Lang::get('setup.mysql1_title');
      $this->template->title = $this->data['page_title'];
      $this->template->content = View::forge('setup/mysql1', $this->data);
    }

    public function action_mysql2()
    {
      $music_dbs = array();
      $video_dbs = array();

      $settings = Session::get('settings');

      $mysqli = new mysqli($settings['host_ip'], $settings['root_username'], $settings['root_password']);

      if ($result = $mysqli->query('SHOW DATABASES'))
      {
        while ($row = $result->fetch_assoc())
        {
          if (preg_match('/MyMusic(\d+)/', $row['Database'], $matches))
          {
            $music_dbs[$matches[1]] = $matches[0];
          }

          if (preg_match('/MyVideos(\d+)/', $row['Database'], $matches))
          {
            $video_dbs[$matches[1]] = $matches[0];
          }
        }
      }

      /* Libération des résultats */
      $result->free();
      $mysqli->close();

      $settings['music_db'] = $music_dbs[max(array_keys($music_dbs))];
      $settings['video_db'] = $video_dbs[max(array_keys($video_dbs))];
      $settings['xbmc_db'] = 'xbmc';
      $settings['username'] = 'xbmc';
      $settings['password'] = 'xbmc';

      Session::set('settings', $settings);

      unset($music_dbs, $video_dbs);

      $this->data['settings'] = $settings;

      if (Input::method() == 'POST')
      {
        $settings = Session::get('settings');

        $music_db = Xbmc::clean(\Input::post('music_db'));
        $video_db = Xbmc::clean(\Input::post('video_db'));
        $xbmc_db = Xbmc::clean(\Input::post('xbmc_db'));
        $username = Xbmc::clean(\Input::post('username'));
        $password = Xbmc::clean(\Input::post('password'));

        $mysqli = new mysqli($settings['host_ip'], $settings['root_username'], $settings['root_password']);

        // drop and create new database in force ;)
        $mysqli->query('DROP DATABASE '.$xbmc_db);
        $mysqli->query('CREATE DATABASE '.$xbmc_db);

        $mysqli->query("CREATE USER '$username'@'%' IDENTIFIED BY '$password';");
        $mysqli->query("GRANT ALL PRIVILEGES ON `MyMusic%` . * TO '$username'@'%'");
        $mysqli->query("GRANT ALL PRIVILEGES ON `MyVideos%` . * TO '$username'@'%'");
        $mysqli->query("GRANT ALL PRIVILEGES ON `$xbmc_db%` . * TO '$username'@'%'");

        $mysqli->close();

        $filename = APPPATH.'views/setup/_db.php';

        // Load the config file template
        $handle = fopen($filename, 'r');
        $contents = fread($handle, filesize($filename));
        fclose($handle);

        // Change the databases informations in configuration file
        $contents = str_replace('HOST_IP', str_replace('localhost', '127.0.0.1', strtolower($settings['host_ip'])), $contents);
        $contents = str_replace('USERNAME', $username, $contents);
        $contents = str_replace('PASSWORD', $password, $contents);
        $contents = str_replace('MUSIC_DB', $music_db, $contents);
        $contents = str_replace('VIDEO_DB', $video_db, $contents);
        $contents = str_replace('XBMC_DB', $xbmc_db, $contents);

        // Save the config file
        $filename = APPPATH.'config/db.php';

        // Save the config file
        $handle = fopen($filename, 'w');
        fwrite($handle, $contents);
        fclose($handle);

        // Save the name of the new xbmc db
        Session::set('xbmc_db', \Input::post('xbmc_db'));

        Response::redirect('setup/last');
      }

      $this->data['page_title'] = Lang::get('setup.mysql2_title');
      $this->template->title = $this->data['page_title'];
      $this->template->content = View::forge('setup/mysql2', $this->data);
    }

    public function action_last()
    {
      Setup::create_table_users();
      Setup::create_table_certifications();
      Setup::create_table_music_server_paths();
      Setup::create_table_video_server_paths();

      $message = Lang::get('setup.database_created').'<br >';
      $message .= Lang::get('setup.user_created').'<br >';
      $message .= Lang::get('setup.table_certification_created').'<br >';
      $message .= Lang::get('setup.table_source_created').'<br >';

      Session::set_flash('success', $message);
      Response::redirect('/');
    }

    public function action_settings()
    {
      Session::delete('settings');

      // On first login in redirect to admin dashboard
      Session::set('destination', 'admin/setup');

      $this->data['page_title'] = Lang::get('setup.public_title');
      $this->template->title = $this->data['page_title'];
      $this->template->content = View::forge('setup/settings', $this->data);
    }

    public function before()
    {
      parent::before();

      $this->data = array();

      // The languages list for availables languages
      $availables_languages = array();

      // Search sub-folders in lang folder for availables languages
      foreach (glob(APPPATH.'lang/*', GLOB_ONLYDIR) as $language)
      {
          $availables_languages[] = substr(strrchr($language, DIRECTORY_SEPARATOR), 1);
      }

      $languages = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);

      foreach($languages as $lang)
      {
        if (in_array($lang, $availables_languages))
        {
          // Set the page locale to the first supported language found
          \Config::set('language', $lang);
          break;
        }
      }

      // Load the language file 'first_launch' to guide the user to set the application
      \Lang::load('setup', true);
      \Lang::load('global', true);
      \Lang::load('user', true);

      // Set a global variable so views can use it
      View::set_global('current_user', null);
    }

}
