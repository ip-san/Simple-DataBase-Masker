<div class="displayed-results">
    <?php if (isset($limit) && !empty($limit)): ?>
      <span><?=__('総件数')?><?=$count_records?><?=__('件中')?><?=$limit?><?=__('件目までを表示')?></span>
    <?php else: ?>
      <span><?=__('総件数')?><?=$count_records?><?=__('件を表示')?></span>
    <?php endif;?>
</div>
