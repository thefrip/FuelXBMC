<?php
class Controller_Admin_Artists extends Controller_Admin
{

	public function action_index()
	{
    $data['page_title'] = Lang::get('title.list_artists');
		$this->template->title = $data['page_title'];
		$this->template->content = View::forge('admin/artist/index', $data);
	}

}
