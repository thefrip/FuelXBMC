<?php echo Form::open(array('class' => 'form-horizontal')); ?>

	<fieldset>
		<div class="control-group">
			<?php echo Form::label(Lang::get('user.username'), 'username', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo Form::input('username', Input::post('username', isset($user) ? $user->username : ''), array('class' => 'span4', 'placeholder' => Lang::get('label.username'))); ?>
			</div>
		</div>

		<div class="control-group">
			<?php echo Form::label(Lang::get('user.password'), 'password', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo Form::password('password', Input::post('password'), array('class' => 'span4', 'placeholder' => Lang::get('label.password'))); ?>
			</div>
		</div>

    <?php if (Request::active()->action == 'edit'): ?>
      <div class="control-group">
        <?php echo Form::label(Lang::get('user.new_password'), 'new_password', array('class'=>'control-label')); ?>
        <div class="controls">
          <?php echo Form::password('new_password', Input::post('new_password'), array('class' => 'span4', 'placeholder' => Lang::get('label.new_password'))); ?>
        </div>
      </div>
    <?php endif; ?>

		<div class="control-group">
			<?php echo Form::label(Lang::get('user.email'), 'email', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo Form::input('email', Input::post('email', isset($user) ? $user->email : ''), array('class' => 'span4', 'placeholder' => Lang::get('label.email'))); ?>
			</div>
		</div>

		<div class="control-group">
			<?php echo Form::label(Lang::get('user.group'), 'group', array('class'=>'control-label')); ?>
			<div class="controls">
        <?php
        $check_admin = false;
        if (isset($user))
        {
          $check_admin = ((int) $user->group == 100);
        }
        $check_user = !$check_admin;
        ?>
        <label class="radio inline">
          <input type="radio" name="group" value="1" <?php echo $check_user ? 'checked' : '' ?>>
          <?php echo Lang::get('user.user'); ?>
        </label>
        <label class="radio inline">
          <input type="radio" name="group" value="<?php echo 100; ?>" <?php echo $check_admin ? 'checked' : '' ?>>
          <?php echo Lang::get('user.admin'); ?>
        </label>
        <span class="help-block"><?php echo Lang::get('user.group_help'); ?></span>
			</div>
		</div>

		<div class="control-group">
			<label class='control-label'>&nbsp;</label>
			<div class='controls'>
        <button type="submit" class="btn btn-success">
          <i class="icon-ok icon-white"></i> <?php echo Lang::get('global.btn_save_change'); ?>
        </button>
        <?php echo Lang::get('global.or'); ?>&nbsp;<?php echo Html::anchor('admin/users', Lang::get('global.cancel'), array('class' => 'btn btn-danger')); ?>
      </div>
		</div>
	</fieldset>
<?php echo Form::close(); ?>
