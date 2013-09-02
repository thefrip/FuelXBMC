<?php
class Controller_Admin_Tvshows extends Controller_Admin
{

	public function action_index()
	{
    $data['page_title'] = Lang::get('title.list_tvshows');
		$this->template->title = $data['page_title'];
		$this->template->content = View::forge('admin/tvshows/index', $data);
	}

}
