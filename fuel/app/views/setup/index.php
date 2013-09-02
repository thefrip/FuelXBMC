<div class="hero-unit">
	<h2><?php echo $page_title; ?></h2>
  <br />
	<p><?php echo Lang::get('setup.index_symbolic_info'); ?></p>
  <p class="alert alert-bloc"><?php echo Lang::get('setup.index_cmd_symbolic'); ?></p>
	<p>
    <?php echo Lang::get('setup.index_symbolic_check'); ?>
    <br />
    <?php echo $symbolic_link; ?>
    <?php if (file_exists($symbolic_link)): ?>
      <span class="label label-success"><?php echo Lang::get('setup.existing'); ?></span>
    <?php else: ?>
      <span class="label label-important"><?php echo Lang::get('setup.missing'); ?></span>
    <?php endif; ?>
  </p>
  <br />
	<p><?php echo Lang::get('setup.index_info'); ?></p>
  <p>
    <?php foreach($paths as $path): ?>
    <?php echo $path['path']; ?>&nbsp;
      <?php if ($path['status'] == 'correct'): ?>
       	<span class="label label-success"><?php echo Lang::get('setup.correct'); ?></span>
      <?php else: ?>
       	<span class="label label-important"><?php echo Lang::get('setup.incorrect'); ?></span>
      <?php endif; ?>
      <br />
    <?php endforeach; ?>
  </p>
  <br />
	<p><?php echo Lang::get('setup.index_writable'); ?></p>
	<p class="alert alert-bloc"><?php echo Lang::get('setup.index_cmd_writable'); ?></p>
  <br />
	<p>
    <?php
    echo Html::anchor('/setup',
                      '<i class="icon-repeat icon-white"></i> '.Lang::get('setup.btn_this_step'),
                      ($no_error) ? array('class' => 'btn btn-primary btn-large', 'disabled' => true) : array('class' => 'btn btn-success btn-large')
                      );
    ?>
     <?php echo Lang::get('setup.or'); ?>
    <?php
    echo Html::anchor('/setup/mysql1',
                      Lang::get('setup.btn_next_step').' <i class="icon-chevron-right icon-white"></i>',
                      ($no_error) ? array('class' => 'btn btn-success btn-large') : array('class' => 'btn btn-primary btn-large', 'disabled' => true)
                      );
    ?>
  </p>
</div>
