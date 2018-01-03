<?php

/**
 * 個人情報難読化のコントローラー
 *
 * @author Ippei Sesoko
 */
class ScrambleController extends BaseController
{

    /**
     * 前処理
     */
    public function before()
    {
        parent::before();
        $this->model = getModelInstans('scramble');
        $this->action_name_text = __(SCRAMBLE_PAGE_NAME);
        $this->set('title', __(SCRAMBLE_PAGE_NAME));
    }

    /**
     * ページ表示
     */
    public function index()
    {
      // テーブルリストを補完項目に使う
      $this->set('table_name_list', $this->model->getTableNameList());
      // カラムリストを補完項目に使う
      $this->set('column_name_list', $this->model->getColumnNameList());
    }

}
