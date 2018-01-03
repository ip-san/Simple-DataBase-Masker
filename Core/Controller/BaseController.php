<?php

/**
 * ベースコントローラー
 *
 * @author Ippei Sesoko
 */
class BaseController
{

    private $viewValues = array();
    protected $baseModel;
    protected $model;
    protected $layout = 'defalut';
    protected $view = '';
    protected $action_name_text;

    /**
     * フロントコントローラーから呼ばれる前処理
     * @return void
     */
    public function before()
    {
        // ログイン以外の場合はトークンをチェックする
        if (isset($_GET['token']) && !checkCsrf($_GET['token'])
                && ($this->viewValues['controller'] !== 'auth')) {
            setErrorMsg('token', 'タイムアウトか不正なアクセスの可能性 再ログインをお願いします');

            unset($_SESSION[SESSION_KEY]['auth']);
            redirect();
        }

        // 毎回トークンを変更
        if (IS_CSRF_CHECK_ONETIME_TOKEN_ENABLE) {
            createToken();
        }

        $this->baseModel = new BaseModel();
    }

    /**
     * 接続確認
     * @return void
     */
    public function connect_test()
    {
        // ビューを設定
        $this->render('index');

        $this->model->catchPostDataBase();

        // テーブルリストを補完項目に使う
        $this->set('table_name_list', $this->model->getTableNameList());
        // カラムリストを補完項目に使う
        $this->set('column_name_list', $this->model->getColumnNameList());

        if (!$this->checkErrorCount()) {
            return;
        }

        try {
            $pdo = $this->model->getPDO();

            // オブジェクトが取得できた場合
            if (is_object($pdo) && ($pdo instanceof PDO)) {
                setSuccessMsg('connect_test', __('データベース接続に成功しました'));
            }
        } catch (Exception $e) {

            setErrorMsg(0, $e);
        }

    }

    /**
     * 置き換えイメージ　アクション
     * @return void
     */
    public function replace_simulation()
    {
        // ビューを指定
        $this->render('index');

        // 入力を受け取る
        $this->model->catchPostData();

        // テーブルリストを補完項目に使う
        $this->set('table_name_list', $this->model->getTableNameList());
        // カラムリストを補完項目に使う
        $this->set('column_name_list', $this->model->getColumnNameList());

        if (!$this->checkErrorCount()) {
            return;
        }

        try {
            // データベース接続数を取得
            $count_records = $this->model->countRecords();
            $this->set('count_records', $count_records);

            // 初期のオフセットを設定
            $this->model->setOffset(0);
            // リミットを設定
            $this->model->setLimit($this->model->getTransactionRecordNum());

            // 表示用リミット
            if ($count_records > $this->model->getTransactionRecordNum()) {
                $this->set('limit', $this->model->getTransactionRecordNum());
            } else {
                $this->set('limit', $count_records);
            }

            // データを取得する
            $datas = $this->model->find();

            // データを加工
            $datas = $this->model->createReplaceDatas($datas);
        } catch (Exception $e) {
            setErrorMsg(0, $e);
        }

        if (is_array($datas) && (count($datas) > 0)) {
            setSuccessMsg('replace_simulation', $this->action_name_text . __('のイメージ作成に成功しました'));
            // ビューへ送り込む
            $this->set('datas', $datas);
        } elseif (is_array($datas) && (count($datas) === 0)) {
            setSuccessMsg('replace_simulation', __('データベースへの接続には成功しておりますが、レコードが存在しません'));
        }
    }

    /**
     * 置き換え実行
     * @return void
     */
    public function do_replace()
    {
        $this->render('index');
        // 入力を受け取る
        $this->model->catchPostData();

        // テーブルリストを補完項目に使う
        $this->set('table_name_list', $this->model->getTableNameList());
        // カラムリストを補完項目に使う
        $this->set('column_name_list', $this->model->getColumnNameList());

        if (!$this->checkErrorCount()) {
            return;
        }

        $update_cnt = (int)0;

        try {
            $pdo = $this->model->getPDO();

            // トランザクション開始
            $pdo->beginTransaction();

            if ($this->model->getTransactionRecordNum()) {
              // データベース接続数を取得
              $connect_num = ceil($this->model->countRecords() / $this->model->getTransactionRecordNum());
            } else {
              $connect_num = 1;
            }

            // 初期のオフセットを設定
            $this->model->setOffset(0);
            // リミットを計算
            $this->model->setLimit($this->model->getTransactionRecordNum());

            for ($i = 0; $i < $connect_num; $i++) {
                // 1回目以外
                if ($i > 0) {
                    // オフセットを計算し直し（前のオフセット + 処理数）
                    $offset = $this->model->getOffset() + $this->model->getLimit();
                    $this->model->setOffset($offset);
                }

                // データを取得する
                $datas = $this->model->find();
                // データを加工
                $datas = $this->model->createReplaceDatas($datas);

                // データを更新する
                $this->model->updateAll($datas);

                // 更新数
                $update_cnt = $update_cnt + count($datas);
            }

            // トランザクション終了
            $pdo->commit();
        } catch (Exception $e) {
            setErrorMsg(0, $e);
        }

        setSuccessMsg('do_replace', $update_cnt . __('件の') . $this->action_name_text . __('に成功しました'));
    }

    /**
     * ビューへ変数をアサイン
     * @param string $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->viewValues[$key] = $value;
    }

    /**
     * ビューの指定
     * @param string $view
     */
    protected function render($view)
    {
        $this->view = $view;
    }

    /**
     * ビューを表示（フロントコントローラーから呼び出し）
     * @return void
     */
    public function view()
    {

        if (isset($GET)) {
            foreach ($GET as $key => $param) {
                $$key = $param;
            }
        }

        foreach ($this->viewValues as $viewKey => $viewValue) {
            $$viewKey = $viewValue;
        }

        // ビューの指定がなければ
        if (empty($this->view)) {
            $view = $action;
        } else {
            $view = $this->view;
        }

        require_once(dirname(__FILE__) . '/../View/Layout/' . $this->layout . '.php');
    }

    /**
     * エラーがあるかどうか
     * @return boolean
     */
    protected function checkErrorCount()
    {
        if (count(getErrorMsg()) === 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * フロントコントローラーから呼ばれる後処理
     */
    public function after()
    {
        /* 無し */
    }

}
