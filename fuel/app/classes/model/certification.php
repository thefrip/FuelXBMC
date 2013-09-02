<?php
use Orm\Model;

class Model_Certification extends Model
{
  protected static $_connection = null;
  protected static $_table_name = 'certifications';
  protected static $_primary_key = array('id');

	protected static $_properties = array(
		'id',
		'name',
		'rating',
	);

}
