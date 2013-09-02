<div class="page-header">
    <h1><?php echo $page_title; ?></h1>
</div>
<?php if ($server_paths): ?>
  <table class="table table-striped">
    <thead>
      <tr>
        <th><?php echo Lang::get('table.server_path'); ?></th>
        <th><?php echo Lang::get('table.content'); ?></th>
        <th><?php echo Lang::get('table.actions'); ?></th>
      </tr>
    </thead>
    <tbody>
        <?php foreach ($server_paths as $server_path): ?>
          <tr>
            <td><?php echo $server_path->server_path; ?></td>
            <td><?php echo Lang::get('table.'.$server_path->strContent); ?></td>
            <td>
              <?php echo Html::anchor('admin/sources/video/edit/'.$server_path->id, '<i class="icon-edit"></i> '.Lang::get('global.btn_edit'), array('class' => 'btn')); ?>&nbsp;
            </td>
          </tr>
        <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <h3><?php echo Lang::get('table.no_source'); ?></h3>
<?php endif; ?>
