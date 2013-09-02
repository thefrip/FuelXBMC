<div class="page-header">
  <h1><?php echo $page_title; ?></h1>
</div>
<?php if ($users): ?>
  <table class="table table-striped">
    <thead>
      <tr>
        <th><?php echo Lang::get('table.username'); ?></th>
        <th><?php echo Lang::get('table.group'); ?></th>
        <th><?php echo Lang::get('table.email'); ?></th>
        <th><?php echo Lang::get('table.last_login'); ?></th>
        <th><?php echo Lang::get('table.actions'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user): ?>
        <tr>
          <td><?php echo $user->username; ?></td>
          <td><?php echo ((int) $user->group == 100) ? Lang::get('user.admin') : Lang::get('user.user'); ?></td>
          <td><?php echo $user->email; ?></td>
          <td><?php echo ($user->last_login > 0) ? date(Lang::get('global.date_login'), $user->last_login) : Lang::get('global.never'); ?></td>
          <td>
            <?php echo Html::anchor('admin/users/edit/'.$user->id, '<i class="icon-edit"></i> '.Lang::get('global.btn_edit'), array('class' => 'btn')); ?>&nbsp;
            <?php if ($user->id == $current_user->id): ?>
              <?php echo Html::anchor('#', '<i class="icon-trash icon-white"></i> '.Lang::get('global.btn_delete'), array('class' => 'btn btn-danger disabled-link disabled')); ?>
            <?php else: ?>
              <?php echo Html::anchor('admin/users/delete/'.$user->id, '<i class="icon-trash icon-white"></i> '.Lang::get('global.btn_delete'), array('class' => 'btn btn-danger', 'onclick' => "return confirm('".Lang::get('global.are_you_sure')."')")); ?>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <tr><?php echo Lang::get('table.no_user'); ?></tr>
<?php endif; ?>
<hr>
<?php echo Html::anchor('admin/users/create', '<i class="icon-plus icon-white"></i> '.Lang::get('global.btn_add_user'), array('class' => 'btn btn-success')); ?>
