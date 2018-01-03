<?php

/**
 * メールアドレス置き換えのコントローラー
 *
 * @author Ippei Sesoko
 */
class EmailController extends BaseController
{

    /**
     * 前処理
     */
    public function before()
    {
        parent::before();
        $this->model = getModelInstans('email');
        $this->action_name_text = __(Email_PAGE_NAME);
        $this->set('title', __(Email_PAGE_NAME));
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

    /**
     * 置き換え実行
     */
    public function do_replace()
    {
        parent::do_replace();
    }

}
