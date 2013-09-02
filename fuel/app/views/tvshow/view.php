<?php if (Auth::member(100)): ?>
  <?php echo Asset::css(array('bootstrap-image-gallery.css', 'select2.css')); ?>
  <script src="<?php echo \Uri::base(false); ?>data/video_genres.js"></script>
  <script src="<?php echo \Uri::base(false); ?>data/studios.js"></script>
  <script src="<?php echo \Uri::base(false); ?>data/certifications.js"></script>
<?php endif; ?>
<style>
div.opacity {
  background-image: url('<?php echo $tvshow->fanart->url; ?>');
}
</style>
<?php echo Asset::js(array('ajax-pagination.js')); ?>
<div class="page-header">
    <h1 id="local-title"><?php echo $tvshow->local_title; ?></h1>
</div>
<ul class="nav nav-pills" id="tvshow">
	<li class="active"><a href="#details" data-toggle="tab"><?php echo Lang::get('navigation.details'); ?></a></li>
	<li><a href="#casting" data-toggle="tab"><?php echo Lang::get('navigation.casting'); ?></a></li>
	<?php foreach($seasons as $season): ?>
		<?php
		$season_name = sprintf(Lang::get('navigation.season'), $season->id);
		if ($season->id == 0) $season_name = Lang::get('navigation.special_season');
		?>
		<li><a href="#season-<?php echo $season->id; ?>" data-toggle="tab"><?php echo $season_name; ?></a></li>
	<?php endforeach; ?>
