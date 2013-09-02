<div id="media-modal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><?php echo Lang::get('title.edit_infos'); ?></h3>
  </div>
  <div class="modal-body">
    <form id="form-modal">
<ul class="nav nav-pills">
  <li class="active"><a href="#modal-details" data-toggle="tab"><?php echo Lang::get('navigation.main'); ?></a></li>
</ul>
<div class="tab-content">

  <div class="tab-pane active" id="modal-details">
    <div class="row-fluid">
        <label><?php echo Lang::get('media.local_title'); ?></label>
        <input id="input-local-title" class="span12" type="text" name="local-title" value="<?php echo $episode->local_title; ?>">
        <label><?php echo Lang::get('media.overview'); ?></label>
        <textarea id="input-overview" class="span12" name="overview" rows="7"><?php echo $episode->overview; ?></textarea>
        <label><?php echo Lang::get('media.in-set'); ?></label>
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
