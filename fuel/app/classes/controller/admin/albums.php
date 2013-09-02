<?php
class Controller_Admin_Albums extends Controller_Admin
{

	public function action_index()
	{
    $data['page_title'] = Lang::get('title.list_albums');
		$this->template->title = $data['page_title'];
		$this->template->content = View::forge('admin/album/index', $data);
	}

}
