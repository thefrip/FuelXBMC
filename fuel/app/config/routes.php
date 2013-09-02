<?php
return array(
	'_root_'  => 'home/index',  // The default route
	'_404_'   => 'home/404',    // The main 404 route

  'data/infos/(movie|tvshow)_(\d+)' => 'data/infos/$1_$2',

  // Security token provided in url
  'media/change_image/([0-9a-z]{128})' => 'media/change_image/$1',

  // Anti hot link and management images
  'media/(:any)' => 'media/index',

  // Albums list
  'albums' => 'albums/index',
  'albums/page/:id' => 'albums/index',
  'albums/year/([0-9]{4})(/page/)?(\d+)?' => 'albums/year/$1/$3',
  'albums/genre/(\d+)\-([\-a-zA-Z]+)(/page/)?(\d+)?' => 'albums/genre/$1/$2/$4/$3',

  // Albums search
  'albums/pre_search' => 'albums/pre_search',
  'albums/search/(:any)' => 'albums/search/$1',

  // Albums list for an artist
  'albums/played/(\d+)\-([\-a-zA-Z]+)(/page/)?(\d+)?' => 'albums/played/$1/$2/$4/$3',

  // Album view
  'album/(\d+)\-([\-a-zA-Z0-9]+)' => 'albums/view/$1/$2',

  // Artist list
  'artists' => 'artists/index',
  'artists/page/:id' => 'artists/index',

  // Artists search
  'artists/pre_search' => 'artists/pre_search',
  'artists/search/(:any)' => 'artists/search/$1',

  // Artist view
  'artist/(\d+)\-([\-a-zA-Z0-9]+)' => 'artists/view/$1/$2',
  'artist/(\d+)\-' => 'artists/view/$1',

  // Movies list
  'movies' => 'movies/index',
  'movies/page/:id' => 'movies/index',
  'movies/year/([0-9]{4})(/page/)?(\d+)?' => 'movies/year/$1/$3',
  'movies/genre/(\d+)\-([\-a-zA-Z]+)(/page/)?(\d+)?' => 'movies/genre/$1/$2/$4/$3',
  'movies/studio/(\d+)\-([\-a-zA-Z]+)(/page/)?(\d+)?' => 'movies/studio/$1/$2/$4/$3',
  'movies/country/(\d+)\-([\-a-zA-Z]+)(/page/)?(\d+)?' => 'movies/country/$1/$2/$4/$3',

  // Movies search
  'movies/pre_search' => 'movies/pre_search',
  'movies/search/(:any)' => 'movies/search/$1',

  // Movies list for a person
  'movies/written/(\d+)\-([\-a-zA-Z]+)(/page/)?(\d+)?' => 'movies/played/$1/$2/$4/$3',
  'movies/directed/(\d+)\-([\-a-zA-Z]+)(/page/)?(\d+)?' => 'movies/played/$1/$2/$4/$3',
  'movies/played/(\d+)\-([\-a-zA-Z]+)(/page/)?(\d+)?' => 'movies/played/$1/$2/$4/$3',

  // Movie view
  'movie/(\d+)\-([\-a-zA-Z0-9]+)' => 'movies/view/$1/$2',

  // TV Shows list
  'tvshows' => 'tvshows/index',
  'tvshows/page/:id' => 'tvshows/index',
  'tvshows/year/([0-9]{4})(/page/)?(\d+)?' => 'tvshows/year/$1/$3',
  'tvshows/genre/(\d+)\-([\-a-zA-Z]+)(/page/)?(\d+)?' => 'tvshows/genre/$1/$2/$4/$3',
  'tvshows/studio/(\d+)\-([\-a-zA-Z]+)(/page/)?(\d+)?' => 'tvshows/studio/$1/$2/$4/$3',

  // TV Shows search
  'tvshows/pre_search' => 'tvshows/pre_search',
  'tvshows/search/(:any)' => 'tvshows/search/$1',

  // TV Shows list for a person
  'tvshows/written/(\d+)\-([\-a-zA-Z]+)(/page/)?(\d+)?' => 'tvshows/played/$1/$2/$4/$3',
  'tvshows/directed/(\d+)\-([\-a-zA-Z]+)(/page/)?(\d+)?' => 'tvshows/played/$1/$2/$4/$3',
  'tvshows/played/(\d+)\-([\-a-zA-Z]+)(/page/)?(\d+)?' => 'tvshows/played/$1/$2/$4/$3',

  // TV Show view
  'tvshow/(\d+)\-([\-a-zA-Z0-9]+)' => 'tvshows/view/$1/$2',

  // TV Show navigation accross seasons
  'tvshow/(\d+)\-([\-a-zA-Z0-9]+)/season/(\d+)/page/(\d+)?' => 'tvshows/season/$1/$2/$3/4',

  // TV Shows list for a person
  'tvshows/played/(\d+)\-([\-a-zA-Z]+)(/page/)?(\d+)?' => 'tvshows/played/$1/$2/$4/$3',

  // Episodes list for a person
  'episodes/written/(\d+)\-([\-a-zA-Z]+)(/page/)?(\d+)?' => 'episodes/played/$1/$2/$4/$3',
  'episodes/directed/(\d+)\-([\-a-zA-Z]+)(/page/)?(\d+)?' => 'episodes/played/$1/$2/$4/$3',
  'episodes/played/(\d+)\-([\-a-zA-Z]+)(/page/)?(\d+)?' => 'episodes/played/$1/$2/$4/$3',

  // Episode view
  'episode/(\d+)\-([\-a-zA-Z0-9]+)' => 'episodes/view/$1/$2',

  // People list
  'people' => 'people/index',
  'people/page/:id' => 'people/index',

  // People search
  'people/pre_search' => 'people/pre_search',
  'people/search/(:any)' => 'people/search/$1',

  // Person view
  'person/(\d+)\-([\-a-zA-Z0-9]+)' => 'people/view/$1/$2',
  'person/(\d+)\-' => 'people/view/$1',

  // Sets list
  'sets' => 'sets/index',
  'sets/page/:id' => 'sets/index',
  'set/(\d+)\-([\-a-zA-Z]+)(/movies/page/)?(\d+)?' => 'sets/view/$1/$2/$4/$3',

  // Sets search
  'sets/pre_search' => 'sets/pre_search',
  'sets/search/(:any)' => 'sets/search/$1',

  // Set view
  'set/(\d+)\-([\-a-zA-Z0-9]+)' => 'sets/view/$1/$2',

  // Profile and authentification
	'profile'   => 'user/profile',
	'profile/edit'   => 'user/edit',
	'login'   => 'auth/login',
	'logout'   => 'auth/logout',

  // Admin routes

);
