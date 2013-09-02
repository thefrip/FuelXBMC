<?php if (Auth::member(100)): ?>
  <?php echo Asset::css(array('bootstrap-image-gallery.css')); ?>
<?php endif; ?>
<style>
div.opacity {
  background-image: url('<?php echo $artist->fanart->url; ?>');
}
</style>
<div class="page-header">
    <h1><?php echo $artist->name; ?></h1>
</div>
<ul class="nav nav-pills">
	<li class="active"><a href="#details" data-toggle="tab"><?php echo Lang::get('navigation.details'); ?></a></li>

	<?php if ($total_albums_played > 0): ?>
		<li><a href="#albums_played" data-toggle="tab"><?php echo Lang::get('navigation.albums'); ?></a></li>
	<?php endif; ?>

</ul>
<div class="opacity">
  <div class="tab-content opaque">
    <div class="tab-pane active" id="details">
      <div class="span7">
        <div class="row-fluid">
          <div class="span2"><?php echo Lang::get('media.biography'); ?></div>
          <div class="span9"><?php echo $artist->biography; ?></div>
        </div>
        <hr>
      </div>

      <div class="span3 offset1" id="sidebar">
        <div class="row">
          <h3><?php echo Lang::get('media.artist_thumb'); ?></h3>
          <div class="thumbnail thumb">
            <img id="thumb" src="<?php echo $artist->thumb->url; ?>" alt="">
          </div>
          <?php if (Auth::member(100) and $artist->images->thumbs->previews): ?>
            <!-- Button to trigger modal box for poster selection -->
            <button id="select-thumb" class="btn btn-info" data-target="#modal-thumb" data-selector="#thumb-gallery [data-gallery=gallery]"><i class="icon-plus icon-white"></i> <?php echo Lang::get('global.btn_change'); ?></button>
          <?php endif; ?>
        </div>
        <div class="row">
          <h3><?php echo Lang::get('media.fanart'); ?></h3>
          <div class="thumbnail fanart">
            <img id="fanart" src="<?php echo $artist->fanart->url; ?>" alt="">
          </div>
          <?php if (Auth::member(100) and $artist->images->fanarts->previews): ?>
            <!-- Button to trigger modal box for fanart selection -->
            <button id="select-fanart" class="btn btn-info" data-target="#modal-fanart" data-selector="#fanart-gallery [data-gallery=gallery]"><i class="icon-plus icon-white"></i> <?php echo Lang::get('global.btn_change'); ?></button>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <?php if ($total_albums_played > 0) echo $albums_played; ?>

  </div>
</div>
<?php echo Asset::js(array('ajax-pagination.js')); ?>
<?php if (Auth::member(100)): ?>

  <?php echo Asset::js(array('load-image.js', 'bootstrap-image-gallery.js', 'images.js')); ?>

  <?php if ($artist->images->thumbs->previews): ?>
    <!-- modal-thumb is the modal dialog used for the thumb gallery -->
    <div id="modal-thumb" class="modal modal-gallery hide fade" tabindex="-1">
      <div class="modal-header">
          <a class="close" data-dismiss="modal">&times;</a>
          <h3 class="modal-title"><?php echo Lang::get('title.select_thumb'); ?></h3>
      </div>
      <div class="modal-body"><div class="modal-image thumb"></div></div>
      <div class="modal-footer">
          <a class="btn btn-success modal-select"><i class="icon-ok icon-white"></i> <?php echo Lang::get('global.btn_select_thumb'); ?></a>
      </div>
    </div>

    <div id="thumb-gallery" data-toggle="modal-gallery" data-target="#modal-thumb">
      <?php foreach($artist->images->thumbs->previews as $thumb): ?>
        <a href="<?php echo $thumb; ?>" data-gallery="gallery" data-type="thumb">
          <img src="<?php echo $thumb; ?>" >
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($artist->images->fanarts->previews): ?>
    <!-- modal-fanart is the modal dialog used for the fanart gallery -->
    <div id="modal-fanart" class="modal modal-gallery hide fade" tabindex="-1">
      <div class="modal-header">
          <a class="close" data-dismiss="modal">&times;</a>
          <h3 class="modal-title"><?php echo Lang::get('title.select_fanart'); ?></h3>
      </div>
      <div class="modal-body"><div class="modal-image fanart"></div></div>
      <div class="modal-footer">
          <a class="btn btn-success modal-select"><i class="icon-ok icon-white"></i> <?php echo Lang::get('global.btn_select_fanart'); ?></a>
      </div>
    </div>

    <div id="fanart-gallery" data-toggle="modal-gallery" data-target="#modal-fanart">
      <?php foreach($artist->images->fanarts->previews as $fanart): ?>
        <a href="<?php echo $fanart; ?>" data-gallery="gallery" data-type="fanart">
          <img src="<?php echo $fanart; ?>" >
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php echo Security::js_fetch_token(); ?>

  <script type="text/javascript">
  var media_id = 'artist_<?php echo $artist->id; ?>';
  </script>

<?php endif; ?>
