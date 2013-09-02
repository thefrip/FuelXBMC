<?php if (Auth::member(100)): ?>
  <?php echo Asset::css(array('bootstrap-image-gallery.css', 'select2.css')); ?>
  <script src="<?php echo \Uri::base(false); ?>data/video_genres.js"></script>
  <script src="<?php echo \Uri::base(false); ?>data/studios.js"></script>
  <script src="<?php echo \Uri::base(false); ?>data/countries.js"></script>
  <script src="<?php echo \Uri::base(false); ?>data/certifications.js"></script>
  <script src="<?php echo \Uri::base(false); ?>data/sets.js"></script>
<?php endif; ?>
<style>
div.opacity {
  background-image: url('<?php echo $movie->fanart->url; ?>');
}
</style>
<div class="page-header">
    <h1 id="local-title"><?php echo $movie->local_title; ?></h1>
    <h3 id="title"><?php echo $original_title; ?></h3>
    <h4 id="set">
      <?php if (($movie->set->id != 0) and Config::get('settings.manage_sets')): ?>
        <?php echo sprintf(Lang::get('media.in_set'), Html::anchor('set/'.$movie->set->id.'-'.Inflector::friendly_title($movie->set->name, '-'), $movie->set->name)); ?>
      <?php endif; ?>
    </h4>
</div>
<ul class="nav nav-pills">
  <li class="active"><a href="#details" data-toggle="tab"><?php echo Lang::get('navigation.details'); ?></a></li>
  <li><a href="#casting" data-toggle="tab"><?php echo Lang::get('navigation.casting'); ?></a></li>
