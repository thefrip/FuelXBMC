<div class='rating_bar'>
  <div class='rating' style='width:<?php echo round($media->rating*10); ?>%;'></div>
</div>
<?php if ($media->votes > 0): ?>
  <div style="padding-top: 3px;">
    <?php printf("%0.1f", $media->rating); ?>&nbsp;(<?php echo $media->votes; ?>&nbsp;<?php echo ($media->votes == 1) ? Lang::get('media.vote') : Lang::get('media.votes'); ?>)
  </div>
<?php endif; ?>
