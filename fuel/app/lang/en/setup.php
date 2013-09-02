<?php

return array(

    // Misc
    'or'  => 'or',
    'warning' => 'Warning:',
    'correct' => 'Correct',
    'incorrect' => 'Incorrect',
    'host_ip' => 'Host IP address:',
    'username' => 'Username:',
    'password' => 'Password:',
    'music_db' => 'Database for music:',
    'video_db' => 'Database of videos:',
    'xbmc_db' => 'Additional database:',
    'loading' => 'Loading...',
    'existing' => 'existing',
    'missing' => 'missing',
    'welcome' => 'Welcome',

    // Folder permissions (index)
    'index_title' => 'Checks about folders',
    'index_symbolic_info' => 'The application uses a symbolic link to the folder where the images of XBMC are stored (called \'TARGET\' in the following).<br />If the symbolic link \'Thumbnails\' is missing, please run the following commands:',
    'index_cmd_symbolic'  => 'user@host:~$ cd '.DOCROOT.'assets'.DIRECTORY_SEPARATOR.'images<br />user@host:~'.DOCROOT.'assets'.DIRECTORY_SEPARATOR.'images$ ln -s TARGET Thumbnails',
    'index_symbolic_check' => 'The symbolic link must be:',
    'index_info'  => 'Check write permissions on the following folders:',
    'index_writable'  => 'If the permissions on any of the above folders are incorrect, please run the following commands:',
    'index_cmd_writable'  => 'user@host:~$ cd '.APPPATH.'<br />user@host:~'.APPPATH.'$ chmod 777 Name_of_the_folder_concerned',

    // Mysql
    'mysql1_title'  => 'Settings of the database',
    'mysql1_info'  => 'Please complete the following informations:',
    'mysql2_title'  => 'Settings of the database (for verification)',
    'mysql2_info'  => 'Please check and complete the following informations:',
    'xbmc_db_help'  => 'It will contain, into something else, the users of the site and will be DESTROYED if it exists.',
    'root_username_help' => 'Password for the user <b>ROOT</b> is required.',
    'xbmc_password_help' => 'Now, password for the user <b>XBMC</b> is required.',
    'admin_email' => 'admin@example.com',

    // Last step
    'database_created' => 'The new database was created successfully.',
    'table_user_created' => 'The users table was created successfully.',
    'table_certification_created' => 'The certifications table was created successfully.',
    'table_source_created' => 'The audio et video sources tables have been created successfully.',
    'user_created' => 'The user account was created successfully.',

    // Settings
    'settings_info' => 'Now the application is properly configured at the database<br /><br />You can connect with the following identifiers:',

    // Buttons
    'btn_this_step'  => 'Recheck this step',
    'btn_next_step'  => 'Take the next step',
    'btn_settings' => 'Change Settings',
    'btn_check_db' => 'Test Connection',

    // Admin zone
    'admin_title' => 'A few tips ...',
    'admin_info' => 'Now think to change your password in the menu <b>%users%</b>.',
    'admin_sources' => 'Similarly, consider parameterize the sources of your videos in the menu <b>%sources%</b>.',
    'admin_home' => 'But first, please follow the link on the button below.',
    'admin_settings' => 'Change Settings',
);
