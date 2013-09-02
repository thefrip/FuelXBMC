<?php if ($page_title != ''): ?>
  <div class="page-header">
      <h1><?php echo $page_title; ?></h1>
  </div>
<?php endif; ?>
<ul class="nav nav-pills">
	<li class="active"><a href="#details" data-toggle="tab"><?php echo Lang::get('navigation.details'); ?></a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="details">
		<div class="row">
			<div class="span8">
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('user.username'); ?></div>
          <div class="span9"><?php echo $current_user->username; ?></div>
        </div>
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('user.email'); ?></div>
          <div class="span9"><?php echo $current_user->email; ?></div>
        </div>
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('user.group'); ?></div>
          <div class="span9"><?php echo ((int) $current_user->group == 100) ? Lang::get('user.admin') : Lang::get('user.user'); ?></div>
        </div>
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('user.registred'); ?></div>
          <div class="span9"><?php echo date(Lang::get('global.date_short'), $current_user->created_at); ?></div>
        </div>
        <hr>
        <?php echo Html::anchor('profile/edit', '<i class="icon-edit icon-white"></i> '.Lang::get('global.btn_edit_profile'), array('class' => 'btn btn-success')); ?>
      </div>
		</div>
	</div>
</div>
