<?php if ($page_title != ''): ?>
  <div class="page-header">
      <h1><?php echo $page_title; ?></h1>
  </div>
<?php endif; ?>
<div class="tab-pane" id="<?php echo $people_type; ?>">
  <?php if ($people): ?>
    <ul class="thumbnails">
      <?php foreach($people as $person): ?>
        <li class="span3">
          <div class="thumbnail person">
            <?php echo Html::anchor('person/'.$person->id.'-'.Inflector::friendly_title($person->name, '-'), '<img src="'.$person->photo->url.'" />'); ?>
            <div class="caption">
              <h3><?php echo Html::anchor('person/'.$person->id.'-'.Inflector::friendly_title($person->name, '-'), $person->name); ?></h3>
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
