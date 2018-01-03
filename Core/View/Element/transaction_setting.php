<div class="transaction-setting">
    <p><span><?=__('1回のデータベース接続で')?></span><input type="number" id="transaction_record_num" name="transaction_record_num" min="0" value="<?= getSession('transaction_record_num')?>" title="<?=__('0を指定すると処理を分けずに実行')?>"><span><?=__('件レコードを処理する')?></span></p>
</div>