<div id="media-modal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><?php echo Lang::get('title.edit_infos'); ?></h3>
  </div>
  <div class="modal-body">
    <form id="form-modal">
<ul class="nav nav-pills">
  <li class="active"><a href="#modal-details" data-toggle="tab"><?php echo Lang::get('navigation.main'); ?></a></li>
  <li><a href="#modal-more" data-toggle="tab"><?php echo Lang::get('navigation.more'); ?></a></li>
</ul>
<div class="tab-content">

  <div class="tab-pane active" id="modal-details">
    <div class="row-fluid">
        <label><?php echo Lang::get('media.local_title'); ?></label>
        <input id="input-local-title" class="span12" type="text" name="local-title" value="<?php echo $tvshow->local_title; ?>">
        <label><?php echo Lang::get('media.overview'); ?></label>
        <textarea id="input-overview" class="span12" name="overview" rows="7"><?php echo $tvshow->overview; ?></textarea>
        <label><?php echo Lang::get('media.in-set'); ?></label>
    </div>

  </div>

  <div class="tab-pane" id="modal-more">

    <div class="row-fluid">
      <div class="span6">
        <label for="year"><?php echo Lang::get('media.year'); ?></label>
        <select id="select-year" name="year" class="span12">
          <?php for($year = date('Y'), $min = $year - 100; $year > $min; $year--): ?>
            <option value="<?php echo $year; ?>"<?php echo ($year == $tvshow->year) ? ' selected="selected"' : ''; ?> ><?php echo $year; ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <div class="span6">
        <label for="select-certification"><?php echo Lang::get('media.mpaa'); ?></label>
        <div id="ajax-certification"></div>
      </div>
    </div>

    <div class="row-fluid">
      <label for="select-genres"><?php echo Lang::get('media.genres'); ?></label>
      <div id="ajax-genres"></div>
    </div>

    <div class="row-fluid">
      <label for="select-studios"><?php echo Lang::get('media.studios'); ?></label>
      <div id="ajax-studios"></div>
    </div>

  </div>

  <input type="hidden" name="token" value="<?php echo \Security::fetch_token();?>" />

</div>

    </form>
  </div>
  <div class="modal-footer">
    <?php echo Html::anchor('#', '<i class="icon-ok icon-white"></i> '.Lang::get('global.btn_save_change'), array('id' => 'submit-form-modal', 'class' => 'btn btn-success')); ?>&nbsp;
    <?php echo Lang::get('global.or'); ?>&nbsp;<?php echo Html::anchor('#', Lang::get('global.cancel'), array('id' => 'cancel-form-modal', 'class' => 'btn btn-danger')); ?>
  </div>
</div>
