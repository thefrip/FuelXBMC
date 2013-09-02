<?php echo Form::open(array('class' => 'form-signin', 'action' => Uri::base(false).'login', 'method' => 'post')); ?>
  <h2 class="form-signin-heading"><?php echo Lang::get('global.please_sign_in'); ?></h2>
  <?php if (isset($incorrect_login) or $val->error('username') or $val->error('password')): ?>
    <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php if (isset($incorrect_login)) echo $incorrect_login; ?>
        <?php if ($val->error('username')) echo $val->error('username')->get_message(Lang::get('error.no_username')); ?>
        <?php if ($val->error('password')) echo $val->error('password')->get_message(Lang::get('error.no_password')); ?>
    </div>
  <?php endif; ?>

  <input type="text" class="input-block-level" name="username" placeholder="<?php echo Lang::get('label.username'); ?>">
  <input type="password" class="input-block-level" name="password" placeholder="<?php echo Lang::get('label.password'); ?>">

  <label class="checkbox">
    <input type="checkbox" name="remember"> <?php echo Lang::get('global.remember_me'); ?>
  </label>

  <button class="btn btn-large btn-primary" type="submit"><?php echo Lang::get('global.btn_sign_in'); ?></button>
<?php echo Form::close(); ?>
