<form id="database-form" method="post" action="javascript:void(0);">
    <?php element('db_conns',
            array('table_name_list' => $table_name_list, 'column_name_list' => $column_name_list))?>
    <hr>
    <div class="patterns">
        <ul>
            <li>
                <?php element('input_text', array('name'=> 'account_pattarn', 'text_name' => __('アカウントパターン')))?>
                <span>001@</span>
            </li>
            <li>
                <?php element('input_text', array('name'=> 'domain', 'text_name' => __('ドメイン')))?>
            </li>
        </ul>
        <label for="loop_pattarn_num"><input type="number" id="loop_pattarn_num" name="loop_pattarn_num" min="0" value="<?= getSession('loop_pattarn_num')?>"><span><?=__('件ごとにパターンを繰り返す')?></span></label>
    </div>
    <?php element('transaction_setting')?>
    <?php element('btns', array('controller'=> $controller))?>
</form>
<?php if (isset($datas) && is_array($datas) && (count($datas) > 0)): ?>
    <hr>
    <?php element('displayed_results', array('count_records' => $count_records, 'limit' => $limit))?>
    <?php element('table', array('datas' => $datas))?>
<?php endif; ?>
