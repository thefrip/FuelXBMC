<?php if ($page_title != ''): ?>
  <div class="page-header">
      <h1><?php echo $page_title; ?></h1>
  </div>
<?php endif; ?>
<div class="tab-pane">
  <?php if ($artists): ?>
    <ul class="thumbnails">
      <?php foreach($artists as $artist): ?>
        <li class="span3">
          <div class="thumbnail thumb">
            <?php echo Html::anchor('artist/'.$artist->id.'-'.Inflector::friendly_title($artist->name, '-'), '<img src="'.$artist->thumb->url.'" />'); ?>
            <div class="caption">
              <h3><?php echo Html::anchor('artist/'.$artist->id.'-'.Inflector::friendly_title($artist->name, '-'), $artist->name); ?></h3>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
	<?php echo $pagination; ?>
  <?php else: ?>
    <?php echo Lang::get('media.no_result'); ?>
  <?php endif; ?>
</div>
