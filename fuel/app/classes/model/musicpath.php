<?php

class Model_MusicPath extends Orm\Model
{
    protected static $_connection = 'music';
    protected static $_table_name = 'path';
    protected static $_primary_key = array('idPath');
    protected static $_properties = array(
        'idPath',
        'strPath' => array(
            'data_type' => 'text',
        ),
    );

}
