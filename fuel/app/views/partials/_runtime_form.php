<?php if (Lang::get('media.runtime_format') != ''): ?>
  <?php $hours = floor($value / 60); $minutes = $value % 60; ?>
    <div class="span4">
      <select id="select-runtime-hour" name="runtime-hour" class="span12">
        <?php for($h = 0; $h <= 5; $h++): ?>
          <option value="<?php echo $h; ?>"<?php echo ($h == $hours) ? ' selected="selected"' : ''; ?>><?php echo sprintf('%02d', $h); ?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="pull-left" style="padding-top: 3px;">&nbsp;<?php echo Lang::get('global.hours_short'); ?>&nbsp;</div>
    <div class="span4">
      <select id="select-runtime-minute" name="runtime-minute" class="span12">
        <?php for($m = 0; $m <= 59; $m++): ?>
          <option value="<?php echo $m; ?>"<?php echo ($m == $minutes) ? ' selected="selected"' : ''; ?>><?php echo sprintf('%02d', $m); ?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="pull-left" style="padding-top: 3px;">&nbsp;<?php echo Lang::get('global.minutes_short'); ?></div>
<?php else: ?>
  <input id="select-runtime-minute" class="span12" type="text" name="runtime-minute" value="<?php echo $value; ?>" >
<?php endif; ?>
