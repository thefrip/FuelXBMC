<div class="page-header">
    <h1><?php echo $person->name; ?></h1>
</div>
<ul class="nav nav-pills">
	<li class="active"><a href="#details" data-toggle="tab"><?php echo Lang::get('navigation.details'); ?></a></li>

	<?php if ($total_movies_written > 0): ?>
		<li><a href="#movies_written" data-toggle="tab"><?php echo Lang::get('navigation.movies_written'); ?></a></li>
	<?php endif; ?>

	<?php if ($total_movies_directed > 0): ?>
		<li><a href="#movies_directed" data-toggle="tab"><?php echo Lang::get('navigation.movies_directed'); ?></a></li>
	<?php endif; ?>

	<?php if ($total_movies_played > 0): ?>
		<li><a href="#movies_played" data-toggle="tab"><?php echo Lang::get('navigation.movies_played'); ?></a></li>
	<?php endif; ?>

	<?php if ($total_tvshows_played > 0): ?>
		<li><a href="#tvshows_played" data-toggle="tab"><?php echo Lang::get('navigation.tvshows_played'); ?></a></li>
	<?php endif; ?>

	<?php if ($total_episodes_written > 0): ?>
		<li><a href="#episodes_written" data-toggle="tab"><?php echo Lang::get('navigation.episodes_written'); ?></a></li>
	<?php endif; ?>

	<?php if ($total_episodes_directed > 0): ?>
		<li><a href="#episodes_directed" data-toggle="tab"><?php echo Lang::get('navigation.episodes_directed'); ?></a></li>
	<?php endif; ?>

	<?php if ($total_episodes_played > 0): ?>
		<li><a href="#episodes_played" data-toggle="tab"><?php echo Lang::get('navigation.episodes_played'); ?></a></li>
	<?php endif; ?>

</ul>
<div class="tab-content">
	<div class="tab-pane active" id="details">
		<div class="span3">
			<h3><?php echo Lang::get('media.media_photo'); ?></h3>
			<div class="thumbnail person">
				<img src="<?php echo $person->photo->url; ?>" alt="">
			</div>
		</div>
	</div>

	<?php if ($total_movies_written > 0) echo $movies_written; ?>
	<?php if ($total_movies_directed > 0) echo $movies_directed; ?>
	<?php if ($total_movies_played > 0) echo $movies_played; ?>
	<?php if ($total_tvshows_played > 0) echo $tvshows_played; ?>
	<?php if ($total_episodes_written > 0) echo $episodes_written; ?>
	<?php if ($total_episodes_directed > 0) echo $episodes_directed; ?>
	<?php if ($total_episodes_played > 0) echo $episodes_played; ?>

</div>
<?php echo Asset::js(array('ajax-pagination.js')); ?>
