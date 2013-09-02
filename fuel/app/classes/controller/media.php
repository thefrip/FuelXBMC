<?php

class Controller_Media extends Controller_Rest
{

	public function get_index()
	{
    // if an url is given
    if ($_SERVER['PATH_INFO'] != '/media/get_index')
    {
      // Incomplete real url requested
      $url = str_replace('/media/', 'http://', $_SERVER['PATH_INFO']);

      // Getting headers sent by the client.
      $headers = apache_request_headers();

      // Time minus two days to fake user's browser cache
      $time = time()-(2*24*60*60);

      // Checking if the client is validating his cache and if it is current.
      if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == $time)) {
          // Client's cache IS current, so we just respond '304 Not Modified'.
          header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT', true, 304);
      } else {
          // Image not cached or cache outdated, we respond '200 OK' and output the image.
          header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT', true, 200);
          header('Content-Type: image/jpg');
      }

      echo Xbmc::download($url);
    }

	}

	public function post_change_image($token = null)
	{

  /* pour test
  $media = "artist_1020";
  $type =	"thumb";
  $url = "http://assets.fanart.tv/fanart/music/cc2c9c3c-b7bc-4b8b-84d8-4fbd8779e493/artistthumb/adele-50741cbeecc8c.jpg/preview";
  */

    // If token is given in the url go else don't change
    if (\Security::check_token($token))
    {
      list($media_type, $media_id) = explode('_', \Input::json('media'));
      $type = \Input::json('type');
      $url = \Input::json('url');

        // Get model for this media and manage image link if model exist
        if (class_exists($model = 'Model_'.ucfirst($media_type).'View'))
        {
          $source = $model::find_source($media_id);

          // Nom de la classe du scraper
          if (class_exists($scraper = $source->scraper_class))
          {
            // Get the url for full size image
            $url = $scraper::get_image_link($url, $media_id, $type);
          }
        }

        // Art model for this media
        if ($media_type == 'movie' or $media_type == 'tvshow')
        {
          $model_art = 'Model_VideoArt';
        }
        else
        {
          $model_art = 'Model_MusicArt';
        }

        // Get the actual image url (not used) and filename by calling the correct method :
        // 'get_tvshow_banner', 'get_tvshow_poster', 'get_tvshow_fanart'
        // 'get_movie_poster', 'get_movie_fanart' ...
        $method = 'get_for_'.$media_type;

        // Actual image saved on disk
        $actual_art_images = $model_art::$method($media_id);

        // Give the actual banner, poster, thumb or fanart filename and suppress it on the disk
        @unlink($actual_art_images->$type->filename);

        // Get the art id matching all datas
        $art_id = $model_art::get_id($media_id, $media_type, $type);

        // Reload the art to set url and save in database
        $art = $model_art::find($art_id);
        $art->url = $url;
        $art->save();

        // Get the new image url (not used here) and filename by calling the correct method :
        // 'get_tvshow_banner', 'get_tvshow_poster', 'get_tvshow_fanart'
        // 'get_movie_poster', 'get_movie_fanart'
        $method = 'get_'.$media_type.'_'.$type;
        $new_image = Xbmc::$method($url);

        // Download from seleted url image to the correct filename and save data into the database
        Xbmc::download($url, $new_image->filename);

        // New image download, so search url on this site
        $new_image = Xbmc::$method($url);

        // Return the new image url on this site and quit
        $json = array('success' => '1',
                      'url' => $new_image->url,
                      'type' => $type
                      );

        return $this->response($json);
    }

	}

}
