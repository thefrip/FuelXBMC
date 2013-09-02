<?php if ($page_title != ''): ?>
  <div class="page-header">
      <h1><?php echo $page_title; ?></h1>
  </div>
<?php endif; ?>
<?php echo Form::open(); ?>

	<fieldset>
		<div class="control-group">
			<?php echo Form::label(Lang::get('server_path.client_path'), 'client_path', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo Form::input('client_path', $path->client_path, array('class' => 'input-xxlarge uneditable-input', 'disabled' => 'true')); ?>
        <span class="help-block"><?php echo Lang::get('server_path.client_path_help'); ?></span>
			</div>
		</div>

		<div class="control-group">
			<?php echo Form::label(Lang::get('server_path.server_path'), 'server_path', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo Form::input('server_path', Input::post('server_path', $path->server_path), array('class' => 'input-xxlarge' )); ?>
			</div>
		</div>

		<div class="control-group">
			<label class='control-label'>&nbsp;</label>
			<div class='controls'>
        <button type="submit" class="btn btn-success">
          <i class="icon-ok icon-white"></i> <?php echo Lang::get('global.btn_save_change'); ?>
        </button>
        <?php echo Lang::get('global.or'); ?>&nbsp;<?php echo Html::anchor('admin/sources/video', Lang::get('global.cancel'), array('class' => 'btn btn-danger')); ?>
      </div>
		</div>
	</fieldset>
<?php echo Form::close(); ?>
