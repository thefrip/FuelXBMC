<?php echo Form::open(); ?>

	<fieldset>
		<div class="control-group">
			<?php echo Form::label(Lang::get('server_path.client_path'), 'client_path', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo Form::input('client_path', Input::post('client_path', isset($path) ? $path->client_path : ''), array('class' => 'input-xxlarge')); ?>
        <span class="help-block"><?php echo Lang::get('server_path.music_client_path_help'); ?></span>
			</div>
		</div>

		<div class="control-group">
			<?php echo Form::label(Lang::get('server_path.server_path'), 'server_path', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo Form::input('server_path', Input::post('server_path', isset($path) ? $path->server_path : ''), array('class' => 'input-xxlarge')); ?>
			</div>
		</div>

		<div class="control-group">
			<label class='control-label'>&nbsp;</label>
			<div class='controls'>
        <button type="submit" class="btn btn-success">
          <i class="icon-ok icon-white"></i> <?php echo Lang::get('global.btn_save_change'); ?>
        </button>
        <?php echo Lang::get('global.or'); ?>&nbsp;<?php echo Html::anchor('admin/sources/music', Lang::get('global.cancel'), array('class' => 'btn btn-danger')); ?>
      </div>
		</div>
	</fieldset>
<?php echo Form::close(); ?>
