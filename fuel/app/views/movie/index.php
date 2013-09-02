<?php if ($page_title != ''): ?>
  <div class="page-header">
      <h1><?php echo $page_title; ?></h1>
  </div>
<?php endif; ?>
<div class="tab-pane" id="<?php echo $movies_type; ?>">
  <?php if ($movies): ?>
    <?php foreach($movies as $movie): ?>
      <div class="row">

        <div class="span3 offset1">
          <div class="thumbnail poster">
            <?php echo Html::anchor('movie/'.$movie->id.'-'.Inflector::friendly_title($movie->local_title, '-'), '<img src="'.$movie->poster->url.'" />'); ?>
          </div>
        </div>

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
        foreach($movie->actors as $key => $value)
          $links[] = Html::anchor('person/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name);

        $actors = implode(' / ', $links);
        if ($actors == '') $actors = Lang::get('media.no_actor');

        $links = array();
        foreach($movie->genres as $key => $value)
          $links[] = Html::anchor('movies/genre/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name, array('class' => 'label label-warning'));

        $genres = implode(' ', $links);
        if ($genres == '') $genres = Lang::get('media.no_genre');

        $links = array();
        foreach($movie->studios as $key => $value)
          $links[] = Html::anchor('movies/studio/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name);

        $studios = implode(' / ', $links);
        if ($studios == '') $studios = Lang::get('media.no_studio');

        $links = array();
        foreach($movie->countries as $key => $value)
          $links[] = Html::anchor('movies/country/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name);

        $countries = implode(' / ', $links);
        if ($countries == '') $countries = Lang::get('media.no_country');

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
            <h3><?php echo Html::anchor('movie/'.$movie->id.'-'.Inflector::friendly_title($movie->local_title, '-'), $movie->local_title); ?></h3>
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
              <div class="span2"><?php echo Lang::get('media.with'); ?></div>
              <div class="span9"><?php echo $actors; ?></div>
            </div>
            <div class="row-fluid">
              <div class="span2"><?php echo Lang::get('media.genres'); ?></div>
              <div class="span9"><?php echo $genres; ?></div>
            </div>
            <div class="row-fluid">
              <div class="span2"><?php echo Lang::get('media.studios'); ?></div>
              <div class="span9"><?php echo $studios; ?></div>
            </div>
            <div class="row-fluid">
              <div class="span2"><?php echo Lang::get('media.rating'); ?></div>
              <div class="span9">
                <div class='rating_bar'>
                  <div  class='rating' title="<?php echo (($movie->rating/10)*10); ?>" style='width:<?php echo round(($movie->rating/10)*100); ?>%;'></div>
                </div>
              </div>
            </div>
            <div class="row-fluid">
              <div class="span2"><?php echo Lang::get('media.year'); ?></div>
              <div class="span9"><?php echo $year; ?></div>
            </div>
            <div class="row-fluid">
              <div class="span2"><?php echo Lang::get('media.countries'); ?></div>
              <div class="span9"><?php echo $countries; ?></div>
            </div>
            <div class="row-fluid">
              <div class="span2"><?php echo Lang::get('media.overview'); ?></div>
              <div class="span"><?php echo Str::truncate($movie->overview, 350); ?></div>
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
