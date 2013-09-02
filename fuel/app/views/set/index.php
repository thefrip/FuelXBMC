<?php if ($page_title != ''): ?>
  <div class="page-header">
      <h1><?php echo $page_title; ?></h1>
  </div>
<?php endif; ?>
<div class="tab-pane" id="<?php echo $sets_type; ?>">
  <?php if ($sets): ?>
    <?php foreach($sets as $set): ?>
      <div class="row">

        <div class="span3 offset1">
          <div class="thumbnail poster">
            <?php echo Html::anchor('set/'.$set->id.'-'.Inflector::friendly_title($set->title, '-'), '<img src="'.$set->poster->url.'" />'); ?>
          </div>
        </div>
        <div class="span7">
          <h3><?php echo Html::anchor('set/'.$set->id.'-'.Inflector::friendly_title($set->title, '-'), $set->title); ?></h3>
          <div class="row-fluid">
            <div class="span4"><?php echo Lang::get('media.total_movies'); ?></div>
            <div class="span7"><?php echo count($set->movies); ?></div>
          </div>
            <div class="row-fluid">
              <?php
              for ($i = 0, $size = count($set->movies); $i < $size; $i++)
              {
                if ($i == 5) break;
                echo '<h4>'.Html::anchor('movie/'.$set->movies[$i]->id.'-'.Inflector::friendly_title($set->movies[$i]->local_title, '-'), $set->movies[$i]->local_title).'</h4><br />';
              }
              if (count($set->movies) > 5) echo '<h4>...</h4>';
              ?>
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
