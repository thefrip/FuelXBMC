<?php if ($page_title != ''): ?>
  <div class="page-header">
      <h1><?php echo $page_title; ?></h1>
  </div>
<?php endif; ?>
<ul class="nav nav-pills" id="home">
  <?php if (Config::get('settings.manage_music')): ?>
    <li><a href="#last_albums" data-toggle="tab"><?php echo Lang::get('navigation.last_albums'); ?></a></li>
  <?php endif; ?>
  <?php if (Config::get('settings.manage_movies')): ?>
    <li><a href="#last_movies" data-toggle="tab"><?php echo Lang::get('navigation.last_movies'); ?></a></li>
  <?php endif; ?>
  <?php if (Config::get('settings.manage_tvshows')): ?>
    <li><a href="#last_tvshows" data-toggle="tab"><?php echo Lang::get('navigation.last_tvshows'); ?></a></li>
    <li><a href="#last_episodes" data-toggle="tab"><?php echo Lang::get('navigation.last_episodes'); ?></a></li>
  <?php endif; ?>
</ul>
<div class="tab-content">
		<?php echo $last_albums; ?>
		<?php echo $last_movies; ?>
		<?php echo $last_tvshows; ?>
		<?php echo $last_episodes; ?>
</div>
<script type="text/javascript">
  $("#home a:first").tab("show");
</script>
