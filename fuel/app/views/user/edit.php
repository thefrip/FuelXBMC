<div class="page-header">
    <h1><?php echo $page_title; ?></h1>
</div>
<?php echo Form::open(array("class"=>"form-horizontal")); ?>

	<fieldset>
		<div class="control-group">
			<?php echo Form::label(Lang::get('user.password'), 'password', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo Form::password('password', Input::post('password'), array('class' => 'span4', 'placeholder' => Lang::get('label.password'))); ?>
			</div>
		</div>

    <div class="control-group">
      <?php echo Form::label(Lang::get('user.new_password'), 'new_password', array('class'=>'control-label')); ?>
      <div class="controls">
        <?php echo Form::password('new_password', Input::post('new_password'), array('class' => 'span4', 'placeholder' => Lang::get('label.new_password'))); ?>
      </div>
    </div>

		<div class="control-group">
			<?php echo Form::label(Lang::get('user.email'), 'email', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo Form::input('email', Input::post('email', $current_user->email), array('class' => 'span4')); ?>
			</div>
		</div>

		<div class="control-group">
			<label class='control-label'>&nbsp;</label>
			<div class='controls'>
        <button type="submit" class="btn btn-success">
          <i class="icon-ok icon-white"></i> <?php echo Lang::get('global.btn_save_change'); ?>
        </button>
        <?php echo Lang::get('global.or'); ?>&nbsp;<?php echo Html::anchor('profile', Lang::get('global.cancel'), array('class' => 'btn btn-danger')); ?>
      </div>
		</div>
	</fieldset>
<?php echo Form::close(); ?>
