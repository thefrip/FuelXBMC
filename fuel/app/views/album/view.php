<div class="page-header">
    <h1><?php echo $album->title; ?></h1>
</div>
<ul class="nav nav-pills">
	<li class="active"><a href="#details" data-toggle="tab"><?php echo Lang::get('navigation.details'); ?></a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="details">
		<div class="row">

      <?php
        $links = array();
        foreach($album->genres as $key => $value)
          $links[] = Html::anchor('albums/genre/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name, array('class' => 'label label-warning'));

        $genres = implode(' ', $links);
        if ($genres == '') $genres = Lang::get('media.no_genre');

        // Année de l'album présente ou pas ?
        if ($album->year != Lang::get('media.no_year'))
            $year = Html::anchor('albums/year/'.$album->year, $album->year);

      ?>

			<div class="span8">
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('media.artists'); ?></div>
          <div class="span9"><?php echo Html::anchor('artist/'.$album->artist->id.'-'.Inflector::friendly_title($album->artist->name, '-'), $album->artist->name); ?></div>
        </div>
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
          <div class="span9"><?php echo $album->review; ?></div>
        </div>
        <hr>
        <?php foreach($album->songs as $song): ?>
          <div class="row-fluid">
            <div class="span2"><?php echo ($song->number == '01') ? Lang::get('media.songs') : '&nbsp;'; ?></div>
            <div class="span9">
              <?php echo $song->number.' - '.$song->title.' ('.$song->duration.')'; ?>
            </div>
          </div>
        <?php endforeach; ?>
			</div>

			<div class="span3 offset1" id="sidebar">
				<div class="row">
					<h3><?php echo Lang::get('media.album_thumb'); ?></h3>
					<div class="thumbnail album">
						<img src="<?php echo $album->thumb->url; ?>" alt="">
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
