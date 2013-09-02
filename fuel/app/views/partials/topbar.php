<?php
// Nom du controller appelé est converti au singulier puis au pluriel
// Exemple person => person => people mais aussi people => person => people
$segment = Inflector::pluralize(Inflector::singularize(Uri::segment(1)));
?>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
      <?php echo Html::anchor('/', Lang::get('global.menu_home'), array('class' => 'brand')); ?>
			<div class="nav-collapse collapse">
				<ul class="nav">
          <?php if (Config::get('settings.manage_music')): ?>
            <li<?php echo ($segment == 'albums') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/albums', Lang::get('global.menu_albums')); ?></li>
            <li<?php echo ($segment == 'artists') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/artists', Lang::get('global.menu_artists')); ?></li>
          <?php endif; ?>
          <?php if (Config::get('settings.manage_movies')): ?>
            <li<?php echo ($segment == 'movies') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/movies', Lang::get('global.menu_movies')); ?></li>
          <?php endif; ?>
          <?php if (Config::get('settings.manage_sets')): ?>
            <li<?php echo ($segment == 'sets') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/sets', Lang::get('global.menu_sets')); ?></li>
          <?php endif; ?>
          <?php if (Config::get('settings.manage_tvshows')): ?>
            <li<?php echo ($segment == 'tvshows') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/tvshows', Lang::get('global.menu_tvshows')); ?></li>
          <?php endif; ?>
          <?php if (Config::get('settings.manage_movies') or Config::get('settings.manage_sets') or Config::get('settings.manage_tvshows')): ?>
            <li<?php echo ($segment == 'people') ? ' class="active"' : ''; ?>><?php echo Html::anchor( '/people', Lang::get('global.menu_people')); ?></li>
          <?php endif; ?>
<?php
// Un seul segment dans l'uri ?
if (Uri::segment(1) and (Request::active()->controller != 'Controller_Setup'))
{
  echo View::forge('partials/search');
}
?>
				</ul>
        <?php if ($current_user): ?>
          <div class="btn-group pull-right">
            <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
              <i class="icon-user icon-white"></i>&nbsp;<?php echo $current_user->username ?>&nbsp;<span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <?php if (Auth::member(100)): ?>
                <li><?php echo Html::anchor('/admin', '<i class="icon-wrench"></i> '.Lang::get('global.action_control_panel')); ?></li>
              <?php endif; ?>
              <li><?php echo Html::anchor('/profile', '<i class="icon-cog"></i> '.Lang::get('global.action_profile')); ?></li>
              <li class="divider"></li>
              <li><?php echo Html::anchor('/logout', '<i class="icon-off"></i> '.Lang::get('global.action_logout')); ?></li>
            </ul>
          </div>
          <p class="navbar-text pull-right">
            <?php echo Lang::get('global.logged_in'); ?>
          </p>
        <?php else: ?>
          <?php if (Request::active()->controller != 'Controller_Setup'): ?>
            <p class="navbar-text pull-right">
              <?php echo Html::anchor('/login', Lang::get('global.action_login'), array('class' => 'navbar-link')); ?>
            </p>
          <?php endif; ?>
        <?php endif; ?>
			</div><!--/.nav-collapse -->
		</div><!--/.container-fluid -->
	</div><!--/.navbar-inner -->
</div><!--/.navbar -->
