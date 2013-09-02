<?php

class Controller_Admin extends Controller_Base
{
	public $template = 'admin/template';

	public function before()
	{
		parent::before();

		\Lang::load('table', true);
		\Lang::load('setting', true);
		\Lang::load('server_path', true);

		if (Request::active()->controller !== 'Controller_Admin' or ! in_array(Request::active()->action, array('login', 'logout')))
		{
			if (Auth::check())
			{
				if ( ! Auth::member(100))
				{
					Session::set_flash('error', Lang::get('error.no_access'));
					Response::redirect('/');
				}
			}
			else
			{
        Session::set('destination', 'admin');
				Response::redirect('login');
			}
		}
	}

	/**
	 * The index action.
	 *
	 * @access  public
	 * @return  void
	 */
	public function action_index()
	{
    if (!$this->no_settings)
    {
      $data['page_title'] = Lang::get('title.dashboard');
      $this->template->title = $data['page_title'];
      $this->template->content = View::forge('admin/dashboard', $data);
    }
    else
    {
      // Load the language file 'first_launch' to guide the user to set the application
      \Lang::load('setup', true);

      $this->data['page_title'] = Lang::get('setup.admin_title');
      $this->template->title = $this->data['page_title'];
      $this->template->content = View::forge('setup/admin', $this->data);
    }
	}

}
