<?php

/**
 * Email置き換え用モデルクラス
 *
 * @author Ippei Sesoko
 */
class EmailModel extends BaseModel
{

    protected $account_pattarn;
    protected $domain;
    protected $loop_pattarn_num;

    /**
     * POSTで渡ってくるデータを受け取る
     * @param　bool $isColumnUse カラムを使うかどうか
     */
    public function catchPostData($isColumnUse = false)
    {
        parent::catchPostData(true);
        $this->setAccountPattarn();
        $this->setDomain();
        $this->setLoopPattarnNum();
    }

    /**
     * 置き換えデータ作成
     * @return array 処理後データ
     */
    public function createReplaceDatas($datas)
    {
        $replaceDatas = array();

        $account_pattarn_index = (int)1;

        foreach ($datas as $row) {
            $replaceRow = array();

            // 主キーを受け継ぐ
            $replaceRow[$this->primary_key] = $row[$this->primary_key];
            // 旧データ
            $replaceRow['before_value'] = $row[$this->column_name];
            // 新データ
            $replaceRow['after_value']=
                    $this->account_pattarn . sprintf('%03d', $account_pattarn_index) . '@' . $this->domain;
            // 返すリストに登録
            $replaceDatas[] = $replaceRow;

            // パターン数に達したら、パターンの連番を戻す
            if ($this->loop_pattarn_num <= $account_pattarn_index) {
                $account_pattarn_index = (int)0;
            }

            // 最後にカウントアップ
            $account_pattarn_index = $account_pattarn_index + 1;
        }

        return $replaceDatas;
    }

    /**
     * アカウントパターン
     * @param string $account_pattarn
     */
    public function setAccountPattarn($account_pattarn = '')
    {
        $this->setParam($account_pattarn, 'account_pattarn', 'アカウントパターンが指定されていません');
    }

    /**
     * ドメイン
     * @param string $domain
     */
    public function setDomain($domain = '')
    {
        $this->setParam($domain, 'domain', 'ドメインが指定されていません');
    }

    /**
     * 繰り返すパターン数
     * @param int $loop_pattarn_num
     */
    public function setLoopPattarnNum($loop_pattarn_num = 0)
    {
        $this->setParam($loop_pattarn_num, 'loop_pattarn_num');
    }
}
