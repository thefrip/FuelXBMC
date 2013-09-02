<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
  <link rel="icon" type="image/png" href="favicon.png" />
  <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /><![endif]-->
	<title><?php echo $title; ?></title>
	<?php echo Asset::css(array('styles.css')); ?>
	<?php echo Asset::js(array('jquery.min.js', 'bootstrap.min.js', 'admin.js')); ?>
  <script type="text/javascript">
  site_url = '<?php echo Uri::base(false); ?>';
  </script>
</head>
<body>
  <div class="ajax-progress"></div>
  <?php echo View::forge('admin/partials/topbar'); ?>
  <div class="container body narrow-body">
    <?php if (Session::get_flash('success')): ?>
      <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo Session::get_flash('success'); ?>
      </div>
    <?php endif; ?>
    <?php if (Session::get_flash('error')): ?>
      <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo Session::get_flash('error'); ?>
      </div>
    <?php endif; ?>
    <?php echo $content; ?>
  </div>

  <?php echo View::forge('partials/footer'); ?>
</body>
</html>
