<?php if ($page_title != ''): ?>
  <div class="page-header">
      <h1><?php echo $page_title; ?></h1>
  </div>
<?php endif; ?>
<div class="tab-pane" id="<?php echo $albums_type; ?>">
  <?php if ($albums): ?>
    <?php foreach($albums as $album): ?>
      <div class="row">

        <div class="span3 offset1">
          <div class="thumbnail album">
            <?php echo Html::anchor('album/'.$album->id.'-'.Inflector::friendly_title($album->title, '-'), '<img src="'.$album->thumb->url.'" />'); ?>
          </div>
        </div>

        <?php
          $links = array();
          foreach($album->genres as $key => $value)
            $links[] = Html::anchor('albums/genre/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name, array('class' => 'label label-warning'));

          $genres = implode(' ', $links);
          if ($genres == '') $genres = Lang::get('media.no_genre');

          $year = Lang::get('media.no_year');
          // Année de l'album présente ou pas ?
          if ($album->year != Lang::get('media.no_year'))
              $year = Html::anchor('albums/year/'.$album->year, $album->year);

        ?>

        <div class="span7">
          <h3><?php echo Html::anchor('album/'.$album->id.'-'.Inflector::friendly_title($album->title, '-'), $album->title); ?></h3>
          <?php if ($albums_type != 'albums_played'): ?>
            <div class="row-fluid">
              <div class="span2"><?php echo Lang::get('media.artists'); ?></div>
              <div class="span9"><?php echo Html::anchor('artist/'.$album->artist->id.'-'.Inflector::friendly_title($album->artist->name, '-'), $album->artist->name); ?></div>
            </div>
          <?php endif; ?>
          <div class="row-fluid">
            <div class="span2"><?php echo Lang::get('media.genres'); ?></div>
            <div class="span9"><?php echo $genres; ?></div>
          </div>
          <div class="row-fluid">
            <div class="span2"><?php echo Lang::get('media.year'); ?></div>
            <div class="span9"><?php echo $year; ?></div>
          </div>
					<div class="row-fluid">
						<div class="span2"><?php echo Lang::get('media.review'); ?></div>
						<div class="span9"><?php echo Str::truncate($album->review, 390); ?></div>
					</div>
        </div>
      </div>
      <hr>
    <?php endforeach; ?>
    <?php echo $pagination; ?>
  <?php else: ?>
    <?php echo Lang::get('media.no_result'); ?>
  <?php endif; ?>
</div>
