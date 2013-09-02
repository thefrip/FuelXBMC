<div class="page-header">
    <h1><?php echo $page_title; ?></h1>
</div>
<?php if ($server_paths): ?>
  <table class="table table-striped">
    <thead>
      <tr>
        <th><?php echo Lang::get('table.server_path'); ?></th>
        <th><?php echo Lang::get('table.client_path'); ?></th>
        <th><?php echo Lang::get('table.actions'); ?></th>
      </tr>
    </thead>
    <tbody>
        <?php foreach ($server_paths as $server_path): ?>
          <tr>
            <td><?php echo $server_path->server_path; ?></td>
            <td><?php echo $server_path->client_path; ?></td>
            <td>
              <?php echo Html::anchor('admin/sources/music/edit/'.$server_path->id, '<i class="icon-edit"></i> '.Lang::get('global.btn_edit'), array('class' => 'btn')); ?>&nbsp;
              <?php echo Html::anchor('admin/sources/music/delete/'.$server_path->id, '<i class="icon-trash icon-white"></i> '.Lang::get('global.btn_delete'), array('class' => 'btn btn-danger', 'onclick' => "return confirm('".Lang::get('global.are_you_sure')."')")); ?>
            </td>
          </tr>
        <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <h3><?php echo Lang::get('table.no_music_server_path'); ?></h3>
<?php endif; ?>
<hr>
<?php echo Html::anchor('admin/sources/music/create', '<i class="icon-plus icon-white"></i> '.Lang::get('global.btn_add_music_server_path'), array('class' => 'btn btn-success')); ?>