</ul>
<div class="opacity">
  <div class="tab-content opaque">
    <div class="tab-pane active" id="details">
      <?php

      $links = array();
      $selected_genres = array();
      foreach($tvshow->genres as $genre)
      {
        $links[] = Html::anchor('tvshows/genre/'.$genre->id.'-'.Inflector::friendly_title($genre->name, '-'), $genre->name, array('class' => 'label label-warning'));
        $selected_genres[] = (int) $genre->id;
      }
      $selected_genres = '['.implode(',', $selected_genres).']';

      $genres = implode(' ', $links);
      if ($genres == '') $genres = Lang::get('media.no_genre');

      $links = array();
      $selected_studios = array();
      foreach($tvshow->studios as $studio)
      {
        $links[] = Html::anchor('tvshows/studio/'.$studio->id.'-'.Inflector::friendly_title($studio->name, '-'), $studio->name);
        $selected_studios[] = (int) $studio->id;
      }
      $selected_studios = '['.implode(',', $selected_studios).']';

      $studios = implode(' / ', $links);
      if ($studios == '') $studios = Lang::get('media.no_studio');

      // Année de la série TV présente ou pas ?
      if ($tvshow->year != 0)
      {
        $year = Html::anchor('tvshows/year/'.$tvshow->year, $tvshow->year);
      }
      else
      {
        $year = Lang::get('media.no_year');
      }

      ?>
      <div class="span7">
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
            <?php echo render('partials/_rating', array('media' =>  $tvshow)); ?>
          </div>
        </div>
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('media.year'); ?></div>
          <div id="year" class="span9"><?php echo $year; ?></div>
        </div>
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('media.mpaa'); ?></div>
          <div id="certification" class="span9"><?php echo $tvshow->mpaa; ?></div>
        </div>
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('media.overview'); ?></div>
          <div id="overview" class="span"><?php echo $tvshow->overview; ?></div>
        </div>
        <hr>
        <p>
         <?php if (Auth::member(100)): ?>
          <?php echo Html::anchor('#media-modal', '<i class="icon-edit icon-white"></i> '.Lang::get('global.btn_edit'), array('class' => 'btn btn-info', 'role' => 'button', 'data-toggle' => 'modal')); ?>&nbsp;
        <?php endif; ?>
         <?php echo Html::anchor($tvshow->external_link, '<i class="icon-globe icon-white"></i> '.Lang::get('media.external_link'), array('class' => 'btn btn-info', 'target' => '_blank')); ?>
        </p>
      </div>
      <div class="span3 offset1" id="sidebar">

        <?php if (Config::get('settings.tvshow_poster') == 'banner'): ?>

          <div class="row">
            <h3><?php echo Lang::get('media.banner'); ?></h3>
            <div class="thumbnail banner">
              <img id="banner" src="<?php echo $tvshow->banner->url; ?>" />
            </div>
            <?php if (Auth::member(100) and $tvshow->images->banners->previews): ?>
              <!-- Button to trigger modal box for banner selection -->
              <button id="select-banner" class="btn btn-info" data-target="#modal-banner" data-selector="#banner-gallery [data-gallery=gallery]"><i class="icon-plus icon-white"></i> <?php echo Lang::get('global.btn_change'); ?></button>
            <?php endif; ?>
          </div>

        <?php else: ?>

          <div class="row">
            <h3><?php echo Lang::get('media.poster'); ?></h3>
            <div class="thumbnail poster">
              <img id="poster" src="<?php echo $tvshow->poster->url; ?>" />
            </div>
            <?php if (Auth::member(100) and $tvshow->images->posters->previews): ?>
              <!-- Button to trigger modal box for poster selection -->
              <button id="select-poster" class="btn btn-info" data-target="#modal-poster" data-selector="#poster-gallery [data-gallery=gallery]"><i class="icon-plus icon-white"></i> <?php echo Lang::get('global.btn_change'); ?></button>
            <?php endif; ?>
          </div>

        <?php endif; ?>

        <div class="row">
          <h3><?php echo Lang::get('media.fanart'); ?></h3>
          <div class="thumbnail fanart">
            <img id="fanart" src="<?php echo $tvshow->fanart->url; ?>" />
          </div>
          <?php if (Auth::member(100) and $tvshow->images->fanarts->previews): ?>
            <!-- Button to trigger modal box for fanart selection -->
            <button id="select-fanart" class="btn btn-info" data-target="#modal-fanart" data-selector="#fanart-gallery [data-gallery=gallery]"><i class="icon-plus icon-white"></i> <?php echo Lang::get('global.btn_change'); ?></button>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="casting">
      <ul class="thumbnails">
        <?php foreach($tvshow->actors as $person): ?>
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

    <?php foreach($seasons as $season): ?>
      <style>
      #season-<?php echo $season->id; ?> {
        background-color:rgba(255, 255, 255, 1);
        padding-bottom: 10px;
      }
      </style>
      <div class="tab-pane" id="season-<?php echo $season->id; ?>">
        <div class="row">
          <div class="span8" id="block-<?php echo $season->id; ?>">
            <?php
              $season_episode = 'episodes_season'.$season->id;
              echo $$season_episode;
            ?>
          </div>

          <div class="span3 offset1" id="season-poster">
            <div class="row">
              <h3><?php echo Lang::get('media.poster'); ?></h3>
              <div class="thumbnail poster">
                <?php $season_poster = $tvshow->seasons[$season->id]; ?>
                <img src="<?php echo $season_poster->url; ?>" alt="">
              </div>
            </div>
          </div>

        </div>
      </div>
    <?php endforeach; ?>

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

  <?php if ((Config::get('settings.tvshow_poster') == 'banner') and $tvshow->images->banners->previews): ?>
    <!-- modal-banner is the modal dialog used for the banner gallery -->
    <div id="modal-banner" class="modal modal-gallery hide fade" tabindex="-1">
      <div class="modal-header">
          <a class="close" data-dismiss="modal">&times;</a>
          <h3 class="modal-title"><?php echo Lang::get('title.select_banner'); ?></h3>
      </div>
      <div class="modal-body"><div class="modal-image banner"></div></div>
      <div class="modal-footer">
          <a class="btn btn-success modal-select"><i class="icon-ok icon-white"></i> <?php echo Lang::get('global.btn_select_banner'); ?></a>
      </div>
    </div>

    <div id="banner-gallery" data-toggle="modal-gallery" data-target="#modal-banner">
      <?php foreach($tvshow->images->banners->previews as $banner): ?>
        <a href="<?php echo $banner; ?>" data-gallery="gallery" data-type="banner">
          <img src="<?php echo $banner; ?>" >
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ((Config::get('settings.tvshow_poster') == 'poster') and $tvshow->images->posters->previews): ?>
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
      <?php foreach($tvshow->images->posters->previews as $poster): ?>
        <a href="<?php echo $poster; ?>" data-gallery="gallery" data-type="poster">
          <img src="<?php echo $poster; ?>" >
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($tvshow->images->fanarts->previews): ?>
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
      <?php foreach($tvshow->images->fanarts->previews as $fanart): ?>
        <a href="<?php echo $fanart; ?>" data-gallery="gallery" data-type="fanart">
          <img src="<?php echo $fanart; ?>" >
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php echo render('tvshow/_edit', array('tvshow' =>  $tvshow)); ?>
  <?php echo Security::js_fetch_token(); ?>

  <script type="text/javascript">
  var media_id = 'tvshow_<?php echo $tvshow->id; ?>';
  var selected_genres = <?php echo $selected_genres ?>;
  var selected_studios = <?php echo $selected_studios ?>;
  var selected_certification = '<?php echo ($tvshow->mpaa != '') ? $tvshow->mpaa : 0; ?>';
  </script>

<?php endif; ?>
