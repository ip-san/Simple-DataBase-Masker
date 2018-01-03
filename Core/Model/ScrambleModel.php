<?php

/**
 * Description of ScrambleModel
 *
 * @author Ippei Sesoko
 */
class ScrambleModel extends BaseModel
{

    protected $is_str_shaffle;
    protected $shift_num;
    protected $prefix_str;
    protected $suffix_str;

    /**
     * POSTで渡ってくるデータを受け取る
     * @param　bool $isColumnUse カラムを使うかどうか
     */
    public function catchPostData($isColumnUse = false)
    {
        parent::catchPostData(true);
        $this->setIsStrShaffle();
        $this->setShiftNum();
        $this->setPrefixStr();
        $this->setSuffixStr();
    }

    /**
     * 置き換えデータ作成
     * @return array 処理後データ
     */
    public function createReplaceDatas($datas)
    {
        $replaceDatas = array();

        foreach ($datas as $row) {
            $replaceRow = array();

            // 主キーを受け継ぐ
            $replaceRow[$this->primary_key] = $row[$this->primary_key];
            // 旧データ
            $replaceRow['before_value'] = $row[$this->column_name];
            $replaceRow['after_value'] = $row[$this->column_name];

            // 文字列シャッフル
            if ($this->is_str_shaffle) {
                // 新データ

                $replaceRow['after_value'] = $this->mb_str_shuffle($replaceRow['after_value']);
            }

            // 文字ずらし
            if (isset($this->shift_num)) {
                // 新データ
                $replaceRow['after_value'] = $this->toShiftStrings($replaceRow['after_value'], $this->shift_num);
            }

            // 文字列先頭に追加
            if (!empty($this->prefix_str)) {
                // 新データ
                $replaceRow['after_value'] = $this->prefix_str . $replaceRow['after_value'];
            }

            // 文字列末尾に追加
            if (!empty($this->suffix_str)) {
                // 新データ
                $replaceRow['after_value'] = $replaceRow['after_value'] . $this->suffix_str;
            }

            // 返すリストに登録
            $replaceDatas[] = $replaceRow;
        }

        return $replaceDatas;
    }


    public function mb_str_shuffle($string, $encoding = 'UTF-8')
    {
        $array = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
        shuffle($array);
        return implode('', $array);
    }

    /**
     * 文字列ずらし
     * @param string $string
     * @param int    $shift_num
     * @return array
     */
    public function toShiftStrings($string, $shift_num)
    {
        // 文字列を配列に変換
        $before_chars = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
        $after_chars = array();

        // 1文字ずつ取り出す
        foreach ($before_chars as $before_char) {
            // 文字を16進数に変更
            $after_char = bin2hex($before_char);
            // 文字を10進数に変更し、指定された数ずらす
            $after_char = (int) hexdec($after_char) + $shift_num;
            // ずらした10進数を16進数に変更
            $after_char = (string) dechex($after_char);
            // 16進数から文字を戻す
            $after_chars[] = (string) hex2bin($after_char);
        }

        // 結合
        return implode($after_chars);
    }

    /**
     * 文字列シャッフル
     * @param int $is_str_shaffle
     */
    public function setIsStrShaffle($is_str_shaffle = 0)
    {
        $this->setParam($is_str_shaffle, 'is_str_shaffle');

        if (!empty($is_str_shaffle)) {
            $this->is_str_shaffle = trim($is_str_shaffle);
        } elseif (isset($_POST['is_str_shaffle'])) {
            $this->is_str_shaffle = trim($_POST['is_str_shaffle']);
        } else {
            $this->is_str_shaffle = 0;
        }

        // 取得が行えた場合
        setSession('is_str_shaffle', $this->is_str_shaffle);
    }

    /**
     * 文字を16進数でずらす
     * @param int $shift_num
     */
    public function setShiftNum($shift_num = 0)
    {
        $this->setParam($shift_num, 'shift_num');
    }

    /**
     * 文字列先頭に追加
     * @param string $prefix_str
     */
    public function setPrefixStr($prefix_str = '')
    {
        $this->setParam($prefix_str, 'prefix_str');
    }

    /**
     * 文字列末尾に追加
     * @param string $suffix_str
     */
    public function setSuffixStr($suffix_str = '')
    {
        $this->setParam($suffix_str, 'suffix_str');
    }

}
