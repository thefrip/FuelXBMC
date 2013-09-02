<?php
class Controller_Admin_Sets extends Controller_Admin
{

	public function action_index()
	{
    $data['page_title'] = Lang::get('title.list_sets');
		$this->template->title = $data['page_title'];
		$this->template->content = View::forge('admin/sets/index', $data);
	}

}
