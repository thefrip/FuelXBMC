<?php echo Asset::js(array('jquery.sortable.js')); ?>
<div class="page-header">
  <h1><?php echo $page_title; ?></h1>
</div>
<?php if ($certifications): ?>
  <div class="row-fluid">
    <ul class="sortable span12">
      <?php foreach ($certifications as $certification): ?>
        <li data-id="<?php echo $certification->rating; ?>">
          <span><i class="icon-resize-vertical"></i></span> <?php echo $certification->name; ?>
          <div class="pull-right">
            <?php echo Html::anchor('admin/certifications/edit/'.$certification->id, '<i class="icon-edit"></i> '.Lang::get('global.btn_edit'), array('class' => 'btn')); ?>&nbsp;
            <?php echo Html::anchor('admin/certifications/delete/'.$certification->id, '<i class="icon-trash icon-white"></i> '.Lang::get('global.btn_delete'), array('class' => 'btn btn-danger', 'onclick' => "return confirm('".Lang::get('global.are_you_sure')."')")); ?>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
	<script>
		$(function() {
			$('.sortable').sortable({
				handle: 'span'
			});

      $('.sortable').sortable().bind('sortupdate', function() {
        var dataIDList = $('.sortable li').map(function(){  return $(this).data("id"); }).get().join(",");
        $.ajax({ type: "POST",  url: "post.php?dataIDList="+dataIDList });
//        $('#status').html(dataIDList);
      });

		});
	</script>

<?php else: ?>
  <?php echo Lang::get('table.no_certification'); ?>
<?php endif; ?>
<hr>
<?php echo Html::anchor('admin/certifications/create', '<i class="icon-plus icon-white"></i> '.Lang::get('global.btn_add_certification'), array('class' => 'btn btn-success')); ?>
