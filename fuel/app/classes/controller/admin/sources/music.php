<?php
class Controller_Admin_Sources_Music extends Controller_Admin
{

	public function action_index()
	{
    $data['server_paths'] = Model_MusicServerPath::find('all');
    $data['page_title'] = Lang::get('title.list_music_server_paths');
		$this->template->title = $data['page_title'];
		$this->template->content = View::forge('admin/sources/music/index', $data);
	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
      $val = Validation::forge('new_music_server_path')->add_model('Model_MusicServerPath');

			if ($val->run())
			{
        $path = new Model_MusicServerPath();
        $path->client_path = \Security::clean(\Input::post('client_path'), array('strip_tags', 'htmlentities'));
        $path->server_path = \Security::clean(\Input::post('server_path'), array('strip_tags', 'htmlentities'));

        $path->client_path .= (substr($path->client_path, -1, 1) != DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : '';
        $path->server_path .= (substr($path->server_path, -1, 1) != DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : '';

				if ($path->save() === false)
				{
					Session::set_flash('error', Lang::get('error.server_path_saved'));
				}
				else
				{
					Session::set_flash('success', Lang::get('success.server_path_saved'));
					Response::redirect('admin/sources/music');
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

    $data['page_title'] = Lang::get('title.create_server_path');
		$this->template->title = $data['page_title'];
		$this->template->content = View::forge('admin/sources/music/create', $data);
	}

	public function action_edit($id = null)
	{
		$path = Model_MusicServerPath::find($id);

		if (Input::method() == 'POST')
		{
      $val = Validation::forge('edit_music_server_path')->add_model('Model_MusicServerPath');

      if ($val->run())
      {
        $path->client_path = \Security::clean(\Input::post('client_path'), array('strip_tags', 'htmlentities'));
        $path->server_path = \Security::clean(\Input::post('server_path'), array('strip_tags', 'htmlentities'));

        $path->client_path .= (substr($path->client_path, -1, 1) != DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : '';
        $path->server_path .= (substr($path->server_path, -1, 1) != DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : '';

        if ($path->save())
        {
          Session::set_flash('success', $new_password_success.Lang::get('success.server_path_updated'));
          Response::redirect('admin/sources/music');
        }
        else
        {
          Session::set_flash('error', $new_password_error.Lang::get('error.server_path_updated'));
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

      Response::redirect('admin/sources/music');
		}

    $this->template->set_global('path', $path, false);

    $data['page_title'] = Lang::get('title.edit_serer_path');
		$this->template->title = $data['page_title'];
		$this->template->content = View::forge('admin/sources/music/edit', $data);
	}

	public function action_delete($id = null)
	{
		if ($path = Model_MusicServerPath::find($id))
		{
			$path->delete();
			Session::set_flash('success', Lang::get('success.server_path_deleted'));
		}
		else
		{
			Session::set_flash('error', Lang::get('error.server_path_deleted'));
		}

		Response::redirect('admin/sources/music');
	}

}
