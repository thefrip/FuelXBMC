<?php
class Controller_Admin_Sources_Video extends Controller_Admin
{

	public function action_index()
	{
    // Try to find server paths
    $server_paths = Model_VideoServerPath::read_all();
    if (count($server_paths) == 0)
    {
      // If fail get them from XBMC sources
      foreach(Model_VideoPath::get_server_paths() as $server_path)
      {
        $new_server_path = new Model_VideoServerPath();

        // Each properties from server_paths (stdClass) passed to server_paths (Orm\Model)
        $new_server_path->idPath = $server_path->idPath;
        $new_server_path->server_path = $server_path->server_path;
        // To database
        $new_server_path->save();
      }

      Session::set_flash('success', Lang::get('success.video_server_paths_from_xbmc'));

      // Re-read new added entrie from database
      $server_paths = Model_VideoServerPath::read_all();
    }

    $data['server_paths'] = $server_paths;
    $data['page_title'] = Lang::get('title.list_video_server_paths');
		$this->template->title = $data['page_title'];
		$this->template->content = View::forge('admin/sources/video/index', $data);
	}

	public function action_edit($id = null)
	{
    $path = Model_VideoServerPath::read($id);

		if (Input::method() == 'POST')
		{

      $val = Validation::forge('edit_video_server_paths');
      $val->add_field('server_path', Lang::get('label.server_path'), 'required');

      if ($val->run())
      {
        $path = Model_VideoServerPath::find($id);
        $path->server_path = \Security::clean(\Input::post('server_path'), array('strip_tags', 'htmlentities'));
        $path->server_path .= (substr($path->server_path, -1, 1) != DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : '';

        if ($path->save())
        {
          Session::set_flash('success', Lang::get('success.server_path_updated'));

          Response::redirect('admin/sources/video');
        }
        else
        {
          Session::set_flash('error', Lang::get('error.server_path_updated'));
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

      Response::redirect('admin/sources/video');
		}

    $this->template->set_global('path', $path, false);

    $data['page_title'] = Lang::get('title.edit_server_path');
		$this->template->title = $data['page_title'];
		$this->template->content = View::forge('admin/sources/video/edit', $data);

	}

}
