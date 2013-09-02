<div class="hero-unit">
	<h2><?php echo $page_title; ?></h2>
  <br />
  <div class="alert alert-error">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <b><?php echo Lang::get('setup.warning'); ?></b> <?php echo str_replace('%users%', Html::anchor( 'admin/users', Lang::get('global.menu_users')), Lang::get('setup.admin_info')); ?>
  </div>
  <div class="alert alert-error">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <b><?php echo Lang::get('setup.warning'); ?></b> <?php echo str_replace('%sources%', Html::anchor( 'admin/sources/video', Lang::get('global.menu_video_server_paths')), Lang::get('setup.admin_sources')); ?>
  </div>
  <p><?php echo Lang::get('setup.admin_home'); ?></p>
  <?php echo Html::anchor('admin/settings', Lang::get('setup.admin_settings'), array('class' => 'btn btn-success btn-large')); ?>
</div>
