<div class="hero-unit">
	<h1><?php echo Lang::get('setup.welcome'); ?></h1>
  <br />
	<p><?php echo Lang::get('setup.settings_info'); ?></p>
  <p>
    <?php echo Lang::get('user.username'); ?> <strong>admin</strong><br />
    <?php echo Lang::get('user.password'); ?> <strong>password</strong>
  </p>
  <br />
	<p>
    <?php echo Html::anchor('login', Lang::get('global.please_sign_in'), array('class' => 'btn btn-success btn-large')); ?>
  </p>
</div>
