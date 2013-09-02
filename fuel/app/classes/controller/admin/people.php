<?php
class Controller_Admin_People extends Controller_Admin
{

	public function action_index()
	{
    $data['page_title'] = Lang::get('title.list_people');
		$this->template->title = $data['page_title'];
		$this->template->content = View::forge('admin/person/index', $data);
	}

}
