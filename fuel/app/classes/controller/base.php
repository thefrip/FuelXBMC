<?php

class Controller_Base extends Controller_Template {

  public $data = array();
  public $no_settings = false;

	public function before()
	{
		parent::before();

    // Load language files
		\Lang::load('global', true);
		\Lang::load('label', true);
		\Lang::load('title', true);
		\Lang::load('user', true);
		\Lang::load('error', true);
		\Lang::load('success', true);
		\Lang::load('navigation', true);
		\Lang::load('media', true);

    // Check if db config db file is available
    // For the first launch, this file is missing so let set up the db for the application
    if (!file_exists(APPPATH.'config'.DIRECTORY_SEPARATOR.'db.php'))
    {
      Response::redirect('setup');
    }

    // Load config file if available
    // For the first launch, there is no settings
    // Let's configure the application by inviting the user to do this
    $this->no_settings = (count(\Config::load('settings', true)) == 0);

    if (Auth::check() or !Config::get('settings.private_site'))
    {
      // Assign current_user to the instance so controllers can use it
      $this->current_user = Auth::check() ? Model_User::find_by_username(Auth::get_screen_name()) : null;

      // Set a global variable so views can use it
      View::set_global('current_user', $this->current_user);
    }
    else
    {
      Response::redirect('login');
    }
	}

}
