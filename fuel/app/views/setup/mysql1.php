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
        <?php echo Form::label(Lang::get('setup.host_ip'), 'host_ip', array('class'=>'control-label')); ?>
        <div class="controls">
          <?php echo Form::input('host_ip', Input::post('host_ip', $settings['host_ip']), array('class' => 'input-small', 'id' => 'input_host_ip')); ?>
        </div>
      </div>

      <div class="control-group">
        <?php echo Form::label(Lang::get('setup.password'), 'root_password', array('class'=>'control-label')); ?>
        <div class="controls">
          <?php echo Form::input('root_password', Input::post('root_password', $settings['root_password']), array('class' => 'input-large', 'id' => 'input_password')); ?>
          <span class="help-block"><?php echo Lang::get('setup.root_password_help'); ?></span>
        </div>
      </div>

    </fieldset>

      <p>
    <button type="submit" class="btn btn-success" id="btn_check_db" >
      <i class="icon-warning-sign icon-white"></i> <?php echo Lang::get('setup.btn_check_db'); ?>
    </button> <span id="status_db"></span>
      </p>

  <?php echo Form::close(); ?>

	<p>
    <?php echo Html::anchor('/setup/mysql2', Lang::get('setup.btn_next_step').' <i class="icon-chevron-right icon-white"></i>', array('id' => 'btn_next_step', 'class' => 'btn btn-primary btn-large', 'disabled' => true)); ?>
  </p>
</div>
