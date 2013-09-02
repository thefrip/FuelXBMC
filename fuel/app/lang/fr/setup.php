<?php

return array(

    // Misc
    'or'  => 'ou',
    'warning' => 'Attention :',
    'correct' => 'Correct',
    'incorrect' => 'Incorrect',
    'host_ip' => 'Adresse du serveur :',
    'username' => 'Nom d\'utilisateur :',
    'password' => 'Mot de passe :',
    'music_db' => 'Base de données de la musique :',
    'video_db' => 'Base de données des vidéos :',
    'xbmc_db' => 'Base de données supplémentaire :',
    'loading' => 'Chargement...',
    'existing' => 'Présent',
    'missing' => 'Manquant',
    'welcome' => 'Bienvenue',

    // Folder permissions (index)
    'index_title' => 'Vérifications sur les dossiers',
    'index_symbolic_info' => 'L\'application utilise un lien symbolique vers le dossier de stockage des images de XBMC (noté \'TARGET\' par la suite).<br />Si ce lien symbolique \'Thumbnails\' est manquant, veuillez exécuter les commandes suivantes :',
    'index_cmd_symbolic'  => 'user@host:~$ cd '.DOCROOT.'assets'.DIRECTORY_SEPARATOR.'images<br />user@host:~'.DOCROOT.'assets'.DIRECTORY_SEPARATOR.'images$ ln -s TARGET Thumbnails',
    'index_symbolic_check' => 'Le lien symbolique doit donc être :',
    'index_info'  => 'Vérifiez les permissions d\'écriture sur les dossiers suivants :',
    'index_writable'  => 'Si les permissions sur l\'un des dossiers précédents sont incorrectes, veuillez exécuter les commandes suivantes :',
    'index_cmd_writable'  => 'user@host:~$ cd '.APPPATH.'<br />user@host:~'.APPPATH.'$ chmod 777 Nom_du_dossier_concerné',

    // Mysql
    'mysql1_title'  => 'Paramètres de la base de données',
    'mysql1_info'  => 'Veuillez compléter les informations suivantes :',
    'mysql2_title'  => 'Paramètres de la base de données (vérification)',
    'mysql2_info'  => 'Veuillez vérifier et compléter les informations suivantes :',
    'xbmc_db_help'  => 'Elle contiendra, en autre chose, les utilisateurs du site et sera DÉTRUITE si elle existe.',
    'root_password_help'  => 'Le mot de passe de l\'utilisateur <b>ROOT</b> est requis.',
    'xbmc_password_help'  => 'Maintemant, le mot de passe de l\'utilisateur <b>XBMC</b> est requis.',
    'admin_email' => 'admin@exemple.fr',

    // Last step
    'database_created' => 'La nouvelle base de données a été créée avec succès.',
    'table_user_created' => 'La table des utilisateurs a été créée avec succès.',
    'table_certification_created' => 'La table des certifications a été créée avec succès.',
    'table_source_created' => 'Les tables des sources audio et vidéo ont été créées avec succès.',
    'user_created' => 'Le compte utilisateur a été enregistré avec succès.',

    // Settings
    'settings_info' => 'Maintenant l\'application est correctement configurée au niveau des bases de données.<br /><br />Vous pouvez vous connecter avec les identifiants suivants :',

    // Buttons
    'btn_this_step'  => 'Revérifiez cette étape',
    'btn_next_step'  => 'Passez à l\'étape suivante',
    'btn_settings' => 'Modifier les paramètres',
    'btn_check_db' => 'Tester la connexion',

    // Admin zone
    'admin_title' => 'Quelques conseils...',
    'admin_info' => 'Maintenant, pensez à changer vos identifiants dans le menu <b>%users%</b>.',
    'admin_sources' => 'De même, pensez à paramètrer les sources de vos vidéos dans le menu <b>%sources%</b>.',
    'admin_home' => 'Mais tout d\'abord, veuillez suivre le lien sur le bouton ci-dessous.',
    'admin_settings' => 'Modifiez les paramètres',
);