</ul>
<div class="opacity">
  <div class="tab-content opaque">
    <div class="tab-pane active" id="details">
      <?php
      $links = array();
      foreach($movie->writers as $key => $value)
        $links[] = Html::anchor('person/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name);

      $writers = implode(' / ', $links);
      if ($writers == '') $writers = Lang::get('media.no_writer');

      $links = array();
      foreach($movie->directors as $key => $value)
        $links[] = Html::anchor('person/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name);

      $directors = implode(' / ', $links);
      if ($directors == '') $directors = Lang::get('media.no_director');

      $links = array();
      $selected_genres = array();
      foreach($movie->genres as $genre)
      {
        $links[] = Html::anchor('movies/genre/'.$genre->id.'-'.Inflector::friendly_title($genre->name, '-'), $genre->name, array('class' => 'label label-warning'));
        $selected_genres[] = (int) $genre->id;
      }
      $selected_genres = '['.implode(',', $selected_genres).']';

      $genres = implode(' ', $links);
      if ($genres == '') $genres = Lang::get('media.no_genre');

      $links = array();
      $selected_studios = array();
      foreach($movie->studios as $studio)
      {
        $links[] = Html::anchor('movies/studio/'.$studio->id.'-'.Inflector::friendly_title($studio->name, '-'), $studio->name);
        $selected_studios[] = (int) $studio->id;
      }
      $selected_studios = '['.implode(',', $selected_studios).']';

      $studios = implode(' / ', $links);
      if ($studios == '') $studios = Lang::get('media.no_studio');

      $links = array();
      $selected_countries = array();
      foreach($movie->countries as $country)
      {
        $links[] = Html::anchor('movies/country/'.$country->id.'-'.Inflector::friendly_title($country->name, '-'), $country->name);
        $selected_countries[] = (int) $country->id;
      }
      $selected_countries = '['.implode(',', $selected_countries).']';

      $countries = implode(' / ', $links);
      if ($countries == '') $countries = Lang::get('media.no_country');

      // Film vue ou pas ?
      if ($movie->seen)
          $seen = Lang::get('media.seen');
      else
          $seen = Lang::get('media.never_seen');

      // Année du film présente ou pas ?
      if ($movie->year != 0)
      {
        $year = Html::anchor('movies/year/'.$movie->year, $movie->year);
      }
      else
      {
        $year = Lang::get('media.no_year');
      }

      ?>
      <div class="span7">
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('media.written_by'); ?></div>
          <div class="span9"><?php echo $writers; ?></div>
        </div>
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('media.directed_by'); ?></div>
          <div class="span9"><?php echo $directors; ?></div>
        </div>
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('media.runtime'); ?></div>
          <div class="span9"><?php echo $movie->runtime->display; ?></div>
        </div>
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('media.genres'); ?></div>
          <div id="genres" class="span9"><?php echo $genres; ?></div>
        </div>
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('media.studios'); ?></div>
          <div id="studios" class="span9"><?php echo $studios; ?></div>
        </div>
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('media.rating'); ?></div>
          <div class="span9">
            <?php echo render('partials/_rating', array('media' =>  $movie)); ?>
          </div>
        </div>
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('media.countries'); ?></div>
          <div id="countries" class="span9"><?php echo $countries; ?></div>
        </div>
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('media.mpaa'); ?></div>
          <div id="certification" class="span9"><?php echo ($movie->mpaa != '') ? $movie->mpaa : Lang::get('media.no_mpaa'); ?></div>
        </div>
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('media.year'); ?></div>
          <div class="span9" id="year"><?php echo $year; ?></div>
        </div>
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('media.tagline'); ?></div>
          <div class="span9" id="tagline"><?php echo $movie->tagline; ?></div>
        </div>
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('media.overview'); ?></div>
          <div id="overview" class="span"><?php echo $movie->overview; ?></div>
        </div>
        <hr>
        <p>
        <?php if (Auth::member(100)): ?>
          <?php echo Html::anchor('#media-modal', '<i class="icon-edit icon-white"></i> '.Lang::get('global.btn_edit'), array('class' => 'btn btn-info', 'role' => 'button', 'data-toggle' => 'modal')); ?>&nbsp;
        <?php endif; ?>
          <?php echo Html::anchor($movie->external_link, '<i class="icon-globe icon-white"></i> '.Lang::get('media.external_link'), array('class' => 'btn btn-info', 'target' => '_blank')); ?>
        </p>
      </div>
      <div class="span3 offset1" id="sidebar">
        <div class="row">
          <h3><?php echo Lang::get('media.poster'); ?></h3>
          <div class="thumbnail poster">
            <img id="poster" src="<?php echo $movie->poster->url; ?>" />
          </div>
          <?php if (Auth::member(100) and $movie->images->posters->previews): ?>
            <!-- Button to trigger modal box for poster selection -->
            <button id="select-poster" class="btn btn-info" data-target="#modal-poster" data-selector="#poster-gallery [data-gallery=gallery]"><i class="icon-plus icon-white"></i> <?php echo Lang::get('global.btn_change'); ?></button>
          <?php endif; ?>
        </div>
        <div class="row">
          <h3><?php echo Lang::get('media.fanart'); ?></h3>
          <div class="thumbnail fanart">
            <img id="fanart" src="<?php echo $movie->fanart->url; ?>" />
          </div>
          <?php if (Auth::member(100) and $movie->images->fanarts->previews): ?>
            <!-- Button to trigger modal box for fanart selection -->
            <button id="select-fanart" class="btn btn-info" data-target="#modal-fanart" data-selector="#fanart-gallery [data-gallery=gallery]"><i class="icon-plus icon-white"></i> <?php echo Lang::get('global.btn_change'); ?></button>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="casting">
      <ul class="thumbnails">
        <?php foreach($movie->actors as $person): ?>
          <li class="span3">
            <div class="thumbnail person">
              <?php echo Html::anchor('person/'.$person->id.'-'.Inflector::friendly_title($person->name, '-'), '<img src="'.$person->photo->url.'" />'); ?>
              <div class="caption">
                <h3><?php echo Html::anchor('person/'.$person->id.'-'.Inflector::friendly_title($person->name, '-'), $person->name); ?></h3>
                <p><?php echo Str::truncate($person->role, 25); ?></p>
              </div>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</div>
<?php if (Auth::member(100)): ?>

  <?php echo Asset::js(array('load-image.js', 'bootstrap-image-gallery.js', 'images.js', 'select2.js', 'jquery.highlight.js', 'infos.js')); ?>

  <?php
  // search for local language if not international language
  if (Lang::get_lang() != 'en')
  {
    $local_languages = array();

    // Search sub-folders in lang folder for availables languages
    foreach (glob(APPPATH.'lang/*', GLOB_ONLYDIR) as $language)
    {
      $iso = substr(strrchr($language, DIRECTORY_SEPARATOR), 1);

      // check if select2 local language file js exists too
      if (file_exists(DOCROOT.'assets/js/select2_locale_'.$iso.'.js'))
      {
        $local_languages[] = $iso;
      }
    }

    // if current language is present in all languages
    if (in_array(Lang::get_lang(), $local_languages))
    {
      // load local language file for select2
      echo Asset::js(array('select2_locale_'.Lang::get_lang().'.js'));
    }
  }
  ?>

  <?php if ($movie->images->posters->previews): ?>
    <!-- modal-poster is the modal dialog used for the poster gallery -->
    <div id="modal-poster" class="modal modal-gallery hide fade" tabindex="-1">
      <div class="modal-header">
          <a class="close" data-dismiss="modal">&times;</a>
          <h3 class="modal-title"><?php echo Lang::get('title.select_poster'); ?></h3>
      </div>
      <div class="modal-body"><div class="modal-image poster"></div></div>
      <div class="modal-footer">
          <a class="btn btn-success modal-select"><i class="icon-ok icon-white"></i> <?php echo Lang::get('global.btn_select_poster'); ?></a>
      </div>
    </div>

    <div id="poster-gallery" data-toggle="modal-gallery" data-target="#modal-poster">
      <?php foreach($movie->images->posters->previews as $poster): ?>
        <a href="<?php echo $poster; ?>" data-gallery="gallery" data-type="poster">
          <img src="<?php echo $poster; ?>" >
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($movie->images->fanarts->previews): ?>
    <!-- modal-fanart is the modal dialog used for the fanart gallery -->
    <div id="modal-fanart" class="modal modal-gallery hide fade" tabindex="-1">
      <div class="modal-header">
          <a class="close" data-dismiss="modal">&times;</a>
          <h3 class="modal-title"><?php echo Lang::get('title.select_fanart'); ?></h3>
      </div>
      <div class="modal-body"><div class="modal-image fanart"></div></div>
      <div class="modal-footer">
          <a class="btn btn-success modal-select"><i class="icon-ok icon-white"></i> <?php echo Lang::get('global.btn_select_fanart'); ?></a>
      </div>
    </div>

    <div id="fanart-gallery" data-toggle="modal-gallery" data-target="#modal-fanart">
      <?php foreach($movie->images->fanarts->previews as $fanart): ?>
        <a href="<?php echo $fanart; ?>" data-gallery="gallery" data-type="fanart">
          <img src="<?php echo $fanart; ?>" >
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php echo render('movie/_edit', array('movie' =>  $movie)); ?>
  <?php echo Security::js_fetch_token(); ?>

  <script type="text/javascript">
  var media_id = 'movie_<?php echo $movie->id; ?>';
  var selected_genres = <?php echo $selected_genres ?>;
  var selected_studios = <?php echo $selected_studios ?>;
  var selected_countries = <?php echo $selected_countries ?>;
  var selected_certification = '<?php echo ($movie->mpaa != '') ? $movie->mpaa : 0; ?>';
  var selected_set = <?php echo $movie->set->id; ?>;
  </script>

<?php endif; ?>



