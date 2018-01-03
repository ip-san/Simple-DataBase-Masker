<?php if ($mesageList): ?>
    <div class="messages <?=$type?>-messages">
        <?php foreach ($mesageList as $key => $mesage):?>
        <p><?=$mesage?><span class="close" data-form-id="<?=$key?>" title="<?=__('閉じる')?>">×</span></p>
        <?php endforeach;?>
    </div>
<?php endif;?>