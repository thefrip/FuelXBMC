<?php
class Controller_Admin_Movies extends Controller_Admin
{

	public function action_index()
	{
    $data['page_title'] = Lang::get('title.list_movies');
		$this->template->title = $data['page_title'];
		$this->template->content = View::forge('admin/movies/index', $data);
	}

}
