<?php if ($page_title != ''): ?>
  <div class="page-header">
      <h1><?php echo $page_title; ?></h1>
  </div>
<?php endif; ?>
<div class="tab-pane" id="<?php echo $tvshows_type; ?>">
  <?php if ($tvshows): ?>
    <?php if (Config::get('settings.tvshow_poster') == 'banner'): ?>

      <div class="row">
        <?php foreach($tvshows as $tvshow): ?>
          <div class="span4 tvshow">
            <div class="row-fluid">
              <div class="thumbnail banner">
                <?php echo Html::anchor('tvshow/'.$tvshow->id.'-'.Inflector::friendly_title($tvshow->local_title, '-'), '<img src="'.$tvshow->banner->url.'" />'); ?>
              </div>
            </div>

            <?php
            $links = array();
            foreach($tvshow->actors as $key => $value)
              $links[] = Html::anchor('person/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name);

            $actors = implode(' / ', $links);
            if ($actors == '') $actors = Lang::get('media.no_actor');

            $links = array();
            foreach($tvshow->genres as $key => $value)
              $links[] = Html::anchor('tvshows/genre/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name, array('class' => 'label label-warning'));

            $genres = implode(' ', $links);
            if ($genres == '') $genres = Lang::get('media.no_genre');

            $links = array();
            foreach($tvshow->studios as $key => $value)
              $links[] = Html::anchor('tvshows/studio/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name);

            $studios = implode(' / ', $links);
            if ($studios == '') $studios = Lang::get('media.no_studio');

            // Année de la série TVprésente ou pas ?
            if ($tvshow->year != Lang::get('media.no_year'))
                $year = Html::anchor('tvshows/year/'.$tvshow->year, $tvshow->year);

            ?>
            <div class="row-fluid">
              <div class="span3"><?php echo Lang::get('media.genres'); ?></div>
              <div class="span8"><?php echo $genres; ?></div>
            </div>
            <div class="row-fluid">
              <div class="span3"><?php echo Lang::get('media.studios'); ?></div>
              <div class="span8"><?php echo $studios; ?></div>
            </div>
            <div class="row-fluid">
              <div class="span3"><?php echo Lang::get('media.rating'); ?></div>
              <div class="span8">
                <div class='rating_bar'>
                    <div  class='rating' title="<?php echo (($tvshow->rating/10)*10); ?>" style='width:<?php echo round(($tvshow->rating/10)*100); ?>%;'>
                    </div>
                </div>
              </div>
            </div>
            <div class="row-fluid">
              <div class="span3"><?php echo Lang::get('media.year'); ?></div>
              <div class="span8"><?php echo $year; ?></div>
            </div>
            <div class="row-fluid">
              <div class="span3"><?php echo Lang::get('media.with'); ?></div>
              <div class="span8"><?php echo $actors; ?></div>
            </div>
            <div class="row-fluid">
              <div class="span3"><?php echo Lang::get('media.total_seasons'); ?></div>
              <div class="span8"><?php echo $tvshow->total_seasons; ?></div>
            </div>
            <div class="row-fluid">
              <div class="span3"><?php echo Lang::get('media.total_episodes'); ?></div>
              <div class="span8"><?php echo $tvshow->total_episodes; ?></div>
            </div>

            <div class="row-fluid">
              <h3><?php echo Html::anchor('tvshow/'.$tvshow->id.'-'.Inflector::friendly_title($tvshow->local_title, '-'), $tvshow->local_title); ?></h3>
              <p><?php echo Str::truncate($tvshow->overview, 350); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
        <script type="text/javascript" >
        function equalHeight(group) {
            tallest = 0;
            group.each(function() {
                thisHeight = $(this).height();
                if(thisHeight > tallest) {
                    tallest = thisHeight;
                }
            });
            group.each(function() { $(this).height(tallest); });
        }

        $(document).ready(function() {
        <?php if (($tvshows_type == 'last_tvshows') or ($tvshows_type == 'tvshows_played')): ?>
          $('a[data-toggle="tab"]').on('shown', function (e) {
            equalHeight($(".tvshow"));
          })
        <?php else: ?>
          equalHeight($(".tvshow"));
        <?php endif; ?>
        });
        </script>
      </div>
      <hr>

    <?php else: ?>

      <?php foreach($tvshows as $tvshow): ?>
        <div class="row">

          <div class="span3 offset1">
            <div class="thumbnail poster">
              <?php echo Html::anchor('tvshow/'.$tvshow->id.'-'.Inflector::friendly_title($tvshow->local_title, '-'), '<img src="'.$tvshow->poster->url.'" />'); ?>
            </div>
          </div>

          <?php
          $links = array();
          foreach($tvshow->actors as $key => $value)
            $links[] = Html::anchor('person/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name);

          $actors = implode(' / ', $links);
          if ($actors == '') $actors = Lang::get('media.no_actor');

          $links = array();
          foreach($tvshow->genres as $key => $value)
            $links[] = Html::anchor('tvshows/genre/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name, array('class' => 'label label-warning'));

          $genres = implode(' ', $links);
          if ($genres == '') $genres = Lang::get('media.no_genre');

          $links = array();
          foreach($tvshow->studios as $key => $value)
            $links[] = Html::anchor('tvshows/studio/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name);

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
            <h3><?php echo Html::anchor('tvshow/'.$tvshow->id.'-'.Inflector::friendly_title($tvshow->local_title, '-'), $tvshow->local_title); ?></h3>
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
                  <div  class='rating' title="<?php echo (($tvshow->rating/10)*10); ?>" style='width:<?php echo round(($tvshow->rating/10)*100); ?>%;'></div>
                </div>
              </div>
            </div>
            <div class="row-fluid">
              <div class="span2"><?php echo Lang::get('media.total_seasons'); ?></div>
              <div class="span9"><?php echo $tvshow->total_seasons; ?></div>
            </div>
            <div class="row-fluid">
              <div class="span2"><?php echo Lang::get('media.total_episodes'); ?></div>
              <div class="span9"><?php echo $tvshow->total_episodes; ?></div>
            </div>
            <div class="row-fluid">
              <div class="span2"><?php echo Lang::get('media.overview'); ?></div>
              <div class="span"><?php echo Str::truncate($tvshow->overview, 350); ?></div>
            </div>
          </div>
        </div>
        <hr>
      <?php endforeach; ?>

    <?php endif; ?>
    <?php echo $pagination; ?>
  <?php else: ?>
    <?php echo Lang::get('media.no_result'); ?>
  <?php endif; ?>
</div>
