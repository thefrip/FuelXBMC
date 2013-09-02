<?php echo Form::open(array('class' => 'form-horizontal')); ?>

	<fieldset>
		<div class="control-group">
			<?php echo Form::label(Lang::get('certification.name'), 'name', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo Form::input('name', Input::post('name', isset($certification) ? $certification->name : ''), array('class' => 'span4', 'placeholder' => Lang::get('label.name'))); ?>
			</div>
		</div>

		<div class="control-group">
			<label class='control-label'>&nbsp;</label>
			<div class='controls'>
        <button type="submit" class="btn btn-success">
          <i class="icon-ok icon-white"></i> <?php echo Lang::get('global.btn_save_change'); ?>
        </button>
        <?php echo Lang::get('global.or'); ?>&nbsp;<?php echo Html::anchor('admin/certifications', Lang::get('global.cancel'), array('class' => 'btn btn-danger')); ?>
      </div>
		</div>
	</fieldset>
<?php echo Form::close(); ?>
