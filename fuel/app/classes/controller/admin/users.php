<?php
class Controller_Admin_Users extends Controller_Admin
{

	public function action_index()
	{
		$data['users'] = Model_User::find('all');
    $data['page_title'] = Lang::get('title.list_users');
		$this->template->title = $data['page_title'];
		$this->template->content = View::forge('admin/users/index', $data);
	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
      $val = Validation::forge('new_user')->add_model('Model_User');

			if ($val->run())
			{
        $new_user = Auth::create_user(\Security::clean(\Input::post('username'), array('strip_tags', 'htmlentities')),
                                      \Security::clean(\Input::post('password'), array('strip_tags', 'htmlentities')),
                                      \Security::clean(\Input::post('email'), array('strip_tags', 'htmlentities')),
                                      (int) \Input::post('group'));

				if ($new_user === false)
				{
					Session::set_flash('error', Lang::get('error.user_saved'));
				}
				else
				{
					Session::set_flash('success', Lang::get('success.user_saved'));
					Response::redirect('admin/users');
				}
			}
			else
			{
        $error_fields = array();
        foreach(array_keys($val->error()) as $field)
        {
          $error_fields[] = $val->error($field)->get_message();
        }

        // Get all messages for fields with error
        $error = implode('<br />', $error_fields);

        Session::set_flash('error', $error);
			}
		}

    $data['page_title'] = Lang::get('title.create_user');
		$this->template->title = $data['page_title'];
		$this->template->content = View::forge('admin/users/create', $data);
	}

	public function action_edit($id = null)
	{
		$user = Model_User::find($id);

		if (Input::method() == 'POST')
		{
      $val = Validation::forge('edit_user');
      $val->add_field('username', Lang::get('label.username'), 'required|max_length[255]');
      $val->add_field('email', Lang::get('label.email'), 'required|valid_email');
      $val->add_field('group', Lang::get('label.group'), 'required|is_numeric');

      $password = \Security::clean(\Input::post('password'), array('strip_tags', 'htmlentities'));
      $new_password = \Security::clean(\Input::post('new_password'), array('strip_tags', 'htmlentities'));

      // User want to change the actual password and enter a new one?
      if (!empty($new_password))
      {
        // New password validation rule require actual password
        $val->add('new_password', Lang::get('label.new_password'))
            ->add_rule('min_length', 8);

        $val->add('password', Lang::get('label.password'))
            ->add_rule('required_with', 'new_password');
      }

      if ($val->run())
      {
        $new_password_success = '';
        $new_password_error = '';

        // Manage a new password?
        if (!empty($new_password))
        {
          if (Auth::change_password($password, $new_password, $user->username))
          {
            $new_password_success = Lang::get('success.password_updated').'<br />';
          }
          else
          {
            $new_password_error = Lang::get('error.password_updated').'<br />';
          }
        }

        $user->username = \Security::clean(\Input::post('username'), array('strip_tags', 'htmlentities'));
        $user->email = \Security::clean(\Input::post('email'), array('strip_tags', 'htmlentities'));
        $user->group = (int) \Input::post('group');

        if ($user->save())
        {
          Session::set_flash('success', $new_password_success.Lang::get('success.user_updated'));
          Response::redirect('admin/users');
        }
        else
        {
          Session::set_flash('error', $new_password_error.Lang::get('error.user_updated'));
          Response::redirect(Uri::current());
        }
      }
      else
      {
        $error_fields = array();
        foreach(array_keys($val->error()) as $field)
        {
          $error_fields[] = $val->error($field)->get_message();
        }

        // Get all messages for fields with error
        $error = implode('<br />', $error_fields);

				Session::set_flash('error', $error);
        Response::redirect(Uri::current());
			}

      Response::redirect('admin/users');
		}

    $this->template->set_global('user', $user, false);

    $data['page_title'] = Lang::get('title.edit_user');
		$this->template->title = $data['page_title'];
		$this->template->content = View::forge('admin/users/edit', $data);
	}

	public function action_delete($id = null)
	{
		if ($user = Model_User::find($id))
		{
			$user->delete();
			Session::set_flash('success', Lang::get('success.user_deleted'));
		}
		else
		{
			Session::set_flash('error', Lang::get('error.user_deleted'));
		}

		Response::redirect('admin/users');
	}

}
