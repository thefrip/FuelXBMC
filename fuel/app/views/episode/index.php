<div class="tab-pane" id="<?php echo $episodes_type; ?>">
<?php if (isset($episodes)) foreach($episodes as $episode): ?>
	<?php
	$episode_number = str_replace('%s', sprintf("%02s", $episode->season_number), Lang::get('media.number_format'));
	$episode_number = str_replace('%e', sprintf("%02s", $episode->episode_number), $episode_number);
	?>
	<h3><?php echo Html::anchor('episode/'.$episode->id.'-'.Inflector::friendly_title($episode->local_title, '-'), $episode_number.$episode->local_title); ?></h3>
	<div class="row">
		<div class="span3">
			<div class="thumbnail episode">
        <?php echo Html::anchor('episode/'.$episode->id.'-'.Inflector::friendly_title($episode->local_title, '-'), '<img src="'.$episode->poster->url.'" />'); ?>
			</div>
		</div>
		<?php
		$links = array();
		foreach($episode->writers as $key => $value)
      $links[] = Html::anchor('person/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name);

		$writers = implode(', ', $links);
		if ($writers == '') $writers = Lang::get('media.no_writer');

		$links = array();
		foreach($episode->directors as $key => $value)
      $links[] = Html::anchor('person/'.$value->id.'-'.Inflector::friendly_title($value->name, '-'), $value->name);

		$directors = implode(', ', $links);
		if ($directors == '') $directors = Lang::get('media.no_director');
		?>
		<div class="span4 offset1">
			<?php if ($episodes_type != 'season_episodes'): ?>
			<div class="row-fluid">
				<div class="span5"><?php echo Lang::get('media.tvshow'); ?></div>
				<div class="span7"><?php echo Html::anchor('tvshow/'.$episode->tvshow_id.'-'.Inflector::friendly_title($episode->tvshow_name, '-'), $episode->tvshow_name); ?></div>
			</div>
			<?php endif; ?>
			<div class="row-fluid">
				<div class="span5"><?php echo Lang::get('media.written_by'); ?></div>
				<div class="span7"><?php echo $writers; ?></div>
			</div>
			<div class="row-fluid">
				<div class="span5"><?php echo Lang::get('media.directed_by'); ?></div>
				<div class="span7"><?php echo $directors; ?></div>
			</div>
			<div class="row-fluid">
				<div class="span5"><?php echo Lang::get('media.runtime'); ?></div>
				<div class="span7"><?php echo $episode->runtime; ?></div>
			</div>
			<div class="row-fluid">
				<div class="span5"><?php echo Lang::get('media.rating'); ?></div>
				<div class="span7">
          <div class='rating_bar'>
            <div class='rating' style='width:<?php echo round(($episode->rating/10)*100); ?>%;'></div>
          </div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span5"><?php echo Lang::get('media.first_aired'); ?></div>
				<div class="span7"><?php echo $episode->first_aired; ?></div>
			</div>
			<div class="row-fluid">
				<div class="span5"><?php echo Lang::get('media.overview'); ?></div>
				<div class="span"><?php echo Str::truncate($episode->overview, 350); ?></div>
			</div>
		</div>
	</div>
  <hr>
<?php endforeach; ?>
<?php echo $pagination; ?>
</div>
