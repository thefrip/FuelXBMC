<?php

class Controller_Auth extends Controller_Template
{

	public function action_login()
	{
		// Already logged in
		if (Auth::check())
    {
      if (Session::get('destination'))
      {
        $destination = Session::get('destination');
        Session::delete('destination');
      }
      else
      {
        $destination = '/';
      }
      Response::redirect($destination);
    }

		\Lang::load('global', true);
		\Lang::load('title', true);
    \Lang::load('label', true);

		$val = Validation::forge();

		if (Input::method() == 'POST')
		{
      $val->add_field('username', 'Username', 'required|trim');
      $val->add_field('password', 'Password', 'required');

			if ($val->run())
			{
				$auth = Auth::instance();

				// check the credentials. This assumes that you have the previous table created
				if (Auth::check() or $auth->login(Input::post('username'), Input::post('password')))
				{
          // did the user want to be remembered?
          if (\Input::param('remember', false))
          {
            // create the remember-me cookie
            \Auth::remember_me();
          }
          else
          {
            // delete the remember-me cookie if present
            \Auth::dont_remember_me();
          }

          $current_user = Model_User::find_by_username(Auth::get_screen_name());

          if (Session::get('destination'))
          {
            $destination = Session::get('destination');
            Session::delete('destination');
          }
          else
          {
            $destination = '/';
          }

					// credentials ok, go right in
          Session::set_flash('success', sprintf(Lang::get('global.welcome'), $current_user->username));
					Response::redirect($destination);
				}
				else
				{
					$this->template->set_global('incorrect_login', Lang::get('error.incorrect_login'));
				}

			}
		}

    $data['page_title'] = Lang::get('title.login');
    $this->template->title = $data['page_title'];
		$this->template->content = View::forge('partials/login', array('val' => $val));
	}

	/**
	 * The logout action.
	 *
	 * @access  public
	 * @return  void
	 */
	public function action_logout()
	{
    // remove the remember-me cookie, we logged-out on purpose
    \Auth::dont_remember_me();

    // logout
    \Auth::logout();

		Response::redirect('/');
	}

}
