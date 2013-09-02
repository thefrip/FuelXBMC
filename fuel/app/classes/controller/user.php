<?php

class Controller_User extends Controller_Base
{

	public function action_profile()
	{
    $data['page_title'] = Lang::get('user.profile');
    $this->template->title = Lang::get('title.profile');
		$this->template->content = View::forge('user/profile', $data);
	}

	public function action_edit()
	{
    $user = Model_User::find($this->current_user->id);

		if (Input::method() == 'POST')
		{

      $val = Validation::forge('edit_profile');
      $val->add_field('email', Lang::get('label.email'), 'required|valid_email');

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
          if (Auth::change_password($password, $new_password))
          {
            $new_password_success = Lang::get('success.password_updated').'<br />';
          }
          else
          {
            $new_password_error = Lang::get('error.password_updated').'<br />';
          }
        }

        $user->email = \Security::clean(\Input::post('email'), array('strip_tags', 'htmlentities'));

        if ($user->save())
        {
          Session::set_flash('success', $new_password_success.Lang::get('success.user_updated'));

          Response::redirect('profile');
        }
        else
        {
          Session::set_flash('error', $new_password_error.Lang::get('error.user_updated'));
          Response::redirect('profile');
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

      Response::redirect('profile');
		}

    $this->current_user->email = $user->email;

    $data['page_title'] = Lang::get('user.edit_profile');
    $this->template->title = Lang::get('title.profile');
		$this->template->content = View::forge('user/edit', $data);
	}

}
