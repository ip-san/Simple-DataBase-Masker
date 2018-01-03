<form id="database-form" method="post" action="javascript:void(0);">
    <?php element('db_conns', 
            array('table_name_list' => $table_name_list, 'column_name_list' => $column_name_list))?>
    <hr>
    <div class="patterns">
        <ul>
            <li>
                <label for="is_str_shaffle"><?=__('文字列シャッフル')?></label>
                <input type="checkbox" id="is_str_shaffle" name="is_str_shaffle"
                       value="<?=getSession('is_str_shaffle')?>"
                       <?php if (getSession('is_str_shaffle') == 1) { echo 'checked="checked"'; }  ?> >
            </li>
            <li>
                <label for="shift_num"><?=__('文字をずらす（文字ごと）')?></label>
                <input type="number" id="shift_num" name="shift_num" value="<?= getSession('shift_num')?>">
            </li>
            <li>
                <?php element('input_text', array('name'=> 'prefix_str', 'text_name' => __('文字列先頭に追加')))?> 
            </li>
            <li>
                <?php element('input_text', array('name'=> 'suffix_str', 'text_name' => __('文字列末尾に追加')))?> 
            </li>
        </ul>
    </div>
    <?php element('transaction_setting')?>
    <?php element('btns', array('controller'=> $controller))?>   
</form>
<?php if (isset($datas) && is_array($datas) && (count($datas) > 0)): ?>
    <hr>
    <?php element('displayed_results', array('count_records' => $count_records, 'limit' => $limit))?>  
    <?php element('table', array('datas' => $datas))?>
<?php endif; ?>