<div class="page-header">
    <h1 id="local-title"><?php echo $page_title; ?></h1>
</div>
<ul class="nav nav-pills">
  <li class="active"><a href="#details" data-toggle="tab"><?php echo Lang::get('navigation.details'); ?></a></li>
  <li><a href="#casting" data-toggle="tab"><?php echo Lang::get('navigation.casting'); ?></a></li>
</ul>
<div class="tab-content">
  <div class="tab-pane active" id="details">
    <div class="row">
      <div class="span3">
        <div class="thumbnail episode">
          <img src="<?php echo $episode->poster->url; ?>" />
        </div>
        <?php if (Auth::member(100)): ?>
          <p>
            <?php echo Html::anchor('#media-modal', '<i class="icon-edit icon-white"></i> '.Lang::get('global.btn_edit'), array('class' => 'btn btn-info', 'role' => 'button', 'data-toggle' => 'modal')); ?>&nbsp;
          </p>
        <?php endif; ?>
      </div>
      <?php
      $links = array();
      foreach($episode->writers as $key => $value)
        $links[] = Html::anchor('person/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name);

      $writers = implode(' / ', $links);
      if ($writers == '') $writers = Lang::get('media.no_writer');

      $links = array();
      foreach($episode->directors as $key => $value)
        $links[] = Html::anchor('person/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name);

      $directors = implode(' / ', $links);
      if ($directors == '') $directors = Lang::get('media.no_director');
      ?>
      <div class="span6 offset1">
        <div class="row-fluid">
          <div class="span3"><?php echo Lang::get('media.tvshow'); ?></div>
          <div class="span9"><?php echo Html::anchor('tvshow/'.$episode->tvshow_id.'-'.Inflector::friendly_title($episode->tvshow_name, '-'), $episode->tvshow_name); ?></div>
        </div>
        <div class="row-fluid">
          <div class="span3"><?php echo Lang::get('media.written_by'); ?></div>
          <div class="span9"><?php echo $writers; ?></div>
        </div>
        <div class="row-fluid">
          <div class="span3"><?php echo Lang::get('media.directed_by'); ?></div>
          <div class="span9"><?php echo $directors; ?></div>
        </div>
        <div class="row-fluid">
          <div class="span3"><?php echo Lang::get('media.runtime'); ?></div>
          <div class="span9"><?php echo $episode->runtime; ?></div>
        </div>
        <div class="row-fluid">
          <div class="span3"><?php echo Lang::get('media.rating'); ?></div>
          <div class="span9">
            <?php echo render('partials/_rating', array('media' =>  $episode)); ?>
          </div>
        </div>
        <div class="row-fluid">
          <div class="span3"><?php echo Lang::get('media.first_aired'); ?></div>
          <div class="span9"><?php echo $episode->first_aired; ?></div>
        </div>
        <div class="row-fluid">
          <div class="span3"><?php echo Lang::get('media.overview'); ?></div>
          <div id="overview" class="span"><?php echo $episode->overview; ?></div>
        </div>
      </div>
    </div>
  </div>
  <div class="tab-pane" id="casting">
    <ul class="thumbnails">
      <?php foreach($episode->actors as $person): ?>
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
<?php if (Auth::member(100)): ?>
  <?php echo Asset::js(array('jquery.highlight.js', 'infos.js')); ?>

  <?php echo render('episode/_edit', array('episode' =>  $episode)); ?>
  <?php echo Security::js_fetch_token(); ?>

  <script type="text/javascript">
  var media_id = 'episode_<?php echo $episode->id; ?>';
  </script>
<?php endif; ?>
