<?php
// Nom du controller appelé est converti au singulier puis au pluriel
// Exemple person => person => people mais aussi people => person => people
// Uri::segment(1) vaut 'admin' !
// Si Uri::segment(2) alors $segment vaut 's' (le pluriel de null ;-) )
$segment = Inflector::pluralize(Inflector::singularize(Uri::segment(2)));
?>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<div class="nav-collapse collapse">
				<ul class="nav">
          <li<?php echo ($segment == 's') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/admin', Lang::get('global.menu_dashboard')); ?></li>
          <?php if (Config::get('settings.manage_music')): ?>
            <li<?php echo ($segment == 'albums') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/admin/albums', Lang::get('global.menu_albums')); ?></li>
            <li<?php echo ($segment == 'artists') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/admin/artists', Lang::get('global.menu_artists')); ?></li>
          <?php endif; ?>
          <?php if (Config::get('settings.manage_movies')): ?>
            <li<?php echo ($segment == 'movies') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/admin/movies', Lang::get('global.menu_movies')); ?></li>
          <?php endif; ?>
          <?php if (Config::get('settings.manage_sets')): ?>
            <li<?php echo ($segment == 'sets') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/admin/sets', Lang::get('global.menu_sets')); ?></li>
          <?php endif; ?>
          <?php if (Config::get('settings.manage_tvshows')): ?>
            <li<?php echo ($segment == 'tvshows') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/admin/tvshows', Lang::get('global.menu_tvshows')); ?></li>
          <?php endif; ?>
          <?php if (Config::get('settings.manage_movies') or Config::get('settings.manage_sets') or Config::get('settings.manage_tvshows')): ?>
            <li<?php echo ($segment == 'people') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/admin/people', Lang::get('global.menu_people')); ?></li>
          <?php endif; ?>
          <li class="dropdown<?php echo (($segment == 'users') or ($segment == 'certifications') or (Request::active()->controller == 'Controller_Admin_Sources_Music') or (Request::active()->controller == 'Controller_Admin_Sources_Video') or ($segment == 'settings')) ? ' active' : ''; ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo Lang::get('global.menu_configuration'); ?> <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li<?php echo ($segment == 'users') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/admin/users', Lang::get('global.menu_users')); ?></li>
              <li<?php echo ($segment == 'certifications') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/admin/certifications', Lang::get('global.menu_certifications')); ?></li>
              <?php if (Config::get('settings.manage_music')): ?>
                <li<?php echo (Request::active()->controller == 'Controller_Admin_Sources_Music') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/admin/sources/music', Lang::get('global.menu_music_server_paths')); ?></li>
              <?php endif; ?>
              <?php if (Config::get('settings.manage_movies') or Config::get('settings.manage_sets') or Config::get('settings.manage_tvshows')): ?>
                <li<?php echo (Request::active()->controller == 'Controller_Admin_Sources_Video') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/admin/sources/video', Lang::get('global.menu_video_server_paths')); ?></li>
              <?php endif; ?>
              <li<?php echo ($segment == 'settings') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/admin/settings', Lang::get('global.menu_settings')); ?></li>
            </ul>
          </li>
				</ul>
				<div class="btn-group pull-right">
          <a href="#" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
						<i class="icon-user icon-white"></i>&nbsp;<?php echo $current_user->username ?>&nbsp;<span class="caret"></span>
          </a>
					<ul class="dropdown-menu">
            <li><?php echo Html::anchor('/', '<i class="icon-home"></i> '.Lang::get('global.action_public_site')); ?></li>
            <li><?php echo Html::anchor('/profile', '<i class="icon-cog"></i> '.Lang::get('global.action_profile')); ?></li>
            <li class="divider"></li>
            <li><?php echo Html::anchor('/logout', '<i class="icon-off"></i> '.Lang::get('global.action_logout')); ?></li>
					</ul>
				</div>
        <p class="navbar-text pull-right">
          <?php echo Lang::get('global.logged_in'); ?>
        </p>
			</div><!--/.nav-collapse -->
		</div><!--/.container-fluid -->
	</div><!--/.navbar-inner -->
</div><!--/.navbar -->
