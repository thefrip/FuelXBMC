<?php echo Asset::js(array('setup.js')); ?>
<script type="text/javascript">
<!--
loading = '<?php echo Lang::get('setup.loading'); ?>';
//-->
</script>
<div class="hero-unit">
	<h2><?php echo $page_title; ?></h2>
  <br />
	<p><?php echo Lang::get('setup.mysql1_info'); ?></p>
  <?php echo Form::open(); ?>

    <fieldset>

      <div class="control-group">
        <?php echo Form::label(Lang::get('setup.username'), 'username', array('class'=>'control-label')); ?>
        <div class="controls">
          <?php echo Form::input('username', Input::post('username', $settings['username']), array('class' => 'input-medium')); ?>
        </div>
      </div>

      <div class="control-group">
        <?php echo Form::label(Lang::get('setup.password'), 'password', array('class'=>'control-label')); ?>
        <div class="controls">
          <?php echo Form::input('password', Input::post('password', $settings['password']), array('class' => 'input-medium')); ?>
          <span class="help-block"><?php echo Lang::get('setup.xbmc_password_help'); ?></span>
        </div>
      </div>

      <div class="control-group">
        <?php echo Form::label(Lang::get('setup.music_db'), 'music_db', array('class'=>'control-label')); ?>
        <div class="controls">
          <?php echo Form::input('music_db', Input::post('music_db', $settings['music_db']), array('class' => 'input-medium')); ?>
        </div>
      </div>

      <div class="control-group">
        <?php echo Form::label(Lang::get('setup.video_db'), 'video_db', array('class'=>'control-label')); ?>
        <div class="controls">
          <?php echo Form::input('video_db', Input::post('video_db', $settings['video_db']), array('class' => 'input-medium')); ?>
        </div>
      </div>

      <div class="control-group">
        <?php echo Form::label(Lang::get('setup.xbmc_db'), 'xbmc_db', array('class'=>'control-label')); ?>
        <div class="controls">
          <?php echo Form::input('xbmc_db', Input::post('xbmc_db', $settings['xbmc_db']), array('class' => 'input-medium')); ?>
        </div>
        <span class="help-block"><?php echo Lang::get('setup.xbmc_db_help'); ?></span>
      </div>

    </fieldset>

    <button type="submit" class="btn btn-success btn-large">
      <?php echo Lang::get('setup.btn_next_step'); ?> <i class="icon-chevron-right icon-white"></i>
    </button>

  <?php echo Form::close(); ?>
</div>
